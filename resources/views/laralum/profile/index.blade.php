@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">{{ trans('laralum.profile') }}</div>
</div>
@endsection
@section('title', trans('laralum.profile'))
@section('icon', "user")
@section('subtitle', Laralum::loggedInUser()->email)
@section('content')
<div class="ui doubling stackable grid container">
    <div class="three wide column"></div>
    <div class="ten wide column">
        <div class="ui very padded segment">
            <form class="ui form" method="POST" enctype="multipart/form-data">
                <center>
                    <img class="ui small circular image" src="{{ $row->avatar(150) }}"><br>
                    <input type="file" name="image">
                </center>
                <br>
                {{ csrf_field() }}
                @include('laralum/forms/master')
                <br><br>
                <div class="field">
                     <label>{{ trans('laralum.current_password') }}</label>
                     <input type="password" id="current_password" name="current_password" placeholder="{{ trans('laralum.current_password') }}" value="">
                </div>
                <br>
                <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
            </form>
            <center>
                @if(config('services.facebook'))
                    @if(!Laralum::loggedInUser()->hasSocial('facebook'))
                        <br><br>
                        <a href="{{ route('Laralum::social', ['provider' => 'facebook']) }}" class="ui facebook button">
                            <i class="facebook icon"></i>
                            Facebook
                        </a>
                    @else
                    <br><br>
                        <a class="ui disabled facebook button">
                            <i class="facebook icon"></i>
                            Facebook ({{ trans('laralum.social_already_linked') }})
                        </a>
                    @endif
                @endif
                @if(config('services.twitter'))
                    @if(!Laralum::loggedInUser()->hasSocial('twitter'))
                        <br><br>
                        <a href="{{ route('Laralum::social', ['provider' => 'twitter']) }}" class="ui twitter button">
                            <i class="twitter icon"></i>
                            Twitter
                        </a>
                    @else
                    <br><br>
                        <a class="ui disabled twitter button">
                            <i class="twitter icon"></i>
                            Twitter ({{ trans('laralum.social_already_linked') }})
                        </a>
                    @endif
                @endif
                @if(config('services.google'))
                    @if(!Laralum::loggedInUser()->hasSocial('google'))
                        <br><br>
                        <a href="{{ route('Laralum::social', ['provider' => 'google']) }}" class="ui youtube button">
                            <i class="google icon"></i>
                            Google
                        </a>
                    @else
                    <br><br>
                        <a class="ui disabled youtube button">
                            <i class="youtube icon"></i>
                            Google ({{ trans('laralum.social_already_linked') }})
                        </a>
                    @endif
                @endif
                @if(config('services.github'))
                    @if(!Laralum::loggedInUser()->hasSocial('github'))
                        <br><br>
                        <a href="{{ route('Laralum::social', ['provider' => 'github']) }}" class="ui black button">
                            <i class="github icon"></i>
                            Github
                        </a>
                    @else
                    <br><br>
                        <a class="ui disabled black button">
                            <i class="github icon"></i>
                            Github ({{ trans('laralum.social_already_linked') }})
                        </a>
                    @endif
                @endif
            </center>
        </div>
    </div>
    <div class="three wide column"></div>
</div>
@endsection
