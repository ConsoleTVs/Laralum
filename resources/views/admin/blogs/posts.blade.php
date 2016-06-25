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
					<div class="row">
						<div class="col-sm-12 col-md-10">
							<h3>{{ $post->title }}</h3>
						</div>
						<div class="col-sm-12  col-md-2">
							<center>
								<div class="btn-group more-small-spacer">
									<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="mdi mdi-arrow-down"></i></a>
									<ul class="dropdown-menu">
										<li><a href="{{ url('admin/posts', $post->id) }}">View Post</a></li>
										<li><a href="{{ url('admin/posts', [$post->id, 'edit']) }}">Edit Post</a></li>
										<li><a href="{{ url('admin/posts', [$post->id, 'graphics']) }}">Post Graphics</a></li>
										<li><a href="{{ url('admin/posts', [$post->id, 'comments']) }}">Post Comments</a></li>
										<li class="divider"></li>
										<li><a href="{{ url('admin/posts', [$post->id, 'delete']) }}">Delete Post</a></li>
									</ul>
								</div>
							</center>
						</div>
					</div>
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

                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
