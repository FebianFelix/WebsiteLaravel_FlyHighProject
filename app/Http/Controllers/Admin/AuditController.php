<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function index(): View
    {
        return view('admin.audit', [
            'events' => AuditEvent::with('actor:id,name,email')->latest()->paginate(30),
        ]);
    }
}
