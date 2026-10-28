<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use App\Models\RefundRequest;
use App\Services\BookingService;
use App\Services\StripeGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefundController extends Controller
{
    public function index(): View
    {
        return view('admin.refunds', ['refunds' => RefundRequest::with(['booking.user','booking.payments'])->latest()->paginate(20)]);
    }

    public function reject(Request $request, RefundRequest $refund): RedirectResponse
    {
        abort_unless($refund->status === 'requested', 409);
        $refund->update(['status' => 'rejected', 'reviewed_by' => $request->user()->id]);
        AuditEvent::create(['actor_user_id' => $request->user()->id, 'action' => 'refund.rejected', 'subject' => (string) $refund->id]);
        return back()->with('success', 'Refund rejected.');
    }

    public function approve(Request $request, RefundRequest $refund, StripeGateway $stripe, BookingService $bookings): RedirectResponse
    {
        abort_unless($refund->status === 'requested', 409);
        $booking = $refund->booking->load(['payments','flight']);
        $payment = $booking->payments->where('status', 'succeeded')->first();
        abort_unless($payment && $booking->flight->departure_at->isFuture(), 409);
        if ($payment->provider === 'stripe') {
            abort_unless(filled($payment->external_reference), 409, 'Missing payment intent for refund');
            $result = $stripe->refund($payment->external_reference, (int) $refund->amount, $refund->id);
            $refund->update([
                'status' => $result['status'] === 'succeeded' ? 'refunded' : 'processing',
                'provider_reference' => $result['id'], 'reviewed_by' => $request->user()->id,
            ]);
        } elseif ($payment->provider === 'demo') {
            $refund->update(['status' => 'refunded', 'provider_reference' => 'DEMO-REFUND-'.$refund->id, 'reviewed_by' => $request->user()->id]);
        } else {
            abort(409, 'Unsupported payment provider');
        }
        $bookings->cancel($booking);
        AuditEvent::create(['actor_user_id' => $request->user()->id, 'action' => 'refund.approved', 'subject' => (string) $refund->id]);
        return back()->with('success', 'Refund action submitted. Check payment provider for final settlement.');
    }
}
