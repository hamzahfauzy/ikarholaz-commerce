<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BaseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/get-district/{province_id}',[BaseController::class,'getDistrict']);
Route::get('/get-service/{courier}',[BaseController::class,'getService']);
Route::get('/get-payment-channel',[BaseController::class,'paymentChannel']);
Route::get('/get-kartu/{nomor}',[BaseController::class,'getKartu']);
Route::get('/get-nomor-regular/{tahun_lulus}',[BaseController::class,'getNomorRegular']);
Route::get('/get-price/{digit}',[BaseController::class,'getPrice']);
