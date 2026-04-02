@extends('layouts.app')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Checkout</h5>
            <h1 class="mb-5">Complete Your Order</h1>
        </div>

        @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <div class="row g-5">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <h4 class="mb-4">Order Summary</h4>
                <div class="bg-light p-4 rounded">
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/'.$item->food->image) }}" alt="{{ $item->food->name }}" class="img-fluid rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1">{{ $item->food->name }}</h6>
                                <small class="text-muted">Qty: {{ $item->quantity }}</small>
                            </div>
                        </div>
                        <span class="text-primary fw-bold">${{ number_format($item->quantity * $item->price, 2) }}</span>
                    </div>
                    @endforeach

                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>

                        @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount @if($coupon)({{ $coupon->type === 'percentage' ? $coupon->value.'%' : '$'.number_format($coupon->value, 2) }}) @endif</span>
                            <span>-${{ number_format($discount, 2) }}</span>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <h5 class="mb-0">Total</h5>
                            <h4 class="text-primary mb-0">${{ number_format($total, 2) }}</h4>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3">Have a Coupon?</h6>
                        @if($coupon)
                        <div class="d-flex justify-content-between align-items-center bg-success bg-opacity-10 p-3 rounded">
                            <div>
                                <span class="badge bg-success me-2">{{ $coupon->code }}</span>
                                <small class="text-success">Discount applied!</small>
                            </div>
                            <form action="{{ route('coupon.remove') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </div>
                        @else
                        <form action="{{ route('coupon.apply') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="code" class="form-control" placeholder="Enter coupon code" value="{{ old('code') }}" style="text-transform: uppercase;">
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                <h4 class="mb-4">Delivery Details</h4>
                <form action="{{ route('place.order') }}" method="POST" id="checkoutForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Delivery Address" value="{{ old('address') }}" required>
                                <label for="address">Delivery Address</label>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Phone Number" value="{{ old('phone') }}" required>
                                <label for="phone">Phone Number</label>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <h6 class="mb-3">Payment Method</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="payment_method" id="payCash" value="cash" checked>
                                    <label class="btn btn-outline-primary w-100 py-3" for="payCash">
                                        <i class="fa fa-money-bill-wave fa-2x mb-2 d-block"></i>
                                        Cash on Delivery
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="payment_method" id="payStripe" value="stripe">
                                    <label class="btn btn-outline-primary w-100 py-3" for="payStripe">
                                        <i class="fa fa-credit-card fa-2x mb-2 d-block"></i>
                                        Pay with Stripe
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 py-3" id="submitBtn">
                                <i class="fa fa-check me-2"></i>Place Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
