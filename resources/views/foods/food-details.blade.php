@extends('layouts.app')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-5 mb-5">
            <div class="col-lg-5">
                <img src="{{ asset('storage/'.$food->image) }}" class="img-fluid rounded w-100" style="object-fit: cover; max-height: 400px;" alt="{{ $food->name }}" data-fallback="{{ asset('assets/img/menu-1.jpg') }}">
            </div>
            <div class="col-lg-7">
                <span class="badge bg-primary mb-2 fs-6">{{ $food->category }}</span>
                <h1 class="mb-3">{{ $food->name }}</h1>
                <h2 class="text-primary mb-3">${{ number_format($food->price, 2) }}</h2>
                <p class="text-muted mb-4">{{ $food->description }}</p>

                @if(!$food->is_available)
                <div class="alert alert-warning mt-4">
                    <i class="fa fa-exclamation-triangle me-2"></i>This item is currently unavailable.
                </div>
                @elseif(auth()->check())
                <form action="{{ route('cart.add', $food->id) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="row align-items-end g-3">
                        <div class="col-sm-4">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="99">
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fa fa-cart-plus me-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-4">
                    <i class="fa fa-sign-in-alt me-2"></i>Login to Order
                </a>
                @endif

                <a href="{{ route('menu') }}" class="btn btn-outline-secondary mt-3">
                    <i class="fa fa-arrow-left me-2"></i>Back to Menu
                </a>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-8">
                <h3 class="mb-4">Customer Reviews</h3>

                @if($food->rating_count > 0)
                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                    <div class="me-3">
                        <h2 class="mb-0">{{ $food->average_rating }}</h2>
                        <div class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star{{ $i <= round($food->average_rating) ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">{{ $food->rating_count }} review{{ $food->rating_count > 1 ? 's' : '' }}</p>
                    </div>
                </div>
                @endif

                @forelse($food->reviews()->with('user')->latest()->get() as $review)
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>{{ $review->user->name }}</strong>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                        </div>
                        @if($review->comment)
                        <p class="mb-0 text-muted">{{ $review->comment }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-muted">No reviews yet. Be the first to review this item!</p>
                @endforelse
            </div>

            <div class="col-lg-4">
                @auth
                @php
                $hasReviewed = \App\Models\Review::where('user_id', auth()->id())->where('food_id', $food->id)->exists();
                $hasOrdered = \App\Models\OrderItem::whereHas('order', function($q) {
                    $q->where('user_id', auth()->id())->where('status', 'delivered');
                })->where('food_id', $food->id)->exists();
                @endphp

                @if(!$hasReviewed && $hasOrdered)
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">Leave a Review</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('review.store', $food->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <div class="d-flex gap-2" id="starRating">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star-o fa-lg text-muted rating-star" data-value="{{ $i }}" style="cursor: pointer;"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingValue" value="0" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Comment</label>
                                <textarea name="comment" rows="3" class="form-control" placeholder="Share your experience..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit Review</button>
                        </form>
                    </div>
                </div>
                @elseif($hasReviewed)
                <div class="alert alert-info">
                    <i class="fa fa-check-circle me-2"></i>You have already reviewed this item.
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fa fa-info-circle me-2"></i>You can review this item after receiving your order.
                </div>
                @endif
                @endauth
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.rating-star').forEach(function(star) {
    star.addEventListener('click', function() {
        var value = parseInt(this.getAttribute('data-value'));
        document.getElementById('ratingValue').value = value;
        document.querySelectorAll('.rating-star').forEach(function(s, i) {
            s.className = i < value ? 'fa fa-star fa-lg text-warning rating-star' : 'fa fa-star-o fa-lg text-muted rating-star';
        });
    });
});
</script>
@endpush
@endsection
