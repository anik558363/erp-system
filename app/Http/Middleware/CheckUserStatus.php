<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jodi user login thake kintu tar status 'active' na hoy
        if (Auth::check() && Auth::user()->status !== 'active') {
            Auth::logout(); // Take logout kore dibe

            return redirect()->route('login')->withErrors([
                'email' => 'Apnar account-ti inactive royeche. Admin approval ba activation chara login korte parben na.'
            ]);
        }

        return $next($request);
    }
}
