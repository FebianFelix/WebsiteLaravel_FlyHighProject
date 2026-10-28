<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingConfirmedNotification extends Notification
{
    use Queueable;
    public function __construct(public readonly Booking $booking) {}
    public function via(object $notifiable): array { return ['mail']; }
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Konfirmasi reservasi demo FlyHigh — '.$this->booking->reference)
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Reservasi demonstrasi Anda berhasil dikonfirmasi.')
            ->line('Referensi: '.$this->booking->reference)
            ->line('Ini bukan tiket maskapai resmi dan tidak ada pembayaran nyata.')
            ->action('Lihat reservasi', route('bookings.show', $this->booking));
    }
}
