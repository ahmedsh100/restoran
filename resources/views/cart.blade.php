@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fa fa-cart-shopping me-2"></i>Your Cart</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($cartItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->food->image)
                                                        <img src="{{ asset($item->food->image) }}" alt="{{ $item->food->name }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->food->name }}</h6>
                                                        <small class="text-muted">{{ $item->food->category }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td><strong>${{ number_format($item->subtotal, 2) }}</strong></td>
                                            <td>
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this item?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td colspan="2"><strong>${{ number_format($total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('menu') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-arrow-left me-2"></i>Continue Shopping
                            </a>
                            <a href="{{ route('checkout') }}" class="btn btn-primary">
                                <i class="fa fa-credit-card me-2"></i>Proceed to Checkout
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-cart-shopping fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Your cart is empty</h5>
                            <p class="text-muted">Add some delicious items to get started!</p>
                            <a href="{{ route('menu') }}" class="btn btn-primary mt-2">
                                <i class="fa fa-utensils me-2"></i>Browse Menu
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
