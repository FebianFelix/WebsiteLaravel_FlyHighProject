<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\TicketController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/halamanLogin', function () {
    return view('halamanLogin');
});

Route::get('/indexLogin', function () {
    return view('indexLogin');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/ticket', function () {
    return view('ticket');
});

Route::get('/pemesanan', function () {
    return view('pemesanan');
});

Route::get('/journey', function () {
    return view('journey');
});

Route::get('/destination', function () {
    return view('destination');
});

Route::get('/contactus', function () {
    return view('contactus');
});

Route::get('/bali', function () {
    return view('bali');
});
Route::get('/singapore', function () {
    return view('singapore');
});

Route::get('/thailand', function () {
    return view('thailand');
});
Route::get('/indexAdmin', function () {
    return view('indexAdmin');
});
Route::get('/team', function () {
    return view('team');
});
Route::get('/ubah', function () {
    return view('ubah');
});

Route::get('/form', [FormController::class, 'showForm'])->name('form.show');

Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/check-database-connection', [DatabaseController::class, 'checkDatabaseConnection']);

Route::post('/store-ticket', [TicketController::class, 'store'])->name('store.ticket');

Route::get('/pembayaran', function () {
    return view('pembayaran');
})->name('pembayaran');

Route::get('/tickets/{id}/edit', [TicketController::class, 'edit'])->name('ticket.edit');

Route::delete('/ticket/{id}', [TicketController::class, 'destroy'])->name('ticket.destroy');


