@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.security_confirm_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.security_confirm_title'))
@section('icon', "warning")
@section('subtitle', trans('laralum.security_confirm_subtitle'))
@section('content')
<div class="ui doubling stackable grid container">
    <div class="four wide column"></div>
    <div class="eight wide column">
        <div class="ui very padded segment">
            <h2 class="ui header">{{ trans('laralum.security_description_title') }}</h2>
            <p>{{ trans('laralum.security_description') }}</p>
            <br>
            <div class="ui grid">
                <div class="eight wide column center aligned">
                    <a href="{{ URL::previous() }}" class="ui button">{{ trans('laralum.back') }}</a>
                </div>
                <div class="eight wide column center aligned">
                    <form method="POST" class="ui form">
                        {{ csrf_field() }}
                        <div class="field">
                            <button id="security_continue" type="submit" class="ui loading disabled {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.continue') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="ui bottom attached progress" style="display: none;" data-value="1" data-total="75" id="security_progress">
                <div class="bar"></div>
            </div>
        </div>
    </div>
    <div class="four wide column"></div>
</div>
@endsection
@section('js')
<script>
$('#security_progress').fadeIn(750)
var interval = setInterval(function(){
    var success = $('#security_progress').progress('is success');
    if(success) {
        $('#security_progress').fadeOut(750)
        setTimeout(function(){
            $('#security_continue').removeClass('disabled');
            $('#security_continue').removeClass('loading');
            clearInterval(interval);
        }, 250);
    } else {
        $('#security_progress').progress('increment');
    }
}, 50);
</script>
@endsection
