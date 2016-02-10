@extends('layouts.admin.index')
@section('title', $user->name)
@section('content')

	<div class="row">


		<div class="col-md-2 col-lg-4"></div>
		<div class="col-md-8 col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
					@if(!$user->active)
						<center>
						  <p style='color: #ff9800;'>This user has is not yet activated</p>
						</center>
					@endif
					@if($user->banned)
						<center>
						  <p style='color: #f44336;'>This user has been banned</p>
						</center>
					@endif
					<br>
					<center>
						<div style="display: none;" id="avatar-div" class="avatar-div">
							<?php $grav_url = "https://www.gravatar.com/avatar/".md5(strtolower(trim($user->email)))."?s=100";?>
							<img height="100" width="100" class="img-responsive img-circle" src="{!! $grav_url !!}">
						</div>
						<h3>
							<span <?php if($user->banned){ echo "style='color: #f44336; text-decoration: line-through;'"; } ?> >{{ $user->name }}</span>
						</h3>
						<h4>
							<span>{{ $user->email }}</span>
						</h4>
                        <?php
                            $json = file_get_contents('admin_panel/assets/countries/names.json');
                            $countries = json_decode($json, true);
                        ?>
                        @foreach($countries as $country)
                            @if($user->country_code == array_search($country, $countries))
                            	<p>{{ $country }}
                            	
                            @endif
                        @endforeach
					</center>
					<a href="{{ url('admin/users') }}" class="btn btn-primary">Back</a>
				</div>
			</div>
		</div>
		<div class="col-md-2 col-lg-4"></div>


	</div>

@endsection
