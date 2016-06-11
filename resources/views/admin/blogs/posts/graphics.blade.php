@extends('layouts.admin.index')
@section('title', $post->name . ' Graphics')
@section('content')
	<a href="{{ url('admin/blogs', [$post->blog->id]) }}" class="btn btn-primary" role="button">Back</a><br><br>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
				<canvas id="countries"></canvas>
            </div>
        </div>
    </div>
	<div class="col-sm-12 col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
				<canvas id="views"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<?php
    $labels = [];
    foreach($post->views as $view){
        $add = true;
        foreach($labels as $label) {
            if($label == $view->country_code){
                $add = false;
            }
        }
        if($add) {
            array_push($labels,$view->country_code);
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
					data: [@foreach($labels as $label) {{ count(App\Post_View::where('country_code', $label)->get()) }}, @endforeach],
					backgroundColor: [@foreach($labels as $label) '{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}', @endforeach]
				}]
			@else
			labels: ['No Views'],
			datasets: [{
				data: [100],
				backgroundColor: ['#424242']
			}]
			@endif
        },
		options: {
			title: {
	            display: true,
	            text: 'Total post views on countries',
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
		count($post->views()->whereDate('created_at', '=', date("Y-m-d"))->get()),
		count($post->views()->whereDate('created_at', '=', date("Y-m-d", strtotime("-1 Day")))->get()),
		count($post->views()->whereDate('created_at', '=', date("Y-m-d", strtotime("-2 Day")))->get()),
		count($post->views()->whereDate('created_at', '=', date("Y-m-d", strtotime("-3 Day")))->get()),
		count($post->views()->whereDate('created_at', '=', date("Y-m-d", strtotime("-4 Day")))->get()),
	])
?>
<script>
	var ctx = document.getElementById("views");
	var data = {
	    labels: [@foreach($dates as $date) "{{ $date }}", @endforeach],
	    datasets: [
	        {
	            label: "Total Views",
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
	            text: 'Post views on the last 5 days',
				fontSize: 20,
	        },
			scales: {
	            yAxes: [{
	                ticks: {
						suggestedMax: {{ max($values) + ((10 * max($values)) / 100 ) }},
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
