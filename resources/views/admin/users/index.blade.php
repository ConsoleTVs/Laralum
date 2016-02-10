@extends('layouts.admin.index')
@section('title', "Users")
@section('content')
	<div class="row">
		<div class="col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-4">
							<center>
								<?php $counter = 0; ?>
								@foreach($users as $user)
									<?php $counter++; ?>
								@endforeach
								<h3>{{ $counter }}<small><br>Total Users</small></h3>
							</center>
						</div>
						<div class="col-sm-4">
							<center>
								<?php $counter = 0; ?>
								@foreach($active_users as $user)
									<?php $counter++; ?>
								@endforeach
								<h3>{{ $counter }}<small><br>Active Users</small></h3>
							</center>
						</div>
						<div class="col-sm-4">
							<center>
								<?php $counter = 0; ?>
								@foreach($banned_users as $user)
									<?php $counter++; ?>
								@endforeach
								<h3>{{ $counter }}<small><br>Banned Users</small></h3>
							</center>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<h4>Newest Member</h4><hr>
					<div class="row">
						<div class="col-md-4 small-spacer">
							<center>
								<?php $grav_url = "https://www.gravatar.com/avatar/".md5(strtolower(trim($latest_user->email)))."?s=100";?>
								<img data-toggle="tooltip" data-placement="bottom" title="" data-original-title="{{ $latest_user->name }}" height="100" width="100" class="img-responsive img-circle" src="{!! $grav_url !!}">
							</center>
						</div>
						<div class="col-md-8 small-spacer">
							<center>
								<h4>{{ $latest_user->name }}</h4>
								<h5>{{ $latest_user->email }}</h5>
							</center>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<h4>Newly Registered Members</h4><hr>
					<div class="row" >
						<?php $counter = 6 ?>
						@foreach($latest_users as $user)
							<div class="col-xs-4 small-spacer">
								<center>
									<?php $grav_url = "https://www.gravatar.com/avatar/".md5(strtolower(trim($user->email)))."?s=100";?>
									<img id="avatar-div" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="{{ $user->name }}" height="100" width="100" class="img-responsive img-circle" src="{!! $grav_url !!}">
								</center>
							</div>
							<?php $counter--; ?>
						@endforeach
						@while($counter > 0)
							<div class="col-xs-4 small-spacer">
								<center>
									<img height="100" width="100" class="img-responsive img-circle lower-opacity" src="{{ asset('admin_panel/img/avatar.jpg') }}">
								</center>
							</div>
							<?php $counter--; ?>
						@endwhile
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="panel panel-default">
			  <div class="panel-body">
			  	<h4>
			  		Members List
			  		<div class="pull-right">
		  				<a href="{{ url('admin/users/settings') }}" class="btn btn-primary">
			  				Settings
				  		</a>
				  		<a href="{{ url('admin/users/create') }}" class="btn btn-primary ">
				  			New User
				  		</a>
			  		</div>
			  	</h4><hr>


			  	<div class="table-responsive">
				  <table class="table table-striped table-hover center">
				    <thead>
				      <tr>
				        <th class="text-center">#</th>
				        <th class="text-center">Avatar</th>
				        <th class="text-center">Name</th>
				        <th class="text-center">Email</th>
				        <th class="text-center">Country</th>
				        <th class="text-center">Roles</th>
						<th class="text-center">Edit</th>
						<th class="text-center">Profile</th>
						<th class="text-center">Delete</th>
				      </tr>
				    </thead>
				    <tbody>
						@foreach($users as $user)
							<tr 

							<?php

								if($user->banned){
									echo "style='color: #f44336; text-decoration: line-through;'";
								} elseif(!$user->active) {
									echo "style='color: #ff9800;'";
								}
								

							?> 

							>
								<td class="text-center">{{ $user->id }}</td>
								<td>
									<center>
										<?php $grav_url = "https://www.gravatar.com/avatar/".md5(strtolower(trim($user->email)))."?s=25";?>
										<img id="avatar-div" class="img-responsive img-circle" src="{!! $grav_url !!}">
									</center>
								</td>
								<td class="text-center">{{ $user->name }}</td>
								<td class="text-center">{{ $user->email }}</td>
								<td class="text-center">{{ $user->country_code }}</td>
								<td class="text-center">
									<a href="{{ url('admin/users', [$user->id, 'roles']) }}" class="btn btn-primary btn-sm">Roles</a>
								</td>
								<td class="text-center">
									<a href="{{ url('admin/users', [$user->id, 'edit']) }}" class="btn btn-primary btn-sm">Edit</a>
								</td>
								<td class="text-center">
									<a href="{{ url('admin/users', $user->id) }}" class="btn btn-primary btn-sm">Profile</a>
								</td>
								<td class="text-center">
									@if($user->su)
										<a disabled class="btn btn-danger btn-sm">Delete</a>
									@else
										<a href="{{ url('admin/users', [$user->id, 'delete']) }}" class="btn btn-danger btn-sm">Delete</a>
									@endif
								</td>
							</tr>
						@endforeach
				    </tbody>
				  </table>
				  </div>
			  </div>
			</div>

		</div>
	</div>
</div>



@endsection