<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Foods\FoodController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FoodController::class, 'index'])->name('home.index');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('dashboard');

Route::get('/food/{id}', [FoodController::class, 'show'])->name('food.show');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/service', [PageController::class, 'service'])->name('service');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');

Route::middleware('auth')->group(function () {
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'removeFromCart'])->name('cart.remove');

    Route::post('/review/{foodId}', [ReviewController::class, 'store'])->name('review.store');

    Route::post('/apply-coupon', [OrderController::class, 'applyCoupon'])->name('coupon.apply');
    Route::post('/remove-coupon', [OrderController::class, 'removeCoupon'])->name('coupon.remove');
    Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout');
    Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place.order');
    Route::get('/payment/success', [OrderController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/cancel', [OrderController::class, 'paymentCancel'])->name('payment.cancel');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my.orders');
    Route::get('/order-success/{orderId}', [OrderController::class, 'orderSuccess'])->name('order.success');
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/foods', [AdminController::class, 'foods'])->name('foods');
    Route::get('/foods/create', [AdminController::class, 'createFood'])->name('foods.create');
    Route::post('/foods', [AdminController::class, 'storeFood'])->name('foods.store');
    Route::get('/foods/{id}/edit', [AdminController::class, 'editFood'])->name('foods.edit');
    Route::put('/foods/{id}', [AdminController::class, 'updateFood'])->name('foods.update');
    Route::delete('/foods/{id}', [AdminController::class, 'deleteFood'])->name('foods.delete');

    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    Route::put('/reviews/{id}/toggle', [AdminController::class, 'toggleReview'])->name('reviews.toggle');
    Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('reviews.delete');

    Route::get('/coupons', [AdminController::class, 'coupons'])->name('coupons');
    Route::get('/coupons/create', [AdminController::class, 'createCoupon'])->name('coupons.create');
    Route::post('/coupons', [AdminController::class, 'storeCoupon'])->name('coupons.store');
    Route::put('/coupons/{id}/toggle', [AdminController::class, 'toggleCoupon'])->name('coupons.toggle');
    Route::delete('/coupons/{id}', [AdminController::class, 'deleteCoupon'])->name('coupons.delete');
});
