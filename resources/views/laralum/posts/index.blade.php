@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::blogs') }}">{{ trans('laralum.blogs_title') }}</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route('Laralum::blogs_posts', ['id' => $post->blog->id]) }}">{{ trans('laralum.blogs_posts_title') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  $post->title }}</div>
    </div>
@endsection
@section('title', $post->title)
@section('icon', "book")
@section('subtitle', $post->description)
@section('content')
<div class="ui doubling stackable grid container">
    <div class="sixteen wide column">
        <div class="ui fluid card">
            <div class="image">
                <img src="@if($post->image) {{ $post->image }} @else http://placehold.it/1920x500 @endif">
            </div>
            <div class="content">
                <div class="ui huge header">{{ $post->title }}</div>
                <div class="meta">
                    <span class="date">{{ Laralum::fancyDate($post->created_at) }} @if($post->edited()) ({{ trans('laralum.posts_edited', ['name' => $post->editor->name, 'date' => Laralum::fancyDate($post->updated_at)]) }}) @endif</span>
                </div>
                <br><br>
                <div class="description">
                    {!! $post->body !!}
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
                <div class="right floated author">
                    {{ $post->author->name }}
                </div>
            </div>
        </div>
        @if($post->logged_in_comments or $post->anonymous_comments)
            <div class="ui very padded segment">
                <h1 class="ui header">{{ trans('laralum.posts_post_comment') }}</h1>
                <form class="ui form" method="POST" action="{{ route('Laralum::comments_create', ['id' => $post->id]) }}">
                    {{ csrf_field() }}
                    @include('laralum/forms/master')
                    <br>
                    <button type="submit" class="ui {{ Laralum::settings()->button_color }} submit button">{{ trans('laralum.submit') }}</button>
                </form>
            </div>
        @endif
        <div class="ui very padded segment">
            <h1 class="ui header">{{ trans('laralum.comments') }}</h1>
            <div class="ui comments">
                @if(count($comments) > 0)
                    @foreach($comments as $comment)
                        @if($comment->user_id)
                            <div class="comment">
                                <div class="avatar">
                                    <img src="{{ $comment->author->avatar(35) }}">
                                </div>
                                <div class="content">
                                    <a href="{{ route('Laralum::users_profile', ['id' => $comment->author->id]) }}" class="author">{{ $comment->author->name }}</a>
                                    <div class="metadata">
                                        <div class="date">{{ Laralum::fancyDate($comment->created_at) }}</div>
                                    </div>
                                    <div class="text">
                                        {{ $comment->content }}
                                    </div>
                                    <div class="actions">
                                        <a href="{{ route('Laralum::comments_edit', ['id' => $comment->id]) }}">Edit</a>
                                        <a href="{{ route('Laralum::comments_delete', ['id' => $comment->id]) }}">Delete</a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="comment">
                                <div class="avatar">
                                    <img src="{{ Laralum::defaultAvatar() }}">
                                </div>
                                <div class="content">
                                    <span class="author">{{ $comment->name }}</span>
                                    <div class="metadata">
                                        <div class="date">{{ Laralum::fancyDate($comment->created_at) }}</div>
                                    </div>
                                    <div class="text">
                                        {{ $comment->content }}
                                    </div>
                                    <div class="actions">
                                        <a href="{{ route('Laralum::comments_edit', ['id' => $comment->id]) }}">Edit</a>
                                        <a href="{{ route('Laralum::comments_delete', ['id' => $comment->id]) }}">Delete</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <p>There are no comments yet</p>
                @endif
            </div>
        </div>
        <br>
    </div>
</div>
@endsection
