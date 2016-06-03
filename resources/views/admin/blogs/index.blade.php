@extends('layouts.admin.index')
@section('title', "Blogs")
@section('css')

@endsection
@section('content')
<a href="{{ url('admin/blogs/create') }}" class="btn btn-primary" role="button">Create Blog</a><br><br>
<div class="row">
    @if(count($blogs) == 0)
		<div class="col-sm-12 col-md-12">
			<center>
				<p>There are no blogs yet.</p>
			</center>
		</div>
	@endif
    @foreach($blogs as $blog)
    <div class="col-sm-12 col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <br>
                <div class="row">
                    <div class="col-md-6 ">
                        <center>
                            <h2 class="feature">
                                <i class="mdi mdi-book-open-variant"></i>
                            </h2>
                            <h2>
                                <?php $counter = 0; ?>
                                @foreach($blog->posts as $post)
                                    <?php $counter++; ?>
                                @endforeach
                                {{ $counter }} Posts
                            </h2>
                            <br><br>
                        </center>
                    </div>
                    <div class="col-md-6 small-spacer">
                        <center>
                            <h2>{{ $blog->name }}</h2>
                            <h5>{{ $blog->user->name }}</h5>
                        </center>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p>
                            <a href="{{ url('admin/blogs', [$blog->id]) }}" class="btn btn-primary" role="button">Posts</a>
	                        <a href="{{ url('admin/blogs', [$blog->id, 'edit']) }}" class="btn btn-primary" role="button">Edit Blog</a>
	                        <a href="{{ url('admin/blogs', [$blog->id, 'delete']) }}" class="btn btn-danger pull-right" role="button">Delete Blog</a>
	                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
