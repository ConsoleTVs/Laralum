@extends('layouts.admin.index')
@section('title', "Edit User")
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
									$value = $user->$field;

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
		                                                    <option <?php if($value == array_search($country, $countries)){echo "selected";} ?> value="{{ array_search($country, $countries) }}">{{ $country }}</option>
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
											      <input  type="{{ $input_type }}" class="form-control" id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
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

									    </fieldset>

									    <br>
						<div class="form-group ">
							<a href="{{ url('admin/users') }}" class="btn btn-primary">back</a>
							<button type="submit" class="btn btn-primary pull-right">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-2 col-lg-4"></div>


	</div>

@endsection
