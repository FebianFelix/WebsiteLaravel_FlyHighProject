<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DemoPaymentController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\PortalController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlightController as AdminFlightController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FlightController::class, 'home'])->name('home');
Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
Route::get('/healthz', fn () => response()->json(['status' => 'ok']))->name('healthz');
Route::get('/verify/{reference}', [TicketController::class, 'verify'])->middleware('signed')->name('tickets.verify');
Route::get('/flights/{flight}', [FlightController::class, 'show'])->name('flights.show');
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::get('/help', [PagesController::class, 'help'])->name('help');
Route::get('/information', [PagesController::class, 'info'])->name('info');
Route::get('/legal', [PagesController::class, 'legal'])->name('legal');
Route::get('/pages/{slug}', [PagesController::class, 'show'])->name('pages.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordReset'])->middleware('throttle:3,1')->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [BookingController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/flights/{flight}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/ticket', [TicketController::class, 'show'])->name('bookings.ticket');
    Route::get('/bookings/{booking}/ticket.pdf', [TicketController::class, 'pdf'])->name('bookings.ticket.pdf');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/demo-payment', [DemoPaymentController::class, 'confirm'])->middleware('throttle:5,1')->name('bookings.demo-payment');
    Route::get('/account', [ProfileController::class, 'edit'])->name('account.edit');
    Route::patch('/account', [ProfileController::class, 'update'])->name('account.update');
    Route::put('/account/password', [ProfileController::class, 'password'])->name('account.password');
    Route::post('/account/tokens', [ProfileController::class, 'createToken'])->middleware('throttle:3,1')->name('account.tokens.create');
    Route::delete('/account/tokens/{tokenId}', [ProfileController::class, 'deleteToken'])->name('account.tokens.destroy');
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->middleware('throttle:3,1')->name('verification.send');
});

Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [PortalController::class, 'index'])->name('portal');
    Route::middleware('admin:monitor')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');
    });
    Route::middleware('admin:config')->group(function () {
        Route::resource('flights', AdminFlightController::class)->except(['show']);
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::resource('pages', ContentController::class)->except(['show']);
    });
});

Route::redirect('/index', '/');
Route::redirect('/indexLogin', '/dashboard');
Route::redirect('/halamanLogin', '/login');
Route::redirect('/pemesanan', '/flights');
Route::redirect('/form', '/flights');
Route::redirect('/ticket', '/bookings');
Route::redirect('/indexAdmin', '/admin');
Route::redirect('/ubah', '/admin');
Route::redirect('/destination', '/flights');
Route::redirect('/journey', '/about');
Route::redirect('/contactus', '/contact');
Route::redirect('/team', '/about');
