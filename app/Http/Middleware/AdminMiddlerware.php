<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddlerware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user())
        {
            if(Auth::user()->status=='0')
            {
                return $next($request);
            }
            else
            {
                return redirect('/dashboard')->with('status', 'access denied!');
            }
        }
        else
        {
            return redirect('/dashboard')->with('status', 'login fisrt ');
        }
    }
}