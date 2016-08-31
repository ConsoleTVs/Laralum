<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laralum;
use URL;

class LocaleController extends Controller
{
    public function set($locale)
    {
        $user = Laralum::loggedInUser();
        $user->locale = $locale;
        $user->save();

        return redirect(URL::previous());
    }
}
