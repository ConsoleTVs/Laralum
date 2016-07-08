@extends('layouts.admin.index')
@section('title', "Create Document")
@section('content')
	<div class="row">

		<div class="col-md-2 col-lg-4"></div>
		<div class="col-md-8 col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<br>
					<center>
						<h3>
							<span>Edit Document ({{ $file->name }})</span>
						</h3>
					</center>
					<br>
					<form method="POST">
						{{ csrf_field() }}

                        @include('admin/forms/master')

					    <br>
						<div class="form-group ">
							<a href="{{ url('admin/files') }}" class="btn btn-primary">Back</a>
							<button type="submit" class="btn btn-primary pull-right">Save Changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-2 col-lg-4"></div>


	</div>

@endsection
