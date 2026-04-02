@extends('layouts.app')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light p-5 rounded">
                    @if($order->payment_method === 'stripe' && $order->payment_status === 'paid')
                    <i class="fa fa-check-circle text-success mb-4" style="font-size: 5rem;"></i>
                    <h1 class="mb-3">Payment Successful!</h1>
                    <p class="mb-4">Your payment has been processed successfully.</p>
                    @elseif($order->payment_method === 'stripe' && $order->payment_status === 'failed')
                    <i class="fa fa-times-circle text-danger mb-4" style="font-size: 5rem;"></i>
                    <h1 class="mb-3">Payment Failed</h1>
                    <p class="mb-4">Your payment could not be processed. Please try again.</p>
                    @else
                    <i class="fa fa-check-circle text-success mb-4" style="font-size: 5rem;"></i>
                    <h1 class="mb-3">Thank You!</h1>
                    <p class="mb-4">Your order has been placed successfully.</p>
                    @endif

                    <div class="bg-white p-4 rounded mb-4">
                        <h5 class="mb-3">Order Details</h5>
                        <p class="mb-2"><strong>Order ID:</strong> #{{ $order->id }}</p>
                        <p class="mb-2"><strong>Total:</strong> <span class="text-primary fw-bold">${{ number_format($order->total_price, 2) }}</span></p>
                        @if($order->discount_amount > 0)
                        <p class="mb-2 text-success"><strong>Discount Applied:</strong> -${{ number_format($order->discount_amount, 2) }}</p>
                        @endif
                        <p class="mb-2"><strong>Payment Method:</strong> <span class="badge bg-{{ $order->payment_method === 'stripe' ? 'info' : 'secondary' }}">{{ ucfirst($order->payment_method) }}</span></p>
                        <p class="mb-2"><strong>Payment Status:</strong> <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'failed' ? 'danger' : 'warning') }}">{{ ucfirst($order->payment_status) }}</span></p>
                        <p class="mb-2"><strong>Order Status:</strong> <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span></p>
                        <p class="mb-0"><strong>Delivery to:</strong> {{ $order->address }}</p>
                    </div>

                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('my.orders') }}" class="btn btn-primary py-3 px-4">View My Orders</a>
                        <a href="{{ route('home.index') }}" class="btn btn-outline-primary py-3 px-4">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
