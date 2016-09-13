<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    /**
    * Returns the user account
    *
    */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
