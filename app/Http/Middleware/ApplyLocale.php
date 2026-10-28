<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ApplyLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->user()?->locale ?? $request->cookie('flyhigh_locale', 'id');
        App::setLocale(in_array($locale, ['id', 'en'], true) ? $locale : 'id');
        return $next($request);
    }
}
