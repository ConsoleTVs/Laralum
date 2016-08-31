@extends('layouts.main')
@section('content')
    <div class="title m-b-md">
        {{ trans('laralum.activation_account') }}
    </div>
    <form method="POST">
        {{ csrf_field() }}
        <input name='token' type="text" placeholder="{{ trans('laralum.activation_key') }}">
        <br><br><br>
        <button class="button button5">{{ trans('laralum.submit') }}</button>
    </form>
@endsection
