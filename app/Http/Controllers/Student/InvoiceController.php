<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Setting;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::where('student_id', Auth::id())
            ->with(['appointment.subject', 'payment'])
            ->orderBy('invoice_date', 'desc')
            ->paginate(10);

        return view('student.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        if ($invoice->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice.');
        }

        $invoice->load(['student', 'appointment.subject', 'payment']);

        return view('student.invoices.show', compact('invoice'));
    }

    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice.');
        }

        if (!$invoice->pdf_path || !Storage::disk('local')->exists($invoice->pdf_path)) {
            $invoiceService = app(InvoiceService::class);
            $invoice = $invoiceService->generatePdf($invoice);
        }

        return Storage::disk('local')->download($invoice->pdf_path, 'Invoice_' . $invoice->invoice_number . '.pdf');
    }

    public function print($id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice.');
        }

        $invoice->load(['student', 'appointment.subject', 'payment']);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'platformName' => Setting::get('platform_name', 'Drumroll'),
            'contactEmail' => Setting::get('contact_email', Setting::get('support_email', 'hello@drumroll.com')),
        ]);

        return $pdf->stream('Invoice_' . $invoice->invoice_number . '.pdf');
    }
}
