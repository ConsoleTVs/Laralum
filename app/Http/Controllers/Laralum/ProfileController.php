<?php

namespace App\Http\Controllers\laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Hash;
use Auth;
use Laralum;


class ProfileController extends Controller
{
    /**
     * Show the profile edit page
     */
    public function profile()
    {
        return view('laralum/profile/index');
    }

    /**
     * Show the image edit page
     */
    public function image()
    {
        return view('laralum/profile/image');
    }

    /**
     * Update the user image
     *
     * @param $request
     */
    public function updateImage(Request $request)
    {

        $this->validate($request, [
            'image' => 'required|image|max:5120',
        ]);

        $request->file('image')->move('avatars', md5(Laralum::loggedInUser()->email));

        return redirect()->route('Laralum::profile')->with('success',trans('laralum.profile_image_changed'));
    }

    /**
     * Show the change password page
     */
    public function password()
    {
        return view('laralum/profile/password');
    }

    /**
     * Update the user password
     *
     * @param $request
     */
    public function updatePassword(Request $request)
    {

        $this->validate($request, [
            'current_password'  => 'required',
            'password'          => 'required|confirmed|min:6|max:50',
        ]);

        $user = Laralum::loggedInUser();

        if (Hash::check($request->current_password, $user->password )){
            # Password correct, setup the new password and redirect with confirmation
            $user->password = bcrypt($request->password);

            return redirect()->route('Laralum::profile')->with('success', trans('laralum.profile_password_changed'));
        } else {
            # Password not correct, redirect back with error
            return redirect()->route('Laralum::profile_password')->with('error', trans('laralum.incorrect_password'));
        }
    }
}
