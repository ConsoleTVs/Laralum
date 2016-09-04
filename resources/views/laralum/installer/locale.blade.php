<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>{{ trans('laralum.installer') }}</title>
	<meta name="description" content="Laralum - Laravel administration panel">
	<meta name="author" content="Èrik Campobadal Forés">

	{!! Laralum::includeAssets('laralum_header') !!}


  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->


</head>

<body style="background-color: #EEEEEE;">

	<div class="ui inverted dimmer"><div class="ui loader"></div></div>


        <div style="padding-top: 100px;">
            <center>
                <img class="ui medium image" src="{{ Laralum::laralumLogo() }}">
            </center>
        </div>
        <div style="padding-top: 100px;">
            <div class="ui doubling stackable grid container">
                <div class="five wide column"></div>
                <div class="six wide column">
                    <div class="ui very padded segment">
                        <br>
                        <center>
                            @foreach(Laralum::locales() as $locale => $locale_info)
                                @if($locale_info['enabled'])
                                    <a href="{{ route('Laralum::install', ['locale' => $locale]) }}" class="ui button">
                                        @if($locale_info['type'] == 'flag')
                                            <i class="{{ $locale_info['type_data'] }} flag"></i>
                                        @elseif($locale_info['type'] == 'image')
                                            <img style="display: inline-block;" class="ui image"  height="12" src="{{ $locale_info['type_data'] }}">&nbsp;
                                        @endif
                                        {{ $locale_info['name'] }}
                                    </a>
                                    <br><br>
                                @endif
                            @endforeach
                        </center>
                    </div>
				</div>
                <div class="five wide column"></div>
            </div>
        </div>


	{!! Laralum::includeAssets('laralum_bottom') !!}



</body>
</html>
