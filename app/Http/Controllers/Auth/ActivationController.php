<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Mail;

class ActivationController extends Controller
{
    public function activate($token = null)
    {
    	if($token) {

    		# Get the user
			$user = Auth::user();

			# Get the user's activation code
			$user_token = $user->activation_key;

    		if($token == 'resend') {
    			# Resend the activation email
    			$this->SendActivationEmail($user, $user_token);

    			# Redirect the user to the main page
				return redirect('/activate')->with('success', "The activation email has been sent!");

    		} else {

    			# Check if they match
    			if($user_token == $token) {

    				# Activate the user
    				$user->active = true;
    				$user->save();

    				# Redirect the user to the main page
    				return redirect('/')->with('success', "Your account has been activated!");

    			} else {

    				# Redirect the user back to the activation page
    				return redirect('activate')->with('error', "The activation code is not valid");

    			}
    		}
    	} else {

    		# Return the activation form
    		return view('auth/activate');

    	}
    }

    public function activateWithForm(Request $request)
    {
    	# Validate Request
    	$this->validate($request, [
    		'token'	=> 'required',
		]);

    	# Get the token
    	$token = $request->input('token');

    	# Call the activate function
    	return $this->activate($token);
    }

    public function SendActivationEmail($user, $token)
    {
		# Sends the activation email to the user
		Mail::send('auth.emails.activation', ['user' => $user, 'token' => $token], function ($m) use ($user) {

			# Set the activation email subject
			$subject = "Activate your account on ".env('APP_NAME')."!";

			# Setup email
            $m->to($user->email, $user->name)->subject($subject);
        });
    }
}
