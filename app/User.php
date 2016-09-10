<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laralum;
use Mail;
use File;
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
        'password', 'remember_token', 'activation_key',
    ];

    /**
    * Mutator to capitalize the name
    *
    * @param mixed $value
    */
    public function setNameAttribute($value){
        $this->attributes['name'] = ucwords($value);
    }

    /**
    * Returns all the roles from the user
    *
    */
    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    /**
    * Returns true if the user has access to laralum
    *
    */
    public function isAdmin()
    {
        return $this->hasPermission('laralum.access');
    }

    /**
    * Returns true if the user has the permission slug
    *
    * @param string $slug
    */
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

    /**
    * Returns true if the user has the role
    *
    * @param string $name
    */
    public function hasRole($name)
    {
        foreach($this->roles as $role) {
            if($role->name == $name) {
                return true;
            }
        }
        return false;
    }

    /**
    * Returns all the blogs owned by the user
    *
    */
    public function blogs()
    {
        return $this->hasMany('App\Blog');
    }

    /**
    * Returns true if the user has blog access
    *
    * @param number $id
    */
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

    /**
    * Returns true if the user owns the blog
    *
    * @param number $id
    */
    public function owns_blog($id)
    {
        if($this->id == Laralum::blog('id', $id)->user_id){
            return true;
        } else {
            return false;
        }
    }

    /**
    * Returns all the posts from the user
    *
    */
    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    /**
    * Returns true if the users owns the post
    *
    * @param number $id
    */
    public function owns_post($id)
    {
        if($this->id == Laralum::post('id', $id)->author->id){
            return true;
        } else {
            return false;
        }
    }

    /**
    * Returns the user avatar from Gavatar
    *
    * @param number $size
    */
    public function avatar($size = null)
    {
        $file = Laralum::avatarsLocation() . '/' . md5(Laralum::loggedInUser()->email);
        $file_url = asset($file);
        if(File::exists($file)){
            return $file_url;
        } else {
            return Laralum::defaultAvatar();
        }
    }
    /**
    * Returns all the documents from the user
    *
    */
    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    /**
    * Sends the welcome email notification to the user
    *
    */
    public function sendWelcomeEmail()
    {
        return $this->notify(new WelcomeMessage($this));
    }

    /**
    * Sends the activation email notification to the user
    *
    */
    public function sendActivationEmail()
    {
        return $this->notify(new AccountActivation($this));
    }
}
