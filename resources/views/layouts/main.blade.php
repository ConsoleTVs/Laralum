<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        {!! Laralum::include('header') !!}

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway';
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            input[type=text], select {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            input[type=email], select {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            input[type=password], select {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            .button {
                background-color: #4CAF50; /* Green */
                border: none;
                color: white;
                padding: 16px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                -webkit-transition-duration: 0.4s; /* Safari */
                transition-duration: 0.4s;
                cursor: pointer;
            }

            .button1 {
                background-color: white;
                color: black;
                border: 2px solid #4CAF50;
            }

            .button1:hover {
                background-color: #4CAF50;
                color: white;
            }

            .button2 {
                background-color: white;
                color: black;
                border: 2px solid #008CBA;
            }

            .button2:hover {
                background-color: #008CBA;
                color: white;
            }

            .button3 {
                background-color: white;
                color: black;
                border: 2px solid #f44336;
            }

            .button3:hover {
                background-color: #f44336;
                color: white;
            }

            .button4 {
                background-color: white;
                color: black;
                border: 2px solid #e7e7e7;
            }

            .button4:hover {background-color: #e7e7e7;}

            .button5 {
                background-color: white;
                color: black;
                border: 2px solid #555555;
            }

            .button5:hover {
                background-color: #555555;
                color: white;
            }

        </style>
    </head>
    <body>
        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
    		{{ csrf_field() }}
    	</form>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                @if(Auth::check())
                    <div class="top-right links">
                        <a href="{{ url('/') }}">Welcome</a>
                        <a href="{{ url('/home') }}">Home</a>
                        @if(Laralum::loggedInUser()->isAdmin())
                            <a href="{{ route('Laralum::dashboard') }}">Administration</a>
                        @endif
                        <a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                @else
                    <div class="top-right links">
                        @if(!Laralum::checkInstalled())
                            <a style="color: #00C853;" href="{{ route('Laralum::install_locale') }}">Install Laralum</a>
                        @endif
                        <a href="{{ url('/') }}">Welcome</a>
                        <a href="{{ url('/home') }}">Home</a>
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    </div>
                @endif
            @endif

            <div class="content">

                @yield('content')
                <br>
                @if(session('success'))
            		<p style='font-size: 40px;color:green;'>{!! session('success') !!}<p>
            	@endif
            	@if(session('error'))
            		<p style='font-size: 40px;color:red;'>{!! session('error') !!}<p>
            	@endif
                @if(session('warning'))
            		<p style='font-size: 40px;color:orange;'>{!! session('warning') !!}<p>
            	@endif
            	@if(session('info'))
            		<p style='font-size: 40px;color:blue;'>{!! session('info') !!}<p>
            	@endif
                @if (session('status'))
                    <p style='font-size: 40px;color:green;'>{!! session('status') !!}<p>
                @endif
            	@if (count($errors) > 0)
            		<?php foreach($errors->all() as $error){ echo "<p style='font-size: 40px;color:red;'>$error<p>"; } ?>
            	@endif

            </div>
        </div>
    </body>
</html>
