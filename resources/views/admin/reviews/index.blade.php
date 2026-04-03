@extends('admin.layout')

@section('page-title', 'Reviews Management')

@section('content')
<div class="card table-card">
    <div class="card-header">
        <h6 class="mb-0">Customer Reviews</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Food</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td>#{{ $review->id }}</td>
                        <td>{{ $review->user->name }}</td>
                        <td>{{ $review->food->name }}</td>
                        <td>
                            <span class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                @endfor
                            </span>
                        </td>
                        <td style="max-width: 200px;" class="text-truncate">{{ $review->comment ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $review->is_approved ? 'success' : 'warning' }}">
                                {{ $review->is_approved ? 'Approved' : 'Pending' }}
                            </span>
                        </td>
                        <td>{{ $review->created_at->format('M d, Y') }}</td>
                        <td>
                            <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline-{{ $review->is_approved ? 'warning' : 'success' }}" title="{{ $review->is_approved ? 'Unapprove' : 'Approve' }}">
                                    <i class="fa fa-{{ $review->is_approved ? 'times' : 'check' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this review?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No reviews found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
