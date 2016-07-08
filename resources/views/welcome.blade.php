@extends('layouts.admin.public')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome to laralum <a href="{{ url('/home') }}" class="pull-right btn btn-primary btn-sm">Check</a></div>

                <div class="panel-body">


                      <h4>Getting started</h4>
                      <p>
                        Laralum is easy to install, and it installs itself at the same time you
                        run the migrations, so in fact you'll just need one command to install it.
                      </p>

                      <br>

                      <h4>Initial Configuration</h4>
                      <p>
                        Before laralum can be installed, some things have to be configured,
                        but don't worry, we took care of the hard work by adding all the configuration in the
                        .env file placed in your root directory
                      </p>

                      <h3><small>.env example (it uses your generated key)</small></h3>
                      <pre>
APP_ENV=local
APP_DEBUG=false
APP_KEY={{ env('APP_KEY') }}

USER_NAME="ADMINISTRATOR_NAME"
USER_EMAIL="my@email.com"
USER_PASSWORD="ADMINISTRATOR_PASSWORD"
USER_COUNTRY_CODE="ES"

ADMINISTRATOR_ROLE_NAME="Administrator"
DEFAULT_ROLE_NAME="Users"

DB_HOST="YOUR_DATABASE_HOST"
DB_DATABASE="YOUR_DATABASE_NAME"
DB_USERNAME="YOUR_DATABASE_USERNAME"
DB_PASSWORD="YOUR_DATABASE_PASSWORD"

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

REDIS_HOST=localhost
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME="YOUR_MAIL_USERNAME"
MAIL_PASSWORD="YOUR_EMAIL_PASSWORD"
MAIL_ENCRYPTION=tls
MAIL_FROM="YOUR_MAIL_NAME"
MAIL_NAME="Laralum"
                      </pre>

                      <br>

                      <h4>Install Laralum</h4>
                      <p>
                        Everything configured? lets get ahead with it!<br>
                        Open a terminal and navigate to where your project is located
                        <h3><small>Navigate to where the project is located</small></h3>
                        <pre>cd {{ base_path() }}</pre>
                        <h3><small>Install it!</small></h3>
                        <pre>php artisan migrate</pre>
                        <br>
                        <h4>What's next?</h4>
                        <p>Press the check button on the top and if no error is found go ahead and login with the credentials you specified in the configuration.</p>
                      </p>

                </div>
            </div>
            @if(session('success'))
                <div class="spacer">
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <p>{{ session('success') }}</p>
                  </div>
                </div>
            @endif
            @if(session('error'))
                <div class="spacer">
                  <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <p>{{ session('error') }}</p>
                  </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
