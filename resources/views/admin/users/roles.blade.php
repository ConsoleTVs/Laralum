@extends('layouts.admin.index')
@section('title', "Edit Roles")
@section('content')
	<div class="row">


		<div class="col-md-2 col-lg-4"></div>
		<div class="col-md-8 col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<br>
					<center>
						<div style="display: none;" id="avatar-div" class="avatar-div">
							<?php $grav_url = "https://www.gravatar.com/avatar/".md5(strtolower(trim($user->email)))."?s=100";?>
							<img height="100" width="100" class="img-responsive img-circle" src="{!! $grav_url !!}">
						</div>
					<br>
						<h4>
							<span <?php if($user->banned){ echo "style='color: #f44336; text-decoration: line-through;'"; } ?> >{{ $user->email }}</span>
						</h4>
					</center>
					<br>
					<form method="POST">
						{{ csrf_field() }}
						<div class="row">
							<div class="lateral-spacer">
						    	@foreach($roles as $role)
						        	<div class="col-sm-6">
						          		<div class="checkbox">
											<label>
												<input


												<?php
													if($user->is($role->name)) {
														echo "checked='checked' ";
													}

													if($user->su) {
														if($role->su) {
															echo "disabled";
														}
													}
												?>


												name="{{ $role->id }}" type="checkbox"> {{ $role->name }}
											</label>
										</div>
						        	</div>
						        @endforeach
					        </div>
						</div>
						<div class="row row-spacer">
							<div class="col-md-12">
								<a href="{{ url('admin/users') }}" class="btn btn-primary">Back</a>
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
