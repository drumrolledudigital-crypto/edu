<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        return view('admin.payments.index');
    }

    public function list()
    {
        $payments = Payment::with(['student', 'appointment.subject'])->latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => $payments
        ]);
    }

    public function show($id)
    {
        $payment = Payment::with(['student', 'appointment.subject', 'appointment.doubt'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $payment
        ]);
    }

    public function updateStatus(Request $request, $id, AuditLoggerService $logger)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,successful,failed,refunded',
        ]);

        $payment = Payment::findOrFail($id);
        $oldData = $payment->toArray();

        $payment->update([
            'payment_status' => $request->payment_status
        ]);

        $logger->log('Payment', 'StatusUpdate', "Payment #{$payment->id} status changed to {$request->payment_status}.", $oldData, $payment->refresh()->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Payment status updated successfully.'
        ]);
    }

    public function markRefunded($id, AuditLoggerService $logger)
    {
        $payment = Payment::findOrFail($id);
        $oldData = $payment->toArray();

        DB::transaction(function () use ($payment) {
            $payment->update(['payment_status' => 'refunded']);
        });

        $logger->log('Payment', 'Refund', "Payment #{$payment->id} marked as refunded.", $oldData, $payment->refresh()->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Payment marked as refunded.'
        ]);
    }

    public function destroy($id, AuditLoggerService $logger)
    {
        $payment = Payment::with(['appointment.slot'])->findOrFail($id);
        $oldData = $payment->toArray();

        DB::transaction(function () use ($payment) {
            if ($payment->appointment) {
                $payment->appointment->update(['status' => 'cancelled']);
                if ($payment->appointment->slot_id) {
                    \App\Models\Slot::where('id', $payment->appointment->slot_id)
                        ->where('status', 'booked')
                        ->update(['status' => 'available']);
                }
            }
            $payment->delete();
        });

        $logger->log('Payment', 'Delete', "Payment #{$id} was deleted.", $oldData);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment record deleted.'
        ]);
    }
}
