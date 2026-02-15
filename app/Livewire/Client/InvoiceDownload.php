<?php

namespace App\Livewire\Client;

use Livewire\Component;

class InvoiceDownload extends Component
{
    public function render()
    {
        $invoices = auth()->user()->invoices()
            ->with('order')
            ->latest()
            ->paginate(10);

        return view('livewire.client.invoice-download', [
            'invoices' => $invoices,
        ]);
    }
}
