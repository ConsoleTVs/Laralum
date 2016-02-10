<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active', 'activation_key', 'phone', 'location', 'bio', 'country_code',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_key',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function has($slug)
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

    public function is($name)
    {
        foreach($this->roles as $role) {
            if($role->name == $name) {
                return true;
            }
        }
        return false;
    }
}
