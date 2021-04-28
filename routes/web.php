<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\CardController;
use App\Http\Controllers\Staff\PaymentController;
use App\Http\Controllers\Staff\ProductController;
use App\Http\Controllers\Staff\CategoryController;
use App\Http\Controllers\Staff\CustomerController;
use App\Http\Controllers\Staff\TransactionController;
use App\Http\Controllers\Staff\ProductImageController;
use App\Http\Controllers\Staff\ProductVariantController;
use App\Http\Controllers\Staff\TransactionItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('staff-login', [App\Http\Controllers\Auth\StaffLoginController::class, 'showLoginForm'])->name('staff-login-form');
Route::post('staff-login', [App\Http\Controllers\Auth\StaffLoginController::class, 'login'])->name('staff-login');
Route::post('staff-logout', [App\Http\Controllers\Auth\StaffLoginController::class, 'logout'])->name('staff-logout');
Route::middleware(['auth:staff'])->prefix('staff')->name('staff.')->group(function(){
    Route::get('/', [App\Http\Controllers\Staff\HomeController::class, 'index'])->name('index');

    Route::resource('categories', CategoryController::class);

    Route::get('cards/setting', [CardController::class,'settin'])->name('cart.setting');

    Route::resource('cards', CardController::class);
    Route::resource('products', ProductController::class);
    Route::resource('product-variants', ProductVariantController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('transaction-items', TransactionItemController::class);

    Route::get('product-images/delete/{id}',[ProductImageController::class,'delete'])->name('product-images.delete');
    Route::resource('product-images', ProductImageController::class);
});

// for public access
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('tripay-callback', [App\Http\Controllers\CallbackController::class, 'tripay'])->name('tripay-callback');
Route::name('shop.')->group(function(){
    Route::get('shop', [App\Http\Controllers\ShopController::class, 'index'])->name('index');
    Route::get('order-kta', [App\Http\Controllers\ShopController::class, 'orderKta'])->name('order-kta');
    Route::post('checkout-kta', [App\Http\Controllers\ShopController::class, 'checkoutKta'])->name('checkout-kta');
    Route::get('checkout', [App\Http\Controllers\ShopController::class, 'checkout'])->name('checkout');
    Route::get('cart', [App\Http\Controllers\ShopController::class, 'cart'])->name('cart');
    Route::get('cart/{id}/remove', [App\Http\Controllers\ShopController::class, 'cartRemove'])->name('cart-remove');
    Route::post('cart/{id}/update', [App\Http\Controllers\ShopController::class, 'cartUpdate'])->name('cart-update');
    Route::get('category/{slug}',[App\Http\Controllers\ShopController::class, 'productList'])->name('product-list');
    Route::get('product/{slug}',[App\Http\Controllers\ShopController::class, 'productDetail'])->name('product-detail');
    Route::get('product/{slug}/add_to_cart',[App\Http\Controllers\ShopController::class, 'addToCart'])->name('add_to_cart');
    Route::post('product/action',[App\Http\Controllers\ShopController::class, 'productAction'])->name('product-action');
    Route::post('place-order',[App\Http\Controllers\ShopController::class, 'placeOrder'])->name('place-order');
});