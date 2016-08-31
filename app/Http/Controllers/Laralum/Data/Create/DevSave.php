<?php

/*
+---------------------------------------------------------------------------+
| Laralum Data Saver														|
+---------------------------------------------------------------------------+
|                                                               			|
| * Requires:                                                               |
|																			|
| $row - The row information                                                |
| $request - The form requrest information                                  |
|																			|
+---------------------------------------------------------------------------+
|																			|
| This files saves the new information to the database                      |                       				|
|																			|
+---------------------------------------------------------------------------+
*/

include('DevGet.php');

# Validate The Request
$this->validate($request, $validator);

# Update the row
foreach($fields as $field) {

    $save = true;

    # Check the field type
    $type = Schema::getColumnType($table, $field);

    # Get the value
    $value = $request->input($field);

    if($type == 'string' or $type == 'integer') {

        # Check if it's a default_random field
        foreach($default_random as $random) {
            if($random == $field) {
                if(!$value) {
                    $value = str_random(10);
                }
            }
        }

        # Check if it's a hashed field
        foreach($hashed as $hash) {
            if($hash == $field) {
                if($value) {
                    $value = Hash::make($value);
                } else {
                    $save = false;
                }
            }
        }

        # Check if it's an encrypted field
        foreach($encrypted as $encrypt) {
            if($encrypt == $field) {
                $value = Crypt::encrypt($value);
            }
        }

        # Check if it's a relation and if the value is on the dropdown
        if(array_key_exists($field, $relations)) {
            if($value == "" or !Laralum::checkValueInRelation($relations[$field]['data'], $value, $relations[$field]['value'])){
                abort(403, trans('laralum.error_relation_value', ['field' => $field]));
            }
        }

        # Save it
        if($save) {
            $update[$field] = $value;
        }

    } elseif($type == 'boolean') {

        # Save it
        if($value) {
            $update[$field] = true;
        } else {
            $update[$field] = false;
        }

    } else {
        # Save it
        $update[$field] = $value;
    }
}

# Save the row
DB::table($name)->insert($update);
