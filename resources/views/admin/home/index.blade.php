@extends('layouts.admin.index')
@section('title', "Dashboard")
@section('css')
<style>
	.title-color {
		color: #757575;
	}
</style>
@endsection
@section('content')
<div class="row">
	<div class="col-lg-12 spacer">
		<center>
			<img height="150" src="{{ url('admin_panel/img/laralum-logo.png') }}">
			<br><br>
			<img height="100" src="{{ url('admin_panel/img/6.png') }}">
			<br><br>
			<h4 class="title-color"><small>{{ App\Settings::first()->laralum_version }}</small></h4>
		</center>
	</div>
</div>
@endsection
