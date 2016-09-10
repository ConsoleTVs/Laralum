<?php

namespace App\Http\Controllers\laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Contracts\Validation\Validator;

use Hash;

use Auth;

use Laralum;


class ProfileController extends Controller
{
    public function profile(){
    	return view('laralum/profile/index');
   }

   public function image(){
    	return view('laralum/profile/image');
   }

   public function updateImage(Request $request){

    	$this->validate($request, [
            'image' => 'required|image|max:5120',
            ]);

      $request->file('image')->move('avatars', md5(Laralum::loggedInUser()->email));
      return redirect(route('Laralum::profile'))->with('success',trans('laralum.profile_image_changed'));
   }


   public function password(){
        return view('laralum/profile/password');
   }

   public function updatePassword(Request $request){

      $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6|max:50',
            ]);

      if (Hash::check($request->current_password, Laralum::loggedInUser()->password )){
          $user = Laralum::loggedInUser();
          $user->where('email','=', Laralum::loggedInUser()->email)
               ->update(['password' => bcrypt($request->password)]);
          return redirect(route('Laralum::profile'))->with('success',trans('laralum.profile_password_changed'));
      }
      else
      {
        return redirect(route('Laralum::profile_password'))->with('error',trans('laralum.incorrect_password'));
      }
   }
}
