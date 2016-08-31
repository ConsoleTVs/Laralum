@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::users') }}">{{ trans('laralum.user_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ $user->name }}</div>
    </div>
@endsection
@section('title', $user->name)
@section('icon', "user")
@section('subtitle', $user->email)
@section('content')
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
        </div>
    </div>
    <div class="four wide column"></div>
</div>
@endsection
