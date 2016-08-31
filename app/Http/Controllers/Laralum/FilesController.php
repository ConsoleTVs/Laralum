<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laralum;
use Storage;
use File;

class FilesController extends Controller
{

    public function fileDownload($file_name)
    {
        Laralum::permissionToAccess('laralum.files.access');

        Laralum::permissionToAccess('laralum.files.download');

        return Laralum::downloadFile($file_name);
    }

    public function files()
    {
        Laralum::permissionToAccess('laralum.files.access');

        $files = Laralum::files();

        return view('laralum/files/index', ['files' => $files]);
    }

    public function showUpload()
    {
        Laralum::permissionToAccess('laralum.files.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.files.upload');

        return view('laralum/files/upload');
    }

    public function upload(Request $request)
    {
        Laralum::permissionToAccess('laralum.files.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.files.upload');

        $files = $request->file('files');

        # Check the file size for each file before uploading any of them
        foreach($files as $file) {
            $file_name = $file->getClientOriginalName();
            $max_upload = $file->getMaxFilesize() / 1000000;
            $max_upload = (string)$max_upload;
            if($file->getClientSize() == 0 or $file->getClientSize() > $file->getMaxFilesize()) {
                return redirect()->route('Laralum::files_upload')->with('error', trans('laralum.msg_max_file_size', ['file' => $file_name, 'number' => substr($max_upload, 0, 4)]));
            }
        }

        foreach($files as $file) {
            $file_name = $file->getClientOriginalName();

            Storage::put($file_name, File::get($file));
        }

        return redirect()->route('Laralum::files')->with('success', trans('laralum.msg_files_uploaded'));

    }

    public function delete($file)
    {
        Laralum::permissionToAccess('laralum.files.access');
        
        # Check permissions
        Laralum::permissionToAccess('laralum.files.delete');

        # Check if it's a file
        Laralum::mustBeFile($file);

        # Check if it's a document
        if(Laralum::isDocument($file)){
            # Delete the document
            Laralum::document('name', $file)->delete();
        }

        # Delete the file
        Laralum::deleteFile($file);

        return redirect()->route('Laralum::files')->with('success', trans('laralum.msg_file_deleted'));
    }
}
