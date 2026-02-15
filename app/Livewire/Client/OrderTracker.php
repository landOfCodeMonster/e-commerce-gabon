<?php

namespace App\Livewire\Client;

use App\Models\Order;
use Livewire\Component;

class OrderTracker extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.client.order-tracker');
    }
}
