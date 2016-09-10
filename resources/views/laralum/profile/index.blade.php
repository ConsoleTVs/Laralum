@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">{{ trans('laralum.profile') }}</div>
</div>
@endsection
@section('title', trans('laralum.profile'))
@section('icon', "user")
@section('subtitle')
{{ trans('laralum.welcome_user', ['name' => Laralum::loggedInUser()->name]) }}
@endsection

@section('content')

<?php $user = Laralum::loggedInUser(); ?>

<div class="ui doubling stackable grid container">
    <div class="four wide column"></div>
    <div class="eight wide column">
        <div class="ui segment">
            <div class="ui {{ Laralum::settings()->button_color }} ribbon label">{{ trans('laralum.avatar') }}</div>
            <br><br>
            <center>
                <img class="ui small circular image" src="{{ $user->avatar(150) }}">
            </center>

            <br><br>
            <div class="ui {{ Laralum::settings()->button_color }} ribbon label">{{ trans('laralum.information') }}</div>
            <br><br>
            <center>
                <?php $countries = Laralum::countries(); ?>
                <div class="ui list">
                    <div class="item">
                    <div class="header">{{ trans('laralum.country') }}</div>
                        @if(in_array($user->country_code, Laralum::noFlags()))<i class="help icon"></i> {{ $countries[$user->country_code] }}@else<i class="{{ strtolower($user->country_code) }} flag"></i> {{ $countries[$user->country_code] }}@endif
                    </div>
                    <div class="item">
                        <div class="header">{{ trans('laralum.join_date') }}:</div>
                        {{ Laralum::fancyDate($user->created_at) }}
                    </div>
                    <div class="item">
                        <div class="header">{{ trans('laralum.last_updated') }}:</div>
                        {{ Laralum::fancyDate($user->updated_at) }}
                    </div>
                </div>
            </center>
            <br><br>
            <div class="ui {{ Laralum::settings()->button_color }} ribbon label">{{ trans('laralum.roles') }}</div>
            <br><br>
            @foreach($user->roles as $role)
                <a href="{{ route('Laralum::roles_edit', ['id' => $role->id]) }}" class="ui {{ $role->color }} tag label">{{ $role->name }}</a>
            @endforeach
            <br><br>

            <div class="ui {{ Laralum::settings()->button_color }} ribbon label">{{ trans('laralum.profile_settings') }}</div>
            <br><br>

            <div class="extra content">
            	<center>
					<a href="{{ route('Laralum::profile_image') }}" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.change_profile_image_subtitle') }}</a>
					<a href="{{ route('Laralum::profile_password') }}" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.change_profile_password_subtitle') }}</a>
     			</center>
     		</div>
        </div>
    </div>
    <div class="four wide column"></div>
</div>
   	
            
@endsection