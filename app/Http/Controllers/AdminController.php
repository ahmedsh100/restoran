<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Food\Food;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');
        $recentOrders = Order::with('user')->latest()->take(10)->get();
        $pendingReviews = Review::where('is_approved', false)->count();
        $activeCoupons = Coupon::where('is_active', true)->count();

        return view('admin.dashboard', compact('totalOrders', 'totalUsers', 'totalRevenue', 'recentOrders', 'pendingReviews', 'activeCoupons'));
    }

    public function foods()
    {
        $foods = Food::latest()->get();

        return view('admin.foods.index', compact('foods'));
    }

    public function createFood()
    {
        return view('admin.foods.create');
    }

    public function storeFood(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('foods', 'public');

        Food::create([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.foods')->with('success', 'Food item created successfully.');
    }

    public function editFood($id)
    {
        $food = Food::findOrFail($id);

        return view('admin.foods.edit', compact('food'));
    }

    public function updateFood(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($food->image) {
                Storage::disk('public')->delete($food->image);
            }
            $data['image'] = $request->file('image')->store('foods', 'public');
        }

        $food->update($data);

        return redirect()->route('admin.foods')->with('success', 'Food item updated successfully.');
    }

    public function deleteFood($id)
    {
        $food = Food::findOrFail($id);

        if ($food->image) {
            Storage::disk('public')->delete($food->image);
        }

        $food->delete();

        return redirect()->route('admin.foods')->with('success', 'Food item deleted successfully.');
    }

    public function orders()
    {
        $orders = Order::with('user')->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,cooking,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function reviews()
    {
        $reviews = Review::with('user', 'food')->latest()->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleReview($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => ! $review->is_approved]);

        return back()->with('success', 'Review status updated.');
    }

    public function deleteReview($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }

    public function coupons()
    {
        $coupons = Coupon::latest()->get();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function createCoupon()
    {
        return view('admin.coupons.create');
    }

    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after:today',
            'usage_limit' => 'nullable|integer|min:0',
        ]);

        Coupon::create([
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type,
            'value' => $request->value,
            'expiry_date' => $request->expiry_date,
            'usage_limit' => $request->usage_limit ?? 0,
            'is_active' => true,
        ]);

        return redirect()->route('admin.coupons')->with('success', 'Coupon created successfully.');
    }

    public function toggleCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['is_active' => ! $coupon->is_active]);

        return back()->with('success', 'Coupon status updated.');
    }

    public function deleteCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return back()->with('success', 'Coupon deleted.');
    }
}
