@extends('layouts.admin.index')
@section('title', "Confirmattion Page")
@section('css')
<style>
	#loading-bar {
	    -webkit-transition: none !important;
	    transition: none !important;
	}
</style>
@endsection
@section('content')

	<div class="row">


		<div class="col-md-2 col-lg-4"></div>
		<div class="col-md-8 col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<h2>Confirmation Page</h2>
					<h4>Please confirm this action</h4>
					<hr>
					<h5>What is this page?</h5>
					<p>This page is a confirmation page to avoid things you might not wanted to do, or just to let you know
					this action can not be undone and so it requires a bit more than a single click to go ahead with it.</p>
					<form method="POST">
						{{ csrf_field() }}
						<div class="form-group ">
							<div class="col-xs-6">
								<a href="{{ URL::previous() }}" class="btn btn-primary">back</a>
							</div>
							<div class="col-xs-6" id="loading-div" style="margin-top: 15px;">
								<div class="progress active">
									<div id="loading-bar" class="progress-bar" style="width: 0%;"></div>
								</div>
							</div>
							<div class="col-xs-6" id="continue-div" style="display: none;">
								<button type="submit" class="btn btn-danger pull-right">Continue</button>
							</div>
						</div><br><br>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-2 col-lg-4"></div>


	</div>

@endsection
@section('js')
<script>
	$(function () {
        $("#loading-bar").animate({
		    width: "100%"
		}, 3000, function(){
			$("#loading-div").fadeOut(250, function() {
				$("#continue-div").fadeIn(500);
			})
		});
      })
</script>
@endsection