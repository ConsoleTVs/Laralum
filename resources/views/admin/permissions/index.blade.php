@extends('layouts.admin.index')
@section('title', 'Permissions')
@section('content')
	<div class="row">
		<div class="col-md-5">
			<div class="panel panel-default">
			  <div class="panel-body">
			  	<h4>
			  		Permission List
			  		<div class="pull-right">
				  		<a href="{{ url('admin/permissions/types/create') }}" class="btn btn-primary ">
				  			New Permission Type
				  		</a>
			  		</div>
			  	</h4><hr>


			  	<div class="table-responsive">
				  <table class="table table-striped table-hover center">
				    <thead>
				      <tr>
				        <th class="text-center">#</th>
				        <th class="text-center">Type</th>
				        <th class="text-center">Permissions</th>
						<th class="text-center">Edit</th>
						<th class="text-center">Delete</th>
				      </tr>
				    </thead>
				    <tbody>
						@foreach($types as $type)
							<tr>
								<td class="text-center">{{ $type->id }}</td>
								<td class="text-center">{{ $type->type }}</td>
								<td class="text-center">
									<?php $counter = 0; ?>
									@foreach($type->permissions as $perm)
										<?php $counter++; ?>
									@endforeach
									{{ $counter }} Permissions
								</td>
								<td class="text-center">
									<a href="{{ url('admin/permissions/types', [$type->id, 'edit']) }}" class="btn btn-primary btn-sm">Edit</a>
								</td>
								<td class="text-center">
									@if($type->su)
										<a disabled class="btn btn-danger btn-sm">Delete</a>
									@else
										<a href="{{ url('admin/permissions/types', [$type->id, 'delete']) }}" class="btn btn-danger btn-sm">Delete</a>
									@endif
								</td>
							</tr>
						@endforeach
				    </tbody>
				  </table>
				  </div>
			  </div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<canvas id="types"></canvas>
				</div>
			</div>
		</div>
		<div class="col-md-7">
			<div class="panel panel-default">
			  <div class="panel-body">
			  	<h4>
			  		Permission List
			  		<div class="pull-right">
				  		<a href="{{ url('admin/permissions/create') }}" class="btn btn-primary ">
				  			New Permission
				  		</a>
			  		</div>
			  	</h4><hr>


			  	<div class="table-responsive">
				  <table class="table table-striped table-hover center">
				    <thead>
				      <tr>
				        <th class="text-center">#</th>
				        <th class="text-center">Name</th>
				        <th class="text-center">Slug</th>
				        <th class="text-center">Roles</th>
						<th class="text-center">Edit</th>
						<th class="text-center">Summary</th>
						<th class="text-center">Delete</th>
				      </tr>
				    </thead>
				    <tbody>
						@foreach($permissions as $perm)
							<tr>
								<td class="text-center">{{ $perm->id }}</td>
								<td class="text-center">{{ $perm->name }}</td>
								<td class="text-center">{{ $perm->slug }}</td>
								<td class="text-center">
									<?php $counter = 0; ?>
									@foreach($perm->roles as $role)
										<?php $counter++; ?>
									@endforeach
									{{ $counter }} Roles
								</td>
								<td class="text-center">
									<a href="{{ url('admin/permissions', [$perm->id, 'edit']) }}" class="btn btn-primary btn-sm">Edit</a>
								</td>
								<td class="text-center">
									<a href="{{ url('admin/permissions', $perm->id) }}" class="btn btn-primary btn-sm">Summary</a>
								</td>
								<td class="text-center">
									@if($perm->su)
										<a disabled class="btn btn-danger btn-sm">Delete</a>
									@else
										<a href="{{ url('admin/permissions', [$perm->id, 'delete']) }}" class="btn btn-danger btn-sm">Delete</a>
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
    var ctx = document.getElementById("types");
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            @if(count($types) > 0)
				labels: [@foreach($types as $type) '{{ $type->type }}', @endforeach],
				datasets: [{
					data: [@foreach($types as $type) {{ count($type->permissions) }}, @endforeach],
					backgroundColor: [@foreach($types as $type) '@if($type->color){{ $type->color }}@else{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}@endif', @endforeach]
				}]
			@else
			labels: ['No Types'],
			datasets: [{
				data: [100],
				backgroundColor: ['#424242']
			}]
			@endif
        },
		options: {
			title: {
	            display: true,
	            text: 'Total permissions per type',
				fontSize: 20,
	        }
		}
    });
</script>
@endsection
