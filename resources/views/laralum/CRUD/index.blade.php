@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{  trans('laralum.database_CRUD') }}</div>
    </div>
@endsection
@section('title', trans('laralum.CRUD_title'))
@section('icon', "database")
@section('subtitle', trans('laralum.CRUD_subtitle'))
@section('content')
<div class="ui doubling stackable one column grid container">
    <div class="column">
        <div class="ui very padded segment">
            <table class="ui table ">
  			  <thead>
  			    <tr>
                  <th>{{ trans('laralum.table') }}</th>
                  <th>{{ trans('laralum.columns') }}</th>
                  <th>{{ trans('laralum.rows') }}</th>
                  <th>{{ trans('laralum.edit') }}</th>
  			    </tr>
  			  </thead>
  			  <tbody>
                @foreach($tables as $table)
                    <tr>
                        <td>{{ $table }}</td>
                        <td>{{ count(\Schema::getColumnListing($table)) }}</td>
                        <td>{{ count(\DB::table($table)->get()) }}</td>
                        <td>
                            <a href="{{ route('Laralum::CRUD_table', ['table' => $table]) }}" class="ui {{ Laralum::settings()->button_color }} button">{{ trans('laralum.edit') }}</a>
                        </td>
                    </tr>
				@endforeach
  			  </tbody>
  			</table>
        </div>
        <br>
    </div>
</div>
@endsection
