<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\CardController;
use App\Http\Controllers\Staff\EventController;
use App\Http\Controllers\Staff\AlumniController;
use App\Http\Controllers\Staff\JolaliController;
use App\Http\Controllers\Staff\PaymentController;
use App\Http\Controllers\Staff\ProductController;
use App\Http\Controllers\Staff\CategoryController;
use App\Http\Controllers\Staff\CustomerController;
use App\Http\Controllers\Staff\MerchantController;
use App\Http\Controllers\Staff\BroadcastController;
use App\Http\Controllers\Staff\TransactionController;
use App\Http\Controllers\Staff\BlacklistNraController;
use App\Http\Controllers\Staff\ProductImageController;
use App\Http\Controllers\Staff\AdvertisementController;
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

Route::match(['get','post'],'otp', [App\Http\Controllers\Auth\OtpController::class, 'otp'])->name("otp");
Route::get('kta', [App\Http\Controllers\Mobile\AlumniController::class, 'ktaDemo']);

Route::get('preview-tiket', [App\Http\Controllers\HomeController::class, 'previewTicket']);
Route::get('staff-login', [App\Http\Controllers\Auth\StaffLoginController::class, 'showLoginForm'])->name('staff-login-form');
Route::post('staff-login', [App\Http\Controllers\Auth\StaffLoginController::class, 'login'])->name('staff-login');
Route::post('staff-logout', [App\Http\Controllers\Auth\StaffLoginController::class, 'logout'])->name('staff-logout');
Route::middleware(['auth:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/', [App\Http\Controllers\Staff\HomeController::class, 'index'])->name('index');

    Route::resource('categories', CategoryController::class);

    Route::get('cards/setting', [CardController::class, 'settin'])->name('cart.setting');

    Route::resource('cards', CardController::class);
    Route::get('products/vouchers', [ProductController::class,'vouchers'])->name('products.vouchers');
    Route::resource('products', ProductController::class);
    Route::resource('product-variants', ProductVariantController::class);
    Route::resource('transactions', TransactionController::class);
    Route::get('transactions/{transaction}/resend', [TransactionController::class,'resend'])->name('transactions.resend');
    Route::get('transactions/{transaction}/approve', [TransactionController::class,'approve'])->name('transactions.approve');
    Route::get('transactions/{transaction}/cancel', [TransactionController::class,'cancel'])->name('transactions.cancel');
    Route::resource('events', EventController::class);
    Route::resource('jolalis', JolaliController::class);
    Route::resource('advertisements', AdvertisementController::class);
    Route::resource('merchants', MerchantController::class);
    Route::resource('blacklist-nra', BlacklistNraController::class);
    Route::resource('broadcasts', BroadcastController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('payments', PaymentController::class);
    Route::post('payments/{payment}/approve',[PaymentController::class, 'approve'])->name('payments.approve');
    Route::match(['get', 'post'], 'alumnis/import', [AlumniController::class, 'import'])->name('alumnis.import');
    Route::match(['get', 'post'], 'alumnis/update-nra/{alumni}', [AlumniController::class,'updateNra'])->name('alumnis.update-nra');
    Route::post('alumnis/approve/{alumni}', [AlumniController::class, 'approve'])->name('alumnis.approve');
    Route::post('alumnis/update-status/{alumni}', [AlumniController::class, 'updateStatus'])->name('alumnis.update-status');
    Route::post('alumnis/unapprove/{alumni}', [AlumniController::class, 'unapprove'])->name('alumnis.unapprove');
    Route::resource('alumnis', AlumniController::class);
    Route::post('transaction-items/update-shipping/{transaction}', [TransactionItemController::class, 'updateShipping'])->name('update-shipping');
    Route::resource('transaction-items', TransactionItemController::class);

    Route::get('product-images/delete/{id}', [ProductImageController::class, 'delete'])->name('product-images.delete');
    Route::resource('product-images', ProductImageController::class);
});

// for user
Route::middleware(['auth:web'])->group(function () {
    Route::get('profile',[App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
    Route::match(['get','post'],'edit-profile',[App\Http\Controllers\HomeController::class, 'editProfile'])->name('edit-profile');
});

// for public access
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('nra', [App\Http\Controllers\HomeController::class, 'nra'])->name('nra.list');
Route::get('nra-buy', [App\Http\Controllers\HomeController::class, 'nraBuy'])->name('nra.buy');
Route::get('nra-blacklist', [App\Http\Controllers\HomeController::class, 'nraBlacklist'])->name('nra.blacklist');
Route::get('list-alumni/{status}', [App\Http\Controllers\HomeController::class, 'listAlumni'])->name('alumni.list');
Route::name('events.')->prefix('events')->group(function () {
    Route::get('/', [App\Http\Controllers\EventController::class, 'index'])->name('index');
    Route::get('{id}', [App\Http\Controllers\EventController::class, 'show'])->name('show');
});
Route::post('tripay-callback', [App\Http\Controllers\CallbackController::class, 'tripay'])->name('tripay-callback');
Route::name('merchant.')->prefix('merchant')->group(function(){
    Route::get('/',[App\Http\Controllers\MerchantController::class, 'index'])->name('index');
    Route::get('scan',[App\Http\Controllers\MerchantController::class, 'scan'])->name('scan');
    Route::get('logout',[App\Http\Controllers\MerchantController::class, 'logout'])->name('logout');
    Route::post('voucher-detail',[App\Http\Controllers\MerchantController::class, 'voucherDetail'])->name('voucher-detail');
    Route::post('claim-voucher',[App\Http\Controllers\MerchantController::class, 'claimVoucher'])->name('claim-voucher');
    Route::post('send-otp',[App\Http\Controllers\MerchantController::class, 'sendOtp'])->name('send-otp');
    Route::post('verify-otp',[App\Http\Controllers\MerchantController::class, 'verifyOtp'])->name('verify-otp');
});
Route::name('shop.')->group(function () {
    Route::get('thankyou', function(){
        return view('shop.thankyou');
    })->name('thankyou');
    Route::get('shop', [App\Http\Controllers\ShopController::class, 'index'])->name('index');
    Route::get('vouchers', [App\Http\Controllers\ShopController::class, 'vouchers'])->name('vouchers');
    Route::get('order-kta', [App\Http\Controllers\ShopController::class, 'orderKta'])->name('order-kta');
    Route::post('checkout-kta', [App\Http\Controllers\ShopController::class, 'checkoutKta'])->name('checkout-kta');
    Route::get('checkout', [App\Http\Controllers\ShopController::class, 'checkout'])->name('checkout');
    Route::get('cart', [App\Http\Controllers\ShopController::class, 'cart'])->name('cart');
    Route::get('cart/{id}/remove', [App\Http\Controllers\ShopController::class, 'cartRemove'])->name('cart-remove');
    Route::post('cart/{id}/update', [App\Http\Controllers\ShopController::class, 'cartUpdate'])->name('cart-update');
    Route::get('category/{slug}', [App\Http\Controllers\ShopController::class, 'productList'])->name('product-list');
    Route::get('product/{slug}', [App\Http\Controllers\ShopController::class, 'productDetail'])->name('product-detail');
    Route::get('product/{slug}/add_to_cart', [App\Http\Controllers\ShopController::class, 'addToCart'])->name('add_to_cart');
    Route::post('product/action', [App\Http\Controllers\ShopController::class, 'productAction'])->name('product-action');
    Route::post('place-order', [App\Http\Controllers\ShopController::class, 'placeOrder'])->name('place-order');
});
