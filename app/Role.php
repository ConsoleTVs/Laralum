<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
    {
    	return $this->belongsToMany('App\User');
    }

    public function permissions()
    {
    	return $this->belongsToMany('App\Permission');
    }

    public function hasPermission($slug)
    {
        foreach($this->permissions as $perm) {
            if($perm->slug == $slug) {
                return true;
            }
        }
        return false;
    }
}
