<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectAttribution
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->set('X-Project-Origin', 'FlyHigh / FebianFelix');
        $response->headers->set('X-Project-Repository', 'https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject');
        return $response;
    }
}
