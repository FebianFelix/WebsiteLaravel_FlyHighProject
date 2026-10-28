<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PreferenceController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'locale' => ['required', Rule::in(['id','en'])],
            'currency' => ['required', Rule::in(['IDR','USD','SGD'])],
            'timezone' => ['required', 'timezone'],
            'email_updates' => ['nullable', 'boolean'],
            'seat_preference' => ['required', Rule::in(['any','window','aisle'])],
        ]);
        $data['email_updates'] = $request->boolean('email_updates');
        $request->user()->update($data);
        return back()->with('success', 'Preferensi Anda sudah disimpan.');
    }
}
