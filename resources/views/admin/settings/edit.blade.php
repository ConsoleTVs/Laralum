@extends('layouts.admin.index')
@section('title', "Settings")
@section('content')
	<div class="row">


		<div class="col-md-3 col-lg-3"></div>
		<div class="col-md-6 col-lg-6">
			<div class="panel panel-default">
				<div class="panel-body">
					<br>
					<center>
						<h3>
							<span>Edit Settings</span>
						</h3>
					</center>
					<br>
					<form method="POST">
						{{ csrf_field() }}

						@include('admin/forms/master')

					   	</fieldset>
					    <br>
						<div class="form-group ">
							<button type="submit" class="btn btn-primary pull-right">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-lg-3"></div>


	</div>

@endsection
