<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;
use App\Role_User;
use App\User;
use App\Users_Settings;
use App\Permission;
use App\Permission_Role;
use Schema;
use Auth;
use Hash;
use Crypt;
use Mail;
use Laralum;

class RolesController extends Controller
{

    public function index()
    {
        Laralum::permissionToAccess('laralum.roles.access');

    	# Get all the roles
    	$roles = Role::all();

    	# Get Default Role
    	$default_role = Role::findOrFail(Users_Settings::first()->default_role);

    	# Return the view
    	return view('laralum/roles/index', ['roles' => $roles, 'default_role' => $default_role]);
    }

    public function show($id)
    {
        Laralum::permissionToAccess('laralum.roles.access');

    	# Get the role
    	$role = Role::findOrFail($id);

    	# Return the view
    	return view('admin/roles/show', ['role' => $role]);
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('laralum.roles.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.roles.edit');

        # Find the role
        $row = Role::findOrFail($id);

        if(!$row->allow_editing and !Laralum::loggedInuser()->su) {
            abort(403, trans('laralum.error_editing_disabled'));
        }

        # Get all the data
        $data_index = 'roles';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/roles/edit', [
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
            'relations' =>  $relations,
        ]);
    }

    public function update($id, Request $request)
    {
        Laralum::permissionToAccess('laralum.roles.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.roles.edit');

        # Find the row
        $row = Role::findOrFail($id);

        if(!$row->allow_editing and !Laralum::loggedInuser()->su) {
            abort(403, trans('laralum.error_editing_disabled'));
        }

        # Save the data
        $data_index = 'roles';
        require('Data/Edit/Save.php');

        # Return the admin to the users page with a success message
        return redirect()->route('Laralum::roles')->with('success', trans('laralum.msg_role_edited'));
    }

    public function create()
    {
        Laralum::permissionToAccess('laralum.roles.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.roles.create');

        # Get all the data
        $data_index = 'roles';
    	require('Data/Create/Get.php');

        # All permissions
        $permissions = Laralum::permissions();

        # Return the view
        return view('laralum/roles/create', [
            'permissions'   =>  $permissions,
            'fields'        =>  $fields,
            'confirmed'     =>  $confirmed,
            'encrypted'     =>  $encrypted,
            'hashed'        =>  $hashed,
            'masked'        =>  $masked,
            'permissions'   =>  $permissions,
            'table'         =>  $table,
            'code'          =>  $code,
            'wysiwyg'       =>  $wysiwyg,
            'relations'     =>  $relations,
        ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('laralum.roles.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.roles.create');

    	# create new role
        $row = new Role;

        # Save the data
        $data_index = 'roles';
        require('Data/Create/Save.php');

        # Set the permissions
        $this->setPermissions($row->id, $request);

        # Return the admin to the roles page with a success message
        return redirect()->route('Laralum::roles')->with('success', trans('laralum.msg_role_created'));
    }

    public function editPermissions($id)
    {
        Laralum::permissionToAccess('laralum.roles.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.roles.permissions');

    	# Find the role
    	$role = Laralum::role('id', $id);

        if(!$role->allow_editing and !Laralum::loggedInuser()->su) {
            abort(403, trans('laralum.error_editing_disabled'));
        }

    	# All permissions
    	$permissions = Laralum::permissions();


    	# Return the view
    	return view('laralum/roles/permissions', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function setPermissions($id, Request $request)
    {
        Laralum::permissionToAccess('laralum.roles.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.roles.permissions');

    	# Find the role
    	$role = Laralum::role('id', $id);

        if(!$role->allow_editing and !Laralum::loggedInuser()->su) {
            abort(403, trans('laralum.error_editing_disabled'));
        }

    	# All permissions
    	$permissions = Laralum::permissions();

    	# Edit the permission
    	foreach($permissions as $perm){

            # Check for su
            $modify = true;
            if($role->su) {
                if($perm->su) {
                    $modify = false;
                }
            }

            if(!$perm->assignable and !Laralum::loggedInUser()->su) {
                $modify = false;
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
    	return redirect()->route('Laralum::roles')->with('success', trans('laralum.msg_role_perms_updated'));
    }

    public function checkPerm($perm_id, $role_id)
    {
        Laralum::permissionToAccess('laralum.roles.access');

    	# This function returns true if the specified permission is found in the specified role and false if not

    	if(Permission_Role::wherePermission_idAndRole_id($perm_id, $role_id)->first()) {
    		return true;
    	} else {
    		return false;
    	}
    }

    public function deletePerm($perm_id, $role_id)
    {
        Laralum::permissionToAccess('laralum.roles.access');

    	$rel = Permission_Role::wherePermission_idAndRole_id($perm_id, $role_id)->first();
    	if($rel) {
    		$rel->delete();
    	}
    }

    public function addPerm($perm_id, $role_id)
    {
        Laralum::permissionToAccess('laralum.roles.access');

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
        Laralum::permissionToAccess('laralum.roles.access');
        
        # Check permissions
        Laralum::permissionToAccess('laralum.roles.delete');

    	# Select Role
    	$role = Laralum::role('id', $id);

        if(!$role->allow_editing and !Laralum::loggedInuser()->su) {
            abort(403, trans('laralum.error_editing_disabled'));
        }

        # Check if it's su
        if($role->su) {
            return abort(403, trans('laralum.error_security_reasons'));
        }

        # Check if it's the default role
        if($role->id == Laralum::defaultRole()->id) {
            abort(403, trans('laralum.error_security_reasons_default_role'));
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
    	return redirect()->route('Laralum::roles')->with('success', trans('laralum.msg_role_deleted'));
    }
}
