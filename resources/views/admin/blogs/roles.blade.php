@extends('layouts.admin.index')
@section('title', "Edit Post Roles")
@section('content')
	<div class="row">


		<div class="col-md-2 col-lg-4"></div>
		<div class="col-md-8 col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<br>
                    <center>
						<h3>
							<span>{{ $blog->name }} Roles</span>
						</h3>
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
													if($blog->has($role->id)) {
														echo "checked='checked' ";
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
								<a href="{{ url('admin/blogs') }}" class="btn btn-primary">Back</a>
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
