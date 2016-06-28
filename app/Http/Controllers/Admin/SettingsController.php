<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Settings;
use Laralum;

class SettingsController extends Controller
{
    public function __construct()
    {
        # Check permissions
        Laralum::permissionToAccess('admin.settings.access');
    }

    public function edit()
    {
        # Check permissions
        $row = Settings::first();

        $data_index = 'settings';
        require('Data/Edit/Get.php');

        return view('admin/settings/edit', [
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

    public function update(Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.settings.edit', '/admin/settings');

        $row = Settings::first();

        $data_index = 'settings';
        require('Data/Edit/Save.php');

        return redirect('admin/settings')->with('success', "The settings have been updated");
    }
}
