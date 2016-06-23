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
								<h3>{{ count($users) }}<small><br>Total Users</small></h3>
							</center>
						</div>
						<div class="col-sm-4">
							<center>
								<h3>{{ count($active_users) }}<small><br>Active Users</small></h3>
							</center>
						</div>
						<div class="col-sm-4">
							<center>
								<h3>{{ count($banned_users) }}<small><br>Banned Users</small></h3>
							</center>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<canvas id="new_users"></canvas>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<canvas id="countries"></canvas>
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
						<?php
						$json = file_get_contents('admin_panel/assets/countries/names.json');
					    $countries = json_decode($json, true);
						?>
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
								<td class="text-center">@if($user->country_code == 'FF')<i>Unknown</i>@else{{ $countries[$user->country_code] }}@endif</td>
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

@section('js')
<?php
    $labels = [];
    foreach($users as $user){
        $add = true;
        foreach($labels as $label) {
            if($label == $user->country_code){
                $add = false;
            }
        }
        if($add) {
            array_push($labels,$user->country_code);
        }
    }
    $json = file_get_contents('admin_panel/assets/countries/names.json');
    $countries = json_decode($json, true);
?>
<script>
    var ctx = document.getElementById("countries");
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            @if(count($labels) > 0)
				labels: [@foreach($labels as $label) '{{ $countries[$label] }}', @endforeach],
				datasets: [{
					data: [@foreach($labels as $label) {{ count(App\User::where('country_code', $label)->get()) }}, @endforeach],
					backgroundColor: [@foreach($labels as $label) '{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}', @endforeach]
				}]
			@else
			labels: ['No Users'],
			datasets: [{
				data: [100],
				backgroundColor: ['#424242']
			}]
			@endif
        },
		options: {
			title: {
	            display: true,
	            text: 'Total users per country',
				fontSize: 20,
	        }
		}
    });
</script>
<?php
	$dates = array_reverse([
		date("Y-m-d"),
		date("Y-m-d", strtotime("-1 Day")),
		date("Y-m-d", strtotime("-2 Day")),
		date("Y-m-d", strtotime("-3 Day")),
		date("Y-m-d", strtotime("-4 Day")),
	]);
	$values = array_reverse([
		count($user->whereDate('created_at', '=', date("Y-m-d"))->get()),
		count($user->whereDate('created_at', '=', date("Y-m-d", strtotime("-1 Day")))->get()),
		count($user->whereDate('created_at', '=', date("Y-m-d", strtotime("-2 Day")))->get()),
		count($user->whereDate('created_at', '=', date("Y-m-d", strtotime("-3 Day")))->get()),
		count($user->whereDate('created_at', '=', date("Y-m-d", strtotime("-4 Day")))->get()),
	])
?>
<script>
	var ctx = document.getElementById("new_users");
	var data = {
	    labels: [@foreach($dates as $date) "{{ $date }}", @endforeach],
	    datasets: [
	        {
	            label: "Total Users",
	            fill: true,
	            lineTension: 0,
	            backgroundColor: "rgba(33, 150, 243, 0.4)",
	            borderColor: "#2196f3",
	            borderCapStyle: 'butt',
	            borderDash: [],
	            borderDashOffset: 0.0,
	            borderJoinStyle: 'miter',
	            pointBorderColor: "rgba(75,192,192,1)",
	            pointBackgroundColor: "#fff",
	            pointBorderWidth: 1,
	            pointHoverRadius: 5,
	            pointHoverBackgroundColor: "rgba(75,192,192,1)",
	            pointHoverBorderColor: "rgba(220,220,220,1)",
	            pointHoverBorderWidth: 1,
	            pointRadius: 0,
	            pointHitRadius: 10,
	            data: [@foreach($values as $value) {{ $value  }}, @endforeach],
	        }
	    ]
	};

	var myLineChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			title: {
	            display: true,
	            text: 'New users on the last 5 days',
				fontSize: 20,
	        },
			scales: {
	            yAxes: [{
	                ticks: {
						stepSize: 1,
						padding: 20,
	                }
	            }],
				xAxes: [{
	                ticks: {

	                }
	            }]
        	}
	    }
	});
</script>
@endsection
