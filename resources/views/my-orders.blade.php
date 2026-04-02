@extends('layouts.app')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Orders</h5>
            <h1 class="mb-5">My Orders</h1>
        </div>

        @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="fa fa-shopping-bag text-muted mb-3" style="font-size: 3rem;"></i>
            <h4 class="text-muted">No orders yet</h4>
            <p class="mb-4">Looks like you haven't placed any orders yet.</p>
            <a href="{{ route('menu') }}" class="btn btn-primary py-3 px-5">Browse Menu</a>
        </div>
        @else
        <div class="row g-4">
            @foreach($orders as $order)
            <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light rounded p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1">Order #{{ $order->id }}</h5>
                            <small class="text-muted">{{ $order->created_at->format('M d, Y - h:i A') }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'danger') }} mb-1">{{ ucfirst($order->status) }}</span>
                            <h5 class="text-primary mb-0">${{ number_format($order->total_price, 2) }}</h5>
                        </div>
                    </div>
                    <div class="accordion" id="orderAccordion{{ $order->id }}">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}">
                                    View Order Items ({{ $order->items->count() }})
                                </button>
                            </h2>
                            <div id="collapse{{ $order->id }}" class="accordion-collapse collapse" data-bs-parent="#orderAccordion{{ $order->id }}">
                                <div class="accordion-body pt-0">
                                    @foreach($order->items as $item)
                                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/img/'.$item->food->image) }}" alt="{{ $item->food->name }}" class="img-fluid rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0">{{ $item->food->name }}</h6>
                                                <small class="text-muted">Qty: {{ $item->quantity }} x ${{ number_format($item->price, 2) }}</small>
                                            </div>
                                        </div>
                                        <span class="fw-bold">${{ number_format($item->quantity * $item->price, 2) }}</span>
                                    </div>
                                    @endforeach
                                    <div class="mt-3">
                                        <small class="text-muted"><strong>Delivery to:</strong> {{ $order->address }} | <strong>Phone:</strong> {{ $order->phone }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
