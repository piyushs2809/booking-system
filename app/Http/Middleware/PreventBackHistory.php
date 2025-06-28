<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->set('Cache-Control','no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0, no-transform');
        $response->headers->set('Pragma','no-cache');
        $response->headers->set('Expires','Sat, 01 Jan 2000 00:00:00 GMT');        
        return $response;
    }
}
