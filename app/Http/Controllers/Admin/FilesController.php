<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laralum;
use Validator;
use Response;
use Storage;
use File;
use Auth;
use Crypt;

class FilesController extends Controller
{
    public function __construct()
    {
        # Check permissions
        Laralum::permissionToAccess('admin.files.access');
    }

    public function fileDownload($file_name)
    {
        Laralum::permissionToAccess('admin.files.download');

        return Laralum::downloadFile($file_name);
    }

    public function files()
    {
        $files = Laralum::files();

        return view('/admin/files/index', ['files' => $files]);
    }

    public function showUpload()
    {
        # Check permissions
        Laralum::permissionToAccess('admin.files.upload');

        return view('/admin/files/upload');
    }

    public function upload(Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.files.upload');

        $files = $request->file('files');

        # Check the file size for each file before uploading any of them
        foreach($files as $file) {
            $file_name = $file->getClientOriginalName();
            $max_upload = $file->getMaxFilesize() / 1000000;
            $max_upload = (string)$max_upload;
            if($file->getClientSize() == 0 or $file->getClientSize() > $file->getMaxFilesize()) {
                return redirect('/admin/files/upload')->with('error', "The file" . $file_name . " is too large, the max upload size is " . substr($max_upload, 0, 4) . " MB" );
            }
        }

        foreach($files as $file) {
            $file_name = $file->getClientOriginalName();

            Storage::put($file_name, File::get($file));
        }

        return redirect('admin/files')->with('success', "The files have been uploaded");

    }

    public function delete($file)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.files.delete');

        # Check if it's a file
        Laralum::mustBeFile($file);

        # Check if it's a document
        if(Laralum::isDocument($file)){
            # Delete the document
            Laralum::document('name', $file)->delete();
        }

        # Delete the file
        Laralum::deleteFile($file);

        return redirect('/admin/files')->with('success', "The file has been deleted");
    }
}
