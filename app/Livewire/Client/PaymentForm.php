<?php

namespace App\Livewire\Client;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\OrderPaidNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class PaymentForm extends Component
{
    public Order $order;
    public string $paymentMethod = 'stripe';
    public bool $processing = false;

    public function mount(Order $order): void
    {
        $this->order = $order;
    }

    public function processPayment(): void
    {
        $this->processing = true;

        try {
            if ($this->paymentMethod === 'stripe') {
                $this->processStripePayment();
            } elseif ($this->paymentMethod === 'paypal') {
                $this->processPayPalPayment();
            }
        } catch (\Exception $e) {
            $this->addError('payment', 'Erreur lors du paiement : ' . $e->getMessage());
            $this->processing = false;
        }
    }

    private function processStripePayment(): void
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($this->order->currency),
                    'product_data' => [
                        'name' => 'Commande #' . $this->order->reference,
                    ],
                    'unit_amount' => (int) ($this->order->total * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('client.orders.show', $this->order) . '?payment=success',
            'cancel_url' => route('client.orders.show', $this->order) . '?payment=cancel',
            'metadata' => [
                'order_id' => $this->order->id,
                'user_id' => auth()->id(),
            ],
        ]);

        // Create pending payment record
        Payment::create([
            'order_id' => $this->order->id,
            'user_id' => auth()->id(),
            'payment_method' => 'stripe',
            'transaction_id' => $session->id,
            'amount' => $this->order->total,
            'currency' => $this->order->currency,
            'status' => 'pending',
        ]);

        $this->redirect($session->url);
    }

    private function processPayPalPayment(): void
    {
        $provider = new \Srmklive\PayPal\Services\PayPal();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => $this->order->currency,
                    'value' => number_format($this->order->total, 2, '.', ''),
                ],
                'reference_id' => $this->order->reference,
            ]],
            'application_context' => [
                'return_url' => route('client.orders.show', $this->order) . '?payment=success&method=paypal',
                'cancel_url' => route('client.orders.show', $this->order) . '?payment=cancel',
            ],
        ]);

        if (isset($response['id'])) {
            Payment::create([
                'order_id' => $this->order->id,
                'user_id' => auth()->id(),
                'payment_method' => 'paypal',
                'transaction_id' => $response['id'],
                'amount' => $this->order->total,
                'currency' => $this->order->currency,
                'status' => 'pending',
            ]);

            $approvalUrl = collect($response['links'])->firstWhere('rel', 'approve')['href'] ?? null;
            if ($approvalUrl) {
                $this->redirect($approvalUrl);
                return;
            }
        }

        $this->addError('payment', 'Erreur PayPal. Veuillez réessayer.');
        $this->processing = false;
    }

    public function simulatePayment(): void
    {
        if (!app()->environment('local')) return;

        $payment = Payment::create([
            'order_id' => $this->order->id,
            'user_id' => auth()->id(),
            'payment_method' => 'simulation',
            'transaction_id' => 'SIM-' . uniqid(),
            'amount' => $this->order->total,
            'currency' => $this->order->currency,
            'status' => 'completed',
        ]);

        $this->order->transitionTo(OrderStatus::Payee, auth()->id(), 'Paiement simulé');

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new OrderPaidNotification($this->order));

        session()->flash('success', 'Paiement effectué avec succès !');
        $this->redirect(route('client.orders.show', $this->order), navigate: true);
    }

    public function render()
    {
        return view('livewire.client.payment-form');
    }
}
