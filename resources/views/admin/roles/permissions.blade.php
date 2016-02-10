@extends('layouts.admin.index')
@section('title', "Edit Permissions")
@section('content')
	<div class="row">


		<div class="col-sm-1 col-lg-2"></div>
		<div class="col-sm-10 col-lg-8">
			<div class="panel panel-default">
				<div class="panel-body">
					<br>
					<center>
						<h3>
							<span>{{ $role->name }} Permissions</span>
						</h3>
					</center>
					<br>
					<form method="POST">
						{{ csrf_field() }}

						

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
													<input


													<?php 
														if($role->has($perm->slug)) {
															echo "checked='checked' ";
														}

														if($role->su) {
															if($perm->su) {
																echo "disabled ";
															}
														}
													?>


													name="{{ $perm->id }}" type="checkbox">{{ $perm->name }}
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
												<input


												<?php 
													foreach($role->permissions as $r_perm) {
														if($perm->id == $r_perm->id) {
															echo "checked='checked'";
														}
													}
												?>


												name="{{ $perm->id }}" type="checkbox">{{ $perm->name }}
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



						<div class="row row-spacer">
							<div class="col-sm-12">
								<a href="{{ url('admin/roles') }}" class="btn btn-primary">Back</a>
								<button type="submit" class="btn btn-primary pull-right">Save changes</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-1 col-lg-2"></div>


	</div>

@endsection
