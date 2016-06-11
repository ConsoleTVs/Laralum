@extends('layouts.admin.index')
@section('title', $blog->name . ' Posts')
@section('css')

@endsection
@section('content')
<a href="{{ url('admin/blogs') }}" class="btn btn-primary" role="button">Back</a>
<a href="{{ url('admin/posts/create', [$blog->id]) }}" class="btn btn-primary pull-right" role="button">Create Post</a>
<br><br>
<div class="row">
	<center>
		<h2>
			{{ $blog->name }}
		</h2>
		<br>
	</center>
	@if(count($posts) == 0)
		<div class="col-sm-12 col-md-12">
			<center>
				<p>There are no posts yet.</p>
			</center>
		</div>
	@endif
    @foreach($posts as $post)
        <div class="col-sm-12 col-md-6">
            <div class="thumbnail panel panel-default">
                <div class="blog-img" style="background-image:url('{{ $post->image }}');"></div>
                <div class="caption">
                    <h3>{{ $post->title }}</h3>
					<p>
						<b>Posted by:</b> {{ $post->author->name }}
						- <b>Posted on:</b> {{ substr($post->created_at, 0, 10) }}
						@if($post->edited_by)
							- <i>(Edited by: {{ App\User::findOrFail($post->edited_by)->name }} on {{ substr($post->updated_at, 0, 10) }})</i>
						@endif
					</p>
                    <div class="post-text">{{ $post->description }}</div>
					<br>
                    <p>
                        <a href="{{ url('admin/posts', [$post->id, 'edit']) }}" class="btn btn-primary" role="button">Edit Post</a>
						<a href="{{ url('admin/posts', [$post->id, 'graphics']) }}" class="btn btn-primary" role="button">Post Graphics</a>
                        <a href="{{ url('admin/posts', $post->id) }}" class="btn btn-primary" role="button">View Post</a>
                        <a href="{{ url('admin/posts', [$post->id, 'delete']) }}" class="btn btn-danger pull-right" role="button">Delete Post</a>
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
