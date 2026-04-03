@extends('admin.layout')

@section('page-title', 'Food Items')

@section('content')
<div class="card table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">All Food Items</h6>
        <a href="{{ route('admin.foods.create') }}" class="btn btn-primary-custom btn-sm">
            <i class="fa fa-plus me-1"></i> Add New Food
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($foods as $food)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/'.$food->image) }}" alt="{{ $food->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>{{ $food->name }}</td>
                        <td><span class="badge bg-secondary">{{ $food->category }}</span></td>
                        <td>${{ number_format($food->price, 2) }}</td>
                        <td>
                            <form action="{{ route('admin.foods.toggle-availability', $food->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm {{ $food->is_available ? 'btn-success' : 'btn-danger' }}">
                                    <i class="fa fa-{{ $food->is_available ? 'check' : 'times' }}"></i>
                                    {{ $food->is_available ? 'Yes' : 'No' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.foods.edit', $food->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.foods.delete', $food->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this food item?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No food items found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
