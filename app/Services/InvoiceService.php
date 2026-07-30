<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceService
{
    /**
     * Generate an invoice for a successful payment.
     */
    public function generateForPayment(Payment $payment): ?Invoice
    {
        if ($payment->payment_status !== 'successful') {
            return null;
        }

        // Check if invoice already exists for this payment
        $existingInvoice = Invoice::where('payment_id', $payment->id)->first();
        if ($existingInvoice) {
            return $existingInvoice;
        }

        $appointment = $payment->appointment;
        
        $invoiceNumber = $this->generateInvoiceNumber();

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'student_id' => $payment->student_id,
            'appointment_id' => $appointment->id,
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'status' => 'generated',
            'invoice_date' => now()->toDateString(),
        ]);

        $this->generatePdf($invoice);

        return $invoice;
    }

    /**
     * Generate and store the PDF file for an invoice.
     */
    public function generatePdf(Invoice $invoice): Invoice
    {
        $invoice->loadMissing(['student', 'appointment.subject', 'payment']);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'platformName' => Setting::get('platform_name', 'Drumroll'),
            'contactEmail' => Setting::get('contact_email', 'admin@drumroll.com'),
        ]);

        $fileName = 'invoices/' . $invoice->invoice_number . '.pdf';
        
        // Store in the private local disk or public disk? Usually invoices are private.
        // We'll store in local 'private' disk if it exists, or just 'local' disk so it's not publicly accessible without auth.
        Storage::disk('local')->put($fileName, $pdf->output());

        $invoice->update(['pdf_path' => $fileName]);

        return $invoice;
    }

    /**
     * Delete an invoice and its associated PDF.
     */
    public function deleteInvoice(Invoice $invoice): bool
    {
        if ($invoice->pdf_path && Storage::disk('local')->exists($invoice->pdf_path)) {
            Storage::disk('local')->delete($invoice->pdf_path);
        }

        return $invoice->delete();
    }

    /**
     * Generate a unique invoice number.
     */
    private function generateInvoiceNumber(): string
    {
        $prefix = Setting::get('invoice_prefix', 'INV-') . date('Ym') . '-';
        $startingNumber = (int) Setting::get('invoice_starting_number', '1');
        $padding = max(4, strlen((string) $startingNumber));
        
        $latest = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($latest) {
            $sequence = (int) Str::after($latest->invoice_number, $prefix);
            $next = $sequence + 1;
        } else {
            $next = $startingNumber;
        }

        return $prefix . str_pad((string) $next, $padding, '0', STR_PAD_LEFT);
    }
}
