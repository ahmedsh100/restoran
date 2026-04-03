<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Food\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cartItems = $request->user()->cart()->with('food')->get();
        $total = calculate_subtotal($cartItems);

        return view('cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $food = Food::findOrFail($id);

        if (isset($food->is_available) && ! $food->is_available) {
            return redirect()->back()->with('error', 'This item is currently unavailable.');
        }

        $user = $request->user();

        DB::transaction(function () use ($food, $user, $request) {
            $existingItem = CartItem::where('user_id', $user->id)
                ->where('food_id', $food->id)
                ->first();

            if ($existingItem) {
                $existingItem->quantity += $request->quantity;
                $existingItem->save();
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'food_id' => $food->id,
                    'quantity' => $request->quantity,
                    'price' => $food->price,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    public function removeFromCart(Request $request, $cartItemId)
    {
        $cartItem = CartItem::where('user_id', $request->user()->id)
            ->where('id', $cartItemId)
            ->firstOrFail();

        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart!');
    }
}
