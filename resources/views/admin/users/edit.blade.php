@extends('layouts.admin.index')
@section('title', "Edit User")
@section('content')
	<div class="row">


		<div class="col-md-2 col-lg-4"></div>
		<div class="col-md-8 col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<br>
					<center>
						<div style="display: none;" id="avatar-div" class="avatar-div">
							<?php $grav_url = "https://www.gravatar.com/avatar/".md5(strtolower(trim($row->email)))."?s=100";?>
							<img height="100" width="100" class="img-responsive img-circle" src="{!! $grav_url !!}">
						</div>
					<br>
						<h4>
							<span <?php if($row->banned){ echo "style='color: #f44336; text-decoration: line-through;'"; } ?> >{{ $row->email }}</span>
						</h4>
					</center>
					<br>
					<form method="POST">
						{{ csrf_field() }}


						@include('admin/forms/master')

									    <br>
						<div class="form-group ">
							<a href="{{ url('admin/users') }}" class="btn btn-primary">Back</a>
							<button type="submit" class="btn btn-primary pull-right">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-2 col-lg-4"></div>


	</div>

@endsection
