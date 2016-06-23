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

    .blog-img {
        height:300px;
        background-size:cover;
        background-position:center center;
        background-repeat:no-repeat;
    }
    .blog-img-full {
        height:500px;
        background-size:cover;
        background-position:center center;
        background-repeat:no-repeat;
    }

    .post-text {
        font-size: 18px;
    }

    .feature {
        font-size: 80px;
    }

    .code {
        min-height: 450px;
    }
    </style>

    @yield('css')

    <!-- Bootstrap -->
    <link href="{{ url('admin_panel/css/bootstrap.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.6.50/css/materialdesignicons.min.css">

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <script src='{{ asset('admin_panel/tinymce/tinymce.min.js') }}'></script>
    <script src="{{ asset('admin_panel/code/ace.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src='{{ asset('admin_panel/chartjs/Chart.js') }}'></script>

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
                @if(Auth::user()->has('admin.users.access'))
                    <li><a href="{{ url('admin/users') }}">Users</a></li>
                @endif
                @if(Auth::user()->has('admin.roles.access'))
                    <li><a href="{{ url('admin/roles') }}">Roles</a></li>
                @endif
                @if(Auth::user()->has('admin.permissions.access'))
                    <li><a href="{{ url('admin/permissions') }}">Permissions</a></li>
                @endif
                @if(Auth::user()->has('admin.blogs.access'))
                    <li><a href="{{ url('admin/blogs') }}">Blogs</a></li>
                @endif
	        </ul>
	        <ul class="nav navbar-nav navbar-right">
                @if(Auth::user()->has('admin.developer.access'))
                    <li><a href="{{ url('admin/developer') }}">Developer Mode</a></li>
                @endif
				<li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
		          <ul class="dropdown-menu" role="menu">
		            <li><a href="{{ url('/admin/users', Auth::user()->id) }}">Profile</a></li>
                <li><a href="{{ url('/') }}">Visit Website</a></li>
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
    <script type="text/javascript" src="{{ url('admin_panel/noty/jquery.noty.packaged.js') }}"></script>
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

    <script>
    tinymce.init({
        selector: '.editor',
        min_height: 450,
        content_css : '{{ url('admin_panel/css/roboto.css') }}'
    });
    </script>

    @if(session('success'))
        <script>
            var n = noty({text: "{{ session('success') }}", layout: 'bottomRight',type: "success"});
        </script>
    @endif

    @if(session('error'))
        <script>
            var n = noty({text: "{{ session('error') }}", layout: 'bottomRight',type: "error"});
        </script>
    @endif

    @if(session('warning'))
        <script>
            var n = noty({text: "{{ session('warning') }}", layout: 'bottomRight',type: "warning"});
        </script>
    @endif

    @if(session('info'))
        <script>
            var n = noty({text: "{{ session('info') }}", layout: 'bottomRight',type: "information"});
        </script>
    @endif

    @yield('js')

  </body>
</html>
