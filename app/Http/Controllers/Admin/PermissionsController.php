<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Permission;
use App\Permission_Role;
use App\Permission_Types;
use Auth;
use Laralum;

class PermissionsController extends Controller
{
    public function __construct()
    {
        # Check permissions
        Laralum::permissionToAccess('admin.permissions.access');
    }

    public function index()
    {
    	# Get all the permissions
    	$permissions = Permission::all();

    	# Get all types
    	$types = Permission_Types::all();

    	# Return the view
    	return view('admin/permissions/index', ['permissions' => $permissions, 'types' => $types]);
    }

    public function show($id)
    {
    	# Get the permission
    	$perm = Permission::findOrFail($id);

    	# Return the view
    	return view('admin/permissions/show', ['perm' => $perm]);
    }

    public function create()
    {
        # Check permissions
        Laralum::permissionToAccess('admin.permissions.create');

    	# Get all types
    	$types = Permission_Types::all();

    	# Return the creation view
    	return view('admin/permissions/create', ['types' => $types]);
    }

    public function store(Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.permissions.create');

		# Validate Request
		$this->validate($request, [
			'slug'		=>	'required|string|unique:permissions',
			'name'		=>	'required|unique:permissions',
			'info'		=>	'required|unique:permissions',
			'type_id'	=>	'required',
		]);

		# Create the permission
		$perm = new Permission;
		$perm->slug = $request->input('slug');
		$perm->name = $request->input('name');
		$perm->info = $request->input('info');
		$perm->type_id = $request->input('type_id');
		$perm->save();

		# return a redirect
		return redirect('admin/permissions')->with('success', "The permission has been created!");
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.permissions.edit');

    	# Get the permission
    	$perm = Permission::findOrFail($id);

        # Check if it's su
        if($perm->su) {
            return redirect('/admin/permissions')->with('info', "For security reasons you can't modify this");
        }

    	# Get all types
    	$types = Permission_Types::all();

    	# Return the view
    	return view('admin/permissions/edit', ['perm' => $perm, 'types' => $types]);
    }

    public function update($id, Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.permissions.edit');

    	# Get the permission
    	$perm = Permission::findOrFail($id);

        # Check if it's su
        if($perm->su) {
            return redirect('/admin/permissions')->with('info', "For security reasons you can't modify this");
        }

    	# Validate Request
		$this->validate($request, [
			'slug'		=>	'required|string|unique:permissions,slug,'.$perm->id,
			'name'		=>	'required|unique:permissions,name,'.$perm->id,
			'info'		=>	'required|unique:permissions,info,'.$perm->id,
			'type_id'	=>	'required',
		]);

    	# Update the permission
    	$perm->slug = $request->input('slug');
		$perm->name = $request->input('name');
		$perm->info = $request->input('info');
		$perm->type_id = $request->input('type_id');
		$perm->save();

		# return a redirect
		return redirect('admin/permissions')->with('success', "The permission has been updated!");
    }

    public function destroy($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.permissions.delete');

    	# Get the permission
    	$perm = Permission::findOrFail($id);

        # Check if it's su
        if($perm->su) {
            return redirect('/admin/permissions')->with('info', "For security reasons you can't delete this");
        }

    	# Delete relationships
    	$rels = Permission_Role::where('permission_id', $perm->id)->get();
    	foreach($rels as $rel) {
    		$rel->delete();
    	}

    	# Delete Permission
    	$perm->delete();

    	# Return a redirect
    	return redirect('admin/permissions')->with('success', "The permission has been deleted");
    }

    public function createType()
    {
        # Check permissions
        Laralum::permissionToAccess('admin.permissions.type.create');

    	# Return the creation form
    	return view('admin/permissions/types/create');
    }

    public function storeType(Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.permissions.type.create');

    	# Validate the request
    	$this->validate($request, [
    		'type'	=> 'required|string|unique:permission_types',
		]);

		# Create the type
		$type = new Permission_Types;
		$type->type = $request->input('type');
		$type->save();

		# Redirect the admin
		return redirect('/admin/permissions')->with('success', 'The permission type has been created');
    }

    public function editType($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.permissions.type.edit');

    	# Get the type
    	$type = Permission_Types::findOrFail($id);

        # Check if it's su
        if($type->su) {
            return redirect('/admin/permissions')->with('info', "For security reasons you can't modify this");
        }

    	# Retrun the form
    	return view('admin/permissions/types/edit', ['type' => $type]);
    }

    public function updateType($id, Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.permissions.type.edit');

    	# Get the type
    	$type = Permission_Types::findOrFail($id);

        # Check if it's su
        if($type->su) {
            return redirect('/admin/permissions')->with('info', "For security reasons you can't modify this");
        }

    	# Validate the request
    	$this->validate($request, [
    		'type'	=> 'required|string|unique:permission_types',
		]);

    	# Update the permission type
    	$type->type = $request->input('type');
    	$type->save();

    	# Redirect the admin
    	return redirect('/admin/permissions')->with('success', "The permission type has been updated");
    }

    public function destroyType($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.permissions.type.delete');

    	# Get the type
    	$type = Permission_Types::findOrFail($id);

        # Check if it's su
        if($type->su) {
            return redirect('/admin/permissions')->with('info', "For security reasons you can't delete this");
        }

    	# Set to 'Other' all the permissions that had that type
    	$rels = $type->permissions;
    	foreach($rels as $rel){
    		$rel->type_id = 0;
    		$rel->save();
    	}

    	# Delete the type
    	$type->delete();

    	# Redirect the admin
    	return redirect('/admin/permissions')->with('success', "The permission type has been deleted");
    }
}
