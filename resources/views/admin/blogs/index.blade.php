@extends('layouts.admin.index')
@section('title', "Blogs")
@section('css')

@endsection
@section('content')
<a href="{{ url('admin/blogs/create') }}" class="btn btn-primary" role="button">Create Blog</a><br><br>
<div class="row">
    <div class="col-sm-12 col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <canvas id="posts"></canvas>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <canvas id="views"></canvas>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-8">
        @if(count($blogs) == 0)
			<center>
				<p>There are no blogs yet.</p>
			</center>
    	@endif
        @foreach($blogs as $blog)
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
                                    {{ count($blog->posts) }} Posts
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
                                <a href="{{ url('admin/blogs', [$blog->id, 'roles']) }}" class="btn btn-primary" role="button">Edit Roles</a>
    	                        <a href="{{ url('admin/blogs', [$blog->id, 'delete']) }}" class="btn btn-danger pull-right" role="button">Delete Blog</a>
    	                    </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
@section('js')
<script>
    var ctx = document.getElementById("posts");
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            @if(count($blogs) > 0)
                labels: [@foreach($blogs as $blog) '{{ $blog->name }}@if(count($blog->posts) == 0) (No Posts)@endif', @endforeach],
                datasets: [{
                    data: [@foreach($blogs as $blog) {{ count($blog->posts) }}, @endforeach],
                    backgroundColor: [@foreach($blogs as $blog) '@if($blog->color){{ $blog->color }}@else{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}@endif', @endforeach]
                }]
			@else
			labels: ['No Blogs'],
			datasets: [{
				data: [100],
				backgroundColor: ['#424242']
			}]
			@endif
        },
		options: {
			title: {
	            display: true,
	            text: 'Total posts per blog',
				fontSize: 20,
	        }
		}
    });
</script>
<script>
    var ctx = document.getElementById("views");
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            @if(count($blogs) > 0)
                labels: [@foreach($blogs as $blog)'{{ $blog->name }}<?php $views = False; ?>@foreach($blog->posts as $post)@if(count($post->views) != 0)<?php $views = True; ?>@endif @endforeach @if(!$views) (No Views)@endif ', @endforeach],
                datasets: [{
                    data: [@foreach($blogs as $blog) <?php $i = 0; ?> @foreach($blog->posts as $post) <?php $i += count($post->views); ?> @endforeach {{ $i }}, @endforeach],
                    backgroundColor: [@foreach($blogs as $blog) '@if($blog->color){{ $blog->color }}@else{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}@endif', @endforeach]
                }]
			@else
			labels: ['No Blogs'],
			datasets: [{
				data: [100],
				backgroundColor: ['#424242']
			}]
			@endif
        },
		options: {
			title: {
	            display: true,
	            text: 'Total views per blog',
				fontSize: 20,
	        }
		}
    });
</script>
@endsection
