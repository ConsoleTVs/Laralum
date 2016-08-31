<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laralum;

class AboutController extends Controller
{
    public function index()
    {
        return view('laralum/about/index');
    }
}
