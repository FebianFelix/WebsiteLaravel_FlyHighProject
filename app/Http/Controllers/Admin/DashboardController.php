<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'flightCount' => Flight::count(),
            'bookingCount' => Booking::count(),
            'memberCount' => User::where('role', 'member')->count(),
            'recentBookings' => Booking::with(['user', 'flight'])->latest()->limit(6)->get(),
        ]);
    }
}
