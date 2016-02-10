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
			<h1 class="title-color">Laralum</h1>
			<h1 class="title-color"><small>Laravel Administration Panel by <a href="http://erik.cat">erik.cat</a></small></h1>
			<h4 class="title-color"><small>{{ env('APP_VERSION') }}</small></h4>
		</center>
	</div>
</div>
@endsection
