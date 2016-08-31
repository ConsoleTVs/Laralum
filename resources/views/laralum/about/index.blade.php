@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{  trans('laralum.about') }}</div>
    </div>
@endsection
@section('title', trans('laralum.about'))
@section('icon', "info")
@section('subtitle', trans('laralum.about_subtitle'))
@section('content')
<center>
    <img class="ui medium image" src="{{ Laralum::laralumLogo() }}">
</center>
<br><br>
<div class="ui container">
    <div class="ui very padded segment">
        <div class="ui icon message" id="updater">
            <i class="notched circle loading icon" id="update_icon"></i>
            <div class="content">
                <span class="header" id="update_header">
                    {{ trans('laralum.updater_looking_title') }}
                </span>
                <p id="update_content">{{ trans('laralum.updater_looking_subtitle') }}</p>
            </div>
        </div>
    </div>
</div>
<br><br>
<div class="ui doubling stackable two column grid container">
    <div class="column">
        <div class="ui very padded segment " style="min-height: 620px;">
            <span class="ui header">{{ trans('laralum.releases') }}</span><br>
            <div class="ui comments" id="releases">



            </div>

        </div>
    </div>
    <div class="column">
        <div class="ui very padded segment" style="min-height: 620px;">
            <span class="ui header">{{ trans('laralum.commits') }}</span><br>
            <div class="ui comments" id="commits">

            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
$.getJSON( "https://api.github.com/repos/ConsoleTVs/Laralum/releases", function( data ) {
    var items = [];
    var counter = 0;
    currentVersionHere = false;
    isNewVersion = false;
    $.each( data, function( key, val ) {
        if(counter < 5) {
            if(counter == 0) {
                var type = 'success';
                var icon = 'smile';
                var latestVersion = val['tag_name'];
            } else if(counter == 1) {
                var type = 'warning';
                var icon = 'meh';
            } else {
                var type = 'error';
                var icon = 'frown';
            }
            version = val['tag_name']
            if(version == '{{ Laralum::version() }}'){
                if(counter != 0) {
                    isNewVersion = true;
                }
                if(type == 'success') {
                    versionAnimation = 'pulse';
                    versionTimer = 2000;
                    versionType = 'success';
                } else if(type == 'warning') {
                    versionAnimation = 'shake';
                    versionTimer = 2000;
                    versionType = 'warning';
                } else {
                    versionAnimation = 'flash';
                    versionTimer = 1000;
                    versionType = 'error';
                }
                type = type + " current_version";
                currentVersionHere = true;
            }
            date = val['published_at'].substr(0, 10);
            items.push("<div class='ui icon " + type + " message'> <i class='" + icon + "  icon'></i> <div class='content'> <div> <a href='" + val['html_url'] + "' class='header'>" + val['name'] + "</a><i>" + jQuery.timeago(date) + "</i></div> <span>" + val['body'] + "</span> </div> </div>");
            counter = counter + 1;
        }
    });
    if(currentVersionHere) {
        if(isNewVersion) {
            $('#update_icon').removeClass('notched circle loading');
            $('#update_icon').addClass('announcement sign');
            $('#update_header').html("{{ trans('laralum.updater_new_version_title') }}");
            $('#update_content').html("{{ trans('laralum.updater_new_version_subtitle') }}");
            $('#updater').addClass(versionType);
        } else {
            $('#update_icon').removeClass('notched circle loading');
            $('#update_icon').addClass('checkmark sign');
            $('#update_header').html("{{ trans('laralum.updater_latest_version_title', ['version' => Laralum::version()]) }}");
            $('#update_content').html("{{ trans('laralum.updater_latest_version_subtitle') }}");
            $('#updater').addClass(versionType);
        }
    } else {
        $('#update_icon').removeClass('notched circle loading');
        $('#update_icon').addClass('warning sign');
        $('#update_header').html("{{ trans('laralum.updater_danger_version_title', ['version' => Laralum::version()]) }}");
        $('#update_content').html("{{ trans('laralum.updater_danger_version_subtitle') }}");
        $('#updater').addClass('error');
    }
    $('#releases').append(items.join(""))
    setInterval(function(){
        $('.current_version')
          .transition(versionAnimation)
        ;
    }, versionTimer);
});
$.getJSON( "https://api.github.com/repos/ConsoleTVs/Laralum/commits", function( data ) {
    var items = [];
    var counter = 0;
    $.each( data, function( key, val ) {
        if(counter < 10) {
            date = val['commit']['author']['date'].substr(0, 10);
            items.push("<div class='comment'> <a class='avatar'> <img src='" + val['author']['avatar_url'] + "'> </a> <div class='content'> <a href='" + val['author']['html_url'] + "' class='author'>" + val['author']['login'] + "</a> " + jQuery.timeago(date) + " <div class='text'><a href='" + val['html_url'] + "'>" + val['commit']['message'] + "</a></div> </div> </div>");
            counter = counter + 1;
        }
    });


    $('#commits').append(items.join(""))
});

</script>
@endsection
