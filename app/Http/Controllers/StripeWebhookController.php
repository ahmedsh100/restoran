<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::find($orderId);

                if ($order && $order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'stripe_payment_intent' => $session->payment_intent,
                    ]);
                }
            }
        }

        if ($event->type === 'payment_intent.payment_failed') {
            $paymentIntent = $event->data->object;
            $sessionId = $paymentIntent->metadata->checkout_session_id ?? null;

            if ($sessionId) {
                $session = Session::retrieve($sessionId);
                $orderId = $session->metadata->order_id ?? null;

                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order) {
                        $order->update(['payment_status' => 'failed']);
                    }
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
