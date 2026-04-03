<?php

namespace App\Http\Controllers;

use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {
        $this->middleware('auth');
    }

    public function store(Request $request, $foodId)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        if (! $this->reviewService->canUserReview($request->user()->id, $foodId)) {
            return redirect()->back()->with('error', 'You can only review items you have ordered and received.');
        }

        $this->reviewService->create(
            $request->user()->id,
            $foodId,
            $request->rating,
            $request->comment
        );

        return redirect()->back()->with('success', 'Thank you for your review!');
    }
}
