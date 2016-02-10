@extends('layouts.admin.index')
@section('title', $role->name)
@section('content')
	<div class="row">


		<div class="col-md-2 col-lg-4"></div>
		<div class="col-md-8 col-lg-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="spacer">
						<center>
							<h1>
								<span>{{ $role->name }}</span>
							</h1>
							<h4><small>{{ $role->color }}</small></h4>
							<br>
								<h4>
									<?php $counter = 0; ?>
									@foreach($role->users as $user)
										<?php $counter++; ?>
									@endforeach	
									{{ $counter }} Total Users
								</h4>
						</center>
					</div>
					<br>
					<div class="row row-spacer">
						<div class="col-sm-12">
							<a href="{{ url('admin/roles') }}" class="btn btn-primary">Back</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-2 col-lg-4"></div>


	</div>

@endsection
