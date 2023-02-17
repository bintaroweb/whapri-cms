<?php

use App\Http\Controllers\Api\BillingController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('billing/notification', [BillingController::class, 'notification']);
Route::post('device/status', [DeviceController::class, 'status']);
Route::post('message/status', [MessageController::class, 'status']);
Route::get('message/blast', [MessageController::class, 'blast']);
Route::get('message/send', [MessageController::class, 'send']);
Route::post('message/contact', [MessageController::class, 'contact']);
