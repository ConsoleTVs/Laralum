<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laralum;

class HomeController extends Controller
{
    public function index()
    {
    	return view('admin/home/index');
    }

    public function test()
    {
        return Laralum::blogs();
    }
}
