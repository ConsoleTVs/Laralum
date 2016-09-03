<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>{{ trans('laralum.download') }} {{ $file->name }} - {{ Laralum::settings()->website_title }}</title>
	<meta name="description" content="Laralum - Laravel administration panel">
	<meta name="author" content="Èrik Campobadal Forés">

	{!! Laralum::includeAssets('laralum_header') !!}


    <style>
        #download_form {
            display: none;
        }

        #download_starting {
            display: none;
        }

        #timer {
            font-size: 40px;
        }
    </style>


  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->


</head>

<body style="background-color: #EEEEEE;">

	<div class="ui inverted dimmer"><div class="ui text loader">Loading</div></div>


	@if(session('success'))
		<script>
			swal({
				title: "Nice!",
				text: "{!! session('success') !!}",
				type: "success",
				confirmButtonText: "Cool"
			});
		</script>
	@endif
	@if(session('error'))
		<script>
			swal({
				title: "Whops!",
				text: "{!! session('error') !!}",
				type: "error",
				confirmButtonText: "Okai"
			});
		</script>
	@endif
	@if(session('warning'))
		<script>
			swal({
				title: "Watch out!",
				text: "{!! session('warning') !!}",
				type: "warning",
				confirmButtonText: "Okai"
			});
		</script>
	@endif
	@if(session('info'))
		<script>
			swal({
				title: "Watch out!",
				text: "{!! session('info') !!}",
				type: "info",
				confirmButtonText: "{{ trans('laralum.okai') }}"
			});
		</script>
	@endif
	@if (count($errors) > 0)
		<script>
			swal({
				title: "Whops!",
				text: "<?php foreach($errors->all() as $error){ echo "$error<br>"; } ?>",
				type: "error",
				confirmButtonText: "Okai",
				html: true
			});
		</script>
	@endif


        <div style="padding-top: 100px;">
            <center>
                <img class="ui medium image" src="@if(Laralum::settings()->logo) {{ Laralum::settings()->logo }} @else {{ Laralum::laralumLogo() }} @endif">
            </center>
        </div>
        <div style="padding-top: 100px;">
            <div class="ui doubling stackable grid container">
                <div class="four wide column"></div>
                <div class="eight wide column">
                    <div class="ui very padded segment">
                        <center>
                            <span class="ui header">{{ $file->name }}</span><br><br>
                            @if($file->disabled)
                                <span>The document is currently disabled</span>
                            @elseif($file->authorization_required and !Auth::check())
                                <span>You need to <a href="{{ url('/login') }}">Login</a> or <a href="{{ url('/register') }}">Register</a> to download this document</span>
                            @else
                                <br>
                                <span id="timer">{{ $file->download_timer }}</span>
                                <form method="POST" id="download_form">
                                    {{ csrf_field() }}
                                    @if($file->password)
                                        <div class="ui input">
                                            <input name="password" type="password" placeholder="{{ trans('laralum.document_password') }}">
                                        </div><br><br><br>
                                    @endif

                                    <div id="download_button">
                                        <button class="ui button" type="submit">{{ trans('laralum.download') }}</button>
                                    </div>
                                </form>
                                <span id="download_starting"><br>{{ trans('laralum.download_starting') }}</span>
                            @endif
                        </center>
                    </div>
				</div>
                <div class="four wide column"></div>
            </div>
        </div>

    @if($file->download_timer)
    <script>
    $(document).ready(function() {

      // Function to update counters on all elements with class counter
      var doUpdate = function() {
        $('#timer').each(function() {
          var count = parseInt($(this).html());
          if (count !== 0) {
            $(this).html(count - 1);
          }
        });
      };

      // Schedule the update to happen once every second
      setInterval(doUpdate, 1000);
    });
    </script>
    @endif
    @if($file->direct_download and !$file->password)
        <script>
            setTimeout(function(){
                $('#download_form').fadeIn();
                $('#timer').fadeOut();
                $('#download_starting').fadeIn();
                $("#download_form").submit();
            }, {{ $file->download_timer * pow(10,3) }});
        </script>
    @else
        <script>
        setTimeout(function(){
            $('#timer').hide();
            $('#download_form').fadeIn();
        }, {{ $file->download_timer * pow(10,3) }});
        </script>
    @endif

	{!! Laralum::includeAssets('laralum_bottom') !!}



</body>
</html>
