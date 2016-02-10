@extends('layouts.admin.index')
@section('title', "Create Permission")
@section('content')
	<div class="row">


		<div class="col-md-2 col-lg-4"></div>
		<div class="col-md-8 col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<br>
					<center>
						<h3>
							<span>Create permission</span>
						</h3>
					</center>
					<br>
					<form method="POST">
						{{ csrf_field() }}
						<fieldset>
					      <div class="row down-spacer">
					        <div class="lateral-spacer">
					        	<label for="name" class="col-lg-2 control-label">Name</label>
						        <div class="col-lg-10">
						          <input required type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ old('name') }}">
						        </div>
					        </div>
					      </div>
						  <div class="row down-spacer">
						  	<div class="lateral-spacer">
						        <label for="slug" class="col-lg-2 control-label">Slug</label>
						        <div class="col-lg-10">
						          <input required type="text" class="form-control" name="slug" id="slug" placeholder="Example: admin.all" value="{{ old('slug') }}">
						        </div>
					        </div>
					      </div>
					      <div class="row down-spacer">
						      <div class="lateral-spacer">
						        <label for="info" class="col-lg-2 control-label">Information</label>
						        <div class="col-lg-10">
						          <textarea placeholder="Permission information" name="info" class="form-control" rows="3" id="info">{{ old('info') }}</textarea>
						        </div>
					        </div>
					      </div>
					      <div class="row down-spacer">
					      	<div class="lateral-spacer">
						        <label for="type_id" class="col-lg-2 control-label">Type</label>
						        <div class="col-lg-10">
									<select name="type_id" class="form-control" id="type_id">
										<option disabled selected value="">Select the permission type</option>
										<option value="0">Other</option>
										@foreach($types as $type)
											<option value="{{ $type->id }}">{{ $type->type }}</option>
										@endforeach
									</select>
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
