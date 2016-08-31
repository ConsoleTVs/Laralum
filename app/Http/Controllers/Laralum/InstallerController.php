<?php

namespace App\Http\Controllers\Laralum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;
use Auth;
use Artisan;
use Laralum;

class InstallerController extends Controller
{
    public function locale()
    {
        // Show the locale options
        if(!Laralum::checkInstalled()){
            return view('laralum/installer/locale');
        } else {
            return redirect()->route('Laralum::dashboard')->with('warning', trans('laralum.already_installed'));
        }
    }

    public function show($locale)
    {
        // Show the installation form
        if(!Laralum::checkInstalled()){
            // Set the locale
            App::setLocale($locale);


            return view('laralum/installer/index');
        } else {
            return redirect()->route('Laralum::dashboard')->with('warning', trans('laralum.already_installed'));
        }
    }

    public function installConfig($locale, Request $request)
    {
        if(!Laralum::checkInstalled()){
            // Install Laralum

            $this->validate($request, [
                'USER_NAME' => 'required',
                'USER_PASSWORD' => 'required|min:6|confirmed',
                'USER_EMAIL' => 'required',
                'USER_COUNTRY_CODE' => 'required',
                'USER_LOCALE' => 'required',
                'ADMINISTRATOR_ROLE_NAME' => 'required',
                'DEFAULT_ROLE_NAME' => 'required',
                'DB_HOST' => 'required',
                'DB_PORT' => 'required',
                'DB_DATABASE' => 'required',
                'DB_USERNAME' => 'required',
            ]);

            $file_location = base_path() . '/.env';
            $env = fopen($file_location, "w") or die("Unable to open file!");
            foreach($request->all() as $key => $data) {
                if($key != '_token' and $key != 'USER_PASSWORD_confirmation') {
                    fwrite($env, $key . "='" . $data . "'\n");
                }
            }
            $default = "\nREDIS_HOST=127.0.0.1\nREDIS_PASSWORD=null\nREDIS_PORT=6379\n\nPUSHER_KEY=\nPUSHER_SECRET=\nPUSHER_APP_ID=\n\nBROADCAST_DRIVER=log\nCACHE_DRIVER=file\nSESSION_DRIVER=file\nQUEUE_DRIVER=sync\n\nAPP_ENV=local\nAPP_KEY=" . env('APP_KEY') . "\nAPP_DEBUG=true\nAPP_LOG_LEVEL=debug\nAPP_URL=" . url('/') . "\n";
            fwrite($env, $default);
            fclose($env);

            return redirect()->route('Laralum::install_confirm', ['locale' => $locale]);
        } else {
            return redirect()->route('Laralum::dashboard')->with('warning', trans('laralum.already_installed'));
        }
    }

    public function install($locale)
    {
        if(!Laralum::checkInstalled()){

            $exitCode = Artisan::call('migrate');

            if (Auth::attempt(['email' => env('USER_EMAIL'), 'password' => env('USER_PASSWORD')])) {
                // Authentication passed...

                $file_location = base_path() . '/.env';
                $default = "\nLARALUM_INSTALLED=true";
                file_put_contents($file_location,$default, FILE_APPEND);

                $url = route('Laralum::dashboard');
                return redirect()->intended($url)->with('success', trans('laralum.welcome_to_laralum'));
            } else{
                die("<b>ERROR: </b> Something went wrong, please post an issue about it on github");
            }
        } else{
            return redirect()->route('Laralum::dashboard')->with('warning', trans('laralum.already_installed'));
        }
    }
}
