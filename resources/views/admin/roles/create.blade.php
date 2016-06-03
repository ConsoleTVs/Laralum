@extends('layouts.admin.index')
@section('title', "Create Role")
@section('content')
	<div class="row">
		<form method="POST">
			{{ csrf_field() }}

		<div class="col-md-12">
			<div class="form-group ">
				<a href="{{ url('admin/roles') }}" class="btn btn-primary">back</a>
				<button type="submit" class="btn btn-primary pull-right">Create Role</button>
			</div><br>
		</div>

		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-body">

				<h4>Basic Information</h4>
				<hr>

			@include('admin/forms/master')

				</div>
			</div>
		</div>


		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-body">
					<h4>Role permissions</h4>
					<hr>
					<div class="lateral-spacer">
					    	@foreach($types as $type)
						    		<div class="row">
							    		<div class="col-md-12">
							    			<h4>{{ $type->type }}</h4>
							    		</div>
							    	<?php $found = false ?>
						        	@foreach($type->permissions as $perm)
						        		<?php $found = true ?>
						        		<div class="col-md-6 col-lg-4 down-spacer">
							          		<div class="checkbox">
												<label>
													<input name="{{ $perm->id }}" type="checkbox">{{ $perm->name }}
												</label>
											</div>
											<i>{{ $perm->info }}</i>
							        	</div>
						        	@endforeach
						        	@if(!$found)
						        		<div class="col-md-6 col-lg-4 down-spacer">
						        			<p>No permissions found</p>
					        			</div>
						        	@endif
						        </div>
					        @endforeach
				    		<div class="row">
					    		<div class="col-md-12">
					    			<h4>Other</h4>
					    		</div>
					    		<?php $found = false ?>
					        	@foreach($untyped as $perm)
					        		<?php $found = true ?>
					        		<div class="col-md-6 col-lg-4 down-spacer">
						          		<div class="checkbox">
											<label>
												<input name="{{ $perm->id }}" type="checkbox">{{ $perm->name }}
											</label>
										</div>
										<i>{{ $perm->info }}</i>
						        	</div>
					        	@endforeach

					        	@if(!$found)
					        		<div class="col-md-6 col-lg-4 down-spacer">
					        			<p>No more permissions found</p>
				        			</div>
					        	@endif

					        </div>
			        	</div>
		        </div>
	        </div>
		</div>

</form>


	</div>

@endsection
