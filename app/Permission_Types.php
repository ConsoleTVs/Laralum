<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission_Types extends Model
{
    protected $table = "Permission_Types";

    public function permissions(){
    	return $this->HasMany('App\Permission', 'type_id');
    }
}
