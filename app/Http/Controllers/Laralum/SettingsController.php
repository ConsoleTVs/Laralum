<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Settings;
use Laralum;

class SettingsController extends Controller
{
    
    public function edit()
    {
        Laralum::permissionToAccess('laralum.settings.access');

        # Check permissions
        $row = Settings::first();

        $data_index = 'settings';
        require('Data/Edit/Get.php');

        return view('laralum/settings/general', [
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

    public function update(Request $request)
    {
        Laralum::permissionToAccess('laralum.settings.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.settings.edit');

        $row = Settings::first();

        $data_index = 'settings';
        require('Data/Edit/Save.php');

        return redirect('admin/settings')->with('success', "The settings have been updated");
    }
}
