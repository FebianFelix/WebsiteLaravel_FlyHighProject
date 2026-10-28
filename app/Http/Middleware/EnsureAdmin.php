<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next, ?string $area = null): Response
    {
        $user = $request->user();
        abort_unless($user?->isAdmin(), 403);
        if ($area === 'athena') {
            abort_unless(in_array($user->role, ['admin', 'athena'], true), 403);
        }
        if ($area === 'config') {
            abort_unless($user->canManageConfig(), 403);
        }
        if ($area === 'monitor') {
            abort_unless($user->canMonitor(), 403);
        }
        return $next($request);
    }
}
