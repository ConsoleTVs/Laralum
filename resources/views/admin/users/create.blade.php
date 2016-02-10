@extends('layouts.admin.index')
@section('title', "Create User")
@section('content')
	<div class="row">
		<form method="POST">
			{{ csrf_field() }}

		<div class="col-md-12">
			<div class="form-group ">
				<a href="{{ url('admin/users') }}" class="btn btn-primary">back</a>
				<button type="submit" class="btn btn-primary pull-right">Create User</button>
			</div><br>
		</div>

		<div class="col-md-1"></div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-body">
				
				<h4>Basic Information</h4>
				<hr>

			<fieldset>





			@foreach($fields as $field)
							<?php

								$show_field = str_replace('_', ' ', ucfirst($field));

								$type = Schema::getColumnType('users', $field);

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

									@if($field == 'country_code')
									
										<!-- COUNTRY CODE COLUMN -->
										<div class="row down-spacer">
											<div class="lateral-spacer">
												<label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
										        <div class="col-lg-10">
													<select class="form-control" name="{{ $field }}" id="{{ $field }}">
		                                                <option disabled selected value="">Select the country</option>
		                                                <?php
		                                                    $json = file_get_contents('admin_panel/assets/countries/names.json');
		                                                    $countries = json_decode($json, true);
		                                                ?>
		                                                @foreach($countries as $country)
		                                                    <option value="{{ array_search($country, $countries) }}">{{ $country }}</option>
		                                                @endforeach
		                                            </select>
										        </div>
									        </div>
									    </div>

							        @else

							        	<!-- STRING COLUMN -->
										<div class="row down-spacer">
											<div class="lateral-spacer">
												<label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
											    <div class="col-lg-10">
											      <input  type="{{ $input_type }}" class="form-control" id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="">
											    </div>
											</div>
										</div>

									@endif

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


		<div class="col-md-2"></div>

		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<h4>User Roles</h4>
					<hr>
					<?php $counter = 0; ?>
			      		@foreach($roles as $role)
				        	<div class="col-md-6">
				          		<div class="checkbox">
									<label>
										<input name="{{ $role->id }}" type="checkbox"> {{ $role->name }}
									</label>
								</div>
				        	</div>
				        	<?php $counter++; ?>
				        @endforeach
				        @if($counter % 2 == 1)
				        	<div class="col-md-6" style="min-height: 43px;">&nbsp;</div>
				        @endif
		        </div>
	        </div>
	        		<br>
    		<div class="panel panel-default">
    			<div class="panel-body">
    				<h4>Additional Information</h4>
					<hr>
					<div class="col-md-6">
		          		<div class="checkbox">
							<label>
								<input checked='checked' id="active" name="active" type="checkbox"> Active User
							</label>
						</div>
		        	</div>
		        	<div class="col-md-6">
		          		<div class="checkbox">
							<label>
								<input id="send_activation" disabled name="send_activation" type="checkbox"> Send Activation Email
							</label>
						</div>
		        	</div>
		        	<div class="col-md-6">
		          		<div class="checkbox">
							<label>
								<input id="mail_checkbox" name="mail" type="checkbox"> Send Welcome Email
							</label>
						</div>
		        	</div>
		        	<div class="col-md-6">
		          		<div class="checkbox">
							<label>
								<input id="send_password" disabled name="send_password" type="checkbox"> Send Password
							</label>
						</div>
		        	</div>
		        </div>
    			</div>
    		</div>

</form>

		<div class="col-md-2 col-lg-1"></div>


	</div>

@endsection
@section('js')
<script>
	$('#mail_checkbox').on('change', function() { 
	    if (!this.checked) {
	    	$('#send_password').prop('checked', false);
	    	$('#send_password').prop("disabled", true );
	    } else {
	    	$('#send_password').prop("disabled", false );
	    }
	});
	$('#active').on('change', function() { 
	    if (!this.checked) {
	    	$('#send_activation').prop("disabled", false );
	    } else {
	    	$('#send_activation').prop('checked', false);
	    	$('#send_activation').prop("disabled", true );
	    }
	});
</script>
@endsection