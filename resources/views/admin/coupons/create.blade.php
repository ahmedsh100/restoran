@extends('admin.layout')

@section('page-title', 'Create Coupon')

@section('content')
<div class="card table-card">
    <div class="card-header">
        <h6 class="mb-0">Create New Coupon</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Coupon Code</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="e.g. SAVE20" required style="text-transform: uppercase;">
                    @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Discount Type</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">Select Type</option>
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                    </select>
                    @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Discount Value</label>
                    <input type="number" name="value" step="0.01" min="0" class="form-control @error('value') is-invalid @enderror" value="{{ old('value') }}" required>
                    @error('value')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date') }}" required>
                    @error('expiry_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Usage Limit</label>
                    <input type="number" name="usage_limit" min="0" class="form-control @error('usage_limit') is-invalid @enderror" value="{{ old('usage_limit', 0) }}">
                    <small class="text-muted">Leave 0 for unlimited</small>
                    @error('usage_limit')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fa fa-save me-1"></i> Create Coupon
                    </button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
