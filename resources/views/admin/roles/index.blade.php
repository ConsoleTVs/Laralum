@extends('layouts.admin.index')
@section('title', 'Roles')
@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<h4>
						Default Users Role
					</h4>
					<hr>&nbsp;
					<div class="">
						<center>
							<h1>
								<span>{{ $default_role->name }}</span>
							</h1>
							<h4><small>{{ $default_role->color }}</small></h4>
							<br>
								<h4>
									<?php $counter = 0; ?>
									@foreach($default_role->users as $user)
										<?php $counter++; ?>
									@endforeach	
									{{ $counter }} Total Users
								</h4>
						</center>
					</div><br><br>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="panel panel-default">
			  <div class="panel-body">
			  	<h4>
			  		Role List
			  		<div class="pull-right">
				  		<a href="{{ url('admin/roles/create') }}" class="btn btn-primary ">
				  			New Role
				  		</a>
			  		</div>
			  	</h4><hr>


			  	<div class="table-responsive">
				  <table class="table table-striped table-hover center">
				    <thead>
				      <tr>
				        <th class="text-center">#</th>
				        <th class="text-center">Name</th>
				        <th class="text-center">Color</th>
				        <th class="text-center">Users</th>
				        <th class="text-center">Permissions</th>
						<th class="text-center">Edit</th>
						<th class="text-center">Summary</th>
						<th class="text-center">Delete</th>
				      </tr>
				    </thead>
				    <tbody>
						@foreach($roles as $role)
							<tr>
								<td class="text-center">{{ $role->id }}</td>
								<td class="text-center">{{ $role->name }}</td>
								<td class="text-center">{{ $role->color }}</td>
								<td class="text-center">
									<?php $counter = 0; ?>
									@foreach($role->users as $user)
										<?php $counter++; ?>
									@endforeach	
									{{ $counter }} Users
								</td>
								<td class="text-center">
									<a href="{{ url('admin/roles', [$role->id, 'permissions']) }}" class="btn btn-primary btn-sm">Permissions</a>
								</td>
								<td class="text-center">
									<a href="{{ url('admin/roles', [$role->id, 'edit']) }}" class="btn btn-primary btn-sm">Edit</a>
								</td>
								<td class="text-center">
									<a href="{{ url('admin/roles', $role->id) }}" class="btn btn-primary btn-sm">Summary</a>
								</td>
								<td class="text-center">
									@if($role->su)
										<a disabled class="btn btn-danger btn-sm">Delete</a>
									@else
										<a href="{{ url('admin/roles', [$role->id, 'delete']) }}" class="btn btn-danger btn-sm">Delete</a>
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
@endsection