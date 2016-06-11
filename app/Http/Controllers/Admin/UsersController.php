<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Users_Settings;
use App\Role;
use App\Role_User;
use Hash;
use Crypt;
use Mail;
use Auth;
use Schema;

class UsersController extends Controller
{

    public function __construct()
    {
        # Check permissions
        if(!Auth::user()->has('admin.users.access')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }
    }

    public function index()
    {
    	# Get all users
    	$users = User::all();

    	# Get the latest users
    	$latest_users = User::orderBy('id', 'desc')->take(6)->get();

    	# Get the newest user
    	$latest_user = User::orderBy('id', 'desc')->first();

    	# Get the active users
    	$active_users = User::where('active', true)->get();

    	# Get Banned Users
    	$banned_users = User::where('banned', true)->get();

    	# Get all roles
    	$roles = Role::all();

    	# Return the view
    	return view('admin/users/index', [
    		'users' 		=> 	$users,
    		'latest_users' 	=> 	$latest_users,
    		'latest_user' 	=> 	$latest_user,
    		'roles'			=>	$roles,
    		'active_users'	=>	$active_users,
    		'banned_users'	=>	$banned_users,
		]);
    }

    public function show($id)
    {
    	# Find the user
    	$user = User::findOrFail($id);

    	# Return the view
    	return view('admin/users/show', ['user' => $user]);
    }

    public function create()
    {
        # Check permissions
        if(!Auth::user()->has('admin.users.create')) {
            return redirect('/admin/users')->with('warning', "You are not allowed to perform this action");
        }

        # Get all roles
        $roles = Role::all();

        # Get all the data
        $data_index = 'users';
        require('Data/Create/Get.php');

        # Return the view
        return view('admin/users/create', [
            'roles'     =>  $roles,
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
            'encrypted' =>  $encrypted,
            'hashed'    =>  $hashed,
            'masked'    =>  $masked,
            'table'     =>  $table,
            'code'      =>  $code,
            'wysiwyg'   =>  $wysiwyg,
        ]);
    }

    public function store(Request $request)
    {

        # Check permissions
        if(!Auth::user()->has('admin.users.create')) {
            return redirect('/admin/users')->with('warning', "You are not allowed to perform this action");
        }

        # create the user
        $row = new User;

        # Save the data
        $data_index = 'users';
        require('Data/Create/Save.php');

        # Setup a random activation key
        $row->activation_key = str_random(25);

        # Get the register IP
        $row->register_ip = $request->ip();

        # Send welcome email if set
        if($request->input('mail')) {
            if($request->input('send_password')) {
                # Send Welcome email with password
                $this->SendWelcomeEmail($row, $password);
            } else {
                # Send Welcome email without password
                $this->SendWelcomeEmail($row, null);
            }
        }

        # Send activation email if set
        if($request->input('send_activation')) {
            $this->sendActivationEmail($row, $activation_key);
        }

        # Activate the user if set
        if($request->input('active')){
            $row->active = true;
        }

        # Save the user
        $row->save();

        $this->setRoles($row->id, $request);

        # Return the admin to the users page with a success message
        return redirect('/admin/users')->with('success', "The user has been created");
    }

    public function edit($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.users.edit')) {
            return redirect('/admin/users')->with('warning', "You are not allowed to perform this action");
        }

        # Find the user
        $row = User::findOrFail($id);

        # Get all the data
        $data_index = 'users';
        require('Data/Edit/Get.php');

        # Return the view
        return view('admin/users/edit', [
            'row'       =>  $row,
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
            'empty'     =>  $empty,
            'encrypted' =>  $encrypted,
            'hashed'    =>  $hashed,
            'masked'    =>  $masked,
            'table'     =>  $table,
            'code'      =>  $code,
            'wysiwyg'   =>  $wysiwyg,
        ]);
    }

    public function update($id, Request $request)
    {

        # Check permissions
        if(!Auth::user()->has('admin.users.edit')) {
            return redirect('/admin/users')->with('warning', "You are not allowed to perform this action");
        }

        # Find the user
        $row = User::findOrFail($id);

        # Save the data
        $data_index = 'users';
        require('Data/Edit/Save.php');

        # Return the admin to the users page with a success message
        return redirect('/admin/users')->with('success', "The user has been edited");
    }

    public function editRoles($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.users.roles')) {
            return redirect('/admin/users')->with('warning', "You are not allowed to perform this action");
        }

    	# Find the user
    	$user = User::findOrFail($id);

    	# Get all roles
    	$roles = Role::all();

    	# Return the view
    	return view('admin/users/roles', ['user' => $user, 'roles' => $roles]);
    }

    public function setRoles($id, Request $request)
    {
        # Check permissions
        if(!Auth::user()->has('admin.users.roles')) {
            return redirect('/admin/users')->with('warning', "You are not allowed to perform this action");
        }

		# Find the user
    	$user = User::findOrFail($id);

    	# Get all roles
    	$roles = Role::all();

    	# Change user's roles
    	foreach($roles as $role) {

            # Check for su
            $modify = true;
            if($user->su) {
                if($role->su) {
                    $modify = false;
                }
            }

            if($modify) {
                if($request->input($role->id)){
                    # The admin selected that role

                    # Check if the user was already in that role
                    if($this->checkRole($user->id, $role->id)) {
                        # The user is already in that role, so no change is made
                    } else {
                        # Add the user to the selected role
                        $this->addRel($user->id, $role->id);
                    }
                } else {
                    # The admin did not select that role

                    # Check if the user was in that role
                    if($this->checkRole($user->id, $role->id)) {
                        # The user is in that role, so as the admin did not select it, we need to delete the relationship
                        $this->deleteRel($user->id, $role->id);
                    } else {
                        # The user is not in that role and the admin did not select it
                    }
                }
            }
    	}

    	# Return Redirect
    	return redirect('/admin/users')->with('success', "The user's roles has been updated");
    }

    public function checkRole($user_id, $role_id)
    {
    	# This function returns true if the specified user is found in the specified role and false if not

    	if(Role_User::whereUser_idAndRole_id($user_id, $role_id)->first()) {
    		return true;
    	} else {
    		return false;
    	}

    }

    public function deleteRel($user_id, $role_id)
    {
    	$rel = Role_User::whereUser_idAndRole_id($user_id, $role_id)->first();
    	if($rel) {
    		$rel->delete();
    	}
    }

    public function addRel($user_id, $role_id)
    {
    	$rel = Role_User::whereUser_idAndRole_id($user_id, $role_id)->first();
    	if(!$rel) {
    		$rel = new Role_User;
    		$rel->user_id = $user_id;
    		$rel->role_id = $role_id;
    		$rel->save();
    	}
    }

    public function destroy($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.users.delete')) {
            return redirect('/admin/users')->with('warning', "You are not allowed to perform this action");
        }

        # Find The User
        $user = User::findOrFail($id);

        # Check if it's su
        if($user->su) {
            return redirect('/admin/users')->with('info', "For security reasons you can't delete this");
        }

    	# Check before deleting
    	if($id == Auth::user()->id) {
    		return redirect('/admin/users')->with('error', "You can't delete yourself");
    	} else {

    		# Delete Relationships
    		$rels = Role_User::where('user_id', $user->id)->get();
    		foreach($rels as $rel) {
    			$rel->delete();
    		}

    		# Delete User
    		$user->delete();

    		# Return the admin with a success message
    		return redirect('admin/users')->with('success', "The user has been deleted");
    	}
    }

    public function editSettings()
    {

        # Check permissions
        if(!Auth::user()->has('admin.users.settings')) {
            return redirect('/admin/users')->with('warning', "You are not allowed to perform this action");
        }

    	# Get the user settings
    	$settings = Users_Settings::first();

    	# Get all the roles
    	$roles = Role::all();

    	return view('admin/users/settings', ['settings' => $settings, 'roles' => $roles]);
    }

    public function updateSettings(Request $request)
    {
        # Check permissions
        if(!Auth::user()->has('admin.users.settings')) {
            return redirect('/admin/users')->with('warning', "You are not allowed to perform this action");
        }

    	# Get the user settings
    	$settings = Users_Settings::first();

    	# Update the settings
    	if($request->input('welcome_email')) {
    		$settings->welcome_email = true;
    	} else {
    		$settings->welcome_email = false;
    	}
    	if($request->input('register_enabled')) {
    		$settings->register_enabled = true;
    	} else {
    		$settings->register_enabled = false;
    	}
    	$settings->default_role = $request->input('default_role');
    	$settings->default_active = $request->input('default_active');
    	$settings->save();

    	# Return a redirect
    	return redirect('/admin/users')->with('success', "The users settings has been updated");
    }

    public function SendWelcomeEmail($user, $password = null)
    {
		# Sends the welcome email to the user
		Mail::send('auth.emails.welcome', ['user' => $user, 'password' => $password], function ($m) use ($user) {

			# Set the welcome email subject
			$subject = "Welcome to ".env('APP_NAME')."!";

			# Setup email
            $m->to($user->email, $user->name)->subject($subject);
        });
    }

    public function SendActivationEmail($user, $token)
    {
		# Sends the activation email to the user
		Mail::send('auth.emails.activation', ['user' => $user, 'token' => $token], function ($m) use ($user) {

			# Set the activation email subject
			$subject = "Activate your account on ".env('APP_NAME')."!";

			# Setup email
            $m->to($user->email, $user->name)->subject($subject);
        });
    }
}
