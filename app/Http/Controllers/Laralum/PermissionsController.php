<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Permission_Role;
use Laralum;

class PermissionsController extends Controller
{
    public function index()
    {
        Laralum::permissionToAccess('laralum.permissions.access');

    	# Get all the permissions
    	$permissions = Laralum::permissions();

    	# Return the view
    	return view('laralum/permissions/index', ['permissions' => $permissions]);
    }

    public function create()
    {
        Laralum::permissionToAccess('laralum.permissions.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.permissions.create');


        $data_index = 'permissions';
        require('Data/Create/Get.php');

    	# Return the creation view
    	return view('laralum/permissions/create', [
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
            'encrypted' =>  $encrypted,
            'hashed'    =>  $hashed,
            'masked'    =>  $masked,
            'table'     =>  $table,
            'code'      =>  $code,
            'wysiwyg'   =>  $wysiwyg,
            'relations' =>  $relations,
        ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('laralum.permissions.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.permissions.create');

		# Create the permission
		$row = Laralum::newPermission();
        $data_index = 'permissions';
		require('Data/Create/Save.php');

		# return a redirect
		return redirect()->route('Laralum::permissions')->with('success', trans('laralum.msg_permission_created'));
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('laralum.permissions.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.permissions.edit');

    	# Get the permission
    	$row = Laralum::permission('id', $id);

        $data_index = 'permissions';
		require('Data/Edit/Get.php');


    	# Return the view
    	return view('laralum/permissions/edit', [
            'row'       =>  $row,
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
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
        Laralum::permissionToAccess('laralum.permissions.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.permissions.edit');

        # Get the permission
    	$row = Laralum::permission('id', $id);

        $data_index = 'permissions';
		require('Data/Edit/Save.php');

		# return a redirect
		return redirect()->route('Laralum::permissions')->with('success', trans('laralum.msg_permission_updated'));
    }

    public function destroy($id)
    {
        Laralum::permissionToAccess('laralum.permissions.access');
        
        # Check permissions
        Laralum::permissionToAccess('laralum.permissions.delete');

    	# Get the permission
    	$perm = Laralum::permission('id', $id);

        # Check if it's su
        if($perm->su) {
            abort(403, trans('laralum.error_security_reasons'));
        }

    	# Delete relationships
    	$rels = Permission_Role::where('permission_id', $perm->id)->get();
    	foreach($rels as $rel) {
    		$rel->delete();
    	}

    	# Delete Permission
    	$perm->delete();

    	# Return a redirect
    	return redirect()->route('Laralum::permissions')->with('success', trans('laralum.msg_permission_deleted'));
    }

}
