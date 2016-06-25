<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post_Comment extends Model
{
    protected $table = 'post_comments';

    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
