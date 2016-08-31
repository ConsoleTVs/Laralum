@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::users') }}">{{ trans('laralum.user_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.users_edit_roles_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.users_edit_roles_title'))
@section('icon', "star")
@section('subtitle', trans('laralum.users_edit_roles_subtitle', ['email' => $user->email]))
@section('content')
<div class="ui doubling stackable grid container">
    <div class="four wide column"></div>
    <div class="eight wide column">
        <div class="ui very padded segment">
            <form method="POST" class="ui form">
                {{ csrf_field() }}
                @include('laralum.forms.roles')
                <br>
                <div class="field">
                    <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="four wide column"></div>
</div>
@endsection
