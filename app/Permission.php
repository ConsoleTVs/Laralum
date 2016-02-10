<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	protected $table = "permissions";

    public function roles()
    {
    	return $this->belongsToMany('App\Role');
    }

    public function type()
    {
    	return $this->belongsTo('App\Permission_types');
    }
}
