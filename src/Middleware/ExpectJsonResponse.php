<?php

namespace Shankar\LaravelBasicSetting\Middleware;

use Closure;
use Illuminate\Http\Request;


class ExpectJsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/*')) {
            $request->headers->set('Accept', 'application/json');
        }
        return $next($request);
    }
}
