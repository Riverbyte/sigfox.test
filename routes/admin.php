<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\MessageController;

Route::get('', [HomeController::class, 'index'])->middleware('can:Ver administrador')->name('home');

Route::resource('roles',RoleController::class)->names('roles');

Route::resource('users',UserController::class)->only(['index','edit','update'])->names('users');

Route::resource('devices',DeviceController::class)->names('devices');

Route::get('/messages/{device_id}', function ($device_id) {
    return view('admin.messages.show',['device_id' => $device_id]);
})->name('admin.messages.show');

Route::resource('messages',MessageController::class)->names('messages');

