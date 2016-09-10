@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">{{ trans('laralum.laralum_API') }}</div>
</div>
@endsection
@section('title', trans('laralum.laralum_API'))
@section('icon', "exchange")
@section('subtitle', trans('laralum.API_subtitle'))
@section('content')
  <div class="ui one column doubling stackable grid container">
  	<div class="column">
  		<div class="ui very padded segment">
  			<table class="ui table ">
  			  <thead>
  			    <tr>
                  <th>{{ trans('laralum.API_url') }}</th>
                  <th>{{ trans('laralum.API_show') }}</th>
  			    </tr>
  			  </thead>
  			  <tbody>
                <?php
    			  	$api = Laralum::apiData();
    			?>
                @foreach($api as $a => $data)
		            <tr>
        				<td>
                            @if($data['enabled'])
                                <i class="green checkmark icon"></i>
                            @else
                                <i class="red close icon"></i>
                            @endif
                            <a href="{{ route('API::show', ['table' => $a]) }}">/{{ $a }}/{accessor?}/{data?}</a>
                        </td>
                        <td>
                            @foreach($data['show'] as $d)
                                <div class="ui basic label">{{ $d }}</div>
                            @endforeach
                        </td>
					</tr>
                    <tr>
        				<td>
                            @if($data['enabled'])
                                <i class="green checkmark icon"></i>
                            @else
                                <i class="red close icon"></i>
                            @endif
                            <a href="{{ route('API::show', ['table' => $a, 'accessor' => 'latest']) }}">/{{ $a }}/latest</a>
                        </td>
                        <td>
                            @foreach($data['show'] as $d)
                                <div class="ui basic label">{{ $d }}</div>
                            @endforeach
                        </td>
					</tr>
                    <tr>
        				<td>
                            @if($data['enabled'])
                                <i class="green checkmark icon"></i>
                            @else
                                <i class="red close icon"></i>
                            @endif
                            <a href="{{ route('API::show', ['table' => $a, 'accessor' => 'latests']) }}">/{{ $a }}/latests/{number?}</a>
                        </td>
                        <td>
                            @foreach($data['show'] as $d)
                                <div class="ui basic label">{{ $d }}</div>
                            @endforeach
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
