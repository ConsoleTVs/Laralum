<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Settings;

class SettingsController extends Controller
{
    public function edit()
    {
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
        $row = Settings::first();

        $data_index = 'settings';
        require('Data/Edit/Save.php');

        return redirect('admin/settings')->with('success', "The settings have been updated");
    }
}
