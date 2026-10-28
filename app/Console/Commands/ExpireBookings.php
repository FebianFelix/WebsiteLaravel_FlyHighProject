<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    protected $signature = 'flyhigh:expire-bookings';
    protected $description = 'Expire unpaid reservations and release capacity';

    public function handle(BookingService $service): int
    {
        $count = 0;
        Booking::query()->where('status', Booking::PENDING)
            ->where('expires_at', '<=', now())->orderBy('id')
            ->chunkById(100, function ($bookings) use ($service, &$count) {
                foreach ($bookings as $booking) {
                    $service->expire($booking);
                    $count++;
                }
            });
        $this->info("Processed {$count} expired booking holds.");
        return self::SUCCESS;
    }
}
