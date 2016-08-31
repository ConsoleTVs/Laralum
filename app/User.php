<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laralum;
use Mail;
use App\Notifications\WelcomeMessage;
use App\Notifications\AccountActivation;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active', 'activation_key', 'register_ip', 'country_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function isAdmin()
    {
        return $this->hasPermission('laralum.access');
    }

    public function hasPermission($slug)
    {
        foreach($this->roles as $role) {
            foreach($role->permissions as $perm) {
                if($perm->slug == $slug) {
                    return true;
                }
            }
        }
        return false;
    }

    public function hasRole($name)
    {
        foreach($this->roles as $role) {
            if($role->name == $name) {
                return true;
            }
        }
        return false;
    }

    public function blogs()
    {
        return $this->hasMany('App\Blog');
    }

    public function has_blog($id)
    {
        foreach($this->roles as $role){
            foreach(Laralum::blog('id', $id)->roles as $b_role){
                if($role->id == $b_role->id){
                    return true;
                }
            }
        }
        return false;
    }

    public function owns_blog($id)
    {
        if($this->id == Laralum::blog('id', $id)->user_id){
            return true;
        } else {
            return false;
        }
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function owns_post($id)
    {
        if($this->id == Laralum::post('id', $id)->author->id){
            return true;
        } else {
            return false;
        }
    }

    public function avatar($size = null){
        $grav_url = "https://www.gravatar.com/avatar/".md5(strtolower(trim($this->email)));
        if($size) {
            $grav_url = $grav_url . '?s=' . $size;
        }
        return $grav_url;
    }

    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    public function sendWelcomeEmail()
    {
        return $this->notify(new WelcomeMessage($this));
    }

    public function sendActivationEmail()
    {
        return $this->notify(new AccountActivation($this));
    }
}
