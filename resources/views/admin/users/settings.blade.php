@extends('layouts.admin.index')
@section('title', "Edit Users Settings")
@section('content')
	<div class="row">


		<div class="col-md-2 col-lg-4"></div>
		<div class="col-md-8 col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
						<h4>
							Edit Users Settings
						</h4>
					<hr>
					<form method="POST">
						{{ csrf_field() }}

						<div class="row">
							<div class="col-sm-5 col-sm-offset-1">
								<div class="checkbox">
						          <label>
						            <input <?php if($settings->welcome_email) { echo "checked='checked'"; } ?> name="welcome_email" type="checkbox"> Send Welcome Email
						          </label>
						        </div>
								<div class="checkbox">
						          <label>
						            <input <?php if($settings->register_enabled) { echo "checked='checked'"; } ?> name="register_enabled" type="checkbox"> Allow Registrations
						          </label>
						        </div>
							</div>
							<div class="col-sm-5">
								<label for="default_role" class="control-label">Default User Role</label>
								<select class="form-control" name="default_role" id="default_role">
									@foreach($roles as $role)
										<option <?php if($role->id == $settings->default_role){ echo "selected"; } ?> value="{{ $role->id }}">{{ $role->name }}</option>
									@endforeach
								</select>
								<br>
								<label for="default_active" class="control-label">Default Activation Method</label>
								<select class="form-control" name="default_active" id="default_active">
									<option <?php if($settings->default_active == 0){ echo "selected"; } ?> value="0">Not activated</option>
									<option <?php if($settings->default_active == 1){ echo "selected"; } ?> value="1">Sends activation email</option>
									<option <?php if($settings->default_active == 2){ echo "selected"; } ?> value="2">Activated</option>
								</select>
							</div>
							<div class="col-sm-12 row-spacer">
								<a href="{{ url('admin/users') }}" class="btn btn-primary">back</a>
								<button type="submit" class="btn btn-primary pull-right">Save changes</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-2 col-lg-4"></div>


	</div>

@endsection
