@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::roles') }}">{{ trans('laralum.roles_title') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.roles_edit_permissions_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.roles_edit_permissions_title'))
@section('icon', "lightning")
@section('subtitle', trans('laralum.roles_edit_permissions_subtitle', ['name' => $role->name]))
@section('content')
<div class="ui one column doubling stackable grid container">
    <div class="column">
        <div class="ui very padded segment">
            <form method="POST" class="ui form">
                {{ csrf_field() }}
                @include('laralum.forms.permissions')
                <br>
                <div class="field">
                    <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
