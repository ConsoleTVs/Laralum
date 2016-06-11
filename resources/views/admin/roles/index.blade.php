@extends('layouts.admin.index')
@section('title', 'Roles')
@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12">
							<center>
								<h3>{{ $default_role->name }}<small><br>Default User Role</small></h3>
							</center>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<canvas id="roles1"></canvas>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<canvas id="roles2"></canvas>
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
								<td class="text-center">
									{{ count($role->users) }} Users
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
@section('js')
<script>
    var ctx = document.getElementById("roles1");
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            @if(count($roles) > 0)
				labels: [@foreach($roles as $role) '{{ $role->name }}', @endforeach],
				datasets: [{
					data: [@foreach($roles as $role) {{ count($role->users) }}, @endforeach],
					backgroundColor: [@foreach($roles as $role) '@if($role->color){{ $role->color }}@else{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}@endif', @endforeach]
				}]
			@else
			labels: ['No Roles'],
			datasets: [{
				data: [100],
				backgroundColor: ['#424242']
			}]
			@endif
        },
		options: {
			title: {
	            display: true,
	            text: 'Total users per role',
				fontSize: 20,
	        }
		}
    });
</script>
<script>
    var ctx = document.getElementById("roles2");
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            @if(count($roles) > 0)
				labels: [@foreach($roles as $role) '{{ $role->name }}', @endforeach],
				datasets: [{
					data: [@foreach($roles as $role) {{ count($role->permissions) }}, @endforeach],
					backgroundColor: [@foreach($roles as $role) '@if($role->color){{ $role->color }}@else{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}@endif', @endforeach]
				}]
			@else
			labels: ['No Roles'],
			datasets: [{
				data: [100],
				backgroundColor: ['#424242']
			}]
			@endif
        },
		options: {
			title: {
	            display: true,
	            text: 'Total permissions per role',
				fontSize: 20,
	        }
		}
    });
</script>
@endsection
