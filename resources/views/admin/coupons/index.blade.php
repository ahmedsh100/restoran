@extends('admin.layout')

@section('page-title', 'Coupons Management')

@section('content')
<div class="card table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">All Coupons</h6>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary-custom btn-sm">
            <i class="fa fa-plus me-1"></i> Create Coupon
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Usage</th>
                        <th>Expiry</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                    <tr>
                        <td><code class="fs-6">{{ $coupon->code }}</code></td>
                        <td><span class="badge bg-secondary">{{ ucfirst($coupon->type) }}</span></td>
                        <td>{{ $coupon->type === 'percentage' ? $coupon->value.'%' : '$'.number_format($coupon->value, 2) }}</td>
                        <td>
                            {{ $coupon->used_count }} /
                            {{ $coupon->usage_limit > 0 ? $coupon->usage_limit : '∞' }}
                        </td>
                        <td>{{ $coupon->expiry_date->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $coupon->is_active ? 'success' : 'danger' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.coupons.toggle', $coupon->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline-{{ $coupon->is_active ? 'warning' : 'success' }}" title="{{ $coupon->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fa fa-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.coupons.delete', $coupon->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this coupon?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No coupons found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
