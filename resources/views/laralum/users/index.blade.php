@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">{{ trans('laralum.user_list') }}</div>
</div>
@endsection
@section('title', trans('laralum.user_list'))
@section('icon', "users")
@section('subtitle', trans('laralum.users_subtitle'))
@section('content')
  <div class="ui two column doubling stackable grid container">
  	<div class="column">
  		<div class="ui padded segment">
            {!! Laralum::widget('latest_users_graph') !!}
  		</div>
  	</div>
  	<div class="column">
  		<div class="ui padded segment">
            {!! Laralum::widget('users_country_pie_graph') !!}
  		</div>
  	</div>
  </div>

  <br><br>

  <div class="ui one column doubling stackable grid container">
  	<div class="column">
  		<div class="ui very padded segment">
  			<table class="ui table ">
  			  <thead>
  			    <tr>
                  <th>{{ trans('laralum.name') }}</th>
                  <th>{{ trans('laralum.email') }}</th>
                  <th>{{ trans('laralum.country') }}</th>
                  <th>{{ trans('laralum.options') }}</th>
  			    </tr>
  			  </thead>
  			  <tbody>
                <?php
    			  	$countries = Laralum::countries();
    			?>
                @foreach($users as $user)
					<tr>
						<td>
                            <div class="text">
                                <img id="avatar-div" class="ui avatar image" src="{!! $user->avatar() !!}">
                                <a href="{{ route('Laralum::users_profile', ['id' => $user->id]) }}">{{ $user->name }}</a>
                                @if($user->su)
                                  <div class="ui red tiny left pointing basic label pop" data-title="{{ trans('laralum.super_user') }}" data-variation="wide" data-content="{{ trans('laralum.super_user_desc') }}" data-position="top center" >{{ trans('laralum.super_user') }}</div>
                                @elseif(Laralum::isAdmin($user))
                                  <div class="ui blue tiny left pointing basic label pop" data-title="{{ trans('laralum.admin_access') }}" data-variation="wide" data-content="{{ trans('laralum.admin_access_desc') }}" data-position="top center">{{ trans('laralum.admin_access') }}</div>
                                @endif
                            </div>
                </td>
    				<td>
                        @if($user->banned)
                            <i data-position="top center" data-content="{{ trans('laralum.users_status_banned') }}" class="pop red close icon"></i>
                        @elseif(!$user->active)
                            <i data-position="top center" data-content="{{ trans('laralum.users_status_unactive') }}" class="pop orange warning icon"></i>
                        @else
                            <i data-position="top center" data-content="{{ trans('laralum.users_status_ok') }}" class="pop green checkmark icon"></i>
                        @endif
                        {{ $user->email }}
                    </td>
    				<td>@if(in_array($user->country_code, Laralum::noFlags()))<i class="help icon"></i> {{ $countries[$user->country_code] }}@else<i class="{{ strtolower($user->country_code) }} flag"></i> {{ $countries[$user->country_code] }}@endif</td>
                <td >
                      @if(!Laralum::isAdmin($user) or Laralum::loggedInUser()->su)
                      <div class="ui {{ Laralum::settings()->button_color }} top icon left pointing dropdown button">
                        <i class="configure icon"></i>
                        <div class="menu">
                          <div class="header">{{ trans('laralum.editing_options') }}</div>
                              <a href="{{ route('Laralum::users_edit', ['id' => $user->id]) }}" class="item">
                                <i class="edit icon"></i>
                                {{ trans('laralum.users_edit') }}
                              </a>
                              <a href="{{ route('Laralum::users_roles', ['id' => $user->id]) }}" class="item">
                                <i class="star icon"></i>
                                {{ trans('laralum.users_edit_roles') }}
                              </a>
                              @if($user->id != Laralum::loggedInUser()->id)
                              <div class="header">{{ trans('laralum.advanced_options') }}</div>
                              <a href="{{ route('Laralum::users_delete', ['id' => $user->id]) }}" class="item">
                                <i class="trash bin icon"></i>
                                {{ trans('laralum.users_delete') }}
                              </a>
                              @endif
                          </div>
                      </div>
                      @else
                        <div class="ui disabled {{ Laralum::settings()->button_color }} icon button">
                            <i class="lock icon"></i>
                        </div>
                      @endif
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
