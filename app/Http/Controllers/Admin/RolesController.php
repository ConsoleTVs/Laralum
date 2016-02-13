<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;
use App\Role_User;
use App\User;
use App\Users_Settings;
use App\Permission;
use App\Permission_Role;
use App\Permission_Types;
use Schema;
use Auth;

class RolesController extends Controller
{

    public function __construct()
    {
        # Check permissions
        if(!Auth::user()->has('admin.roles.access')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }
    }

    public function tableSettings($role = null)
    {
        # --------------------
        # Roles Table settings
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
        if(!$role) {
            $role = Role::first();
        }
        #
        # NOTE: if you use the variable $role and it's not set, it will use the first role in the database

        $data = [
            'create'    =>  [
                'hidden'            =>  ['id', 'su', 'created_at', 'updated_at'],
                'default_random'    =>  [],
                'confirmed'         =>  [],
                'encrypted'         =>  [],
                'hashed'            =>  [],
                'masked'            =>  [],
                'validator'         =>  [
                    'name' => 'required|unique:roles',
                ],
            ],
            'edit'      =>  [
                'hidden'            =>  ['id', 'su', 'created_at', 'updated_at'],
                'su_hidden'         =>  ['color', 'name'],
                'empty'             =>  [],
                'default_random'    =>  [],
                'confirmed'         =>  [],
                'encrypted'         =>  [],
                'hashed'            =>  [],
                'masked'            =>  [],
                'validator'         =>  [
                    'name' => 'sometimes|required|unique:roles,name,'.$role->id,
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
    	# Get all the roles
    	$roles = Role::all();

    	# Get Latest Role
    	$default_role = Role::findOrFail(Users_Settings::first()->default_role);

    	# Return the view
    	return view('admin/roles/index', ['roles' => $roles, 'default_role' => $default_role]);
    }

    public function show($id)
    {
    	# Get the role
    	$role = Role::findOrFail($id);

    	# Return the view
    	return view('admin/roles/show', ['role' => $role]);
    }

    public function edit($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.roles.edit')) {
            return redirect('/admin/roles')->with('warning', "You are not allowed to perform this action");
        }

        # Get the users table columns
        $columns = Schema::getColumnListing('roles');

        # Find the role
        $role = Role::findOrFail($id);

        # Get the table settings
        $data = $this->tableSettings($role);

        # Get the table data
        $hidden = $data['edit']['hidden'];
        $empty = $data['edit']['empty'];
        $default_random = $data['edit']['default_random'];
        $confirmed = $data['edit']['confirmed'];
        $encrypted = $data['edit']['encrypted'];
        $hashed = $data['edit']['hashed'];
        $masked = $data['edit']['masked'];
        $su_hidden = $data['edit']['su_hidden'];

        # Add su_hidden to hidden if the user is su
        if($role->su) {
            $hidden = $this->addSuHidden($hidden, $su_hidden);
        }

        # Get the available fields
        $fields = $this->fields($columns, $hidden);

        # Return the view
        return view('admin/roles/edit', [
            'role'      => $role,
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
        if(!Auth::user()->has('admin.roles.edit')) {
            return redirect('/admin/roles')->with('warning', "You are not allowed to perform this action");
        }

        # Find the user
        $role = Role::findOrFail($id);

        # Get the table settings
        $data = $this->tableSettings($role);

        # Get the users table columns
        $columns = Schema::getColumnListing('roles');

        # Get the table data
        $hidden = $data['edit']['hidden'];
        $encrypted = $data['edit']['encrypted'];
        $hashed = $data['edit']['hashed'];
        $validator = $data['edit']['validator'];
        $default_random = $data['edit']['default_random'];
        $su_hidden = $data['edit']['su_hidden'];

        # Add su_hidden to hidden if the user is su
        if($role->su) {
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
            $type = Schema::getColumnType('roles', $field);

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
                    $role->$field = $value;
                }

            } elseif($type == 'boolean') {
                
                # Save it
                if($value) {
                    $role->$field = true;
                } else {
                    $role->$field = false;
                }

            } else {
                # Save it
                $role->$field = $value;
            }
        }

        # Save the user
        $role->save();

        # Return the admin to the users page with a success message
        return redirect('/admin/roles')->with('success', "The role has been edited");
    }

    public function create()
    {
        # Check permissions
        if(!Auth::user()->has('admin.roles.create')) {
            return redirect('/admin/roles')->with('warning', "You are not allowed to perform this action");
        }

        # Get all the permissions
        $permissions = Permission::all();

    	# Get the users table columns
        $columns = Schema::getColumnListing('roles');

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

        # All permissions
        $permissions = Permission::all();

        # All permissions types
        $types = Permission_Types::all();

        # Get all untyped permissions
        $untyped = Permission::where('type_id', 0)->get();

        # Return the view
        return view('admin/roles/create', [
            'permissions'   =>  $permissions,
            'fields'        =>  $fields,
            'confirmed'     =>  $confirmed,
            'encrypted'     =>  $encrypted,
            'hashed'        =>  $hashed,
            'masked'        =>  $masked,
            'permissions'   =>  $permissions,
            'types'         =>  $types,
            'untyped'       =>  $untyped,
        ]);
    }

    public function store(Request $request)
    {
        # Check permissions
        if(!Auth::user()->has('admin.roles.create')) {
            return redirect('/admin/roles')->with('warning', "You are not allowed to perform this action");
        }

    	# Find the user
        $role = new Role;

        # Get the table settings
        $data = $this->tableSettings();

        # Get the users table columns
        $columns = Schema::getColumnListing('roles');

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
            $type = Schema::getColumnType('roles', $field);

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
                    $role->$field = $value;
                }

            } elseif($type == 'boolean') {
                
                # Save it
                if($value) {
                    $role->$field = true;
                } else {
                    $role->$field = false;
                }

            } else {
                # Save it
                $role->$field = $value;
            }
        }

        # Save the user
        $role->save();

        # Set the permissions
        $this->setPermissions($role->id, $request);

        # Return the admin to the roles page with a success message
        return redirect('/admin/roles')->with('success', "The role has been created");
    }

    public function editPermissions($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.roles.permissions')) {
            return redirect('/admin/roles')->with('warning', "You are not allowed to perform this action");
        }

    	# Find the role
    	$role = Role::findOrFail($id);

    	# All permissions
    	$permissions = Permission::all();

    	# All permissions types
    	$types = Permission_Types::all();

        # Get all untyped permissions
        $untyped = Permission::where('type_id', 0)->get();

    	# Return the view
    	return view('admin/roles/permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'types' => $types,
            'untyped' => $untyped,
        ]);
    }

    public function setPermissions($id, Request $request)
    {
        # Check permissions
        if(!Auth::user()->has('admin.roles.permissions')) {
            return redirect('/admin/roles')->with('warning', "You are not allowed to perform this action");
        }

    	# Find the role
    	$role = Role::findOrFail($id);

    	# All permissions
    	$permissions = Permission::all();

    	# Edit the permission
    	foreach($permissions as $perm){

            # Check for su
            $modify = true;
            if($role->su) {
                if($perm->su) {
                    $modify = false;
                }
            }
            
            if($modify) {
                if($request->input($perm->id)) {
                    # The admin selected that permission

                    # Check if the relation existed
                    if($this->checkPerm($perm->id, $role->id)) {
                        # The role had already that permission
                    } else {
                        # The role did not have that permission, so we need to add it
                        $this->addPerm($perm->id, $role->id);
                    }
                } else {
                    # The admin did not select that permission

                    # Check if the relation existed
                    if($this->checkPerm($perm->id, $role->id)) {
                        # The role had this permission, so we need to delete it
                        $this->deletePerm($perm->id, $role->id);
                    } else {
                        # The role did not exist and nothing need to be done
                    }
                }
            }
    	}

    	# Return a redirect
    	return redirect('admin/roles')->with('success', "The role permission have been updated");
    }

    public function checkPerm($perm_id, $role_id)
    {
    	# This function returns true if the specified permission is found in the specified role and false if not

    	if(Permission_Role::wherePermission_idAndRole_id($perm_id, $role_id)->first()) {
    		return true;
    	} else {
    		return false;
    	}
    }

    public function deletePerm($perm_id, $role_id)
    {	
    	$rel = Permission_Role::wherePermission_idAndRole_id($perm_id, $role_id)->first();
    	if($rel) {
    		$rel->delete();
    	}
    }

    public function addPerm($perm_id, $role_id)
    {
    	$rel = Permission_Role::wherePermission_idAndRole_id($perm_id, $role_id)->first();
    	if(!$rel) {
    		$rel = new Permission_Role;
    		$rel->permission_id = $perm_id;
    		$rel->role_id = $role_id;
    		$rel->save();
    	}
    }

    public function destroy($id)
    {
        # Check permissions
        if(!Auth::user()->has('admin.roles.delete')) {
            return redirect('/admin/roles')->with('warning', "You are not allowed to perform this action");
        }

    	# Delete Role
    	$role = Role::findOrFail($id);

        # Check if it's su
        if($role->su) {
            return redirect('/admin/roles')->with('info', "For security reaseons you can't delete this");
        }

    	# Delete all relationships

    	# Permission Relation
    	$rels = Permission_Role::where('role_id', $id)->get();
    	foreach($rels as $rel) {
    		$rel->delete();
    	}
    	# Users Relation
    	$rels = Role_User::where('role_id', $id)->get();
    	foreach($rels as $rel) {
    		$rel->delete();
    	}

    	# Delete Role
    	$role->delete();

    	# Redirect the admin
    	return redirect('admin/roles')->with('success', "The role has been deleted");
    }
}
