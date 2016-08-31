@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::files') }}">{{ trans('laralum.files_title') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.files_upload_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.files_upload_title'))
@section('icon', "upload")
@section('subtitle', trans('laralum.files_upload_subtitle'))
@section('content')
<div class="ui doubling stackable two column grid container">
    <div class="column">
        <div class="ui very padded segment">
            <h3>{{ trans('laralum.information') }}</h3><br>
            <p>{{ trans('laralum.files_max_upload_size', ['size' => ini_get('upload_max_filesize')]) }}</p>
            <p>{{ trans('laralum.files_max_execution_time', ['time' => ini_get('max_execution_time')]) }}</p>
        </div>
    </div>
    <div class="column">
        <div class="ui very padded segment">
            <form method="POST" enctype="multipart/form-data">
                <h3>{{ trans('laralum.files_select_files') }}</h3>
                <input required type="file" name="files[]" id="files" multiple="true"><br><br>
                {{ csrf_field() }}
                <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
