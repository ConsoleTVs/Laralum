<fieldset>
    @if(!$fields)
        <div class="row">
            <div class="lateral-spacer">
                <div class="col-lg-12">
                    <p>There aren't any available fields to edit</p>
                </div>
            </div>
        </div>
    @endif

@foreach($fields as $field)
    <?php

        $error = false;
        $warning = false;

        $code_script = false;
        $editor_script = false;

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
                    $error = "Cannot decrypt the value";
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

    ?>

    @if($editor_script or $code_script)

        <!-- COUNTRY CODE COLUMN -->
        <div class="row down-spacer">
            <div class="lateral-spacer">
                <label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
                <div class="col-lg-10">
                    <center>
                        <button type="button" data-toggle="modal" data-target="#edit-{{ $field }}" class="btn btn-default">Edit {{ $show_field }}</button>
                    </center>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="edit-{{ $field }}" tabindex="-1" role="dialog" aria-labelledby="{{ $field }}Label">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="{{ $field }}Label">Edit {{ $show_field }}</h4>
                    </div>
                    <div class="modal-body">
                        @if($editor_script)
                            <textarea name="{{ $field }}" class="editor">{{ $value }}</textarea>
                        @elseif($code_script)
                            <pre class="code" id="{{ $field }}-code">{{ $value }}</pre>
                            <textarea hidden name="{{ $field }}" id="{{ $field }}">{{ $value }}</textarea>
                        @endif
                        <script>
                            var editor = ace.edit("{{ $field }}-code");
                            editor.getSession().on('change', function(){
                                $("#{{ $field }}").val(editor.getSession().getValue());
                            });
                        </script>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
                    </div>
                </div>
            </div>
        </div>

    @else

        @if($type == 'string')

                @if($field == 'country_code')

                    <!-- COUNTRY CODE COLUMN -->
                    <div class="row down-spacer">
                        <div class="lateral-spacer">
                            <label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="{{ $field }}" id="{{ $field }}">
                                    <option disabled selected value="">Select the country</option>
                                    <?php
                                        $json = file_get_contents('admin_panel/assets/countries/names.json');
                                        $countries = json_decode($json, true);
                                    ?>
                                    @foreach($countries as $country)
                                        <option <?php if($value == array_search($country, $countries)){echo "selected";} ?> value="{{ array_search($country, $countries) }}">{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                @else

                    <!-- STRING COLUMN -->
                    <div class="row down-spacer">
                        <div class="lateral-spacer form-group @if($error) has-error @elseif($warning) has-warning @endif">
                            <label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
                            <div class="col-lg-10">
                              <input  type="{{ $input_type }}" class="form-control" id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
                              @if($error or $warning)
                                <span class="help-block">{{ $error }}{{ $warning }}</span>
                              @endif
                            </div>
                        </div>
                    </div>

                @endif

            @elseif($type == 'integer')

                <!-- INTEGER COLUMN -->
                <div class="row down-spacer">
                    <div class="lateral-spacer form-group @if($error) has-error @elseif($warning) has-warning @endif">
                        <label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
                        <div class="col-lg-10">
                          <input type="{{ $input_type }}" class="form-control" id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
                          @if($error or $warning)
                            <span class="help-block">{{ $error }}{{ $warning }}</span>
                          @endif
                        </div>
                    </div>
                </div>

            @elseif($type == 'boolean')

                <!-- BOOLEAN COLUMN -->
                <div class="row little-down-spacer">
                    <div class="lateral-spacer form-group @if($error) has-error @elseif($warning) has-warning @endif">
                        <label for="bio" class="col-md-2 control-label">{{ $show_field }}</label>
                        <div class="col-md-10">
                            <div class="checkbox">
                                <label>
                                    <input <?php if($value){ echo "checked='checked'"; } ?> name="{{ $field }}" type="checkbox"> {{ $show_field }}
                                </label>
                            </div>
                            @if($error or $warning)
                              <span class="help-block">{{ $error }}{{ $warning }}</span>
                            @endif
                        </div>
                    </div>
                </div>

            @elseif($type == 'text')

                <!-- TEXT COLUMN -->
                <div class="row down-spacer">
                    <div class="lateral-spacer form-group @if($error) has-error @elseif($warning) has-warning @endif">
                        <label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
                        <div class="col-lg-10">
                          <textarea placeholder="{{ $show_field }}" name="{{ $field }}" class="form-control" rows="3" id="{{ $field }}">{{ $value }}</textarea>
                          @if($error or $warning)
                            <span class="help-block">{{ $error }}{{ $warning }}</span>
                          @endif
                        </div>
                    </div>
                </div>

            @else

                <!-- ALL OTHER COLUMN -->
                <div class="row down-spacer">
                    <div class="lateral-spacer form-group @if($error) has-error @elseif($warning) has-warning @endif">
                        <label for="{{ $field }}" class="col-lg-2 control-label">{{ $show_field }}</label>
                        <div class="col-lg-10">
                          <input  type="{{ $input_type }}" class="form-control" id="{{ $field }}" name="{{ $field }}" placeholder="{{ $show_field }}" value="{{ $value }}">
                          @if($error or $warning)
                            <span class="help-block">{{ $error }}{{ $warning }}</span>
                          @endif
                        </div>
                    </div>
                </div>

            @endif
        @foreach($confirmed as $confirm)
            @if($field == $confirm)
                @if($type == 'string')

                    <!-- STRING CONFIRMATION CONFIRMATION -->
                    <div class="row down-spacer">
                        <div class="lateral-spacer">
                            <label for="{{ $field }}_confirmation" class="col-lg-2 control-label">{{ $show_field }}</label>
                            <div class="col-lg-10">
                              <input type="{{ $input_type }}" class="form-control" id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="{{ $value }}">
                            </div>
                        </div>
                    </div>

                @elseif($type == 'integer')

                    <!-- INTEGER COLUMN CONFIRMATION -->
                    <div class="row down-spacer">
                        <div class="lateral-spacer">
                            <label for="{{ $field }}_confirmation" class="col-lg-2 control-label">{{ $show_field }}</label>
                            <div class="col-lg-10">
                              <input type="{{ $input_type }}" class="form-control" id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="{{ $value }}">
                            </div>
                        </div>
                    </div>

                @elseif($type == 'boolean')

                    <!-- BOOLEAN CONFIRMATION -->
                    <div class="row little-down-spacer">
                        <div class="lateral-spacer">
                            <label for="bio" class="col-md-2 control-label">{{ $show_field }}</label>
                            <div class="col-md-10">
                                <div class="checkbox">
                                    <label>
                                        <input <?php if($value){ echo "checked='checked'"; } ?> name="{{ $field }}_confirmation" type="checkbox"> {{ $show_field }} confirmation
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($type == 'text')

                    <!-- TEXT COLUMN -->
                    <div class="row down-spacer">
                        <div class="lateral-spacer">
                            <label for="{{ $field }}_confirmation" class="col-lg-2 control-label">{{ $show_field }}</label>
                            <div class="col-lg-10">
                              <textarea placeholder="{{ $show_field }}" name="{{ $field }}_confirmation" class="form-control" rows="3" id="{{ $field }}_confirmation">{{ $value }}</textarea>
                            </div>
                        </div>
                    </div>

                @else

                <!-- ALL OTHER COLUMN CONFIRMATION -->
                <div class="row down-spacer">
                    <div class="lateral-spacer">
                        <label for="{{ $field }}_confirmation" class="col-lg-2 control-label">{{ $show_field }}</label>
                        <div class="col-lg-10">
                          <input  type="{{ $input_type }}" class="form-control" id="{{ $field }}_confirmation" name="{{ $field }}_confirmation" placeholder="{{ $show_field }} confirmation" value="{{ $value }}">
                        </div>
                    </div>
                </div>

                @endif

            @endif
        @endforeach

    @endif

@endforeach

</fieldset>
