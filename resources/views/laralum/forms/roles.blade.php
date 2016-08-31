<?php

// Setup the $row variable if it's not set but intended
if(isset($user)){
    $row = $user;
} elseif(isset($blog)){
    $row = $blog;
}

?>
@foreach($roles as $role)
    <div class="inline field">
        <div class="ui slider checkbox">
            <input
            <?php
                if(isset($row)){
                    if($row->hasRole($role->name)) {
                        echo "checked='checked' ";
                    }
                }

                $disabled = false;

                if($role->su) {
                    $disabled = true;
                }

                if(!$role->assignable and !Laralum::loggedInUser()->su){
                    $disabled = true;
                }

                if($disabled) {
                    echo "disabled";
                }
            ?>
            type="checkbox" name="{{ $role->id }}" tabindex="0" class="hidden">
            <label>{{ $role->name }}
                @if($role->su)<i class="red asterisk thin icon pop" data-variation="wide" data-position="right center" data-title="{{ trans('laralum.su_role') }}" data-content="{{ trans('laralum.su_role_desc') }}"></i>@endif
                @if(!$role->assignable and !Laralum::loggedInUser()->su)
                    <i data-variation="wide" class="red lock icon pop" data-position="right center" data-title="{{ trans('laralum.unassignable_role') }}" data-content="{{ trans('laralum.unassignable_role_desc') }}"></i>
                @endif
                @if(!$role->assignable and Laralum::loggedInUser()->su)
                    <i data-variation="wide" class="red unlock icon pop" data-position="right center" data-title="{{ trans('laralum.unassignable_role_unlocked') }}" data-content="{{ trans('laralum.unassignable_role_unlocked_desc') }}"></i>
                @endif
            </label>
        </div>
    </div>
@endforeach
