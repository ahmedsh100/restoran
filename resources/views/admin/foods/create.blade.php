@extends('admin.layout')

@section('page-title', 'Add New Food')

@section('content')
<div class="card table-card">
    <div class="card-header">
        <h6 class="mb-0">Add New Food Item</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Price ($)</label>
                    <input type="number" name="price" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                    @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        <option value="Main Course" {{ old('category') == 'Main Course' ? 'selected' : '' }}>Main Course</option>
                        <option value="Appetizer" {{ old('category') == 'Appetizer' ? 'selected' : '' }}>Appetizer</option>
                        <option value="Dessert" {{ old('category') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                        <option value="Beverage" {{ old('category') == 'Beverage' ? 'selected' : '' }}>Beverage</option>
                    </select>
                    @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fa fa-save me-1"></i> Save Food Item
                    </button>
                    <a href="{{ route('admin.foods.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
