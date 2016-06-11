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
use Hash;
use Crypt;
use Mail;

class RolesController extends Controller
{

    public function __construct()
    {
        # Check permissions
        if(!Auth::user()->has('admin.roles.access')) {
            return redirect('/admin')->with('warning', "You are not allowed to perform this action")->send();
        }
    }

    public function index()
    {
    	# Get all the roles
    	$roles = Role::all();

    	# Get Default Role
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

        # Find the role
        $row = Role::findOrFail($id);

        # Get all the data
        $data_index = 'roles';
        require('Data/Edit/Get.php');

        # Return the view
        return view('admin/roles/edit', [
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
        if(!Auth::user()->has('admin.roles.edit')) {
            return redirect('/admin/roles')->with('warning', "You are not allowed to perform this action");
        }

        # Find the row
        $row = Role::findOrFail($id);

        # Save the data
        $data_index = 'roles';
        require('Data/Edit/Save.php');

        # Return the admin to the users page with a success message
        return redirect('/admin/roles')->with('success', "The role has been edited");
    }

    public function create()
    {
        # Check permissions
        if(!Auth::user()->has('admin.roles.create')) {
            return redirect('/admin/roles')->with('warning', "You are not allowed to perform this action");
        }

        # Get all the data
        $data_index = 'roles';
    	require('Data/Create/Get.php');

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
            'table'         =>  $table,
            'code'          =>  $code,
            'wysiwyg'       =>  $wysiwyg,
        ]);
    }

    public function store(Request $request)
    {
        # Check permissions
        if(!Auth::user()->has('admin.roles.create')) {
            return redirect('/admin/roles')->with('warning', "You are not allowed to perform this action");
        }

    	# create new role
        $row = new Role;

        # Save the data
        $data_index = 'roles';
        require('Data/Create/Save.php');

        # Set the permissions
        $this->setPermissions($row->id, $request);

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

    	# Select Role
    	$role = Role::findOrFail($id);

        # Check if it's su
        if($role->su) {
            return redirect('/admin/roles')->with('info', "For security reasons you can't delete this");
        }

        # Check if it's the default role
        if($role->id == Users_Settings::first()->default_role) {
            return redirect('/admin/roles')->with('info', "For security reasons you can't delete the default user role");
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
