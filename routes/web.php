<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DemoPaymentController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\LiveOfferController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\RefundController as AdminRefundController;
use App\Http\Controllers\Admin\PortalController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlightController as AdminFlightController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FlightController::class, 'home'])->name('home');
Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
Route::get('/air-offers', [LiveOfferController::class, 'index'])->name('flights.live.index');
Route::post('/air-offers', [LiveOfferController::class, 'search'])->middleware('throttle:5,1')->name('flights.live.search');
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
    Route::get('/itineraries/create', [ItineraryController::class, 'create'])->name('itineraries.create');
    Route::post('/itineraries', [ItineraryController::class, 'store'])->middleware('throttle:8,1')->name('itineraries.store');
    Route::get('/itineraries/{itinerary}', [ItineraryController::class, 'show'])->name('itineraries.show');
    Route::post('/itineraries/{itinerary}/demo-confirm', [ItineraryController::class, 'confirm'])->name('itineraries.confirm');
    Route::patch('/itineraries/{itinerary}/cancel', [ItineraryController::class, 'cancel'])->name('itineraries.cancel');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/flights/{flight}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/ticket', [TicketController::class, 'show'])->name('bookings.ticket');
    Route::get('/bookings/{booking}/ticket.pdf', [TicketController::class, 'pdf'])->name('bookings.ticket.pdf');
    Route::get('/bookings/{booking}/invoice', [InvoiceController::class, 'show'])->name('bookings.invoice');
    Route::get('/bookings/{booking}/invoice.pdf', [InvoiceController::class, 'pdf'])->name('bookings.invoice.pdf');
    Route::post('/bookings/{booking}/stripe-checkout', [StripeCheckoutController::class, 'checkout'])->middleware('throttle:5,1')->name('bookings.stripe-checkout');
    Route::post('/bookings/{booking}/refund', [RefundController::class, 'store'])->middleware('throttle:3,1')->name('bookings.refund');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/demo-payment', [DemoPaymentController::class, 'confirm'])->middleware('throttle:5,1')->name('bookings.demo-payment');
    Route::get('/account', [ProfileController::class, 'edit'])->name('account.edit');
    Route::patch('/account', [ProfileController::class, 'update'])->name('account.update');
    Route::patch('/account/preferences', [PreferenceController::class, 'update'])->name('account.preferences');
    Route::get('/account/wishlist', [WishlistController::class, 'index'])->name('account.wishlist');
    Route::post('/flights/{flight}/wishlist', [WishlistController::class, 'toggle'])->name('flights.wishlist');
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
        Route::get('/refunds', [AdminRefundController::class, 'index'])->name('refunds.index');
    });
    Route::middleware('admin:config')->group(function () {
        Route::resource('flights', AdminFlightController::class)->except(['show']);
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::resource('pages', ContentController::class)->except(['show']);
        Route::get('/promos', [PromoController::class, 'index'])->name('promos.index');
        Route::post('/promos', [PromoController::class, 'store'])->name('promos.store');
        Route::patch('/promos/{promo}', [PromoController::class, 'toggle'])->name('promos.toggle');
    });
    Route::post('/refunds/{refund}/approve', [AdminRefundController::class, 'approve'])->middleware('admin:athena')->name('refunds.approve');
    Route::post('/refunds/{refund}/reject', [AdminRefundController::class, 'reject'])->middleware('admin:athena')->name('refunds.reject');
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
