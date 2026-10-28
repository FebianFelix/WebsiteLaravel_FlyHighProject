<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Dompdf\Dompdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    private function authorized(Request $request, Booking $booking): void
    {
        abort_unless(
            $request->user()->id === $booking->user_id || $request->user()->isAdmin(),
            403
        );
        abort_unless($booking->status === Booking::CONFIRMED, 404);
    }

    private function details(Booking $booking): array
    {
        $booking->load(['flight', 'passengers', 'user']);
        $url = URL::temporarySignedRoute('tickets.verify', now()->addYear(), [
            'reference' => $booking->reference,
        ]);
        $qr = (new SvgWriter())->write(new QrCode(data: $url, size: 180, margin: 0));
        return [
            'booking' => $booking,
            'verificationUrl' => $url,
            'qrSvg' => $qr->getString(),
        ];
    }

    public function show(Request $request, Booking $booking): View
    {
        $this->authorized($request, $booking);
        return view('tickets.show', $this->details($booking));
    }

    public function pdf(Request $request, Booking $booking): Response
    {
        $this->authorized($request, $booking);
        $html = view('tickets.pdf', $this->details($booking))->render();
        $pdf = new Dompdf(['isRemoteEnabled' => false]);
        $pdf->loadHtml($html, 'UTF-8');
        $pdf->setPaper('A4');
        $pdf->render();
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="FlyHigh-'.$booking->reference.'.pdf"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function verify(Request $request, string $reference): View
    {
        // This route is protected by Laravel's "signed" middleware.
        $booking = Booking::query()->with('flight')->where('reference', $reference)->firstOrFail();
        return view('tickets.verify', [
            'reference' => $booking->reference,
            'status' => $booking->status,
            'routeLabel' => $booking->flight->origin.' → '.$booking->flight->destination,
        ]);
    }
}
