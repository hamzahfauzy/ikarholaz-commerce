<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\Bot\VoucherController as BotVoucherController;
use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\AdminController;
use App\Http\Controllers\Mobile\AlumniController;

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

Route::get('/get-provinces', [BaseController::class, 'getProvinces']);
Route::get('/get-fields/{fields}', [BaseController::class, 'getFields']);
Route::get('/get-district/{province_id}', [BaseController::class, 'getDistrict']);
Route::get('/get-service/{courier}', [BaseController::class, 'getService']);
Route::get('/get-payment-channel', [BaseController::class, 'paymentChannel']);
Route::get('/get-kartu/{nomor}', [BaseController::class, 'getKartu']);
Route::get('/cek-kartu/{nomor}', [BaseController::class, 'cekKartu']);
Route::get('/get-nomor-regular/{tahun_lulus}', [BaseController::class, 'getNomorRegular']);
Route::get('/get-price/{digit}', [BaseController::class, 'getPrice']);
Route::get('/test-wa', [BaseController::class, 'testWa']);
Route::get('/test-pdf', [BaseController::class, 'testPdf']);
Route::post('/send-pdf', [BaseController::class, 'sendPdf']);
Route::post('/download-pdf', [BaseController::class, 'downloadPdf']);
Route::get('/get-agenda', [BaseController::class, 'getAgenda']);
Route::get('/get-jolali', [BaseController::class, 'getJolali']);
Route::get('/register-wa', [AlumniController::class, 'registerWa']);
Route::post('/register-wa2', [AlumniController::class, 'registerWa']);
Route::post('/order-tiket', [BaseController::class, 'orderTiket']);
Route::post('/reg-tiket', [BaseController::class, 'regTiket']);
Route::post('/reg-tiket-options', [BaseController::class, 'regTiketOption']);
Route::post('/info-nra', [AlumniController::class, 'getNra']);
Route::post('/cek-nra', [BaseController::class, 'cekNra']);
Route::post('/send-candidates', [BaseController::class, 'sendCandidates']);
Route::post('/get-alumnis', [BaseController::class, 'getAlumnis']);

Route::prefix('bot')->group(function(){
    Route::prefix('vouchers')->group(function(){
        Route::post('/',[BotVoucherController::class,'index']);
        Route::post('buy',[BotVoucherController::class,'buy']);
    });
});

Route::prefix('events')->group(function(){
    Route::get('/',[EventController::class,'index']);
    Route::post('create',[EventController::class,'store']);
    Route::put('update/{event}',[EventController::class,'update']);
    Route::patch('status/{event}',[EventController::class,'patchStatus']);
    Route::delete('delete/{id}',[EventController::class,'destroy']);
    Route::get('show/{id}',[EventController::class,'show']);
});

Route::prefix('mobile')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('login-nra', [AuthController::class, 'loginNra']);
    Route::post('otp', [AuthController::class, 'otp']);
    Route::post('verify-otp-nra', [AuthController::class, 'verifyOtpNra']);
    Route::post('register', [AuthController::class, 'register']);
    
    Route::get('kta/{id}', [AlumniController::class, 'kta']);
    Route::get('dpt', [AlumniController::class, 'alumnidpt']);

    Route::prefix('alumni')->group(function () {
        Route::get('get-notifications/{id}', [AlumniController::class, 'getNotifications']);
        Route::get('get-broadcasts/{id}', [AlumniController::class, 'getBroadcasts']);
        Route::post('mark-as-read', [AlumniController::class, 'markAsRead']);
        Route::post('edit', [AlumniController::class, 'edit']);

        Route::get('delete-skill/{id}', [AlumniController::class, 'deleteSkill']);
        Route::get('delete-business/{id}', [AlumniController::class, 'deleteBusiness']);
        Route::get('delete-community/{id}', [AlumniController::class, 'deleteCommunity']);
        Route::get('delete-profession/{id}', [AlumniController::class, 'deleteProfession']);
        Route::get('delete-training/{id}', [AlumniController::class, 'deleteTraining']);
        Route::get('delete-appreciation/{id}', [AlumniController::class, 'deleteAppreciation']);
        Route::get('delete-interest/{id}', [AlumniController::class, 'deleteInterest']);
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