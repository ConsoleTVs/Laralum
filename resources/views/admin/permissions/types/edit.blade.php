@extends('layouts.admin.index')
@section('title', "Edit Permission Type")
@section('content')
	<div class="row">


		<div class="col-md-2 col-lg-4"></div>
		<div class="col-md-8 col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<br>
					<center>
						<h3>
							<span>{{ $type->type }}</span>
						</h3>
					</center>
					<br>
					<form method="POST">
						{{ csrf_field() }}
						<fieldset>
					      <div class="row down-spacer">
					        <div class="lateral-spacer">
					        	<label for="type" class="col-lg-2 control-label">Type</label>
						        <div class="col-lg-10">
						          <input required type="text" class="form-control" id="type" name="type" placeholder="Type" value="{{ $type->type }}">
						        </div>
					        </div>
					      </div>		  
					   	</fieldset>
					    <br>
						<div class="form-group ">
							<a href="{{ url('admin/permissions') }}" class="btn btn-primary">Back</a>
							<button type="submit" class="btn btn-primary pull-right">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-2 col-lg-4"></div>


	</div>

@endsection
