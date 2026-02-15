<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification
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
            ->subject('Paiement reçu - Commande #' . $this->order->reference)
            ->greeting('Paiement confirmé !')
            ->line('La commande #' . $this->order->reference . ' a été payée.')
            ->line('Client : ' . $this->order->user->name)
            ->line('Montant : ' . number_format($this->order->total, 0, ',', ' ') . ' ' . $this->order->currency)
            ->action('Gérer la commande', route('admin.orders.show', $this->order))
            ->line('La commande est en attente de traitement.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'reference' => $this->order->reference,
            'message' => 'Paiement reçu pour la commande #' . $this->order->reference,
            'type' => 'order_paid',
        ];
    }
}
