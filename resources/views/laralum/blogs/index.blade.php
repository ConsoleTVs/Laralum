@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.blogs_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.blogs_title'))
@section('icon', "book")
@section('subtitle', trans('laralum.blogs_subtitle'))
@section('content')
  <div class="ui two column doubling stackable grid container">
  	<div class="column">
  		<div class="ui padded segment">
            <?php
                $title = trans('laralum.blogs_graph1');
                $labels = [];
                $data = [];
                $colors = [];
                foreach($blogs as $blog){
                    array_push($labels, $blog->name);
                    array_push($data, count($blog->posts));
                }
            ?>
            {!! Laralum::pieChart($title, $labels, $data, $colors) !!}
  		</div>
  	</div>
  	<div class="column">
  		<div class="ui padded segment">
            <?php
                $title = trans('laralum.blogs_graph2');
                $labels = [];
                $data = [];
                $colors = [];
                foreach($blogs as $blog){
                    array_push($labels, $blog->name);
                    array_push($data, count($blog->views));
                }
            ?>
            {!! Laralum::pieChart($title, $labels, $data, $colors) !!}
  		</div>
  	</div>
  </div>

  <br><br>

  <div class="ui one column doubling stackable grid container">
  	<div class="column">
  		<div class="ui very padded segment">
  			@if(count($blogs) > 0)
                <table class="ui five column table ">
                  <thead>
                    <tr>
                      <th>{{ trans('laralum.name') }}</th>
                      <th>{{ trans('laralum.posts') }}</th>
                      <th>{{ trans('laralum.views') }}</th>
                      <th>{{ trans('laralum.options') }}</th>
                      <th>{{ trans('laralum.access') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($blogs as $blog)
                        <tr>
                            <td>
                                <div class="text">
                                    {{ $blog->name }}
                                </div>
                            </td>
                            <td>{{ trans('laralum.blogs_posts', ['number' => count($blog->posts)]) }}</td>
                            <td>{{ trans('laralum.blogs_views', ['number' => count($blog->views)]) }}</td>
                            <td>
                              @if(Laralum::loggedInuser()->owns_blog($blog->id) or Laralum::loggedInUser()->su)
                                  <div class="ui {{ Laralum::settings()->button_color }} top icon left pointing dropdown button">
                                    <i class="configure icon"></i>
                                    <div class="menu">
                                      <div class="header">{{ trans('laralum.editing_options') }}</div>
                                      <a href="{{ route('Laralum::blogs_edit', ['id' => $blog->id]) }}" class="item">
                                        <i class="edit icon"></i>
                                        {{ trans('laralum.blogs_edit') }}
                                      </a>
                                      <a href="{{ route('Laralum::blogs_roles', ['id' => $blog->id]) }}" class="item">
                                        <i class="star icon"></i>
                                        {{ trans('laralum.blogs_edit_roles') }}
                                      </a>
                                      <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                      <a href="{{ route('Laralum::blogs_delete', ['id' => $blog->id]) }}" class="item">
                                        <i class="trash bin icon"></i>
                                        {{ trans('laralum.blogs_delete') }}
                                      </a>
                                    </div>
                                  </div>
                              @else
                                  <div class="ui disabled {{ Laralum::settings()->button_color }} icon button">
                                      <i class="lock icon"></i>
                                  </div>
                              @endif
                            </td>
                            <td>
                                <a href="{{ route('Laralum::blogs_posts', ['id' => $blog->id]) }}" class="ui right labeled icon {{ Laralum::settings()->button_color }} button">
                                    <i class="right arrow icon"></i>
                                    {{ trans('laralum.blogs_access_blog') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
            @else
                <div class="ui negative icon message">
                    <i class="frown icon"></i>
                    <div class="content">
                        <div class="header">
                            {{ trans('laralum.missing_title') }}
                        </div>
                        <p>{{ trans('laralum.missing_subtitle', ['element'  =>  "blogs"]) }}</p>
                    </div>
                </div>
            @endif
  		</div>
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
			labels: ["{{ trans('laralum.blogs_no_blogs') }}"],
			datasets: [{
				data: [100],
				backgroundColor: ['#424242']
			}]
			@endif
        },
		options: {
			title: {
	            display: true,
	            text: "{{ trans('laralum.blogs_graph1') }}",
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
			labels: ["{{ trans('laralum.blogs_no_blogs') }}"],
			datasets: [{
				data: [100],
				backgroundColor: ['#424242']
			}]
			@endif
        },
		options: {
			title: {
	            display: true,
	            text: "{{ trans('laralum.blogs_graph2') }}",
				fontSize: 20,
	        }
		}
    });
</script>
@endsection
