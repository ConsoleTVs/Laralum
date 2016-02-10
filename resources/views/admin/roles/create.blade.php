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

			<fieldset>





			@foreach($fields as $field)
							<?php

								$show_field = str_replace('_', ' ', ucfirst($field));

								$type = Schema::getColumnType('roles', $field);

								# Set the input type
								if($type == 'string') {
									$input_type = "text";
								} elseif($type == 'integer') {
									$input_type = "number";
								}

								# Check if it needs to be masked
								foreach($masked as $mask) {
									if($mask == $field) {
										$input_type = "password";
									}
								}

							?>
							@if($type == 'string')
						     
						        	<!-- STRING COLUMN -->
									<div class="row down-spacer">
										<div class="lateral-spacer">
											<label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
										    <div class="col-lg-10">
										      <input  type="{{ $input_type }}" class="form-control" id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="">
										    </div>
										</div>
									</div>


								@elseif($type == 'integer')

									<!-- INTEGER COLUMN -->
									<div class="row down-spacer">
										<div class="lateral-spacer">
											<label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
										    <div class="col-lg-10">
										      <input type="{{ $input_type }}" class="form-control" id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="">
										    </div>
										</div>
									</div>

								@elseif($type == 'boolean')

									<!-- BOOLEAN COLUMN -->
									<div class="row little-down-spacer">
										<div class="lateral-spacer">
											<label for="bio" class="col-md-2 control-label">{{ $show_field }}</label>
										    <div class="col-md-10">
												<div class="checkbox">
													<label>
														<input name="{{ $field }}" type="checkbox"> {{ $show_field }}
													</label>
												</div>
										    </div>
										</div>
									</div>

								@elseif($type == 'text')

									<!-- TEXT COLUMN -->
									<div class="row down-spacer">
										<div class="lateral-spacer">
											<label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
										    <div class="col-lg-10">
										      <textarea placeholder="{{ $show_field }}" name="{{ $field }}" class="form-control" rows="3" id="{{ $field }}"></textarea>
										    </div>
										</div>
									</div>

								@else

									<!-- ALL OTHER COLUMN -->
									<div class="row down-spacer">
										<div class="lateral-spacer">
											<label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
										    <div class="col-lg-10">
										      <input  type="{{ $input_type }}" class="form-control" id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="">
										    </div>
										</div>
									</div>

								@endif
							@foreach($confirmed as $confirm)
								@if($field == $confirm)
									@if($type == 'string')

										<!-- STRING CONFIRMATION CONFIRMATION -->
										<div class="row down-spacer">
											<div class="lateral-spacer">
												<label for="{{ $field }}_confirmation" class="col-lg-2 control-label">{{ $show_field }}</label>
											    <div class="col-lg-10">
											      <input type="{{ $input_type }}" class="form-control" id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="">
											    </div>
											</div>
										</div>

									@elseif($type == 'integer')

										<!-- INTEGER COLUMN CONFIRMATION -->
										<div class="row down-spacer">
											<div class="lateral-spacer">
												<label for="{{ $field }}_confirmation" class="col-lg-2 control-label">{{ $show_field }}</label>
											    <div class="col-lg-10">
											      <input type="{{ $input_type }}" class="form-control" id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="">
											    </div>
											</div>
										</div>

									@elseif($type == 'boolean')
									
										<!-- BOOLEAN CONFIRMATION -->
										<div class="row little-down-spacer">
											<div class="lateral-spacer">
												<label for="bio" class="col-md-2 control-label">{{ $show_field }}</label>
											    <div class="col-md-10">
													<div class="checkbox">
														<label>
															<input name="{{ $field }}_confirmation" type="checkbox"> {{ $show_field }} confirmation
														</label>
													</div>
											    </div>
											</div>
										</div>

									@elseif($type == 'text')

										<!-- TEXT COLUMN -->
										<div class="row down-spacer">
											<div class="lateral-spacer">
												<label for="{{ $field }}_confirmation" class="col-lg-2 control-label">{{ $show_field }}</label>
											    <div class="col-lg-10">
											      <textarea placeholder="{{ $show_field }}" name="{{ $field }}_confirmation" class="form-control" rows="3" id="{{ $field }}_confirmation"></textarea>
											    </div>
											</div>
										</div>

									@else

									<!-- ALL OTHER COLUMN CONFIRMATION -->
									<div class="row down-spacer">
										<div class="lateral-spacer">
											<label for="{{ $field }}_confirmation" class="col-lg-2 control-label">{{ $show_field }}</label>
										    <div class="col-lg-10">
										      <input  type="{{ $input_type }}" class="form-control" id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="">
										    </div>
										</div>
									</div>

									@endif

								@endif
							@endforeach
						@endforeach



		    </fieldset>
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
