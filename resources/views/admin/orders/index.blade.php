@extends('admin.layout')

@section('page-title', 'Orders Management')

@section('content')
<div class="card table-card">
    <div class="card-header">
        <h6 class="mb-0">All Customer Orders</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->phone }}</td>
                        <td style="max-width: 150px;" class="text-truncate">{{ $order->address }}</td>
                        <td>{{ $order->items->count() }}</td>
                        <td>${{ number_format($order->total_price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'cooking' ? 'info' : ($order->status === 'delivered' ? 'success' : 'danger')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="cooking" {{ $order->status === 'cooking' ? 'selected' : '' }}>Cooking</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
