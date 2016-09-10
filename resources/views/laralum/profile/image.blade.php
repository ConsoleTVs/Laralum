@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::profile') }}">{{ trans('laralum.profile') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.change_profile_image_subtitle') }}</div>
    </div>
@endsection
@section('title', trans('laralum.change_profile_image_title'))
@section('icon', "picture")
@section('subtitle', trans('laralum.change_profile_image_subtitle'))
@section('content')
<div class="ui doubling stackable two column grid container">
    <div class="column">
        <div class="ui very padded segment">
            <h3>{{ trans('laralum.information') }}</h3><br>
            <p>{{ trans('laralum.profile_image_max_upload_size', ['size' => '5 MB']) }}</p>
            <p>{{ trans('laralum.files_max_execution_time', ['time' => ini_get('max_execution_time')]) }}</p>
        </div>
    </div>
    <div class="column">
        <div class="ui very padded segment">
            <form method="POST" enctype="multipart/form-data">
                <h3>{{ trans('laralum.search_image') }}</h3>
                <input required type="file" name="image" id="files"><br><br>
                {{ csrf_field() }}
                <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
