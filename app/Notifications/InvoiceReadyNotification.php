<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceReadyNotification extends Notification
{
    use Queueable;

    public function __construct(public Invoice $invoice) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Facture disponible - ' . $this->invoice->invoice_number)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Votre facture ' . $this->invoice->invoice_number . ' est disponible.')
            ->line('Montant : ' . number_format($this->invoice->amount, 0, ',', ' ') . ' XAF')
            ->action('Télécharger la facture', route('client.invoices.download', $this->invoice))
            ->line('Merci pour votre achat !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'message' => 'Facture ' . $this->invoice->invoice_number . ' disponible',
            'type' => 'invoice_ready',
        ];
    }
}
