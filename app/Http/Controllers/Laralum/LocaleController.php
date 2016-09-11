<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laralum;
use URL;
use Auth;

class LocaleController extends Controller
{
    public function set($locale, Request $request)
    {
    	if (Auth::check()){
        $user = Laralum::loggedInUser();
        $user->locale = $locale;
        $user->save();
    	}
    	else
    	{
    		$request->session()->put('locale', $locale);
    	}
        return redirect(URL::previous());
    }
}
