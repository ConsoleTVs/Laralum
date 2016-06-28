<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Permission;
use App\Blog;
use App\Post;
use App\Settings;

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

}
