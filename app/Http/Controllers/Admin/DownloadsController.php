<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Crypt;
use Auth;
use Laralum;

class DownloadsController extends Controller
{
    public function downloader($slug)
    {
        $file = Laralum::document('slug',$slug);
        if($file) {
            if(!$file->password) {
                if($file->download_timer == 0) {
                    if($file->direct_download) {
                        return $this->fileDownload($file->name);
                    }
                }
            }
            return view('/downloader/index', ['file' => $file]);
        } else {
            abort(404);
        }
    }

    public function download($slug, Request $request)
    {
        $file = Laralum::document('slug', $slug);
        if($file) {
            # Check if disabled
            if($file->disabled) {
                abort(404);
            }

            # Check Auth
            if($file->authorization_required) {
                if(!Auth::check()){
                    return redirect('/login');
                }
            }

            # Check password
            if($file->password) {
                if(Crypt::decrypt($file->password) == $request->input('password')) {
                    return $this->fileDownload($file->name);
                } else {
                    return redirect(Laralum::downloadLink($file->name))->with('error', "The password is not correct");
                }
            } else {
                return $this->fileDownload($file->name);
            }
        } else {
            abort(404);
        }
    }

    public function fileDownload($file_name)
    {
        return Laralum::downloadFile($file_name);
    }
}
