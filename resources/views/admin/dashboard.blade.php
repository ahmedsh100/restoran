@extends('admin.layout')

@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-primary me-3">
                    <i class="fa fa-clipboard-list"></i>
                </div>
                <div>
                    <p class="text-muted mb-1">Total Orders</p>
                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-success me-3">
                    <i class="fa fa-users"></i>
                </div>
                <div>
                    <p class="text-muted mb-1">Total Users</p>
                    <h3 class="mb-0">{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-warning me-3">
                    <i class="fa fa-dollar-sign"></i>
                </div>
                <div>
                    <p class="text-muted mb-1">Total Revenue</p>
                    <h3 class="mb-0">${{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-info me-3">
                    <i class="fa fa-tags"></i>
                </div>
                <div>
                    <p class="text-muted mb-1">Active Coupons</p>
                    <h3 class="mb-0">{{ $activeCoupons }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Recent Orders</h6>
                <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>${{ number_format($order->total_price, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'cooking' ? 'info' : ($order->status === 'delivered' ? 'success' : 'danger')) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No orders yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Pending Reviews</h6>
                <a href="{{ route('admin.reviews') }}" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
            <div class="card-body text-center py-4">
                <div class="display-4 text-warning mb-2">{{ $pendingReviews }}</div>
                <p class="text-muted mb-0">Reviews awaiting moderation</p>
                @if($pendingReviews > 0)
                <a href="{{ route('admin.reviews') }}" class="btn btn-sm btn-outline-warning mt-2">Review Now</a>
                @endif
            </div>
        </div>

        <div class="card table-card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Quick Links</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.foods.create') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fa fa-plus me-2"></i>Add New Food
                </a>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-outline-success w-100 mb-2">
                    <i class="fa fa-tags me-2"></i>Create Coupon
                </a>
                <a href="{{ route('admin.orders') }}" class="btn btn-outline-info w-100">
                    <i class="fa fa-clipboard-list me-2"></i>View All Orders
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
