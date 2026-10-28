<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.settings', ['settings' => collect(array_keys(SiteSetting::DEFAULTS))
            ->mapWithKeys(fn ($key) => [$key => SiteSetting::valueFor($key)])]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'brand_name' => ['required', 'string', 'max:60'],
            'tagline' => ['required', 'string', 'max:160'],
            'support_email' => ['required', 'email', 'max:160'],
            'support_hours' => ['required', 'string', 'max:160'],
            'banner_message' => ['nullable', 'string', 'max:240'],
            'booking_enabled' => ['required', 'boolean'],
            'maintenance_notice' => ['nullable', 'string', 'max:240'],
        ]);
        foreach (array_keys(SiteSetting::DEFAULTS) as $key) {
            SiteSetting::query()->updateOrCreate(['key' => $key], ['value' => (string) ($data[$key] ?? '')]);
        }
        AuditEvent::create(['actor_user_id' => $request->user()->id, 'action' => 'settings.updated', 'subject' => 'site', 'details' => ['fields' => array_keys($data)]]);
        return back()->with('success', 'Pengaturan situs telah disimpan.');
    }
}
