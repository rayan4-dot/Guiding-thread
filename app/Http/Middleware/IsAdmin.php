<?php

// app/Http/Middleware/IsAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        if (Auth::check() && Auth::user()->role && Auth::user()->role->role == 'admin') {
            return $next($request);
        }
        return response()->view('errors.user-denied');
    }
}
