<?php

use Illuminate\Support\Facades\Route;

use App\Http\Livewire\MessageComponent;
use App\Http\Controllers\MessageController;
use App\Http\Livewire\UsersComponent;

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
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('devices.admin');
})->name('dashboard');


//Route::middleware(['auth:sanctum', 'verified'])->get('/messages', MessageComponent::class)->name('messages');
Route::middleware(['auth:sanctum', 'verified'])->get('/messages', function () {
    return view('messages.index');
})->name('messages');

Route::middleware(['auth:sanctum', 'verified'])->get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');

Route::middleware(['auth:sanctum', 'verified'])->get('/users', UsersComponent::class)->name('users');