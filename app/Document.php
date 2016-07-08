<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
