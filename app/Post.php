<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Location;
use Laralum;

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

    public function edited()
    {
        if($this->edited_by) {
            return true;
        } else {
            return false;
        }
    }

    public function editor()
    {
        if($this->edited()){
            return $this->belongsTo('App\User', 'edited_by');
        } else {
            return null;
        }
    }

    public function views()
    {
        return $this->hasMany('App\Post_View');
    }

    public function commentsEnabled()
    {
        if($this->logged_in_comments or $this->anonymous_comments) {
            return true;
        } else {
            return false;
        }
    }

    public function addView()
    {
        # Check if view exists
        if($this->limit_views_per_ip) {
            $views = Laralum::postViews('post_id', $this->id);
            foreach($views as $view){
                if($view->ip == Laralum::getIP()){
                    return false;
                }
            }
        }
        $view = Laralum::newPostView();
        $view->post_id = $this->id;
        $view->ip = Laralum::getIP();
        $view->url = Laralum::currentURL();;
        $view->ref = Laralum::previousURL();
        if($this->blog->views_location){
            try {
                $view->country_code = Location::get($view->ip)->countryCode; #it takes some time to check the api...
            } catch (Exception $e) {
                $view->country_code = "FF"; #FF will be translated into nothing later
            }
        } else {
            $view->country_code = "FF"; #FF will be translated into nothing later
        }
        $view->save();

        return true;
    }

    public function comments()
    {
        return $this->hasmany('App\Post_Comment');
    }
}
