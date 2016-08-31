<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Role_User;
use Laralum;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'password' => 'required|min:6|confirmed',
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
        $settings = Laralum::userSettings();

        # Check if register is enabled
        if(!$settings->register_enabled) {
            abort(403, trans('laralum.error_registrations_disabled'));
        }

        # Setup a random activation key
        $activation_key = str_random(25);

        # Get the register IP
        $register_ip = Laralum::getIP();

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
                $user_data['country_code'] = Laralum::getCountryCode($register_ip);
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
            $user->sendWelcomeEmail();
        }

        # Send activation email if set
        if($send_activation) {
            # Sends the activation email to the user
            $user->sendActivationEmail();
        }

        return $user;
    }
}
