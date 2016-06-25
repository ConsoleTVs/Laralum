@extends('layouts.admin.index')
@section('title', $post->title . " Comments")
@section('content')
	<a href="{{ url('admin/blogs', [$post->blog->id]) }}" class="btn btn-primary" role="button">Back</a><br><br>
<div class="row">
    <div class="col-sm-12 col-md-6 col-md-offset-3">
        <center>
            <h3>
                {{ $post->title }} Comments
            </h3>
        </center>
        <br>
        @if(count($comments) == 0)
            <center>
                This post have no comments yet.
            </center>
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
