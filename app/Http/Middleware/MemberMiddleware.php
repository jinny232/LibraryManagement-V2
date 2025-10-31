<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MemberMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('member')->check()) {
            return redirect()->route('login.qr.form');
        }
        return $next($request);
    }
}
