<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generate(Order $order): Invoice
    {
        $order->load(['items', 'user']);

        $invoice = Invoice::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'amount' => $order->total,
            'issued_at' => now(),
        ]);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'order' => $order,
            'company' => [
                'name' => Setting::get('company_name', 'iCommerce Gabon'),
                'address' => Setting::get('company_address', 'Libreville, Gabon'),
                'phone' => Setting::get('company_phone', ''),
                'email' => Setting::get('admin_email', ''),
            ],
        ]);

        $path = 'invoices/' . $invoice->invoice_number . '.pdf';
        Storage::disk('local')->put($path, $pdf->output());

        $invoice->update(['pdf_path' => $path]);

        return $invoice;
    }
}
