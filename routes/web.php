<?php

use Illuminate\Support\Facades\Route;

use App\Http\Livewire\MessageComponent;
use App\Http\Controllers\MessageController;
use App\Http\Livewire\UsersComponent;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\BulkSmsController;

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

Route::get('/', function () {
    return view('welcome');
});

/*
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
*/



//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', DeviceComponent::class)->name('dashboard');
//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//    return view('devices.admin');
//})->name('dashboard');


Route::middleware(['auth:sanctum', 'verified'])->get('/devices', function () {
    return view('devices.admin');
})->name('devices');


//Route::middleware(['auth:sanctum', 'verified'])->get('/messages', MessageComponent::class)->name('messages');
Route::middleware(['auth:sanctum', 'verified'])->get('/messages', function () {
    return view('messages.index');
})->name('messages');

//Route::middleware(['auth:sanctum', 'verified'])->get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');

Route::middleware(['auth:sanctum', 'verified'])->get('/messages/{device_id}', function ($device_id) {
    return view('messages.show',['device_id' => $device_id]);
})->name('messages.show');

Route::middleware(['auth:sanctum', 'verified'])->get('/users', UsersComponent::class)->name('users');

Route::middleware(['auth:sanctum', 'verified'])->resource('/events', EventController::class)->names('events');

//Route::middleware(['auth:sanctum', 'verified'])->resource('/devices', DeviceController::class)->names('devices');

Route::view('/bulksms', 'bulksms');

Route::post('/bulksms', [BulkSmsController::class,'sendSms'])->name('bulksms.sms');