<?php

namespace App\Http\Middleware\Laralum;

use Closure;
use Auth;
use Laralum;
use App;

class Base
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
        # Check if the user is activated
        if(Auth::check()) {

            $user = Laralum::loggedInuser();

            if(!$user->active) {
                if(Laralum::currentURL() != url('/logout')) {
                    if (strpos(Laralum::currentURL(), route('Laralum::activate_form')) !== false) {
                        // Seems to be ok
                    } else {
                        return redirect()->route('Laralum::activate_form');
                    }
                }

            }

            if($user->banned and Laralum::currentURL() != route('Laralum::banned')) {
                if(Laralum::currentURL() != url('/logout')) {
                    return redirect()->route('Laralum::banned');
                }
            }

            # Set App Locale
            if($user->locale) {
                App::setLocale($user->locale);
            }

        }

        return $next($request);
    }
}
