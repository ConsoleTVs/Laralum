@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::users') }}">{{ trans('laralum.user_list') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.users_create_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.users_create_title'))
@section('icon', "plus")
@section('subtitle', trans('laralum.users_create_subtitle'))
@section('content')
<div class="ui container">
    <form class="ui form" method="POST">
        <div class="ui doubling stackable grid">
            <div class="row">
                <div class="eight wide column">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}
                        @include('laralum/forms/master')
                    </div>
                </div>
                <div class="eight wide column">
                    <div class="ui very padded segment">
                        @include('laralum.forms.roles')
                    </div>
                    <div class="ui very padded segment">
                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" id="active" name="active" tabindex="0" class="hidden">
                                <label>{{ trans('laralum.users_activate_user') }}</label>
                            </div>
                        </div>
                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" id="send_activation" name="send_activation" tabindex="0" class="hidden">
                                <label>{{ trans('laralum.users_send_activation') }}</label>
                            </div>
                        </div>
                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" id="mail_checkbox" name="mail" tabindex="0" class="hidden">
                                <label>{{ trans('laralum.users_welcome_email') }}</label>
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('js')
<script>
    $('#active').change(function(){
        if(this.checked) {
            $('#send_activation').prop('checked', false);
        }
    });
    $('#send_activation').change(function(){
        if(this.checked) {
            $('#active').prop('checked', false);
        }
    });
</script>
@endsection
