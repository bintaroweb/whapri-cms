<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

// Route::get('/login', [App\Http\Controllers\LoginController::class, 'authenticate']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//User
Route::get('users/datatable', [\App\Http\Controllers\UserController::class, 'datatable']);
Route::resource('users', '\App\Http\Controllers\UserController');
//Device
Route::get('devices/datatable', [\App\Http\Controllers\DeviceController::class, 'datatable']);
Route::post('devices/status', [\App\Http\Controllers\DeviceController::class, 'status']);
Route::resource('devices', '\App\Http\Controllers\DeviceController');
//Contact
Route::get('contacts/datatable', [\App\Http\Controllers\ContactController::class, 'datatable']);
Route::post('contacts/import', [\App\Http\Controllers\ContactController::class, 'import']);
Route::resource('contacts', '\App\Http\Controllers\ContactController');
//Message
Route::get('messages/autocomplete', [\App\Http\Controllers\MessageController::class, 'autocomplete']);
Route::get('messages/datatable', [\App\Http\Controllers\MessageController::class, 'datatable']);
Route::get('messages/detail', [\App\Http\Controllers\MessageController::class, 'detail']);
Route::get('messages/template', [\App\Http\Controllers\MessageController::class, 'template']);
Route::resource('messages', '\App\Http\Controllers\MessageController');
//Broadcast
Route::get('broadcasts/autocomplete', [\App\Http\Controllers\BroadcastController::class, 'autocomplete']);
Route::get('broadcasts/datatable', [\App\Http\Controllers\BroadcastController::class, 'datatable']);
Route::resource('broadcasts', '\App\Http\Controllers\BroadcastController');
//Group
Route::get('groups/datatable', [\App\Http\Controllers\GroupController::class, 'datatable']);
Route::get('groups/autocomplete', [\App\Http\Controllers\GroupController::class, 'autocomplete']);
Route::resource('groups', '\App\Http\Controllers\GroupController');
//Template
Route::get('templates/datatable', [\App\Http\Controllers\TemplateController::class, 'datatable']);
Route::get('templates/autocomplete', [\App\Http\Controllers\TemplateController::class, 'autocomplete']);
Route::resource('templates', '\App\Http\Controllers\TemplateController');
//Billing
Route::get('billings/datatable', [\App\Http\Controllers\BillingController::class, 'datatable']);
Route::get('billings/datatable_transaction', [\App\Http\Controllers\BillingController::class, 'datatable_transaction']);
Route::get('billings/autocomplete', [\App\Http\Controllers\BillingController::class, 'autocomplete']);
Route::post('billings/payment', [\App\Http\Controllers\BillingController::class, 'payment']);
Route::get('billings/ipaymu', [\App\Http\Controllers\BillingController::class, 'ipaymu']);
Route::get('billings/detail', [\App\Http\Controllers\BillingController::class, 'detail']);
Route::get('billings/transaction', [\App\Http\Controllers\BillingController::class, 'transaction']);
Route::resource('billings', '\App\Http\Controllers\BillingController');
