<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FilesController extends Controller
{
    public function __construct()
    {
        # Check permissions
        Laralum::permissionToAccess('admin.files.access');
    }

    public function files()
    {
        return view('/admin/files/index');
    }

    public function showUpload()
    {
        # Check permissions
        Laralum::permissionToAccess('admin.files.upload');

        return view('/admin/files/upload');
    }

    public function upload()
    {
        # Check permissions
        Laralum::permissionToAccess('admin.files.upload');

        return redirect('/admin/files')->with('success', "The file/s have been uploaded");
    }
}
