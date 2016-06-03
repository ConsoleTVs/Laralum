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
@endsection
