@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Cart</h5>
            <h1 class="mb-5">Your Shopping Cart</h1>
        </div>

        @php
        $hasUnavailableItems = $cartItems->contains(function($item) {
            return !$item->food->is_available;
        });
        @endphp

        @if($hasUnavailableItems)
        <div class="alert alert-warning mb-4">
            <i class="fa fa-exclamation-triangle me-2"></i>Some items in your cart are no longer available. Please remove them before checkout.
        </div>
        @endif

        @php
        $hasUnavailableItems = $cartItems->contains(function($item) {
            return !$item->food->is_available;
        });
        @endphp

        @if($hasUnavailableItems)
        <div class="alert alert-warning mb-4">
            <i class="fa fa-exclamation-triangle me-2"></i>Some items in your cart are no longer available. Please remove them before checkout.
        </div>
        @endif

        @if($cartItems->count() > 0)
        <div class="row g-5">
            <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                <div class="order-summary p-4">
                    @foreach($cartItems as $item)
                    <div class="order-item {{ !$item->food->is_available ? 'opacity-50' : '' }}">
                        <img src="{{ asset('storage/'.$item->food->image) }}" alt="{{ $item->food->name }}" data-fallback="{{ asset('assets/img/menu-1.jpg') }}">
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $item->food->name }} @if(!$item->food->is_available)<span class="badge bg-danger">Unavailable</span>@endif</h6>
                            <small class="text-muted">{{ $item->food->category }}</small>
                            <div class="d-flex align-items-center mt-2">
                                <span class="text-muted me-3">Qty: {{ $item->quantity }}</span>
                                <span class="text-primary fw-bold">${{ number_format($item->price, 2) }} each</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="price-tag mb-2">${{ number_format($item->subtotal, 2) }}</div>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fa fa-trash me-1"></i>Remove
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="order-summary p-4">
                    <h5 class="mb-4">Order Summary</h5>
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">{{ $item->food->name }} x{{ $item->quantity }}</span>
                        <span>${{ number_format($item->subtotal, 2) }}</span>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">Total</h5>
                        <h5 class="text-primary mb-0">${{ number_format($total, 2) }}</h5>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg">
                            <i class="fa fa-credit-card me-2"></i>Proceed to Checkout
                        </a>
                        <a href="{{ route('menu') }}" class="btn btn-outline-primary">
                            <i class="fa fa-utensils me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="empty-state wow fadeInUp" data-wow-delay="0.1s">
            <div class="empty-state-icon">
                <i class="fas fa-cart-shopping"></i>
            </div>
            <h4>Your Cart is Empty</h4>
            <p>Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('menu') }}" class="btn btn-primary btn-lg">
                <i class="fa fa-utensils me-2"></i>Browse Our Menu
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
