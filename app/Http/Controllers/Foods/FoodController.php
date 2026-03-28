<?php

namespace App\Http\Controllers\Foods;

use App\Http\Controllers\Controller;
use App\Models\Food\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(){
        $breakfast = Food::where("category", "Main Course")->take(4)->get();
        $lunch = Food::where("category", "Appetizer")->take(4)->get();
        $dinner = Food::where("category", "Dessert")->take(4)->get();
        return view("home", compact("breakfast", "lunch", "dinner"));
    }

    public function addToCart(Request $request){
        $food = Food::find($request->food_id);
        if($food){
            $request->user()->cart()->attach($food->id, [
                "quantity" => $request->quantity,
                "price" => $food->price,
            ]);
        }
        }

    }
