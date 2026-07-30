<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\IntegrationAutomationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function index()
    {
        return view('admin.refunds.index');
    }

    public function list()
    {
        $refunds = Refund::with(['student', 'payment.appointment.subject', 'invoice'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $refunds
        ]);
    }

    public function updateStatus(Request $request, $id, IntegrationAutomationService $automation, \App\Services\AuditLoggerService $logger)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,refunded',
            'admin_notes' => 'nullable|string'
        ]);

        $refund = Refund::with(['payment', 'appointment', 'student'])->findOrFail($id);
        $previousStatus = $refund->status;
        $oldData = $refund->toArray();
        
        DB::transaction(function () use ($request, $refund, $previousStatus) {
            $updateData = ['status' => $request->status];
            if ($request->filled('admin_notes')) {
                $updateData['admin_notes'] = $request->admin_notes;
            }
            if ($request->status === 'refunded' && $previousStatus !== 'refunded') {
                $updateData['refund_date'] = now();
                // Optionally update payment status
                if ($refund->payment) {
                    $refund->payment->update(['payment_status' => 'refunded']);
                }
                // Optionally cancel appointment
                if ($refund->appointment && $refund->appointment->status !== 'completed') {
                    $refund->appointment->update(['status' => 'cancelled']);
                }
            }

            $refund->update($updateData);
        });

        // Notifications
        if ($previousStatus !== $request->status) {
            $student = $refund->student;
            $typeMap = [
                'approved' => 'refund_approved',
                'rejected' => 'refund_rejected',
                'refunded' => 'refund_completed',
            ];

            if (isset($typeMap[$request->status]) && $student) {
                try {
                    $subject = ucfirst($request->status) . ' - Refund Request';
                    $currency = $refund->payment?->currency ?? Setting::get('currency', 'USD');
                    $message = "Your refund request for {$currency} {$refund->refund_amount} has been {$request->status}.";
                    if ($request->admin_notes) {
                        $message .= " Note from admin: {$request->admin_notes}";
                    }
                    
                    $emailLog = \App\Models\EmailLog::create([
                        'recipient' => $student->email,
                        'subject' => $subject,
                        'type' => $typeMap[$request->status],
                        'status' => 'pending',
                        'appointment_id' => $refund->appointment_id,
                    ]);
                    
                    \App\Jobs\SendLoggedEmail::dispatch($emailLog->id, 'emails.layout', [
                        'title' => 'Refund Update',
                        'content' => $message,
                    ]);
                } catch (\Throwable $th) {
                    // Silently fail
                }
            }
            
            // Add internal admin notification for record
            try {
                $automation->createInternalAdminNotification(
                    'Refund',
                    'Refund ' . ucfirst($request->status),
                    "Refund #{$refund->id} for {$student->name} was marked as {$request->status}.",
                    $refund,
                    $student,
                    "/admin/refunds",
                    'check-circle'
                );
            } catch (\Throwable $th) {}
        }
        
        $logger->log('Refund', 'StatusUpdate', "Refund #{$refund->id} status changed from {$previousStatus} to {$request->status}.", $oldData, $refund->refresh()->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Refund status updated successfully.'
        ]);
    }
}
