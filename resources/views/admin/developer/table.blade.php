@extends('layouts.admin.index')
@section('title', "Edit ". $name)
@section('content')
    <a href="{{ url('admin/developer') }}" class="btn btn-primary" role="button">Back</a><br><br>
    <div class="row">
        <div class="col-sm-12">
            <center>
                <h3>Edit {{ $name }}</h3><br>
            </center>
        </div>
        <?php require(app_path() . '/Http/Controllers/Admin/Data/Edit/DevGet.php'); $allow_edit = $allow; ?>
        <?php require(app_path() . '/Http/Controllers/Admin/Data/Create/DevGet.php'); ?>
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    @if($allow)
                        <a href="{{ url('admin/developer', [$name, 'create']) }}" class="btn btn-primary btn-sm">Create</a><br>
                    @else
                        <a disabled class="btn btn-primary btn-sm">Create</a><br>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-hover center">
                            <thead>
                              <tr>
                                @foreach($columns as $column)
                                    <th class="text-center">{{$column}}</th>
                                @endforeach
                            	<th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php
                                    require(app_path() . '/Http/Controllers/Admin/Data/DevData.php');
                                    $hide = [];
                                    if(array_key_exists($name, $data)){
                                        if(array_key_exists('hide_display', $data[$name])){
                                            $hide = $data[$name]['hide_display'];
                                        }
                                    }
                                ?>
                                @foreach($rows as $row)
                                    <tr>
                                        @foreach($columns as $column)
                                            <td class="text-center">@if(in_array($column,$hide))<i>HIDDEN</i>@else @if($row->$column == "")<i>EMPTY</i>@else {{ $row->$column }} @endif @endif</td>
                                        @endforeach
                                        @if($allow_edit and \Schema::hasColumn($name, 'id'))
                                            <td class="text-center">
                                                <a href="{{ url('admin/developer', [$name, $row->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a disabled class="btn btn-primary btn-sm">Edit</a>
                                            </td>
                                        @endif
                                        <?php
                                            # Check if you're allowed to delete rows
                                            require(app_path() . '/Http/Controllers/Admin/Data/DevData.php');
                                            $del = true;
                                            if(array_key_exists($name, $data)){
                                                if(array_key_exists('delete', $data[$name])) {
                                                    if(!$data[$name]['delete']){
                                                        $del = false;
                                                    }
                                                }
                                            }
                                        ?>
                                        @if($del)
                                            <td class="text-center">
                                                <a href="{{ url('admin/developer', [$name, $row->id, 'delete']) }}" class="btn btn-danger btn-sm">Delete</a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a disabled class="btn btn-danger btn-sm">Delete</a>
                                            </td>
                                        @endif
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
