<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use App\Models\Coupon;
use App\Models\User;
use App\Repositories\Contracts\FoodRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\CouponService;
use App\Services\OrderService;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct(
        protected FoodRepositoryInterface $foodRepository,
        protected OrderRepositoryInterface $orderRepository,
        protected OrderService $orderService,
        protected CouponService $couponService,
        protected ReviewService $reviewService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        return view('admin.dashboard', [
            'totalOrders' => $this->orderRepository->count(),
            'totalUsers' => User::count(),
            'totalRevenue' => $this->orderRepository->getTotalRevenue('cancelled'),
            'recentOrders' => $this->orderRepository->getRecent(10),
            'pendingReviews' => $this->reviewService->getPendingCount(),
            'activeCoupons' => Coupon::where('is_active', true)->count(),
        ]);
    }

    public function foods()
    {
        $foods = $this->foodRepository->all();

        return view('admin.foods.index', compact('foods'));
    }

    public function createFood()
    {
        return view('admin.foods.create');
    }

    public function storeFood(StoreFoodRequest $request)
    {
        $imagePath = $request->file('image')->store('foods', 'public');

        $this->foodRepository->create([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imagePath,
            'is_available' => true,
        ]);

        return redirect()->route('admin.foods.index')->with('success', 'Food item created successfully.');
    }

    public function editFood($id)
    {
        $food = $this->foodRepository->findById($id);

        if (! $food) {
            return redirect()->route('admin.foods.index')->with('error', 'Food item not found.');
        }

        return view('admin.foods.edit', compact('food'));
    }

    public function updateFood(UpdateFoodRequest $request, $id)
    {
        $food = $this->foodRepository->findById($id);

        if (! $food) {
            return redirect()->route('admin.foods.index')->with('error', 'Food item not found.');
        }

        $data = $request->only(['name', 'price', 'category', 'description']);
        $data['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            if ($food->image) {
                Storage::disk('public')->delete($food->image);
            }
            $data['image'] = $request->file('image')->store('foods', 'public');
        }

        $this->foodRepository->update($id, $data);

        return redirect()->route('admin.foods.index')->with('success', 'Food item updated successfully.');
    }

    public function deleteFood($id)
    {
        $food = $this->foodRepository->findById($id);

        if (! $food) {
            return redirect()->route('admin.foods.index')->with('error', 'Food item not found.');
        }

        if ($food->image) {
            Storage::disk('public')->delete($food->image);
        }

        $this->foodRepository->delete($id);

        return redirect()->route('admin.foods.index')->with('success', 'Food item deleted successfully.');
    }

    public function toggleFoodAvailability($id)
    {
        $food = $this->foodRepository->findById($id);

        if (! $food) {
            return redirect()->route('admin.foods.index')->with('error', 'Food item not found.');
        }

        $this->foodRepository->update($id, ['is_available' => ! $food->is_available]);

        return redirect()->route('admin.foods.index')->with('success', 'Food availability updated.');
    }

    public function orders()
    {
        $orders = $this->orderRepository->all();

        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:pending,cooking,delivered,cancelled'],
        ]);

        $order = $this->orderRepository->findById($id);

        if (! $order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        if ($order->payment_method === 'stripe' && $order->payment_status !== 'paid' && $request->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Cannot update status of unpaid Stripe order.');
        }

        $this->orderService->updateStatus($id, $request->status);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function reviews()
    {
        $reviews = $this->reviewService->getAllWithRelations();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleReview($id)
    {
        $this->reviewService->toggleApproval($id);

        return redirect()->back()->with('success', 'Review status updated.');
    }

    public function deleteReview($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Review deleted.');
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
            'code' => ['required', 'string', 'unique:coupons,code'],
            'type' => ['required', 'in:fixed,percentage'],
            'value' => ['required', 'numeric', 'min:0'],
            'expiry_date' => ['required', 'date', 'after:today'],
            'usage_limit' => ['nullable', 'integer', 'min:0'],
        ]);

        Coupon::create([
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type,
            'value' => $request->value,
            'expiry_date' => $request->expiry_date,
            'usage_limit' => $request->usage_limit ?? 0,
            'is_active' => true,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function toggleCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['is_active' => ! $coupon->is_active]);

        return redirect()->back()->with('success', 'Coupon status updated.');
    }

    public function deleteCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->back()->with('success', 'Coupon deleted.');
    }
}
