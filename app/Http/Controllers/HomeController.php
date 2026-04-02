<?php

namespace App\Http\Controllers;

use App\Models\Food\Food;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $breakfast = Food::where('category', 'Main Course')->take(4)->get();
        $lunch = Food::where('category', 'Appetizer')->take(4)->get();
        $dinner = Food::where('category', 'Dessert')->take(4)->get();

        return view('home', compact('breakfast', 'lunch', 'dinner'));
    }
}
