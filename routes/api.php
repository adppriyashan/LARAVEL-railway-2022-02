<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TrainController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [ApiAuthController::class, 'register'])->name('api.register');
Route::post('/login', [ApiAuthController::class, 'login'])->name('api.login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('api.logout');
    Route::post('/enrollbooking', [BookingController::class, 'enrollAPI'])->name('api.bookings.enroll');
    Route::post('/available', [TrainController::class, 'getAvailableAPI'])->name('api.trains.get.available');
});
