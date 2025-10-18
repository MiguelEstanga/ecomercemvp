<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
Route::get('/',  [MainController::class, 'index'])->name('home');


Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/login', [AuthController::class, 'loginView'])->name('login');


Route::prefix('product')->group(function () {
  Route::get('/{id}', [ProductController::class, 'show'])->name('product.show');
});

Route::prefix('checkout')->group(function () {
  Route::get('/{id}', [CheckoutController::class, 'index'])->name('checkout.index');
  Route::post('/', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::prefix('profile')->middleware('auth')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
});

Route::prefix('order')->middleware('auth')->group(function () {
  Route::get('/{id}', [OrderController::class, 'show'])->name('order.show');
});
