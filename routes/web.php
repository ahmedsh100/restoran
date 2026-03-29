<?php

use App\Http\Controllers\Foods\FoodController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [FoodController::class, 'index']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/food/add-to-cart', [FoodController::class, 'addToCart'])->name('food.addTo-cart');
Route::post('/food/detail/{food_id}', [FoodController::class, 'detail'])->name('food.detail');
