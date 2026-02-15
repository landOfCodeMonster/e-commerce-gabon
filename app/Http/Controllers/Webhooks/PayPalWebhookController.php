<?php

namespace App\Http\Controllers\Webhooks;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\OrderPaidNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PayPalWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();
        $eventType = $data['event_type'] ?? null;

        Log::info('PayPal webhook received', ['event_type' => $eventType]);

        if ($eventType === 'CHECKOUT.ORDER.APPROVED' || $eventType === 'PAYMENT.CAPTURE.COMPLETED') {
            $resourceId = $data['resource']['id'] ?? null;

            if ($resourceId) {
                $payment = Payment::where('transaction_id', $resourceId)
                    ->orWhere('transaction_id', $data['resource']['supplementary_data']['related_ids']['order_id'] ?? '')
                    ->first();

                if ($payment && $payment->status !== 'completed') {
                    $payment->update([
                        'status' => 'completed',
                        'gateway_response' => $data,
                    ]);

                    $order = $payment->order;
                    if ($order->status === OrderStatus::Commandee) {
                        $order->transitionTo(OrderStatus::Payee, null, 'Paiement PayPal confirmé');

                        $admins = User::where('role', 'admin')->get();
                        Notification::send($admins, new OrderPaidNotification($order));
                    }
                }
            }
        }

        return response('OK', 200);
    }
}
