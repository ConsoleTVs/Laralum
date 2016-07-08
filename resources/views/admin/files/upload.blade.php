@extends('layouts.admin.index')
@section('title', "File Uploader")
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <center>
                <h3>Upload Files</h3><br>
            </center>
        </div>
        <div class="col-sm-12 col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row margins">
                            <div class="col-sm-12 col-md-6">
                                <center>
                                    <h4>Select the files</h4><br>
                                    <input required type="file" name="files[]" id="files" multiple="true"><br>
                                    <p>Max upload size (based on the php configuration): <b>{{ ini_get('upload_max_filesize') }}</b></p>
                                </center>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <center>
                                    <br><br>
                                    <button type="submit" class="btn btn-lg btn-primary">Upload Files</button>
                                </center>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
