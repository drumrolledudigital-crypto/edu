<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('admin.invoices.index');
    }

    public function list()
    {
        $invoices = Invoice::with(['student', 'appointment.subject'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $invoices
        ]);
    }

    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        if (!$invoice->pdf_path || !Storage::disk('local')->exists($invoice->pdf_path)) {
            return back()->with('error', 'PDF file not found.');
        }

        return Storage::disk('local')->download($invoice->pdf_path, 'Invoice_' . $invoice->invoice_number . '.pdf');
    }

    public function regenerate($id, InvoiceService $invoiceService)
    {
        $invoice = Invoice::findOrFail($id);

        try {
            $invoiceService->generatePdf($invoice);
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice PDF regenerated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to regenerate PDF. Please try again later.'
            ], 500);
        }
    }

    public function destroy($id, InvoiceService $invoiceService)
    {
        $invoice = Invoice::findOrFail($id);

        try {
            $invoiceService->deleteInvoice($invoice);
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice deleted successfully.'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Invoice delete failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete invoice. Please try again later.'
            ], 500);
        }
    }
}
