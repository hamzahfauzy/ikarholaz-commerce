<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Mobile\AdminController;
use App\Http\Controllers\Mobile\AlumniController;
use App\Http\Controllers\Mobile\AuthController;

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

Route::get('/get-district/{province_id}', [BaseController::class, 'getDistrict']);
Route::get('/get-service/{courier}', [BaseController::class, 'getService']);
Route::get('/get-payment-channel', [BaseController::class, 'paymentChannel']);
Route::get('/get-kartu/{nomor}', [BaseController::class, 'getKartu']);
Route::get('/get-nomor-regular/{tahun_lulus}', [BaseController::class, 'getNomorRegular']);
Route::get('/get-price/{digit}', [BaseController::class, 'getPrice']);
Route::get('/test-wa', [BaseController::class, 'testWa']);

Route::prefix('mobile')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('otp', [AuthController::class, 'otp']);
    Route::post('register', [AuthController::class, 'register']);

    Route::prefix('alumni')->group(function () {
        Route::post('edit', [AlumniController::class, 'edit']);

        Route::get('delete-skill/{id}', [AlumniController::class, 'deleteSkill']);
        Route::post('upload-profile', [AlumniController::class, 'uploadProfile']);
    });


    Route::prefix('admin')->group(function () {

        Route::prefix('alumni')->group(function () {
            Route::get('', [AdminController::class, 'getAlumni']);
            Route::get('{id}', [AdminController::class, 'getDetailAlumni']);
            Route::get('search/{key}', [AdminController::class, 'searchAlumni']);
            Route::get('approve/{id}', [AdminController::class, 'approveAlumni']);
            Route::get('delete/{id}', [AdminController::class, 'deleteAlumni']);
        });
    });
});
