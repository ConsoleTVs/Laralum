<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role_User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Request;
use Mail;
use App\Users_Settings;
use Location;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        # Get the users settings
        $settings = Users_Settings::first();

        # Check if register is enabled
        if(!$settings->register_enabled) {
            abort(403);
        }

        # Setup a random activation key
        $activation_key = str_random(25);

        # Get the register IP
        $register_ip = Request::ip();

        # Setup the activation method
        if($settings->default_active == 0) {

            # User not activated by default action
            $active = false;

            # Will not send the activation email
            $send_activation = false;

        } elseif($settings->default_active == 1) {

            # User not activated by default action
            $active = false;

            # Will send the activation email
            $send_activation = true;

        } else {

            # User activated by default action
            $active = true;

            # Will not send the activation email
            $send_activation = false;

        }

        # Create the user
        $user_data = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'active' => $active,
            'activation_key' => $activation_key,
            'register_ip' => $register_ip,
        ];

        if($settings->location){
            try {
                $user_data['country_code'] = Location::get($register_ip)->countryCode;
            } catch (Exception $e) {
                $user_data['country_code'] = "FF";
            }
        } else {
            $user_data['country_code'] = "FF";
        }

        $user = User::create($user_data);

        # Add Relationshop
        $rel = new Role_User;
        $rel->user_id = $user->id;
        $rel->role_id = $settings->default_role;
        $rel->save();

        # Send the welcome email if set
        if($settings->welcome_email) {
            # Sends the welcome email to the user
            Mail::send('auth.emails.welcome', ['user' => $user, 'password' => null], function ($m) use ($user) {

                # Set the welcome email subject
                $subject = "Welcome to ".env('APP_NAME')."!";

                # Setup email
                $m->to($user->email, $user->name)->subject($subject);
            });
        }

        # Send activation email if set
        if($send_activation) {
            # Sends the activation email to the user
            Mail::send('auth.emails.activation', ['user' => $user, 'token' => $activation_key], function ($m) use ($user) {

                # Set the activation email subject
                $subject = "Activate your account on ".env('APP_NAME')."!";

                # Setup email
                $m->to($user->email, $user->name)->subject($subject);
            });
        }

        return $user;
    }
}
