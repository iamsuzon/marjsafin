<?php

use App\Http\Controllers\UserAppointmentBookingController;
use App\Http\Controllers\WafPaymentManageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/webhook/wafid', [UserAppointmentBookingController::class, 'wafidWebhook'])->name('wafid.webhook');
Route::get('/booking-data', [UserAppointmentBookingController::class, 'getDataforLocal']);

Route::post('/set-payment-links', [WafPaymentManageController::class, 'setPaymentLinks']);
