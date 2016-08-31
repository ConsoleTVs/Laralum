@extends('layouts.main')
@section('content')
    <div class="title m-b-md">
        {{ trans('laralum.login_title') }}
    </div>
    <form method="POST">
        {{ csrf_field() }}
        <input name='email' type="email" placeholder="{{ trans('laralum.email') }}">
        <input name='password' type="password" placeholder="{{ trans('laralum.password') }}">
        <input type="checkbox" name="remember"> Remember Me<br><br>
        <a href="{{ url('password/reset') }}">{{ trans('laralum.forgot_password') }}</a>
        <br><br><br>
        <button class="button button5">{{ trans('laralum.submit') }}</button>
    </form>
@endsection
