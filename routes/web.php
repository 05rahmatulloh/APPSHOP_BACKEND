<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductManajement\CategoryController;
use App\Http\Controllers\ProductManajement\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\DiscountController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');






Route::middleware(['auth'])->prefix('admin')->group(function () {
// Route::resource('products', ProductController::class);
// Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class)->except(['show']);


Route::get('/discounts/create', [DiscountController::class, 'create']);
Route::post('/discounts', [DiscountController::class, 'store'])
->name('discounts.store');


});
Route::get('/midtrans-test', function () {
return view('midtrans');
});
