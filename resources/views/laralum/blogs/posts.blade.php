@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::blogs') }}">{{ trans('laralum.blogs_title') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{ trans('laralum.blogs_posts_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.blogs_posts_title'))
@section('icon', "book")
@section('subtitle', trans('laralum.blogs_posts_subtitle', ['name' => $blog->name]))
@section('content')
    <div class="ui doubling stackable grid container">
        <div class="six wide column">
            <div class="ui padded segment">
                <a href="{{ route('Laralum::posts_create', ['id' => $blog->id]) }}" class="ui fluid {{ Laralum::settings()->button_color }} labeled icon button">
                    <i class="add icon"></i>
                    {{ trans('laralum.blogs_create_post') }}
                </a>
            </div>
            <div class="ui segment">
                <div class="ui {{ Laralum::settings()->button_color }} ribbon label">{{ trans('laralum.statistics') }}</div><br><br>
                <div class="ui stackable grid two columns container">
                    <div class="column">
                        <center>
                            <div class="ui small statistic">
                                <div class="value">
                                    {{ count($blog->views) }}
                                </div>
                                <div class="label">
                                    {{ trans('laralum.total_views') }}
                                </div>
                            </div>
                        </center>
                    </div>
                    <div class="column">
                        <center>
                            <div class="ui small statistic">
                                <div class="value">
                                    {{ count($blog->posts) }}
                                </div>
                                <div class="label">
                                    {{ trans('laralum.posts') }}
                                </div>
                            </div>
                        </center>
                        <br>
                    </div>
                </div>

            </div>
        </div>
        <div class="ten wide column">
            @if(count($blog->posts) == 0)
                <div class="ui very padded segment">
                    <div class="ui negative icon message">
                        <i class="frown icon"></i>
                        <div class="content">
                            <div class="header">
                                {{ trans('laralum.missing_title') }}
                            </div>
                            <p>{{ trans('laralum.missing_subtitle', ['element'  =>  "posts"]) }}</p>
                        </div>
                    </div>
                </div>
            @else
                    @foreach($blog->posts as $post)
                            <div class="ui fluid card">
                                <div class="image">
                                    <img src="@if($post->image) {{ $post->image }} @else http://placehold.it/1920x500 @endif">
                                </div>
                                <div class="content">
                                    <a href="{{ route('Laralum::posts', ['id' => $post->id]) }}" class="header">{{ $post->title }}</a>
                                    <div class="meta">
                                        <span class="date">{{ Laralum::fancyDate($post->created_at) }}</span>
                                    </div>
                                    <div class="description">
                                        {{ $post->description }}
                                    </div>
                                </div>
                                <div class="extra content">
                                    <span>
                                        <i class="talk icon"></i>
                                        {{ trans('laralum.posts_comments', ['number' => count($post->comments)]) }}
                                    </span>
                                    	&nbsp;
                                    <span>
                                        <i class="eye icon"></i>
                                        {{ trans('laralum.posts_views', ['number' => count($post->views)]) }}
                                    </span>
                                        &nbsp;
                                    <span>
                                        <div class="ui top left pointing dropdown">
                                            <i class="configure icon"></i>
                                            Options
                                            <div class="menu">
                                                @if(count($post->views) > 0)
                                                    <a href="{{ route('Laralum::posts_graphics', ['id' => $post->id]) }}" class="item">{{ trans('laralum.statistics') }}</a>
                                                @endif
                                                <a href="{{ route('Laralum::posts_edit', ['id' => $post->id]) }}" class="item">{{ trans('laralum.posts_edit') }}</a>
                                                <a href="{{ route('Laralum::posts_delete', ['id' => $post->id]) }}" class="item">{{ trans('laralum.posts_delete') }}</a>
                                            </div>
                                        </div>
                                    </span>
                                    <div class="right floated author pop" data-variation="wide" data-position="top center" data-html="<center><div>{{ $post->author->email }}</div></center>">
                                        {{ $post->author->name }}
                                    </div>
                                </div>

                            </div>
                    @endforeach
                    <br>
            @endif
        </div>
    </div>
@endsection
