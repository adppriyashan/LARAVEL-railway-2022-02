<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\TrainTicketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth']);
Route::get('/home/logout', [HomeController::class, 'logout'])->name('home.logout');
Route::get('/import/locations', [LocationController::class, 'importLocations']);

Route::get('/auth/google', [LoginController::class, 'googleSignin']);
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::group(['prefix' => 'trains'], function () {
    Route::get('/', [TrainController::class, 'index'])->name('trains.index')->middleware(['auth']);
    Route::post('/enroll', [TrainController::class, 'enroll'])->name('trains.enroll');
    Route::post('/get', [TrainController::class, 'get'])->name('trains.get');
    Route::post('/delete', [TrainController::class, 'delete'])->name('trains.delete');
    Route::get('/available', [TrainController::class, 'getAvailable'])->name('trains.get.available');
});

Route::group(['prefix' => 'seasons'], function () {
    Route::get('/', [SeasonController::class, 'index'])->name('seasons.index')->middleware(['auth']);
    Route::post('/enroll', [SeasonController::class, 'enroll'])->name('seasons.enroll');
    Route::post('/get', [SeasonController::class, 'get'])->name('seasons.get');
    Route::post('/delete', [SeasonController::class, 'delete'])->name('seasons.delete');
    Route::get('/print', [SeasonController::class, 'print'])->name('seasons.print');
});

Route::group(['prefix' => 'tickets'], function () {
    Route::get('/', [TrainTicketController::class, 'index'])->name('tickets.index');
    Route::post('/enroll', [TrainTicketController::class, 'enroll'])->name('tickets.enroll');
    Route::post('/get', [TrainTicketController::class, 'get'])->name('tickets.get');
    Route::post('/delete', [TrainTicketController::class, 'delete'])->name('tickets.delete');
});

Route::group(['prefix' => 'schedules'], function () {
    Route::get('/', [ScheduleController::class, 'index'])->name('schedules.index')->middleware(['auth']);
    Route::post('/enroll', [ScheduleController::class, 'enroll'])->name('schedules.enroll');
    Route::post('/get', [ScheduleController::class, 'get'])->name('schedules.get');
    Route::post('/delete', [ScheduleController::class, 'delete'])->name('schedules.delete');
});

Route::group(['prefix' => 'bookings'], function () {
    Route::get('/new', [BookingController::class, 'index'])->name('bookings.index')->middleware(['auth']);
    Route::get('/enroll', [BookingController::class, 'enroll'])->name('bookings.enroll');
    Route::get('/getBookedSeats/{turn}/{start}/{end}', [BookingController::class, 'getBookedSeats'])->name('bookings.seats');
    Route::get('/getTicketPrices/{train}/{location1}/{location2}', [BookingController::class, 'getTicketPrices'])->name('bookings.seats');
    Route::get('/pass/view/{seatid}', [BookingController::class, 'viewPass'])->name('pass.view');
    Route::get('/check/{seatid}/{turnno}/{start}/{end}/{station}', [BookingController::class, 'checkAttend'])->name('pass.check');
    Route::get('/season/check/{authcode}', [BookingController::class, 'isValidSeason'])->name('season.check');
});
