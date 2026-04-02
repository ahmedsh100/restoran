<?php

namespace App\Http\Controllers;

use App\Models\Food\Food;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, $foodId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $food = Food::findOrFail($foodId);
        $user = $request->user();

        $hasReviewed = Review::where('user_id', $user->id)
            ->where('food_id', $food->id)
            ->exists();

        if ($hasReviewed) {
            return back()->with('error', 'You have already reviewed this item.');
        }

        $hasOrdered = OrderItem::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('status', 'delivered');
        })->where('food_id', $food->id)->exists();

        if (! $hasOrdered) {
            return back()->with('error', 'You can only review items you have ordered and received.');
        }

        Review::create([
            'user_id' => $user->id,
            'food_id' => $food->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
