<?php

namespace App\Http\Controllers\Foods;

use App\Http\Controllers\Controller;
use App\Models\Food\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(){
        $breakfast = Food::where("category", "breakfast")->take(4)->get();
        $lunch = Food::where("category", "lunch")->take(4)->get();
        $dinner = Food::where("category", "dinner")->take(4)->get();
        return view("home", compact("breakfast", "lunch", "dinner"));
    }
}
