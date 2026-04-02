<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Review;

class ReviewService
{
    public function canUserReview(int $userId, int $foodId): bool
    {
        $hasReviewed = Review::where('user_id', $userId)
            ->where('food_id', $foodId)
            ->exists();

        if ($hasReviewed) {
            return false;
        }

        return $this->hasDeliveredOrder($userId, $foodId);
    }

    public function hasDeliveredOrder(int $userId, int $foodId): bool
    {
        return OrderItem::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('status', 'delivered');
        })->where('food_id', $foodId)->exists();
    }

    public function create(int $userId, int $foodId, int $rating, ?string $comment = null): Review
    {
        return Review::create([
            'user_id' => $userId,
            'food_id' => $foodId,
            'rating' => $rating,
            'comment' => $comment,
            'is_approved' => true,
        ]);
    }

    public function toggleApproval(int $reviewId): bool
    {
        $review = Review::findOrFail($reviewId);

        return $review->update(['is_approved' => ! $review->is_approved]);
    }

    public function getPendingCount(): int
    {
        return Review::where('is_approved', false)->count();
    }

    public function getAllWithRelations()
    {
        return Review::with(['user', 'food'])->latest()->get();
    }

    public function getForFood(int $foodId, bool $approvedOnly = true)
    {
        $query = Review::where('food_id', $foodId)->with('user');

        if ($approvedOnly) {
            $query->where('is_approved', true);
        }

        return $query->latest()->get();
    }
}
