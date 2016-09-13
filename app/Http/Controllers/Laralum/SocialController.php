<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Socialite;
use App\Social;
use App\Role_User;
use Auth;

class SocialController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $social_user = Socialite::driver($provider)->user();

        $social = new Social;
        $social->provider = $provider;
        $social->social_id = $social_user->id;

        $logged_in = false;
        $new_user = false;

        $url = '/';

        if(Auth::check()){
            # User is logged in, add social account and link it

            if(!Social::whereProviderAndSocial_id($provider, $social_user->id)->first()){
                $social->user_id = Laralum::loggedInUser()->id;
                $social->save();
                $msg = trans('laralum.social_success', ['provider' => $provider]);
            } else {
                $msg = trans('laralum.social_error', ['provider' => $provider]);
            }

            if(Laralum::loggedInUser()->isAdmin()){
                $url = route('Laralum::profile');
            }

        } else {
            # User is not logged in, create an account & social account & login

            # Check if the user got an account
            if($user = Laralum::user('email', $social_user->email)){
                if(!Social::whereProviderAndSocial_id($provider, $social_user->id)->first()){
                    $social->user_id = $user->id;
                    $social->save();
                    $new_link = true;
                } else {
                    $new_link = false;
                }
            } else {
                $user = Laralum::newUser();
                $user->email = $social_user->email;
                $user->name = $social_user->name;

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

                if($settings->location){
                    try {
                        $user->country_code = Laralum::getCountryCode($register_ip);
                    } catch (Exception $e) {
                        $user->country_code = "FF";
                    }
                } else {
                    $user->country_code = "FF";
                }

                $user->active = $active;
                $user->activation_key = $activation_key;
                $user->register_ip = $register_ip;
                $user->save();

                $social->user_id = $user->id;
                $social->save();

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

                $new_user = true;
            }

            $logged_in = true;
            Auth::login($user);
        }

        if($logged_in){
            if($new_user){
                $msg = trans('laralum.social_success_register', ['provider' => $provider]);
            } else {
                if($new_link){
                    $msg = trans('laralum.social_success_login_link', ['provider' => $provider]);
                } else {
                    $msg = trans('laralum.social_success_login', ['provider' => $provider]);
                }
            }
        }

        return redirect($url)->with('success', $msg);
    }
}
