<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Role_User;
use Hash;

class SetupController extends Controller
{

    public function index()
    {
    	#	-----------------
    	#	Laralum Versioner
    	#	-----------------

    	$version = "1.0";

		return view('admin/Setup/index', ['version' => $version]);
    }

    public function setup(Request $request)
    {
    	# Validate Request
    	$this->validate($request, [
	        'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'country_code' => 'required',
            'role_name' => 'required',
            'role_color' => 'required',
	    ]);

    	# Create User
    	$user = new User;
    	$user->name = $request->input('name');
    	$user->email = $request->input('email');
    	$user->password = Hash::make($request->input('password'));
    	$user->country_code = $request->input('country_code');
    	$user->save();

    	# Create Administrator Role
    	$role = new Role;
    	$role->name = $request->input('role_name'); # Default name: 'Administrator'
    	$role->color = $request->input('role_color'); #4caf50'; # Default green color: '#4caf50'
    	$role->save();

    	# Add user to the administrator role
    	$rel = new Role_User;
    	$rel->user_id = $user->id;
    	$rel->role_id = $role->id;
    	$rel->save();

    	# Redirect to main page
    	return redirect('/')->with('success', "Laralum was installed!");
    }
}
