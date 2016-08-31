@extends('layouts.main')
@section('content')
    <div class="title m-b-md">
        {{ trans('laralum.reset_password') }}
    </div>
    <form method="POST" action="{{ url('/password/email') }}">
        {{ csrf_field() }}
        <input type="email" name="email" placeholder="{{ trans('laralum.email') }}" value="{{ old('email') }}">
        <br><br><br>
        <button class="button button5">{{ trans('laralum.send_password_link') }}</button>
    </form>
@endsection
