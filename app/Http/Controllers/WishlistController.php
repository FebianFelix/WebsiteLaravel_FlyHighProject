<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        return view('account.wishlist', [
            'flights' => $request->user()->wishlistedFlights()->orderBy('departure_at')->paginate(9),
        ]);
    }

    public function toggle(Request $request, Flight $flight): RedirectResponse
    {
        $user = $request->user();
        if ($user->wishlistedFlights()->where('flights.id', $flight->id)->exists()) {
            $user->wishlistedFlights()->detach($flight->id);
            $status = 'Penerbangan dihapus dari wishlist.';
        } else {
            $user->wishlistedFlights()->syncWithoutDetaching([$flight->id]);
            $status = 'Penerbangan disimpan ke wishlist.';
        }
        return back()->with('success', $status);
    }
}
