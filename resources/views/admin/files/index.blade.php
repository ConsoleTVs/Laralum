@extends('layouts.admin.index')
@section('title', "Files")
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <center>
                <h3>Files</h3><br>
            </center>
        </div>
        <div class="col-sm-12 col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        @if(count($files) == 0)
                            No files found.
                        @endif
                        @foreach($files as $file)
                            @if(Laralum::isDocument($file))
                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">
                                                {{ $file }}
                                                <?php $slug = Laralum::document('name', $file)->slug; ?>
                                                <div class="btn-group pull-right">
                                                    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="mdi mdi-arrow-down"></i></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="{{ Laralum::downloadLink($file) }}">Download Link</a></li>
                                                        <li><a href="{{ url('/admin/documents', [$slug, 'edit']) }}">Edit Document</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="{{ url('admin/documents', [$slug, 'delete']) }}">Delete Document</a></li>
                                                        <li><a href="{{ url('admin/files', [$file, 'delete']) }}">Delete File</a></li>
                                                    </ul>
                                                </div>
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <center>
                                                <i class="file-icon mdi {{ Laralum::fileIcon($file) }}"></i><br>
                                                <i>{{ Laralum::document('name', $file)->downloads }} Downloads</i>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <div class="panel panel-warning">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">
                                                {{ $file }}
                                                <div class="btn-group pull-right">
                                                    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="mdi mdi-arrow-down"></i></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="{{ url('admin/files', [$file, 'download']) }}">Download</a></li>
                                                        <li><a href="{{ url('admin/documents', [$file, 'create']) }}">Create Document</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="{{ url('admin/files', [$file, 'delete']) }}">Delete File</a></li>
                                                    </ul>
                                                </div>
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <center>
                                                <i class="file-icon mdi {{ Laralum::fileIcon($file) }}"></i><br>
                                                <i>Not a document</i>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
