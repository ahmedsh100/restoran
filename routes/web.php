<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Foods\FoodController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StripeWebhookController;
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

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/food/{id}', [FoodController::class, 'show'])->name('food.show');

Route::controller(PageController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/service', 'service')->name('service');
    Route::get('/menu', 'menu')->name('menu');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit')->name('contact.submit');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Cart Routes
    Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/add/{id}', 'addToCart')->name('add');
        Route::delete('/remove/{cartItemId}', 'removeFromCart')->name('remove');
    });

    // Review Routes
    Route::post('/review/{foodId}', [ReviewController::class, 'store'])->name('review.store');

    // Checkout & Order Routes
    Route::controller(OrderController::class)->group(function () {
        Route::post('/apply-coupon', 'applyCoupon')->name('coupon.apply');
        Route::post('/remove-coupon', 'removeCoupon')->name('coupon.remove');
        Route::get('/checkout', 'showCheckout')->name('checkout');
        Route::post('/place-order', 'placeOrder')->name('place.order');
        Route::get('/payment/success', 'paymentSuccess')->name('payment.success');
        Route::get('/payment/cancel', 'paymentCancel')->name('payment.cancel');
        Route::get('/my-orders', 'myOrders')->name('my.orders');
        Route::get('/order-success/{orderId}', 'orderSuccess')->name('order.success');
    });
});

/*
|--------------------------------------------------------------------------
| Stripe Webhook (No Auth Required)
|--------------------------------------------------------------------------
*/
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Food Management
    Route::prefix('foods')->name('foods.')->controller(AdminController::class)->group(function () {
        Route::get('/', 'foods')->name('index');
        Route::get('/create', 'createFood')->name('create');
        Route::post('/', 'storeFood')->name('store');
        Route::put('/{id}/toggle-availability', 'toggleFoodAvailability')->name('toggle-availability');
        Route::get('/{id}/edit', 'editFood')->name('edit');
        Route::put('/{id}', 'updateFood')->name('update');
        Route::delete('/{id}', 'deleteFood')->name('delete');
    });

    // Order Management
    Route::prefix('orders')->name('orders.')->controller(AdminController::class)->group(function () {
        Route::get('/', 'orders')->name('index');
        Route::put('/{id}/status', 'updateOrderStatus')->name('status');
    });

    // Review Management
    Route::prefix('reviews')->name('reviews.')->controller(AdminController::class)->group(function () {
        Route::get('/', 'reviews')->name('index');
        Route::put('/{id}/toggle', 'toggleReview')->name('toggle');
        Route::delete('/{id}', 'deleteReview')->name('delete');
    });

    // Coupon Management
    Route::prefix('coupons')->name('coupons.')->controller(AdminController::class)->group(function () {
        Route::get('/', 'coupons')->name('index');
        Route::get('/create', 'createCoupon')->name('create');
        Route::post('/', 'storeCoupon')->name('store');
        Route::put('/{id}/toggle', 'toggleCoupon')->name('toggle');
        Route::delete('/{id}', 'deleteCoupon')->name('delete');
    });
});
