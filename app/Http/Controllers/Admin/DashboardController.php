<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use App\Models\RefundRequest;
use App\Models\PaymentEvent;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $summary = [
            'flights' => Flight::count(),
            'bookings' => Booking::count(),
            'confirmed' => Booking::where('status', Booking::CONFIRMED)->count(),
            'pending' => Booking::where('status', Booking::PENDING)->where('expires_at', '>', now())->count(),
            'cancelled' => Booking::where('status', Booking::CANCELLED)->count(),
            'gross_booking_value' => Booking::where('status', Booking::CONFIRMED)->sum('total_price'),
            'members' => User::where('role', 'member')->count(),
            'reserved_seats' => Booking::where('status', Booking::CONFIRMED)->sum('seats'),
            'reconciliation_required' => PaymentEvent::where('status','requires_review')->count(),
            'refund_requested' => RefundRequest::where('status','requested')->count(),
            'refund_processing' => RefundRequest::where('status','processing')->count(),
        ];
        $daily = collect(range(6, 0))->map(function ($days) {
            $date = now()->subDays($days)->toDateString();
            return [
                'label' => now()->subDays($days)->format('d M'),
                'count' => Booking::whereDate('created_at', $date)->count(),
            ];
        });
        return view('admin.dashboard', [
            'summary' => $summary, 'daily' => $daily,
            'recentBookings' => Booking::with(['user','flight'])->latest()->limit(8)->get(),
        ]);
    }
}
