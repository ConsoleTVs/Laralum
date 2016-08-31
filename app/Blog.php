<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Blog extends Model
{
    //

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function hasRole($name)
    {
        foreach($this->roles as $role){
            if($role->name == $name){
                return true;
            }
        }
        return false;
    }

    public function views()
    {
        return $this->hasManyThrough('App\Post_View', 'App\Post');
    }

    public function comments()
    {
        return $this->hasManyThrough('App\Post_Comment', 'App\Post');
    }

}
