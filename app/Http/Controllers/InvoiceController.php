<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function download(Invoice $invoice)
    {
        // Only owner or admin can download
        if (!auth()->user()->isAdmin() && $invoice->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$invoice->pdf_path || !Storage::disk('local')->exists($invoice->pdf_path)) {
            abort(404, 'Facture introuvable.');
        }

        return Storage::disk('local')->download(
            $invoice->pdf_path,
            'Facture-' . $invoice->invoice_number . '.pdf'
        );
    }
}
