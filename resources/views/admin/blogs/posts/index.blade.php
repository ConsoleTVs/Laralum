@extends('layouts.admin.index')
@section('title', $post->name)
@section('css')
<style>
	.title-color {
		color: #757575;
	}
</style>
@endsection
@section('content')
	<a href="{{ url('admin/blogs', [$post->blog->id]) }}" class="btn btn-primary" role="button">Back</a><br><br>
<div class="row">
    <div class="col-sm-12 col-md-8 col-md-offset-2">
        <div class="thumbnail panel panel-default">
            <div class="blog-img-full" style="background-image:url('{{ $post->image }}');"></div>
            <div class="caption">
                <h2>{{ $post->title }}</h2>
				<p>
					<b>Posted by:</b> {{ $post->author->name }}
					- <b>Posted on:</b> {{ substr($post->created_at, 0, 10) }}
					@if($post->edited_by)
						- <i>(Edited by: {{ App\User::findOrFail($post->edited_by)->name }} on {{ substr($post->updated_at, 0, 10) }})</i>
					@endif
				</p>
                <div class="post-text">{!! $post->body !!}</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-sm-12 col-md-8 col-md-offset-2">
		<h3>Comments</h3><br>
		@if($post->logged_in_comments or $post->anonymous_comments)
			<form method="POST" action="{{ url('admin/posts', [$post->id, 'comments', 'create']) }}">
				{{ csrf_field() }}
				<div class="panel panel-default">
					<div class="panel-body">
						<center>
							<h4>Add Comment</h4>
						</center>
						<br>
						@include('admin/forms/master')
						<button type="submit" class="btn btn-primary pull-right">Comment</button>
					</div>
				</div>
			</form>
		@endif
		@foreach($comments as $comment)
			<div class="panel panel-default">
				<div class="panel-body">
					@if($comment->user_id)
						<div class="row">
							<div class="col-sm-6 col-md-2">
								<?php $grav_url = "https://www.gravatar.com/avatar/".md5(strtolower(trim($comment->author->email)))."?s=75";?>
								<center>
									<img height="75" width="75" class="img-responsive img-circle" src="{!! $grav_url !!}">
								</center>
							</div>
							<div class="col-sm-6 col-md-10">
								<span class="comment-author">{{ $comment->author->name }}</span>
								<span class="pull-right">{{ date("F jS, Y",strtotime($comment->created_at)) }}</span>
								<br>
								<i>{{ $comment->author->email }}</i>
							</div>
						</div>
					@else
						<div class="row">
							<div class="col-sm-6 col-md-2">
								<center>
									<img height="75" width="75" class="img-responsive img-circle" src="{{ url('admin_panel/img/avatar.jpg') }}">
								</center>
							</div>
							<div class="col-sm-6 col-md-10">
								<span class="comment-author">{{ $comment->name }}</span>
								<span class="pull-right">{{ date("F jS, Y",strtotime($comment->created_at)) }}</span>
								<br>
								<i>{{ $comment->email }}</i>
							</div>
						</div>
					@endif
					<br>
					<span class="comment-content">
						{{ $comment->content }}
					</span>
				</div>
			</div>
		@endforeach
	</div>
</div>
@endsection
