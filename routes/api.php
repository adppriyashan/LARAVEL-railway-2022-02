<?php

use App\Http\Controllers\Auth\ApiAuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [ApiAuthController::class, 'register'])->name('api.register');
Route::post('/login', [ApiAuthController::class, 'login'])->name('api.login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('api.logout');
    Route::get('/new', [BookingController::class, 'index'])->name('bookings.index')->middleware(['auth']);
    Route::get('/enroll', [BookingController::class, 'enroll'])->name('bookings.enroll');
    Route::get('/getBookedSeats/{turn}/{start}/{end}', [BookingController::class, 'getBookedSeats'])->name('bookings.seats');
    Route::get('/getTicketPrices/{train}/{location1}/{location2}', [BookingController::class, 'getTicketPrices'])->name('bookings.seats');
    Route::get('/pass/view/{seatid}', [BookingController::class, 'viewPass'])->name('pass.view');
    Route::get('/check/{seatid}/{turnno}/{start}/{end}/{station}', [BookingController::class, 'checkAttend'])->name('pass.check');
    Route::get('/season/check/{authcode}', [BookingController::class, 'isValidSeason'])->name('season.check');
});
