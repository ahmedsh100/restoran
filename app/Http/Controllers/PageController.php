<?php

namespace App\Http\Controllers;

use App\Models\Food\Food;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('about');
    }

    public function service()
    {
        return view('service');
    }

    public function menu()
    {
        $breakfast = Food::where('category', 'Main Course')->where('is_available', true)->get();
        $lunch = Food::where('category', 'Appetizer')->where('is_available', true)->get();
        $dinner = Food::where('category', 'Dessert')->where('is_available', true)->get();

        return view('menu', compact('breakfast', 'lunch', 'dinner'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
