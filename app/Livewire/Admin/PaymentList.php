<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentList extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Payment::with(['order', 'user'])->latest();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->whereHas('order', fn ($q) => $q->where('reference', 'like', '%' . $this->search . '%'));
        }

        return view('livewire.admin.payment-list', [
            'payments' => $query->paginate(15),
        ]);
    }
}
