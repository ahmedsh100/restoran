<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\CouponService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected CouponService $couponService
    ) {
        $this->middleware('auth');
    }

    public function showCheckout(Request $request)
    {
        $cartItems = $request->user()->cart()->with('food')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = calculate_subtotal($cartItems);
        $coupon = $this->couponService->getActive();
        $discount = $coupon ? $this->couponService->calculateDiscount($coupon, $subtotal) : 0;
        $total = calculate_total($subtotal, $discount);

        return view('checkout', compact('cartItems', 'subtotal', 'discount', 'total', 'coupon'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $result = $this->couponService->apply($request->code);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function removeCoupon()
    {
        $this->couponService->remove();

        return back()->with('success', 'Coupon removed.');
    }

    public function placeOrder(CheckoutRequest $request)
    {
        $cartItems = $request->user()->cart()->with('food')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        foreach ($cartItems as $item) {
            if (isset($item->food->is_available) && ! $item->food->is_available) {
                return redirect()->back()->with('error', "The item '{$item->food->name}' is no longer available. Please remove it from your cart.");
            }
        }

        if ($request->payment_method === 'stripe') {
            return $this->processStripePayment($request, $cartItems);
        }

        return $this->processCashPayment($request, $cartItems);
    }

    public function paymentSuccess(Request $request)
    {
        $order = auth()->user()->orders()->find($request->order);

        if (! $order) {
            return redirect()->route('my.orders')->with('error', 'Order not found.');
        }

        $this->orderService->markAsPaid(
            $order->id,
            $request->session_id
        );

        return redirect()->route('order.success', ['orderId' => $order->id])->with('success', 'Payment successful!');
    }

    public function paymentCancel(Request $request)
    {
        $order = auth()->user()->orders()->find($request->order);

        if (! $order) {
            return redirect()->route('my.orders')->with('error', 'Order not found.');
        }

        $this->orderService->markAsFailed($order->id);

        return redirect()->route('checkout')->with('error', 'Payment was cancelled. Please try again.');
    }

    public function myOrders(Request $request)
    {
        $orders = $request->user()->orders()->with('items.food')->latest()->get();

        return view('my-orders', compact('orders'));
    }

    public function orderSuccess($orderId)
    {
        $order = auth()->user()->orders()
            ->with(['coupon', 'items.food'])
            ->findOrFail($orderId);

        return view('order-success', compact('order'));
    }

    protected function processCashPayment(Request $request, $cartItems)
    {
        $order = $this->orderService->createCashOrder(
            $request->user()->id,
            $cartItems,
            $request->address,
            $request->phone
        );

        return redirect()->route('order.success', ['orderId' => $order->id])->with('success', 'Order placed successfully!');
    }

    protected function processStripePayment(Request $request, $cartItems)
    {
        $result = $this->orderService->createStripeOrder(
            $request->user()->id,
            $cartItems,
            $request->address,
            $request->phone,
            $request->user()->email
        );

        return redirect($result['session_url']);
    }
}
