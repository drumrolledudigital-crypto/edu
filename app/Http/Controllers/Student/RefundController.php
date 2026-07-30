<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Refund;
use App\Services\IntegrationAutomationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    /**
     * Display a listing of the student's refunds.
     */
    public function index()
    {
        $refunds = Refund::where('student_id', Auth::id())
            ->with(['payment.appointment.subject', 'invoice'])
            ->latest()
            ->paginate(10);

        return view('student.refunds.index', compact('refunds'));
    }

    /**
     * Show the form for creating a new refund request.
     */
    public function create(Payment $payment)
    {
        // Ensure payment belongs to the student and is successful
        if ($payment->student_id !== Auth::id() || $payment->payment_status !== 'successful') {
            abort(403, 'Unauthorized or invalid payment for refund.');
        }

        // Check if refund already exists
        if (Refund::where('payment_id', $payment->id)->exists()) {
            return redirect()->route('student.refunds.index')->with('error', 'A refund request already exists for this payment.');
        }

        return view('student.refunds.create', compact('payment'));
    }

    /**
     * Store a newly created refund request in storage.
     */
    public function store(Request $request, Payment $payment, IntegrationAutomationService $automation, \App\Services\AuditLoggerService $logger)
    {
        if ($payment->student_id !== Auth::id() || $payment->payment_status !== 'successful') {
            abort(403, 'Unauthorized or invalid payment for refund.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        // Atomic duplicate check using database lock
        $refund = DB::transaction(function () use ($payment, $request) {
            $existingRefund = DB::table('refunds')
                ->where('payment_id', $payment->id)
                ->lockForUpdate()
                ->first();

            if ($existingRefund) {
                return null;
            }

            return Refund::create([
                'student_id' => Auth::id(),
                'payment_id' => $payment->id,
                'invoice_id' => $payment->invoice?->id,
                'appointment_id' => $payment->appointment_id,
                'amount' => $payment->amount,
                'refund_amount' => $payment->amount,
                'reason' => $request->reason,
                'status' => 'pending',
                'refund_date' => null,
            ]);
        });

        if (!$refund) {
            return redirect()->route('student.refunds.index')->with('error', 'A refund request already exists for this payment.');
        }

        // Notify Admin about new refund request
        try {
            $automation->sendAdminNotification('new_refund_request', [
                'message' => "Student " . Auth::user()->name . " has requested a refund for Payment ID #{$payment->id}. Amount: {$payment->currency} {$payment->amount}.",
            ]);
            $automation->createInternalAdminNotification(
                'Refund',
                'Refund Requested',
                "Student " . Auth::user()->name . " requested a refund of {$payment->currency} {$payment->amount}.",
                $refund,
                Auth::user(),
                route('admin.refunds.index'),
                'corner-up-left'
            );
        } catch (\Throwable $th) {
            // Silently fail
        }
        
        $logger->log('Refund', 'Create', "Student '" . Auth::user()->name . "' requested a refund.", null, $refund->toArray());

        return redirect()->route('student.refunds.index')->with('success', 'Refund request submitted successfully. We will review it shortly.');
    }
}
