@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::CRUD') }}">{{ trans('laralum.database_CRUD') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.CRUD_table_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.CRUD_table_title'))
@section('icon', "edit")
@section('subtitle', trans('laralum.CRUD_table_subtitle', ['table' => $name]))
@section('content')
<?php require(Laralum::dataPath() . '/Edit/DevGet.php'); $allow_edit = $allow; ?>
<?php require(Laralum::dataPath() . '/Create/DevGet.php'); ?>
<div class="ui doubling stackable one column grid container">
    <div class="column">
        <div class="ui very padded segment">
            @if($allow and Schema::hasColumn($name, 'id'))
                <a href="{{ route('Laralum::CRUD_create', ['table' => $name]) }}" class="ui {{ Laralum::settings()->button_color }} button">Create</a><br>
            @else
                <a class="ui disabled {{ Laralum::settings()->button_color }} button">Create</a><br>
            @endif
            <br>
            <div style="overflow-x:auto;">
                <table class="ui stackable table ">
      			  <thead>
      			    <tr>
                        @foreach($columns as $column)
                            <th>{{$column}}</th>
                        @endforeach
                        <th>{{ trans('laralum.edit') }}</th>
                        <th>{{ trans('laralum.delete') }}</th>
      			    </tr>
      			  </thead>
      			  <tbody>
                      <?php
                          require(Laralum::dataPath() . '/DevData.php');
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
                                  <td>@if(in_array($column,$hide))<i>HIDDEN</i>@else @if($row->$column == "")<i>EMPTY</i>@else {{ $row->$column }} @endif @endif</td>
                              @endforeach
                              @if($allow_edit and \Schema::hasColumn($name, 'id'))
                                  <td>
                                      <a href="{{ route('Laralum::CRUD_edit', ['table' => $name, 'id' => $row->id]) }}" class="ui {{ Laralum::settings()->button_color }} button">{{ trans('laralum.edit') }}</a>
                                  </td>
                              @else
                                  <td>
                                      <a class="ui disabled {{ Laralum::settings()->button_color }} button">{{ trans('laralum.edit') }}</a>
                                  </td>
                              @endif
                              <?php
                                  # Check if you're allowed to delete rows
                                  require(Laralum::dataPath() . '/DevData.php');
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
                                  <td>
                                      <a href="{{ route('Laralum::CRUD_delete', ['table' => $name, 'id' => $row->id]) }}" class="ui {{ Laralum::settings()->button_color }} button">{{ trans('laralum.delete') }}</a>
                                  </td>
                              @else
                                  <td>
                                      <a class="ui disabled {{ Laralum::settings()->button_color }} button">{{ trans('laralum.delete') }}</a>
                                  </td>
                              @endif
                          </tr>
                      @endforeach
      			  </tbody>
      			</table>
            </div>
        </div>
        <br>
    </div>
</div>
@endsection
