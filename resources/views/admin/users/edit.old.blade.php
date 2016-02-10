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
									      <div class="form-group">
									        <label for="name" class="col-lg-2 control-label">Name</label>
									        <div class="col-lg-10">
									          <input required type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ $user->name }}">
									        </div>
									      </div>
										  <br><br>
										  <div class="form-group">
									        <label for="email" class="col-lg-2 control-label">Email</label>
									        <div class="col-lg-10">
									          <input required type="email" disabled class="form-control" name="email" id="email" placeholder="Email" value="{{ $user->email }}">
									        </div>
									      </div>
										  <br><br>
										  <div class="form-group">
									        <label for="password" class="col-lg-2 control-label">Password</label>
									        <div class="col-lg-10">
									          <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="">
									        </div>
									      </div>
										  <br><br>
										  <div class="form-group">
									        <label for="password_confirmation" class="col-lg-2 control-label">Repeat</label>
									        <div class="col-lg-10">
									          <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Repeat Password" value="">
									        </div>
									      </div>
										  <br><br>
										  <div class="form-group">
									        <div class="col-lg-10 col-lg-offset-2">
									          <p>If you leave password blank, the password won't be changed</p>
									        </div>
									      </div>
									      <br>
										  <div class="form-group">
									        <label for="country_code" class="col-lg-2 control-label">Country</label>
									        <div class="col-lg-10">
												<select required class="form-control" name="country_code" id="country_code">
	                                                <option disabled selected value="">Select the country</option>
	                                                <?php
	                                                    $json = file_get_contents('admin_panel/assets/countries/names.json');
	                                                    $countries = json_decode($json, true);
	                                                ?>
	                                                @foreach($countries as $country)
	                                                    <option <?php if($user->country_code == array_search($country, $countries)){echo "selected";} ?> value="{{ array_search($country, $countries) }}">{{ $country }}</option>
	                                                @endforeach
	                                            </select>
									        </div>
									      </div>
									      <br><br>
	  								      <div class="form-group">
									        <label for="phone" class="col-lg-2 control-label">Phone</label>
									        <div class="col-lg-10">
									          <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" value="{{ $user->phone }}">
									        </div>
									      </div>
										  <br><br>
										  <div class="form-group">
									        <label for="location" class="col-lg-2 control-label">Location</label>
									        <div class="col-lg-10">
									          <input type="text" class="form-control" name="location" id="location" placeholder="Location" value="{{ $user->location }}">
									        </div>
									      </div>
									      <br><br>
									      <div class="form-group">
									        <label for="bio" class="col-lg-2 control-label">Biography</label>
									        <div class="col-lg-10">
									          <textarea class="form-control" placeholder="Biography" rows="3" name="bio" id="bio">{{ $user->bio }}</textarea>
									        </div>
									      </div>
									      <br><br><br><br>
									      <div class="form-group">
									        <label for="bio" class="col-md-2 control-label">More</label>
									        <div class="col-md-10">
									          	<div class="checkbox">
													<label>
														<input <?php if($user->active){ echo "checked='checked'"; } ?> name="active" type="checkbox"> Active User
													</label>
												</div>
												<div class="checkbox">
													<label>
														<input <?php if($user->banned){ echo "checked='checked'"; } ?> name="banned" type="checkbox"> Banned User
													</label>
												</div>
									        </div>
									      </div>
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
