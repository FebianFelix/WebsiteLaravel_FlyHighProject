<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
    private function authorizeBooking(Request $request, Booking $booking): void
    {
        abort_unless($request->user()->id === $booking->user_id || $request->user()->canMonitor(), 403);
    }

    public function show(Request $request, Booking $booking): View
    {
        $this->authorizeBooking($request, $booking);
        return view('invoices.show', ['booking' => $booking->load(['user', 'flight', 'payments'])]);
    }

    public function pdf(Request $request, Booking $booking): Response
    {
        $this->authorizeBooking($request, $booking);
        $html = view('invoices.pdf', ['booking' => $booking->load(['user', 'flight', 'payments'])])->render();
        $pdf = new Dompdf(['isRemoteEnabled' => false]);
        $pdf->loadHtml($html, 'UTF-8');
        $pdf->setPaper('A4');
        $pdf->render();
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="FlyHigh-Invoice-'.$booking->reference.'.pdf"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
