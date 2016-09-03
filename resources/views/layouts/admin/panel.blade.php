<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>@yield('title') - {{ Laralum::settings()->website_title }}</title>
	<meta name="description" content="Laralum - Laravel administration panel">
	<meta name="author" content="Èrik Campobadal Forés">

	{!! Laralum::includeAssets('laralum_header') !!}

	{!! Laralum::includeAssets('charts') !!}

  @yield('css')

  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

</head>

<body>

	<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
		{{ csrf_field() }}
	</form>

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


	<div class="ui sidebar left-menu " >
		<header>
			<div class="ui left fixed vertical menu" id="vertical-menu">
				<div id="vertical-menu-height">
					<a href="{{ route('Laralum::dashboard') }}" class="item logo-box">
						<div class="logo-container">
							<img class="logo-image ui fluid small image" src="@if(Laralum::settings()->logo) {{ Laralum::settings()->logo }} @else {{ Laralum::laralumLogo() }} @endif">
						</div>
					</a>
					<div class="item">
						<div class="header">{{ trans('laralum.user_manager') }}</div>
						<div class="menu">
							<a href="{{ route('Laralum::users') }}" class="item">{{ trans('laralum.user_list') }}</a>
							<a href="{{ route('Laralum::users_create') }}" class="item">{{ trans('laralum.create_user') }}</a>
							<a href="{{ route('Laralum::users_settings') }}" class="item">{{ trans('laralum.users_settings') }}</a>
						</div>
					</div>
					<div class="item">
						<div class="header">{{ trans('laralum.role_manager') }}</div>
						<div class="menu">
							<a href="{{ route('Laralum::roles') }}" class="item">{{ trans('laralum.role_list') }}</a>
							<a href="{{ route('Laralum::roles_create') }}" class="item">{{ trans('laralum.create_role') }}</a>
						</div>
					</div>
					<div class="item">
						<div class="header">{{ trans('laralum.permission_manager') }}</div>
						<div class="menu">
							<a href="{{ route('Laralum::permissions') }}" class="item">{{ trans('laralum.permission_list') }}</a>
							<a href="{{ route('Laralum::permissions_create') }}" class="item">{{ trans('laralum.create_permission') }}</a>
						</div>
					</div>
					<div class="item">
						<div class="header">{{ trans('laralum.blog_manager') }}</div>
						<div class="menu">
							<a href="{{ route('Laralum::blogs') }}" class="item">{{ trans('laralum.blog_list') }}</a>
							<a href="{{ route('Laralum::blogs_create') }}" class="item">{{ trans('laralum.create_blog') }}</a>
						</div>
					</div>
					<div class="item">
						<div class="header">{{ trans('laralum.file_manager') }}</div>
						<div class="menu">
							<a href="{{ route('Laralum::files') }}" class="item">{{ trans('laralum.file_document_list') }}</a>
							<a href="{{ route('Laralum::files_upload') }}" class="item">{{ trans('laralum.upload_file') }}</a>
						</div>
					</div>
					<div class="item">
						<div class="header">{{ trans('laralum.developer_tools') }}</div>
						<div class="menu">
							<a href="{{ route('Laralum::CRUD') }}" class="item">{{ trans('laralum.database_CRUD') }}</a>
						</div>
					</div>
					<div class="item">
						<div class="header">{{ trans('laralum.settings') }}</div>
						<div class="menu">
							<a href="{{ route('Laralum::settings') }}" class="item">{{ trans('laralum.general_settings') }}</a>
						</div>
					</div>
					<div class="item">
						<div class="header">Laralum</div>
						<div class="menu">
							<a href="{{ route('Laralum::about') }}" class="item">{{ trans('laralum.about') }}</a>
						</div>
					</div>
				</div>
		</header>
	</div>




	<div class="ui top fixed menu"  id="menu-div">
		<div class="item" id="menu">
			<div class="ui secondary button"><i class="bars icon"></i> {{ trans('laralum.menu') }}</div>
		</div>
		<div class="item" id="breadcrumb" style="margin-left: 210px !important;" >
			@yield('breadcrumb')
		</div>
		<div class="right menu">
			<div class="item">
				<div class="ui secondary top labeled icon left pointing dropdown button responsive-button">
				  <i class="globe icon"></i>
				  <span class="text responsive-text"> {{ trans('laralum.language') }}</span>
				  <div class="menu">
					@foreach(Laralum::locales() as $locale => $locale_info)
						@if($locale_info['enabled'])
							<a href="{{ route('Laralum::locale', ['locale' => $locale]) }}" class="item">
								@if($locale_info['type'] == 'image')
									<img class="ui image"  height="11" src="{{ $locale_info['type_data'] }}">
								@elseif($locale_info['type'] == 'flag')
									<i class="{{ $locale_info['type_data'] }} flag"></i>
								@endif
								{{ $locale_info['name'] }}
							</a>
						@endif
					@endforeach
				  </div>
				</div>
			</div>
			<div class="item">
				<div class="ui {{ Laralum::settings()->button_color }} top labeled icon left pointing dropdown button responsive-button">
				  <i class="user icon"></i>
				  <span class="text responsive-text">{{ Auth::user()->name }}</span>
				  <div class="menu">
				  	<a href="{{ route('Laralum::users_profile', ['id' => Laralum::loggedInUser()->id]) }}" class="item">
						{{ trans('laralum.profile') }}
  					</a>
					<a href="{{ url('/') }}" class="item">
						{{ trans('laralum.visit_site') }}
  					</a>
				  	<a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="item">
						{{ trans('laralum.logout') }}
  					</a>
				  </div>
				</div>
			</div>
		</div>
	</div>




	<div class="pusher back">
		<div class="menu-margin">
			<div class="content-title" style="background-color: {{ Laralum::settings()->header_color }};">
				<div class="menu-pusher">
					<div class="ui one column doubling stackable grid container">
						<div class="column">
							<h2 class="ui header">
								<i class="@yield('icon') icon white-text"></i>
								<div class="content white-text">
									@yield('title')
									<div class="sub header">
										<span class="white-text">@yield('subtitle')</span>
									</div>
								</div>
							</h2>
						</div>
					</div>
				</div>
			</div>






			    <div class="page-content">
					<div class="menu-pusher">
		      			@yield('content')
					</div>
				</div>
				<br><br>
				<div class="page-footer">
					<div class="ui bottom fixed padded segment">
						<div class="menu-pusher">
			      			<div class="ui container">
								<a href="{{ url('/') }}" class="ui tiny header">
									{{ Laralum::websiteTitle() }}
								</a>
									<?php
										$locales = Laralum::locales();
										if(Laralum::loggedInUser()->locale) {
											$locale = $locales[$locale];
										} else {
											$locale = $locales['en'];
										}
									 ?>
								-
								<a href="{{ $locale['website'] }}" class="ui tiny header">
									{{ trans('laralum.translated_by', ['author' => $locale['author']]) }}
								</a>
								<a class="ui tiny header right floated" href='https://github.com/ConsoleTVs/Laralum'>&copy; Copyright Laralum {{ Laralum::version() }}</a>
								<a class="ui tiny header right floated" href="https://erik.cat">Author</a>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>


	{!! Laralum::includeAssets('laralum_bottom') !!}

	@yield('js')

	<script>
		setInterval(function(){
			var footer = $('.page-footer');
			footer.removeAttr("style");
			var footerPosition = footer.position();
			var docHeight = $( document ).height();
			var winHeight = $( window ).height();
			if(winHeight == docHeight) {
				if((footerPosition.top + footer.height() + 3) < docHeight) {
					var topMargin = (docHeight - footer.height()) - footerPosition.top;
					footer.css({'margin-top' : topMargin + 'px'});
				}
			}
		}, 10);
	</script>

</body>
</html>
