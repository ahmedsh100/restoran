<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showCheckout(Request $request)
    {
        $cartItems = auth()->user()->cart()->with('food')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $discount = 0;
        $coupon = null;
        $couponCode = Session::get('coupon_code');

        if ($couponCode) {
            $coupon = Coupon::where('code', strtoupper($couponCode))->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                Session::forget('coupon_code');
            }
        }

        $total = $subtotal - $discount;

        return view('checkout', compact('cartItems', 'subtotal', 'discount', 'total', 'coupon'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (! $coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        if (! $coupon->isValid()) {
            if ($coupon->expiry_date->isPast()) {
                return back()->with('error', 'This coupon has expired.');
            }
            if ($coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit) {
                return back()->with('error', 'This coupon has reached its usage limit.');
            }

            return back()->with('error', 'This coupon is no longer active.');
        }

        Session::put('coupon_code', $coupon->code);

        return back()->with('success', 'Coupon applied successfully!');
    }

    public function removeCoupon()
    {
        Session::forget('coupon_code');

        return back()->with('success', 'Coupon removed.');
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cash,stripe',
        ]);

        $cartItems = $request->user()->cart()->with('food')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $discount = 0;
        $couponId = null;
        $couponCode = Session::get('coupon_code');

        if ($couponCode) {
            $coupon = Coupon::where('code', strtoupper($couponCode))->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
                $couponId = $coupon->id;
            }
        }

        $total = $subtotal - $discount;

        if ($request->payment_method === 'stripe') {
            return $this->createStripeSession($request, $cartItems, $subtotal, $discount, $couponId, $total);
        }

        return $this->createCashOrder($request, $cartItems, $subtotal, $discount, $couponId, $total);
    }

    protected function createCashOrder($request, $cartItems, $subtotal, $discount, $couponId, $total)
    {
        $order = DB::transaction(function () use ($request, $cartItems, $total, $discount, $couponId) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => $total,
                'coupon_id' => $couponId,
                'discount_amount' => $discount,
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'status' => 'pending',
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $item->food_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            if ($couponId) {
                Coupon::where('id', $couponId)->increment('used_count');
            }

            $request->user()->cart()->delete();
            Session::forget('coupon_code');

            return $order;
        });

        return redirect()->route('order.success', $order->id);
    }

    protected function createStripeSession($request, $cartItems, $subtotal, $discount, $couponId, $total)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

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

        $order = Order::create([
            'user_id' => $request->user()->id,
            'total_price' => $total,
            'coupon_id' => $couponId,
            'discount_amount' => $discount,
            'payment_method' => 'stripe',
            'payment_status' => 'pending',
            'status' => 'pending',
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $item->food_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.success', ['order' => $order->id, 'session_id' => '{CHECKOUT_SESSION_ID}']),
            'cancel_url' => route('payment.cancel', ['order' => $order->id]),
            'metadata' => [
                'order_id' => $order->id,
                'user_id' => $request->user()->id,
            ],
            'customer_email' => $request->user()->email,
        ]);

        $order->update(['stripe_session_id' => $session->id]);

        if ($couponId) {
            Coupon::where('id', $couponId)->increment('used_count');
        }

        $request->user()->cart()->delete();
        Session::forget('coupon_code');

        return redirect($session->url);
    }

    public function paymentSuccess(Request $request)
    {
        $order = Order::where('user_id', auth()->id())
            ->findOrFail($request->order);

        if ($order->payment_status !== 'paid') {
            $order->update(['payment_status' => 'paid']);
        }

        return redirect()->route('order.success', $order->id);
    }

    public function paymentCancel(Request $request)
    {
        $order = Order::where('user_id', auth()->id())
            ->findOrFail($request->order);

        if ($order->payment_status === 'pending') {
            $order->update(['payment_status' => 'failed']);
        }

        return redirect()->route('checkout')->with('error', 'Payment was cancelled. Your order has been saved — please try again.');
    }

    public function myOrders()
    {
        $orders = auth()->user()->orders()->with('items.food')->latest()->get();

        return view('my-orders', compact('orders'));
    }

    public function orderSuccess($orderId)
    {
        $order = Order::where('user_id', auth()->id())
            ->with('coupon')
            ->findOrFail($orderId);

        return view('order-success', compact('order'));
    }
}
