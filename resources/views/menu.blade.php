@extends('layouts.app')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food Menu</h5>
            <h1 class="mb-5">Most Popular Items</h1>
        </div>
        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
            <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                <li class="nav-item">
                    <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 active" data-bs-toggle="pill" href="#tab-1">
                        <i class="fa fa-coffee fa-2x text-primary"></i>
                        <div class="ps-3">
                            <small class="text-body">Popular</small>
                            <h6 class="mt-n1 mb-0">Breakfast</h6>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="d-flex align-items-center text-start mx-3 pb-3" data-bs-toggle="pill" href="#tab-2">
                        <i class="fa fa-hamburger fa-2x text-primary"></i>
                        <div class="ps-3">
                            <small class="text-body">Special</small>
                            <h6 class="mt-n1 mb-0">Lunch</h6>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="d-flex align-items-center text-start mx-3 me-0 pb-3" data-bs-toggle="pill" href="#tab-3">
                        <i class="fa fa-utensils fa-2x text-primary"></i>
                        <div class="ps-3">
                            <small class="text-body">Lovely</small>
                            <h6 class="mt-n1 mb-0">Dinner</h6>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane fade show p-0 active">
                    <div class="row g-4">
                        @forelse ($breakfast as $food)
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 img-fluid rounded" src="{{ asset('assets/img/'.$food->image) }}" alt="" style="width: 80px;">
                                <div class="w-100 d-flex flex-column text-start ps-4">
                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                        <span>{{ $food->name }}</span>
                                        <span class="text-primary">${{ number_format($food->price, 2) }}</span>
                                    </h5>
                                    <small class="fst-italic">{{ $food->description }}</small>
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{ route('food.show', $food->id) }}" class="btn btn-outline-primary py-2 px-3">Details</a>
                                        @auth
                                        <form action="{{ route('cart.add', $food->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary py-2 px-3">Add to Cart</button>
                                        </form>
                                        @else
                                        <a href="{{ route('login') }}" class="btn btn-primary py-2 px-3">Login to Order</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center text-muted">
                            <p>No items available in this category.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div id="tab-2" class="tab-pane fade show p-0">
                    <div class="row g-4">
                        @forelse ($lunch as $food)
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 img-fluid rounded" src="{{ asset('assets/img/'.$food->image) }}" alt="" style="width: 80px;">
                                <div class="w-100 d-flex flex-column text-start ps-4">
                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                        <span>{{ $food->name }}</span>
                                        <span class="text-primary">${{ number_format($food->price, 2) }}</span>
                                    </h5>
                                    <small class="fst-italic">{{ $food->description }}</small>
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{ route('food.show', $food->id) }}" class="btn btn-outline-primary py-2 px-3">Details</a>
                                        @auth
                                        <form action="{{ route('cart.add', $food->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary py-2 px-3">Add to Cart</button>
                                        </form>
                                        @else
                                        <a href="{{ route('login') }}" class="btn btn-primary py-2 px-3">Login to Order</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center text-muted">
                            <p>No items available in this category.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div id="tab-3" class="tab-pane fade show p-0">
                    <div class="row g-4">
                        @forelse ($dinner as $food)
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 img-fluid rounded" src="{{ asset('assets/img/'.$food->image) }}" alt="" style="width: 80px;">
                                <div class="w-100 d-flex flex-column text-start ps-4">
                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                        <span>{{ $food->name }}</span>
                                        <span class="text-primary">${{ number_format($food->price, 2) }}</span>
                                    </h5>
                                    <small class="fst-italic">{{ $food->description }}</small>
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{ route('food.show', $food->id) }}" class="btn btn-outline-primary py-2 px-3">Details</a>
                                        @auth
                                        <form action="{{ route('cart.add', $food->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary py-2 px-3">Add to Cart</button>
                                        </form>
                                        @else
                                        <a href="{{ route('login') }}" class="btn btn-primary py-2 px-3">Login to Order</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center text-muted">
                            <p>No items available in this category.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
