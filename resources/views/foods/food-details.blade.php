@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="row g-0">
                    <div class="col-md-5">
                        <img src="{{ asset('assets/img/' . $food->image) }}" class="img-fluid rounded-start w-100 h-100" style="object-fit: cover;" alt="{{ $food->name }}">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body p-5">
                            <span class="badge bg-primary mb-2">{{ $food->category }}</span>
                            <h2 class="card-title">{{ $food->name }}</h2>
                            <h3 class="text-primary my-3">${{ number_format($food->price, 2) }}</h3>
                            <p class="card-text text-muted">{{ $food->description }}</p>

                            @auth
                            <form action="{{ route('cart.add', $food->id) }}" method="POST" class="mt-4">
                                @csrf
                                <div class="row align-items-end g-3">
                                    <div class="col-sm-4">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="99">
                                    </div>
                                    <div class="col-sm-8">
                                        <button type="submit" class="btn btn-primary btn-lg w-100">
                                            <i class="fa fa-cart-plus me-2"></i>Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-4">
                                <i class="fa fa-sign-in-alt me-2"></i>Login to Order
                            </a>
                            @endauth

                            <a href="{{ url('/') }}" class="btn btn-outline-secondary mt-3">
                                <i class="fa fa-arrow-left me-2"></i>Back to Menu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
