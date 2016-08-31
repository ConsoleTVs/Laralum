@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">{{ trans('laralum.roles_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.roles_title'))
@section('icon', "star")
@section('subtitle', trans('laralum.roles_subtitle'))
@section('content')
  <div class="ui two column doubling stackable grid container">
  	<div class="column">
  		<div class="ui padded  segment">
            <?php
                $title = trans('laralum.roles_graph1');
                $labels = [];
                $data = [];
                $colors = [];
                foreach($roles as $role){
                    array_push($labels, $role->name);
                    array_push($data, count($role->users));
                    array_push($colors, $role->color);
                }
            ?>
            {!! Laralum::pieChart($title, $labels, $data, $colors) !!}
  		</div>
  	</div>
  	<div class="column">
  		<div class="ui padded  segment">
            <?php
                $title = trans('laralum.roles_graph2');
                $labels = [];
                $data = [];
                $colors = [];
                foreach($roles as $role){
                    array_push($labels, $role->name);
                    array_push($data, count($role->permissions));
                    array_push($colors, $role->color);
                }
            ?>
            {!! Laralum::pieChart($title, $labels, $data, $colors) !!}
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
                  <th>{{ trans('laralum.users') }}</th>
                  <th>{{ trans('laralum.permissions') }}</th>
                  <th>{{ trans('laralum.options') }}</th>
  			    </tr>
  			  </thead>
  			  <tbody>
                @foreach($roles as $role)
					<tr>
						<td>
                            <div class="text">
                                {{ $role->name }}
                                @if($role->su)
                                  <div class="ui red tiny left pointing basic label pop" data-title="{{ trans('laralum.super_user_role') }}" data-variation="wide" data-content="{{ trans('laralum.super_user_role_desc') }}" data-position="top center" >{{ trans('laralum.super_user_role') }}</div>
                                @elseif($role->hasPermission('laralum.access'))
                                  <div class="ui blue tiny left pointing basic label pop" data-title="{{ trans('laralum.admin_access_role') }}" data-variation="wide" data-content="{{ trans('laralum.admin_access_role_desc') }}" data-position="top center">{{ trans('laralum.admin_access_role') }}</div>
                                @endif
                            </div>
                        </td>
                        <td>{{ trans('laralum.roles_users', ['number' => count($role->users)]) }}</td>
                        <td>{{ trans('laralum.roles_permissions', ['number' => count($role->permissions)]) }}</td>
                        <td>
                          @if($role->allow_editing or Laralum::loggedInUser()->su)
                              <div class="ui {{ Laralum::settings()->button_color }} top icon left pointing dropdown button">
                                <i class="configure icon"></i>
                                <div class="menu">
                                  <div class="header">{{ trans('laralum.editing_options') }}</div>
                                  <a href="{{ route('Laralum::roles_edit', ['id' => $role->id]) }}" class="item">
                                    <i class="edit icon"></i>
                                    {{ trans('laralum.roles_edit') }}
                                  </a>
                                  <a href="{{ route('Laralum::roles_permissions', ['id' => $role->id]) }}" class="item">
                                    <i class="lightning icon"></i>
                                    {{ trans('laralum.roles_edit_permissions') }}
                                  </a>
                                  @if(!$role->su and $role->id != Laralum::defaultRole()->id)
                                  <div class="header">{{ trans('laralum.advanced_options') }}</div>
                                  <a href="{{ route('Laralum::roles_delete', ['id' => $role->id]) }}" class="item">
                                    <i class="trash bin icon"></i>
                                    {{ trans('laralum.roles_delete') }}
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
