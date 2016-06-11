<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post_View extends Model
{
    protected $table = 'post_views';

    public function post()
    {
        return $this->hasOne('App\Post');
    }
}
