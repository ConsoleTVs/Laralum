<?php

namespace App\Http\Middleware\Laralum;

use Closure;
use Auth;
use Laralum;

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
        if(Laralum::checkInstalled()) {
            if(Auth::check()) {
                Laralum::mustBeAdmin(Laralum::loggedInUser());
            } else {
                return redirect('/')->with('error', 'You are not logged in');
            }
        }
        return $next($request);
    }
}
