@extends('layouts.admin.index')
@section('title', "Blogs")
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <center>
                <h3>Developer Mode</h3><br>
            </center>
        </div>
        <div class="col-sm-12 col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover center">
                            <thead>
                              <tr>
                                <th class="text-center">Table</th>
                                <th class="text-center">Columns</th>
                                <th class="text-center">Rows</th>
                            	<th class="text-center">Edit</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($tables as $table)
                                    <tr>
                                        <td class="text-center">{{ $table }}</td>
                                        <td class="text-center">{{ count(\Schema::getColumnListing($table)) }}</td>
                                        <td class="text-center">{{ count(\DB::table($table)->get()) }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('admin/developer', $table) }}" class="btn btn-primary btn-sm">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
