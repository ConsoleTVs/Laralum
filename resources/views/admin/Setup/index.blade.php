<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Setup Admin Account</title>
    <style>
        .spacer {
            margin-top: 150px;
        }

        .big-spacer {
            margin-top: 250px;
        }

        .label-size {
            font-size: 15px;
        }
    </style>

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
	        <a class="navbar-brand" href="{{ url('/') }}">Laralum</a>
	      </div>
	    </div>
	  </nav>
      <div class="container spacer">
          <div class="row">
              <div class="col-md-6 big-spacer">
                    <center>
                      <h1>Laralum</h1>
                      <h4>Laravel Administration Panel</h4>
                      <p>Version {{ $version }}</p>
                  </center>
              </div>
              <div class="col-md-6">
                <div class="panel panel-default">
                  <div class="panel-body">
                      <br><br>
                      <div class="row">
                          <div class="col-md-1"></div>
                          <div class="col-md-10">
                              <form method="POST">
                                  {{ csrf_field() }}
                                  <div class="form-group">
                                      <label class="control-label label-size" for="name"><i class="mdi mdi-account"></i> Administrator Name</label>
                                      <input required type="text" name="name" class="form-control" id="name" placeholder="Name">
                                   </div>
                                   <div class="form-group">
                                       <label class="control-label label-size" for="email"><i class="mdi mdi-email"></i> Administrator Email</label>
                                       <input required type="email" name="email" class="form-control" id="email" placeholder="Email">
                                   </div>
                                   <div class="form-group">
                                       <label class="control-label label-size" for="password"><i class="mdi mdi-lock"></i> Administrator Password</label>
                                       <input required type="password" name="password" class="form-control" id="password" placeholder="Password">
                                   </div>
                                   <div class="form-group">
                                       <label class="control-label label-size" for="password_confirmation"><i class="mdi mdi-lock"></i> Repeat Password</label>
                                       <input required type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Repeat Password">
                                   </div>
                                   <div class="form-group">
                                        <label for="country_code" class="control-label label-size"><i class="mdi mdi-earth"></i> Country</label>
                                        <select required class="form-control" name="country_code" id="country_code">
                                            <option disabled selected value="">Select the country</option>
                                            <?php
                                                $json = file_get_contents('http://country.io/names.json');
                                                $countries = json_decode($json, true);
                                            ?>
                                            @foreach($countries as $country)
                                                <option value="{{ array_search($country, $countries) }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                   </div>
                                   <div class="form-group">
                                      <label class="control-label label-size" for="role_name"><i class="mdi mdi-key"></i> Administrator Role Name</label>
                                      <input required type="text" name="role_name" class="form-control" id="role_name" placeholder="Role Name">
                                   </div>
                                   <div class="form-group">
                                       <label class="control-label label-size" for="role_color"><i class="mdi mdi-palette"></i> Administrator Role Color</label>
                                       <input required type="text" name="role_color" class="form-control" id="role_color" placeholder="Role Color">
                                   </div>
                                   <div class="form-group">
                                       <center>
                                           <button type="submit" class="btn btn-primary">Setup Laralum</button>
                                       </center>
                                   </div>
                              </form>
                              @if (count($errors) > 0)
                                  <div class="alert alert-danger">
                                      <ul>
                                          @foreach ($errors->all() as $error)
                                              <li>{{ $error }}</li>
                                          @endforeach
                                      </ul>
                                  </div>
                              @endif
                          </div>
                          <div class="col-md-1"></div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
      </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ url('admin_panel/js/bootstrap.min.js') }}"></script>
  </body>
</html>
