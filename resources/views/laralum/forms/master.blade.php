
@if(!$fields)
    <div class="ui  message segment">
        <div class="header">
            {{ trans('laralum.form_no_fields_title') }}
        </div>
        <p>{{ trans('laralum.form_no_fields_subtitle') }}</p>
    </div>
@endif


@foreach($fields as $field)
   <?php

       $error = false;
       $warning = false;

       $code_script = false;
       $editor_script = false;
       $relation = false;

       # Setup the value
       $empty_value = false;
       if(isset($empty)) {
           foreach($empty as $emp) {
               if($field == $emp) {
                   $empty_value = true;
               }
           }
       }
       if($empty_value or !isset($row)) {
           $value = "";
       } else {
           $value = $row->$field;

           # Check if the value needs to be decrypted
           $decrypt = false;
           foreach($encrypted as $encrypt) {
               if($field == $encrypt) {
                   if($value != ''){
                       $decrypt = true;
                   }
               }
           }
           if($decrypt) {
               try {
                   $value = Crypt::decrypt($value);
               } catch (Exception $e) {
                   $error = trans('laralum.decrypt_fail');
               }
           }

           # Check if it's a hashed value, to display it empty
           $hashed_value = false;
           foreach($hashed as $hash) {
               if($field == $hash) {
                   $hashed_value = true;
               }
           }
           if($hashed_value) {
               $value = "";
           }
       }

       $show_field = str_replace('_', ' ', ucfirst($field));

       $type = Schema::getColumnType($table, $field);


       # Set the input type
       if($type == 'string') {
           $input_type = "text";
       } elseif($type == 'integer') {
           $input_type = "number";
       } else {
           $input_type = "text";
       }

       # Check if it needs to be masked
       foreach($masked as $mask) {
           if($mask == $field) {
               $input_type = "password";
           }
       }

       # Check if it's a code
       foreach($code as $cd) {
           if($cd == $field) {
               $code_script = true;
           }
       }

       # Check for the editor values
       foreach($wysiwyg as $ed) {
           if($ed == $field) {
               $editor_script = true;
           }
       }

       # Check if it's a relation
       if(array_key_exists($field, $relations)) {
           $relation = true;
       }

   ?>

   @if($editor_script or $code_script)

      <div class="field">
          <label>{{ $show_field }}</label>
          @if($editor_script)
              <textarea  name="{{ $field }}" class="ckeditor">{{ $value }}</textarea>
          @elseif($code_script)
              <pre class="code" id="{{ $field }}-code">{{ $value }}</pre>
              <textarea hidden name="{{ $field }}" id="{{ $field }}">{{ $value }}</textarea>
              <script>
                  var editor = ace.edit("{{ $field }}-code");
                  editor.getSession().on('change', function(){
                      $("#{{ $field }}").val(editor.getSession().getValue());
                  });
              </script>

          @endif
      </div>



   @elseif($relation)

        <div class="field">
            <label>{{ $show_field }}</label>
            <select name="{{ $field }}" id="{{ $field }}" class="ui search dropdown">
                @foreach($relations[$field]['data'] as $relation_data)
                    <?php $relation_value = $relations[$field]['value']; $relation_show = $relations[$field]['show']; ?>
                    <option <?php if($value == $relation_data->$relation_value){ echo "selected"; } ?> value="{{ $relation_data->$relation_value }}">{{ $relation_data->$relation_show }}</option>
                @endforeach
            </select>
        </div>

   @else

       @if($type == 'string')

               @if($field == Laralum::countryCodeField())


                    <?php $cc_value = $value; $cc_id = $field; ?>

                    <?php $no_flags = Laralum::noFlags() ?>

                    <?php
                        $countries = Laralum::countries();
                    ?>
                    <div class="field">
                        <label>{{ $show_field }}</label>
                        <div class="ui fluid search selection dropdown" id="{{ $field }}_dropdown">
                            <input type="hidden" name="{{ $field }}" id="{{ $field }}">
                            <i class="dropdown icon"></i>
                            <div class="default text">{{ $show_field }}</div>
                            <div class="menu">
                                @foreach($countries as $country)
                                <?php $cc_field_value = array_search($country, $countries); ?>
                                    <div class="item" data-value="{{ $cc_field_value }}">@if(in_array($cc_field_value, $no_flags))<i class="help icon"></i>@else<i class="{{ strtolower($cc_field_value) }} flag"></i>@endif{{ $country }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
               @else

                   <!-- STRING COLUMN -->
                   <div class="field @if($error) error @endif">
                        <label>{{ $show_field }}</label>
                        <input type="{{ $input_type }}"  id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
                        @if($error)
                            <div class="ui pointing red basic label">
                                {{ $error }}
                            </div>
                        @endif
                   </div>

               @endif

           @elseif($type == 'integer')

               <!-- INTEGER COLUMN -->
               <div class="field @if($error) error @endif">
                    <label>{{ $show_field }}</label>
                    <input type="{{ $input_type }}"  id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
                    @if($error)
                        <div class="ui pointing red basic label">
                            {{ $error }}
                        </div>
                    @endif
               </div>

           @elseif($type == 'boolean')



                <!-- BOOLEAN COLUMN -->
                <div class="inline field">
                    <div class="ui slider checkbox">
                        <input <?php if($value){ echo "checked='checked'"; } ?> type="checkbox" tabindex="0" class="hidden" name="{{ $field }}">
                        <label>{{ $show_field }}</label>
                    </div>
                    @if($error)
                        <div class="ui left pointing red basic label">
                            {{ $error }}
                        </div>
                    @endif
                </div>


           @elseif($type == 'text')

                <!-- TEXT COLUMN -->
                <div class="field @if($error) error @endif">
                    <label>{{ $show_field }}</label>
                    <textarea placeholder="{{ $show_field }}" name="{{ $field }}" rows="3" id="{{ $field }}">{{ $value }}</textarea>
                    @if($error)
                        <div class="ui pointing red basic label">
                            {{ $error }}
                        </div>
                    @endif
                </div>

           @else

               <!-- ALL OTHER COLUMN -->
               <div class="field @if($error) error @endif">
                    <label>{{ $show_field }}</label>
                    <input type="{{ $input_type }}"  id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
                    @if($error)
                        <div class="ui pointing red basic label">
                            {{ $error }}
                        </div>
                    @endif
               </div>

           @endif
       @foreach($confirmed as $confirm)
           @if($field == $confirm)
               @if($type == 'string')

                   <!-- STRING CONFIRMATION -->
                   <div class="field @if($error) error @endif">
                        <label>{{ $show_field }} {{ trans('laralum.confirmation') }}</label>
                        <input type="{{ $input_type }}"  id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="{{ $value }}">
                        @if($error)
                            <div class="ui pointing red basic label">
                                {{ $error }}
                            </div>
                        @endif
                   </div>

               @elseif($type == 'integer')

                   <!-- INTEGER COLUMN CONFIRMATION -->
                   <div class="field @if($error) error @endif">
                        <label>{{ $show_field }} {{ trans('laralum.confirmation') }}</label>
                        <input type="{{ $input_type }}"  id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="{{ $value }}">
                        @if($error)
                            <div class="ui pointing red basic label">
                                {{ $error }}
                            </div>
                        @endif
                   </div>

               @elseif($type == 'boolean')

                   <!-- BOOLEAN CONFIRMATION -->
                   <div class="inline field">
                       <div class="ui slider checkbox">
                           <input <?php if($value){ echo "checked='checked'"; } ?> type="checkbox" tabindex="0" class="hidden" name="{{ $field }}_confirmation">
                           <label>{{ $show_field }} {{ trans('laralum.confirmation') }}</label>
                       </div>
                       @if($error)
                           <div class="ui left pointing red basic label">
                               {{ $error }}
                           </div>
                       @endif
                   </div>

               @elseif($type == 'text')

                   <!-- TEXT COLUMN -->
                   <div class="field @if($error) error @endif">
                       <label>{{ $show_field }} {{ trans('laralum.confirmation') }}</label>
                       <textarea placeholder="{{ $show_field }}" name="{{ $field }}_confirmation" rows="3" id="{{ $field }}_confirmation">{{ $value }}</textarea>
                       @if($error)
                           <div class="ui pointing red basic label">
                               {{ $error }}
                           </div>
                       @endif
                   </div>

               @else

               <!-- ALL OTHER COLUMN CONFIRMATION -->
               <div class="field @if($error) error @endif">
                    <label>{{ $show_field }} {{ trans('laralum.confirmation') }}</label>
                    <input type="{{ $input_type }}"  id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="{{ $value }}">
                    @if($error)
                        <div class="ui pointing red basic label">
                            {{ $error }}
                        </div>
                    @endif
               </div>

               @endif

           @endif
       @endforeach

   @endif

@endforeach
@section('js')
    @if(isset($cc_id) and isset($cc_value))
        <script>
            $("#{{ $cc_id }}_dropdown").dropdown("set selected", "{{ $cc_value }}");
            $("#{{ $cc_id }}_dropdown").dropdown("refresh");
        </script>
    @endif
@endsection
