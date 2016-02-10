@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Account Banned</div>
                <div class="panel-body">
                    <p>Your account has been banned</p>
                </div>
            </div>
            @if(session('error'))
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<p>{{ session('error') }}</p>
				</div>
			@endif
			@if(session('success'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<p>{{ session('success') }}</p>
				</div>
			@endif
        </div>
    </div>
</div>
@endsection
