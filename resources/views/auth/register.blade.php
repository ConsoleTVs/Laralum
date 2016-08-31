@extends('layouts.main')
@section('content')
    <div class="title m-b-md">
        {{ trans('laralum.register_title') }}
    </div>
    <form method="POST">
        {{ csrf_field() }}
        <input name='name' type="text" placeholder="{{ trans('laralum.name') }}">
        <input name='email' type="email" placeholder="{{ trans('laralum.email') }}">
        <input name='password' type="password" placeholder="{{ trans('laralum.password') }}">
        <input name='password_confirmation' type="password" placeholder="{{ trans('laralum.repeat_password') }}">
        <br><br><br>
        <button class="button button5">{{ trans('laralum.submit') }}</button>
    </form>
@endsection
