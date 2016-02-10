@extends('layouts.admin.index')
@section('title', "Edit Role")
@section('content')
	<div class="row">


		<div class="col-md-2 col-lg-4"></div>
		<div class="col-md-8 col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<br>
					<center>
						<h3>
							<span>{{ $role->name }}</span>
						</h3>
					</center>
					<br>
					<form method="POST">
						{{ csrf_field() }}
						<fieldset>

							@if(!$fields)
								<div class="row">
									<div class="lateral-spacer">
								        <div class="col-lg-12">
											<p>There aren't any available fields to edit</p>
								        </div>
							        </div>
							    </div>
							@endif


							@foreach($fields as $field)
							<?php
								# Setup the value
								$empty_value = false;
								foreach($empty as $emp) {
									if($field == $emp) {
										$empty_value = true;
									}
								}
								if($empty_value) {
									$value = "";
								} else {
									$value = $role->$field;

									# Check if the value needs to be decrypted
									$decrypt = false;
									foreach($encrypted as $encrypt) {
										if($field == $encrypt) {
											$decrypt = true;
										}
									}
									if($decrypt) {
										$value = Crypt::decrypt($value);
									}

									# Check if it's a hashed value, to display it empty
									$hashed_value = false;
									foreach($hashed as $hash) {
										if($field == $hash) {
											$hashed_value = true;
										}
									}
									if($hashed_value) {
										$value = "";
									}
								}

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
										      <input  type="{{ $input_type }}" class="form-control" id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
										    </div>
										</div>
									</div>

								@elseif($type == 'integer')

									<!-- INTEGER COLUMN -->
									<div class="row down-spacer">
										<div class="lateral-spacer">
											<label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
										    <div class="col-lg-10">
										      <input type="{{ $input_type }}" class="form-control" id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
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
														<input <?php if($value){ echo "checked='checked'"; } ?> name="{{ $field }}" type="checkbox"> {{ $show_field }}
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
										      <textarea placeholder="{{ $show_field }}" name="{{ $field }}" class="form-control" rows="3" id="{{ $field }}">{{ $value }}</textarea>
										    </div>
										</div>
									</div>

								@else

									<!-- ALL OTHER COLUMN -->
									<div class="row down-spacer">
										<div class="lateral-spacer">
											<label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
										    <div class="col-lg-10">
										      <input  type="{{ $input_type }}" class="form-control" id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
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
											      <input type="{{ $input_type }}" class="form-control" id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="{{ $value }}">
											    </div>
											</div>
										</div>

									@elseif($type == 'integer')

										<!-- INTEGER COLUMN CONFIRMATION -->
										<div class="row down-spacer">
											<div class="lateral-spacer">
												<label for="{{ $field }}_confirmation" class="col-lg-2 control-label">{{ $show_field }}</label>
											    <div class="col-lg-10">
											      <input type="{{ $input_type }}" class="form-control" id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="{{ $value }}">
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
															<input <?php if($value){ echo "checked='checked'"; } ?> name="{{ $field }}_confirmation" type="checkbox"> {{ $show_field }} confirmation
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
											      <textarea placeholder="{{ $show_field }}" name="{{ $field }}_confirmation" class="form-control" rows="3" id="{{ $field }}_confirmation">{{ $value }}</textarea>
											    </div>
											</div>
										</div>

									@else

									<!-- ALL OTHER COLUMN CONFIRMATION -->
									<div class="row down-spacer">
										<div class="lateral-spacer">
											<label for="{{ $field }}_confirmation" class="col-lg-2 control-label">{{ $show_field }}</label>
										    <div class="col-lg-10">
										      <input  type="{{ $input_type }}" class="form-control" id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="{{ $value }}">
										    </div>
										</div>
									</div>

									@endif

								@endif
							@endforeach
						@endforeach




						<!--
					      <div class="form-group">
					        <label for="name" class="col-lg-2 control-label">Name</label>
					        <div class="col-lg-10">
					          <input required type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ $role->name }}">
					        </div>
					      </div>
						  <br><br>
						  <div class="form-group">
					        <label for="color" class="col-lg-2 control-label">Color</label>
					        <div class="col-lg-10">
					          <input required type="text" class="form-control" name="color" id="color" placeholder="Color" value="{{ $role->color }}">
					        </div>
					      </div>	
						-->


					   	</fieldset>
					    <br>
						<div class="form-group ">
							<a href="{{ url('admin/roles') }}" class="btn btn-primary">Back</a>
							<button type="submit" class="btn btn-primary pull-right">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-2 col-lg-4"></div>


	</div>

@endsection
