<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class OrderService
{
    public function __construct(
        protected CouponService $couponService
    ) {}

    public function createCashOrder(int $userId, Collection $cartItems, string $address, string $phone): Order
    {
        $subtotal = $this->calculateSubtotal($cartItems);
        $coupon = $this->couponService->getActive();
        $discount = $coupon ? $this->couponService->calculateDiscount($coupon, $subtotal) : 0;
        $total = max(0, $subtotal - $discount);

        return DB::transaction(function () use ($userId, $cartItems, $address, $phone, $discount, $total, $coupon) {
            $order = Order::create([
                'user_id' => $userId,
                'total_price' => $total,
                'coupon_id' => $coupon?->id,
                'discount_amount' => $discount,
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'status' => 'pending',
                'address' => $address,
                'phone' => $phone,
            ]);

            $this->createOrderItems($order, $cartItems);

            if ($coupon) {
                $this->couponService->incrementUsage($coupon->id);
            }

            $this->cleanupCart($userId);

            return $order;
        });
    }

    public function createStripeOrder(int $userId, Collection $cartItems, string $address, string $phone, string $userEmail): array
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $subtotal = $this->calculateSubtotal($cartItems);
        $coupon = $this->couponService->getActive();
        $discount = $coupon ? $this->couponService->calculateDiscount($coupon, $subtotal) : 0;
        $total = max(0, $subtotal - $discount);

        return DB::transaction(function () use ($userId, $cartItems, $address, $phone, $userEmail, $discount, $total, $coupon) {
            $order = Order::create([
                'user_id' => $userId,
                'total_price' => $total,
                'coupon_id' => $coupon?->id,
                'discount_amount' => $discount,
                'payment_method' => 'stripe',
                'payment_status' => 'pending',
                'status' => 'pending',
                'address' => $address,
                'phone' => $phone,
            ]);

            $this->createOrderItems($order, $cartItems);

            $lineItems = $this->buildStripeLineItems($cartItems, $discount);

            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.success', ['order' => $order->id, 'session_id' => '{CHECKOUT_SESSION_ID}']),
                'cancel_url' => route('payment.cancel', ['order' => $order->id]),
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $userId,
                ],
                'customer_email' => $userEmail,
            ]);

            $order->update(['stripe_session_id' => $session->id]);

            return ['order' => $order, 'session_url' => $session->url];
        });
    }

    public function markAsPaid(int $orderId, ?string $paymentIntent = null): bool
    {
        return DB::transaction(function () use ($orderId, $paymentIntent) {
            $order = Order::find($orderId);

            if (! $order || $order->payment_status === 'paid') {
                return false;
            }

            $data = ['payment_status' => 'paid'];

            if ($paymentIntent) {
                $data['stripe_payment_intent'] = $paymentIntent;
            }

            $order->update($data);

            if ($order->coupon_id) {
                $this->couponService->incrementUsage($order->coupon_id);
            }

            $this->cleanupCart($order->user_id);

            return true;
        });
    }

    public function markAsFailed(int $orderId): bool
    {
        $order = Order::find($orderId);

        if (! $order || $order->payment_status !== 'pending') {
            return false;
        }

        return $order->update(['payment_status' => 'failed']);
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        return Order::where('id', $orderId)->update(['status' => $status]);
    }

    protected function calculateSubtotal(Collection $cartItems): float
    {
        return calculate_subtotal($cartItems);
    }

    protected function createOrderItems(Order $order, Collection $cartItems): void
    {
        $items = $cartItems->map(function ($item) use ($order) {
            return [
                'order_id' => $order->id,
                'food_id' => $item->food_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        OrderItem::insert($items);
    }

    protected function buildStripeLineItems(Collection $cartItems, float $discount): array
    {
        $lineItems = [];

        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->food->name,
                        'description' => $item->food->description,
                    ],
                    'unit_amount' => round($item->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        }

        if ($discount > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Discount',
                    ],
                    'unit_amount' => -round($discount * 100),
                ],
                'quantity' => 1,
            ];
        }

        return $lineItems;
    }

    protected function cleanupCart(int $userId): void
    {
        \App\Models\CartItem::where('user_id', $userId)->delete();
        Session::forget('coupon_code');
    }
}
