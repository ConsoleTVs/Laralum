<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()) {
            if(Auth::user()->has('admin.access')) {
                return $next($request);
            }
            return redirect('/')->with('error', "You don't have permissions");

        } else {
            return redirect('/')->with('error', 'You are not logged in');
        }
    }
}
