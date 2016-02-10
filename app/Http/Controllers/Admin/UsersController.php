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

    public function tableSettings($user = null)
    {
        # --------------------
        # Users Table settings
        # --------------------
        #
        # hidden: Columns that will not be displayed in the edit form, and they won't be updated
        # empty: Columns that will not have their current value when editing them (eg: password field is hidden in the model)
        # confirmed: fields that will need to be confirmed twice
        # encrypted: Fields that will be encrypted using: Crypt::encrypt(); when they are saved and decrypted when editing them
        # hashed: Fields that will be hashed when they are saved in the database, will be empty on editing, and if saved as empty they will not be modified
        # masked: Fields that will be displayed as a type='password', so their content when beeing modified won't be visible
        # default_random: Fields that if no data is set, they will be randomly generated (10 characters)
        # su_hidden: Columns that will be added to the hidden array if the user is su
        # validator: validator settings when executing: $this->validate();
        #
        #
        if(!$user) {
            $user = User::first();
        }
        #
        # NOTE: if you use the variable $user and it's not set, it will use the first user in the database

        $data = [
            'create'    =>  [
                'hidden'            =>  ['id', 'su', 'active', 'banned', 'register_ip', 'activation_key', 'remember_token', 'created_at', 'updated_at'],
                'default_random'    =>  ['password'],
                'confirmed'         =>  ['password'],
                'encrypted'         =>  [],
                'hashed'            =>  ['password'],
                'masked'            =>  ['password'],
                'validator'         =>  [
                    'name'              => 'required|max:255',
                    'email'             => 'required|email|unique:users',
                    'password'          => 'confirmed|min:6',
                    'country_code'      => 'required',
                ],
            ],
            'edit'      =>  [
                'hidden'            =>  ['id', 'su', 'email', 'register_ip', 'activation_key', 'remember_token', 'created_at', 'updated_at'],
                'su_hidden'         =>  ['name', 'bio', 'active', 'banned', 'password', 'phone', 'location', 'country_code'],
                'empty'             =>  ['password'],
                'default_random'    =>  [],
                'confirmed'         =>  ['password'],
                'encrypted'         =>  [],
                'hashed'            =>  ['password'],
                'masked'            =>  ['password'],
                'validator'         =>  [
                    'name'              => 'sometimes|required|max:255',
                    'password'          => 'sometimes|confirmed|min:6',
                    'country_code'      => 'sometimes|required',
                ],
            ],
        ];
        return $data;
    }   

    public function fields($columns, $hidden)
    {
        # Gets the fields available to edit / update
        $final_columns = [];
        foreach($columns as $column) {
            $add = true;
            foreach($hidden as $hide) {
                if($column == $hide) {
                    $add = false;
                }
            }
            if($add) {
                array_push($final_columns, $column);
            }
        }
        return $final_columns;
    }

    public function addSuHidden($hidden, $su_hidden)
    {
        # Add the su_hidden fields to the hiden variable
        foreach($su_hidden as $su_hid) {
            array_push($hidden, $su_hid);
        }

        return $hidden;
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

        # Get the users table columns
        $columns = Schema::getColumnListing('users');

        # Get the table settings
        $data = $this->tableSettings();

        # Get the table data
        $hidden = $data['create']['hidden'];
        $confirmed = $data['create']['confirmed'];
        $encrypted = $data['create']['encrypted'];
        $hashed = $data['create']['hashed'];
        $masked = $data['create']['masked'];

        # Get the available fields
        $fields = $this->fields($columns, $hidden);

        # Return the view
        return view('admin/users/create', [
            'roles'      =>  $roles,
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
            'encrypted' =>  $encrypted,
            'hashed'    =>  $hashed,
            'masked'    =>  $masked,
        ]);
    }

    public function store(Request $request)
    {

        # Check permissions
        if(!Auth::user()->has('admin.users.create')) {
            return redirect('/admin/users')->with('warning', "You are not allowed to perform this action");
        }

        # Find the user
        $user = new User;

        # Get the table settings
        $data = $this->tableSettings();

        # Get the users table columns
        $columns = Schema::getColumnListing('users');

        # Get the table data
        $hidden = $data['create']['hidden'];
        $default_random = $data['create']['default_random'];
        $encrypted = $data['create']['encrypted'];
        $hashed = $data['create']['hashed'];
        $validator = $data['create']['validator'];

        # Validate The Request
        $this->validate($request, $validator);

        # Get the available fields to update
        $fields = $this->fields($columns, $hidden);

        # Update the user
        foreach($fields as $field) {

            $save = true;

            # Check the field type
            $type = Schema::getColumnType('users', $field);

            # Get the value
            $value = $request->input($field);

            if($type == 'string' or $type == 'integer') {

                # Check if it's a default_random field
                foreach($default_random as $random) {
                    if($random == $field) {
                        if(!$value) {
                            $value = str_random(10);
                        }
                    }
                }

                # Check if it's a hashed field
                foreach($hashed as $hash) {
                    if($hash == $field) {
                        if($value) {
                            $value = Hash::make($value);
                        } else {
                            $save = false;
                        }
                    }
                }

                # Check if it's an encrypted field
                foreach($encrypted as $encrypt) {
                    if($encrypt == $field) {
                        $value = Crypt::encrypt($value);
                    }
                }

                # Save it
                if($save) {
                    $user->$field = $value;
                }

            } elseif($type == 'boolean') {
                
                # Save it
                if($value) {
                    $user->$field = true;
                } else {
                    $user->$field = false;
                }

            } else {
                # Save it
                $user->$field = $value;
            }
        }

        # Setup a random activation key
        $user->activation_key = str_random(25);

        # Get the register IP
        $user->register_ip = $request->ip();

        # Save the user
        $user->save();

        # Send welcome email if set
        if($request->input('mail')) {
            if($request->input('send_password')) {
                # Send Welcome email with password
                $this->SendWelcomeEmail($user, $password);
            } else {
                # Send Welcome email without password
                $this->SendWelcomeEmail($user, null);
            }
        }

        # Send activation email if set
        if($request->input('send_activation')) {
            $this->sendActivationEmail($user, $activation_key);
        }

        $this->setRoles($user->id, $request);

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
        $user = User::findOrFail($id);

        # Get the users table columns
        $columns = Schema::getColumnListing('users');

        # Get the table settings
        $data = $this->tableSettings($user);

        # Get the table data
        $hidden = $data['edit']['hidden'];
        $empty = $data['edit']['empty'];
        $confirmed = $data['edit']['confirmed'];
        $encrypted = $data['edit']['encrypted'];
        $hashed = $data['edit']['hashed'];
        $masked = $data['edit']['masked'];
        $su_hidden = $data['edit']['su_hidden'];

        # Add su_hidden to hidden if the user is su
        if($user->su) {
            $hidden = $this->addSuHidden($hidden, $su_hidden);
        }

        # Get the available fields
        $fields = $this->fields($columns, $hidden);


        # Return the view
        return view('admin/users/edit', [
            'user'      =>  $user,
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
            'empty'     =>  $empty,
            'encrypted' =>  $encrypted,
            'hashed'    =>  $hashed,
            'masked'    =>  $masked,
        ]);
    }

    public function update($id, Request $request)
    {

        # Check permissions
        if(!Auth::user()->has('admin.users.edit')) {
            return redirect('/admin/users')->with('warning', "You are not allowed to perform this action");
        }

        # Find the user
        $user = User::findOrFail($id);

        # Get the table settings
        $data = $this->tableSettings($user);

        # Get the users table columns
        $columns = Schema::getColumnListing('users');

        # Get the table data
        $hidden = $data['edit']['hidden'];
        $encrypted = $data['edit']['encrypted'];
        $hashed = $data['edit']['hashed'];
        $validator = $data['edit']['validator'];
        $default_random = $data['edit']['default_random'];
        $su_hidden = $data['edit']['su_hidden'];

        # Add su_hidden to hidden if the user is su
        if($user->su) {
            $hidden = $this->addSuHidden($hidden, $su_hidden);
        }

        # Validate The Request
        $this->validate($request, $validator);

        # Get the available fields to update
        $fields = $this->fields($columns, $hidden);

        # Update the user
        foreach($fields as $field) {

            $save = true;

            # Check the field type
            $type = Schema::getColumnType('users', $field);

            # Get the value
            $value = $request->input($field);

            if($type == 'string' or $type == 'integer') {

                # Check if it's a default_random field
                foreach($default_random as $random) {
                    if($random == $field) {
                        if(!$value) {
                            $value = str_random(10);
                        }
                    }
                }

                # Check if it's a hashed field
                foreach($hashed as $hash) {
                    if($hash == $field) {
                        if($value) {
                            $value = Hash::make($value);
                        } else {
                            $save = false;
                        }
                    }
                }

                # Check if it's an encrypted field
                foreach($encrypted as $encrypt) {
                    if($encrypt == $field) {
                        $value = Crypt::encrypt($value);
                    }
                }

                # Save it
                if($save) {
                    $user->$field = $value;
                }

            } elseif($type == 'boolean') {
                
                # Save it
                if($value) {
                    $user->$field = true;
                } else {
                    $user->$field = false;
                }

            } else {
                # Save it
                $user->$field = $value;
            }
        }

        # Save the user
        $user->save();

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
            return redirect('/admin/users')->with('info', "For security reaseons you can't delete this");
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
