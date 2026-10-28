<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlightController as AdminFlightController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FlightController::class, 'home'])->name('home');
Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
Route::get('/flights/{flight}', [FlightController::class, 'show'])->name('flights.show');
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [BookingController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/flights/{flight}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('flights', AdminFlightController::class)->except(['show']);
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
});

// Redirect bookmarks from the original 2023 student project.
Route::redirect('/index', '/');
Route::redirect('/indexLogin', '/dashboard');
Route::redirect('/halamanLogin', '/login');
Route::redirect('/pemesanan', '/flights');
Route::redirect('/form', '/flights');
Route::redirect('/ticket', '/bookings');
Route::redirect('/indexAdmin', '/admin');
Route::redirect('/ubah', '/admin/bookings');
Route::redirect('/destination', '/flights');
Route::redirect('/journey', '/about');
Route::redirect('/contactus', '/contact');
Route::redirect('/team', '/about');
Route::redirect('/bali', '/flights?destination=Bali');
Route::redirect('/singapore', '/flights?destination=Singapore');
Route::redirect('/thailand', '/flights?destination=Bangkok');
