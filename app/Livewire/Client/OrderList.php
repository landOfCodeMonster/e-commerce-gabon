<?php

namespace App\Livewire\Client;

use App\Enums\OrderStatus;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $search = '';
    public int $limit = 0;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = auth()->user()->orders()
            ->with('items')
            ->latest();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where('reference', 'like', '%' . $this->search . '%');
        }

        $orders = $this->limit > 0
            ? $query->limit($this->limit)->get()
            : $query->paginate(10);

        return view('livewire.client.order-list', [
            'orders' => $orders,
            'statuses' => OrderStatus::cases(),
        ]);
    }
}
