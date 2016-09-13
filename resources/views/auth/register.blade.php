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
        <br><br>
        @if(config('services.facebook'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'facebook']) }}">{{ trans('laralum.social_register_with', ['provider' => 'facebook']) }}</a>
        @endif
        @if(config('services.twitter'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'twitter']) }}">{{ trans('laralum.social_register_with', ['provider' => 'twitter']) }}</a>
        @endif
        @if(config('services.google'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'google']) }}">{{ trans('laralum.social_register_with', ['provider' => 'google']) }}</a>
        @endif
        @if(config('services.github'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'github']) }}">{{ trans('laralum.social_register_with', ['provider' => 'github']) }}</a>
        @endif
        <br><br><br>
        <button class="button button5">{{ trans('laralum.submit') }}</button>
    </form>
@endsection
