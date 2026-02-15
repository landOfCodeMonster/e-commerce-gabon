<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Notifications\OrderStatusChangedNotification;
use App\Services\InvoiceService;
use Livewire\Component;

class OrderDetailAdmin extends Component
{
    public Order $order;
    public string $newStatus = '';
    public string $statusComment = '';
    public float $profitMargin = 0;
    public string $adminNotes = '';
    public float $shippingFee = 0;

    public function mount(Order $order): void
    {
        $this->order = $order->load(['items', 'payments', 'invoice', 'statusHistories.changedByUser', 'user']);
        $this->profitMargin = (float) $order->profit_margin;
        $this->adminNotes = $order->admin_notes ?? '';
        $this->shippingFee = (float) $order->shipping_fee;
    }

    public function updateStatus(): void
    {
        $this->validate([
            'newStatus' => 'required|string',
        ]);

        $newStatusEnum = OrderStatus::from($this->newStatus);

        try {
            $this->order->transitionTo($newStatusEnum, auth()->id(), $this->statusComment ?: null);
            $this->order->refresh();

            // Notify client
            $this->order->user->notify(new OrderStatusChangedNotification($this->order));

            // Generate invoice when delivered
            if ($newStatusEnum === OrderStatus::Livree) {
                app(InvoiceService::class)->generate($this->order);
            }

            $this->statusComment = '';
            $this->newStatus = '';
            session()->flash('success', 'Statut mis à jour avec succès.');
        } catch (\InvalidArgumentException $e) {
            $this->addError('status', $e->getMessage());
        }
    }

    public function updateProfitMargin(): void
    {
        $this->validate(['profitMargin' => 'required|numeric|min:0']);

        $this->order->update(['profit_margin' => $this->profitMargin]);
        $this->order->calculateTotals();
        $this->order->refresh();

        session()->flash('success', 'Marge bénéficiaire mise à jour.');
    }

    public function updateShippingFee(): void
    {
        $this->validate(['shippingFee' => 'required|numeric|min:0']);

        $this->order->update(['shipping_fee' => $this->shippingFee]);
        $this->order->calculateTotals();
        $this->order->refresh();

        session()->flash('success', 'Frais de livraison mis à jour.');
    }

    public function saveAdminNotes(): void
    {
        $this->order->update(['admin_notes' => $this->adminNotes]);
        session()->flash('success', 'Notes sauvegardées.');
    }

    public function getAllowedTransitionsProperty(): array
    {
        return $this->order->status->allowedTransitions();
    }

    public function render()
    {
        return view('livewire.admin.order-detail-admin');
    }
}
