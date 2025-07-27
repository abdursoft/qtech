<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register',[AuthController::class,'signup'])->name('register');
Route::post('/login',[AuthController::class,'signin'])->name('login');


// customer authenticated routes
Route::middleware(AuthMiddleware::class)->group(function(){
    Route::get('/services', [ServiceController::class, 'show'])->name('customer.services');
    Route::get('bookings', [BookingController::class, 'store'])->name('customer.booking_list');
    Route::post('bookings', [BookingController::class, 'store'])->name('customer.booking');
});


// admin authenticated routes
Route::middleware(AdminMiddleware::class)->group(function(){
    Route::post('/services', [ServiceController::class, 'store'])->name('admin.services');
    Route::put('/services/{id}', [ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('admin.services.delete');
    Route::get('/admin/bookings', [BookingController::class, 'store'])->name('admin.booking_list');
});
