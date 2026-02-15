<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle commande #' . $this->order->reference)
            ->greeting('Nouvelle commande reçue !')
            ->line('Client : ' . $this->order->user->name)
            ->line('Montant : ' . number_format($this->order->total, 0, ',', ' ') . ' ' . $this->order->currency)
            ->action('Voir la commande', route('admin.orders.show', $this->order))
            ->line('Merci.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'reference' => $this->order->reference,
            'message' => 'Nouvelle commande #' . $this->order->reference . ' de ' . $this->order->user->name,
            'type' => 'new_order',
        ];
    }
}
