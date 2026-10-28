<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use App\Models\PromoCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PromoController extends Controller
{
    public function index(): View { return view('admin.promos', ['promos' => PromoCode::latest()->paginate(25)]); }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'alpha_dash', 'max:40', 'unique:promo_codes,code'],
            'type' => ['required', Rule::in(['fixed', 'percentage'])],
            'value' => ['required', 'integer', 'min:1', 'max:100000000'],
            'max_uses' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after:valid_from'],
        ]);
        if ($data['type'] === 'percentage' && $data['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage cannot exceed 100.'])->withInput();
        }
        $data['code'] = strtoupper($data['code']);
        PromoCode::create($data + ['is_active' => true]);
        AuditEvent::create(['actor_user_id' => $request->user()->id, 'action' => 'promo.created', 'subject' => $data['code']]);
        return back()->with('success', 'Promo dibuat.');
    }

    public function toggle(Request $request, PromoCode $promo): RedirectResponse
    {
        $promo->update(['is_active' => ! $promo->is_active]);
        AuditEvent::create(['actor_user_id' => $request->user()->id, 'action' => 'promo.toggled', 'subject' => $promo->code]);
        return back()->with('success', 'Status promo diperbarui.');
    }
}
