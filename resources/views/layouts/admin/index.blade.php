<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>@yield('title')</title>
    <style>

    </style>
    <style>
    .spacer {
        margin-top: 100px;
    }
    .main-spacer {
        margin-top: 50px;
    }

    .small-spacer {
      margin-top: 25px;
      margin-bottom: 25px;
    }

    .main {
      margin-left: 2%;
      margin-right: 2%;
    }

    .lower-opacity {
      opacity: 0.5;
      filter: alpha(opacity=40); /* For IE8 and earlier */
    }

    .avatar-div {
      min-height: 100px;
    }

    .row-spacer {
      margin-top: 25px;
    }

    .lateral-spacer {
      margin-left: 5%;
      margin-right: 5%;
    }

    .down-spacer {
      margin-bottom: 25px;
    }

    .little-down-spacer {
      margin-bottom: 15px;
    }
    </style>

    @yield('css')

    <!-- Bootstrap -->
    <link href="{{ url('admin_panel/css/bootstrap.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.4.57/css/materialdesignicons.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="background-color: #eeeeee;">
	  <nav class="navbar navbar-default navbar-fixed-top">
	    <div class="container-fluid">
	      <div class="navbar-header">
	        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	          <span class="sr-only">Toggle navigation</span>
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	        </button>
	        <a class="navbar-brand" href="{{ url('/admin') }}">Laralum</a>
	      </div>

	      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	        <ul class="nav navbar-nav">
	          <li><a href="{{ url('admin/users') }}">Users</a></li>
	          <li><a href="{{ url('admin/roles') }}">Roles</a></li>
            <li><a href="{{ url('admin/permissions') }}">Permissions</a></li>
	        </ul>
	        <ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
		          <ul class="dropdown-menu" role="menu">
		            <li><a href="{{ url('/admin/users', Auth::user()->id) }}">Profile</a></li>
		            <li class="divider"></li>
		            <li><a href="{{ url('/logout') }}">Logout</a></li>
		          </ul>
		        </li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <div id='admin-content' style="display: none;">
      <div class="main spacer">
          @if (count($errors) > 0)
            <div class="spacer">
              <div class="alert alert-danger">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            </div>
          @endif

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

          @if(session('warning'))
            <div class="spacer">
              <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <p>{{ session('warning') }}</p>
              </div>
            </div>
          @endif

          @if(session('info'))
            <div class="spacer">
              <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <p>{{ session('info') }}</p>
              </div>
            </div>
          @endif

        @yield('content')

      </div>
    </div>
    <div id="load" style="display: none;margin-top: 150px;">
      <div class="container">

          <div class="row">
            <div class="col-sm-3 col-lg-4"></div>
            <div class="col-sm-6 col-lg-4">
              <div class="panel panel-default">
                <div class="panel-body">
                  <center>
                    <br><br>
                    <img src="{{ asset('admin_panel/svg/oval.svg') }}" /><br>
                    <br><br>
                  </center>
                </div>
              </div>
            </div>
            <div class="col-sm-3 col-lg-4"></div>
          </div>

      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ url('admin_panel/js/bootstrap.min.js') }}"></script>
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $( "#admin-content" ).fadeIn(500);
      })
      $(window).bind('beforeunload', function(){
        $( "#admin-content" ).fadeOut(250);
        $( ".modal-backdrop" ).fadeOut(250);
      });
      $(function () {
        $( "#avatar-div" ).fadeIn(2500);
      })
      $("form").submit(function(){
          $( "#load" ).fadeIn(750);
      });
    </script>

    @yield('js')

  </body>
</html>
