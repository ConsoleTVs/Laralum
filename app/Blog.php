<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Blog extends Model
{
    //

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function has($role_id)
    {
        foreach($this->roles as $role){
            if($role->id == $role_id){
                return true;
            }
        }
        return false;
    }

}
