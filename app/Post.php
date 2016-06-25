<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post_View;
use Request;
use URL;
use Location;

class Post extends Model
{
    protected $table = 'posts';

    public function blog()
    {
        return $this->belongsTo('App\Blog');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function views()
    {
        return $this->hasMany('App\Post_View');
    }

    public function addView()
    {
        $view = new Post_View;

        $view->post_id = $this->id;
        $view->ip = Request::ip();
        $view->url = Request::url();
        $view->ref = URL::previous();
        if($this->blog->views_location){
            try {
                $view->country_code = Location::get($view->ip)->countryCode; #it takes some time to chech the api...
            } catch (Exception $e) {
                $view->country_code = "FF"; #FF will be translated into nothing later
            }
        } else {
            $view->country_code = "FF"; #FF will be translated into nothing later
        }
        $view->save();

        return True;
    }

    public function comments()
    {
        return $this->hasmany('App\Post_Comment');
    }
}
