@extends('layouts.app')

@section('css')
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
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Download file: {{ $file->name }}</div>
                <div class="panel-body">
                    <center>
                        @if($file->disabled)
                            <span>The document is currently disabled</span>
                        @elseif($file->authorization_required and !Auth::check())
                            <span>You need to <a href="{{ url('/login') }}">Login</a> or <a href="{{ url('/register') }}">Register</a> to download this document</span>
                        @else
                            <span id="timer"> {{ $file->download_timer }} </span>
                            <form method="POST" id="download_form">
                                {{ csrf_field() }}
                                @if($file->password)
                                    <input name="password" type="password" placeholder="Document Password"><br><br>
                                @endif

                                <div id="download_button">
                                    <button type="submit">Download</button>
                                </div>
                            </form>
                            <span id="download_starting"><br>Your download is starting...</span>
                        @endif
                    </center>
                </div>
            </div>
            @if(session('error'))
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<p>{{ session('error') }}</p>
				</div>
			@endif
			@if(session('success'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<p>{{ session('success') }}</p>
				</div>
			@endif
        </div>
    </div>
</div>
@endsection
@section('js')
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
@endsection
