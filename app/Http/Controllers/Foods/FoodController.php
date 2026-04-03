<?php

namespace App\Http\Controllers\Foods;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FoodRepositoryInterface;

class FoodController extends Controller
{
    public function __construct(
        protected FoodRepositoryInterface $foodRepository
    ) {}

    public function index()
    {
        $breakfast = $this->foodRepository->findByCategory('Main Course', 4);
        $lunch = $this->foodRepository->findByCategory('Appetizer', 4);
        $dinner = $this->foodRepository->findByCategory('Dessert', 4);

        return view('home', compact('breakfast', 'lunch', 'dinner'));
    }

    public function show($id)
    {
        $food = $this->foodRepository->findById($id);

        if (! $food) {
            abort(404);
        }

        return view('foods.food-details', compact('food'));
    }
}
