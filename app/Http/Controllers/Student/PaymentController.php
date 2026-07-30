<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Services\IntegrationAutomationService;
use App\Services\InvoiceService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        private StripeService $stripeService,
        private IntegrationAutomationService $automation,
        private InvoiceService $invoiceService
    ) {
    }

    /**
     * Display the payment page for a specific appointment.
     */
    public function show(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $appointment->load('payment');

        if ($appointment->payment && $appointment->payment->payment_status === 'successful') {
            return redirect()->route('student.booking.index')->with('info', 'This booking is already paid.');
        }

        return view('student.payments.show', compact('appointment'));
    }

    /**
     * Create a Stripe Checkout session.
     */
    public function checkout(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) abort(403);

        $appointment->load('payment');

        if ($appointment->payment && $appointment->payment->payment_status === 'successful') {
            return redirect()->route('student.booking.index')->with('info', 'Already paid.');
        }

        try {
            $session = $this->stripeService->createCheckoutSession($appointment, Auth::user());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        // Create or update pending payment record
        Payment::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'student_id' => Auth::id(),
                'stripe_payment_id' => $session->id,
                'amount' => $session->amount_total / 100,
                'currency' => $session->currency,
                'payment_status' => 'pending',
            ]
        );

        return redirect($session->url);
    }

    /**
     * Handle payment success.
     */
    public function success(Request $request, \App\Services\AuditLoggerService $logger)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) return redirect()->route('student.booking.index');

        $session = $this->stripeService->getSession($sessionId);
        
        $payment = Payment::with(['appointment.student', 'appointment.subject'])->where('stripe_payment_id', $sessionId)->lockForUpdate()->firstOrFail();

        // Verify the payment belongs to the authenticated user
        if ($payment->student_id !== Auth::id()) {
            abort(403);
        }
        
        if ($session->payment_status === 'paid') {
            $oldData = $payment->toArray();
            $payment->update([
                'payment_status' => 'successful',
                'transaction_id' => $session->payment_intent,
                'payment_date' => now(),
            ]);

            // Update appointment status to confirmed
            $appointment = $payment->appointment;
            $appointment->update(['status' => 'confirmed']);
            
            // Generate Invoice
            try {
                $invoice = $this->invoiceService->generateForPayment($payment);
                if ($invoice) {
                    $this->automation->createInternalAdminNotification(
                        'Invoice',
                        'Invoice Generated',
                        "Invoice #{$invoice->invoice_number} generated for {$appointment->student->name}.",
                        $invoice,
                        $appointment->student,
                        route('admin.invoices.index'),
                        'file-text'
                    );
                    $logger->log('Invoice', 'Create', "Invoice #{$invoice->invoice_number} automatically generated.", null, $invoice->toArray());
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Invoice generation failed: ' . $e->getMessage());
            }

            // Trigger Google Meet generation and other automations
            try {
                $this->automation->automateForStatus($appointment->refresh());
                $this->automation->createInternalAdminNotification(
                    'Payment',
                    'Payment Successful',
                    "{$appointment->student->name} paid {$payment->currency} {$payment->amount} for a session.",
                    $payment,
                    $appointment->student,
                    route('admin.payments.index'),
                    'dollar-sign'
                );
            } catch (\Exception $e) {
                // Silently fail or log, don't break the success page
                \Illuminate\Support\Facades\Log::error('Automation failed after payment: ' . $e->getMessage());
            }
            
            $logger->log('Payment', 'Payment', "Successful payment of {$payment->currency} {$payment->amount}.", $oldData, $payment->refresh()->toArray());

            return view('student.payments.success', compact('appointment'));
        }

        return redirect()->route('student.payment.failed', ['appointment' => $payment->appointment_id]);
    }

    /**
     * Handle payment failure.
     */
    public function failed(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) abort(403);

        $payment = $appointment->payment;
        if ($payment && $payment->payment_status !== 'successful') {
            $payment->update(['payment_status' => 'failed']);
        }

        return view('student.payments.failed', compact('appointment'));
    }

    /**
     * Display student's payment history.
     */
    public function history()
    {
        $payments = Payment::where('student_id', Auth::id())
            ->with(['appointment.subject', 'appointment.doubt', 'invoice'])
            ->latest()
            ->paginate(10);

        return view('student.payments.history', compact('payments'));
    }
}
