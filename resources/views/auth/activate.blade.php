@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Account Activation</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/activate') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('token') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Activation token</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="token" value="{{ old('token') }}" placeholder="Enter your activation token">

                                @if ($errors->has('token'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('token') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Activate
                                </button>

                                <a class="btn btn-link" href="{{ url('/activate/resend') }}">Resend activation email</a>
                            </div>
                        </div>
                    </form>
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
