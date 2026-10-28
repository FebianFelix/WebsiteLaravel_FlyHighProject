<?php

namespace App\Http\Controllers;

use App\Models\ContentPage;
use App\Models\SiteSetting;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function help(): View { return view('pages.help'); }
    public function info(): View { return view('pages.info'); }
    public function legal(): View { return view('pages.legal'); }
    public function show(string $slug): View
    {
        return view('pages.content', ['page' => ContentPage::where('slug', $slug)->where('is_published', true)->firstOrFail()]);
    }
}
