<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Auth;
use Storage;
use Schema;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Permission;
use App\Blog;
use App\Post;
use App\Settings;
use App\Document;

class Laralum extends Controller
{

    public static function users()
    {
        return User::all();
    }

    public static function roles()
    {
        return Role::all();
    }

    public static function permissions()
    {
        return Permission::all();
    }

    public static function blogs()
    {
        return Blog::all();
    }

    public static function posts()
    {
        return Post::all();
    }

    public static function user($id)
    {
        return User::findOrFail($id);
    }

    public static function role($id)
    {
        return Role::findOrFail($id);
    }

    public static function permission($id)
    {
        return Permission::findOrFail($id);
    }

    public static function blog($id)
    {
        return Blog::findOrFail($id);
    }

    public static function post($id)
    {
        return Post::findOrFail($id);
    }

    public static function settings()
    {
        return Settings::first();
    }

    public static function getIP()
    {
        # Get Real IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    public static function permissionToAccess($slug)
    {
        if(!Auth::user()->has($slug)) {
            abort(401);
        }
    }

    public static function files()
    {
        $files = Storage::files();
        $ignore = ['.gitignore'];
        $final_files = [];
        foreach($files as $file) {
            $add = true;
            foreach($ignore as $ign){
                if($ign == $file) {
                    $add = false;
                }
            }
            if($add) {
                array_push($final_files, $file);
            }
        }
        $files = $final_files;

        return $files;
    }

    public static function document($type, $data)
    {
        return Document::where($type, $data)->first();
    }

    public static function isDocument($file_name)
    {
        if(Laralum::document('name', $file_name)) {
            return true;
        } else {
            return false;
        }
    }

    public static function addDownload($file_name)
    {
        if(Laralum::isDocument($file_name)) {
            $file = Document::where('name', $file_name)->first();
            $file->downloads = $file->downloads + 1;
            $file->save();
        }
    }

    public static function downloadLink($file_name)
    {
        $link = url('/');
        if(Laralum::isDocument($file_name)) {
            $document = Document::where('name', $file_name)->first();
            $link = url('/document', [$document->slug]);
        }
        return $link;
    }

    public static function downloadFile($file_name)
    {
        # Add a new download to the file if it's a document
        if(Laralum::isDocument($file_name)) {
            Laralum::addDownload($file_name);
        }
        return response()->download(storage_path('app/' . $file_name));
    }

    public static function isFile($file_name)
    {
        $files = Laralum::files();
        if(in_array($file_name, $files)) {
            return true;
        } else {
            return false;
        }
    }

    public static function mustBeFile($file_name)
    {
        if(!Laralum::isFile($file_name)) {
            abort(404);
        }
    }

    public static function fileExtension($file_name)
    {
        return pathinfo($file_name, PATHINFO_EXTENSION);
    }

    public static function fileIcon($file_name)
    {
        $extension = Laralum::fileExtension($file_name);

        $types = [
            'mdi-file-image'    =>  ['png', 'jpg', 'jpeg', 'gif', 'bmp'],
            'mdi-file-pdf'      =>  ['pdf'],
            'mdi-file-music'    =>  ['mp3', 'wav'],
        ];

        foreach($types as $key => $type) {
            if(in_array($extension, $type)) { return $key; }
        }

        return 'mdi-file';
    }

    public static function checkInstalled()
    {
        if(Schema::hasTable('users') and Schema::hasTable('roles') and Schema::hasTable('permissions') and Schema::hasTable('settings') and Schema::hasTable('blogs') and Schema::hasTable('posts') and Schema::hasTable('documents')) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkDocumentOwner($type, $data){
        if(Auth::user()->id == Laralum::document($type, $data)->author->id) {
            return true;
        } else {
            return false;
        }
    }

    public static function deleteFile($file_name)
    {
        Laralum::mustBeFile($file_name);

        Storage::delete($file_name);
    }

}
