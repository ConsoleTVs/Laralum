@extends('layouts.main')
@section('content')
    <div class="title m-b-md">
        You are logged in!
    </div>
    <br>
    @if(config('services.facebook'))
        @if(!Laralum::loggedInUser()->hasSocial('facebook'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'facebook']) }}">{{ trans('laralum.social_link', ['provider' => 'facebook']) }}</a>
        @else
            <a class="social">Facebook ({{ trans('laralum.social_already_linked') }})</a>
        @endif
    @endif
    @if(config('services.twitter'))
        @if(!Laralum::loggedInUser()->hasSocial('twitter'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'twitter']) }}">{{ trans('laralum.social_link', ['provider' => 'twitter']) }}</a>
        @else
            <a class="social">Twitter ({{ trans('laralum.social_already_linked') }})</a>
        @endif
    @endif
    @if(config('services.google'))
        @if(!Laralum::loggedInUser()->hasSocial('google'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'google']) }}">{{ trans('laralum.social_link', ['provider' => 'google']) }}</a>
        @else
            <a class="social">Google ({{ trans('laralum.social_already_linked') }})</a>
        @endif
    @endif
    @if(config('services.github'))
        @if(!Laralum::loggedInUser()->hasSocial('github'))
            <a class="social" href="{{ route('Laralum::social', ['provider' => 'github']) }}">{{ trans('laralum.social_link', ['provider' => 'github']) }}</a>
        @else
            <a class="social">Github ({{ trans('laralum.social_already_linked') }})</a>
        @endif
    @endif
@endsection
