<?php

namespace App\Livewire\Client;

use App\Enums\OrderStatus;
use App\Models\Order;
use Livewire\Component;

class OrderDetail extends Component
{
    public Order $order;
    public bool $editing = false;
    public bool $confirmingCancel = false;
    public string $cancelReason = '';

    // Editable item fields
    public array $editItems = [];

    public function mount(Order $order): void
    {
        $this->order = $order->load(['items', 'payments', 'invoice', 'statusHistories.changedByUser']);
    }

    public function getCanEditProperty(): bool
    {
        return $this->order->status === OrderStatus::Commandee;
    }

    public function getCanCancelProperty(): bool
    {
        return in_array($this->order->status, [OrderStatus::Commandee, OrderStatus::Payee]);
    }

    public function startEditing(): void
    {
        if (!$this->canEdit) return;

        $this->editItems = $this->order->items->map(fn ($item) => [
            'id' => $item->id,
            'scraped_title' => $item->scraped_title,
            'quantity' => $item->quantity,
            'color' => $item->color ?? '',
            'size' => $item->size ?? '',
        ])->toArray();

        $this->editing = true;
    }

    public function cancelEditing(): void
    {
        $this->editing = false;
        $this->editItems = [];
    }

    public function saveChanges(): void
    {
        if (!$this->canEdit) return;

        $this->validate([
            'editItems.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($this->editItems as $editItem) {
            $this->order->items()->where('id', $editItem['id'])->update([
                'quantity' => $editItem['quantity'],
                'color' => $editItem['color'] ?: null,
                'size' => $editItem['size'] ?: null,
            ]);
        }

        $this->order->refresh();
        $this->order->calculateTotals();
        $this->order->load(['items', 'payments', 'invoice', 'statusHistories.changedByUser']);

        $this->editing = false;
        $this->editItems = [];

        session()->flash('success', 'Commande modifiee avec succes.');
    }

    public function removeItem(int $itemId): void
    {
        if (!$this->canEdit) return;
        if ($this->order->items->count() <= 1) {
            $this->addError('items', 'La commande doit contenir au moins un article.');
            return;
        }

        $this->order->items()->where('id', $itemId)->delete();
        $this->order->refresh();
        $this->order->calculateTotals();
        $this->order->load(['items', 'payments', 'invoice', 'statusHistories.changedByUser']);

        $this->editItems = collect($this->editItems)->filter(fn ($i) => $i['id'] !== $itemId)->values()->toArray();

        session()->flash('success', 'Article supprime.');
    }

    public function confirmCancel(): void
    {
        $this->confirmingCancel = true;
    }

    public function dismissCancel(): void
    {
        $this->confirmingCancel = false;
        $this->cancelReason = '';
    }

    public function cancelOrder(): void
    {
        if (!$this->canCancel) return;

        $comment = $this->cancelReason ?: 'Annulee par le client';

        $this->order->transitionTo(OrderStatus::Annulee, auth()->id(), $comment);
        $this->order->refresh();
        $this->order->load(['items', 'payments', 'invoice', 'statusHistories.changedByUser']);

        $this->confirmingCancel = false;
        $this->cancelReason = '';

        session()->flash('success', 'Commande annulee.');
    }

    public function render()
    {
        return view('livewire.client.order-detail');
    }
}
