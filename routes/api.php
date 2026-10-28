<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\FlightController;
use Illuminate\Http\Request;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/payments/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

Route::get('/v1/flights', [FlightController::class, 'index']);
Route::get('/v1/flights/{flight}', [FlightController::class, 'show']);

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/me', fn (Request $request) => $request->user()->only('id', 'name', 'email'));
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store'])->middleware('throttle:10,1');
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
});
