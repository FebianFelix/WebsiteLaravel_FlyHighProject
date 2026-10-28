<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate([
            'status' => ['nullable', 'in:pending,confirmed,cancelled,expired'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $query = Booking::query()->with(['user', 'flight'])->latest();
        if (! empty($data['status'])) { $query->where('status', $data['status']); }
        if (! empty($data['search'])) {
            $query->where('reference', 'like', '%'.addcslashes($data['search'], '%_\\').'%');
        }
        return view('admin.bookings.index', ['bookings' => $query->paginate(20)->withQueryString()]);
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Reference', 'Member', 'Email', 'Flight', 'Status', 'Passengers', 'Demo total IDR', 'Created UTC']);
            Booking::with(['user', 'flight'])->orderBy('id')->chunk(200, function ($bookings) use ($handle) {
                foreach ($bookings as $booking) {
                    $safe = fn ($value) => preg_match('/^[=+\-@]/', (string) $value) ? "'".$value : $value;
                    fputcsv($handle, [
                        $safe($booking->reference), $safe($booking->user->name), $safe($booking->user->email),
                        $safe($booking->flight->flight_number), $booking->status,
                        $booking->seats, $booking->total_price, $booking->created_at->toIso8601String(),
                    ]);
                }
            });
            fclose($handle);
        }, 'flyhigh-demo-bookings.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
