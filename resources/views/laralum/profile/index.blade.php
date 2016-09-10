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
        </div>
    </div>
    <div class="three wide column"></div>
</div>
@endsection
