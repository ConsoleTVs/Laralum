<?php

/*
+---------------------------------------------------------------------------+
| Laralum Developer Data Fetcher											|
+---------------------------------------------------------------------------+
|                                                               			|
| * Requires:                                                               |
|																			|
| $row - The row information                                                |
|																			|
| * Available variables:                  									|
|																			|
| $data - The table settings 												|
| $table - The table name 												    +-------------+
| $hidden: Columns that will not be displayed in the edit form, and they won't be updated +----------------------------+
| $empty: Columns that will not have their current value when editing them (eg: password field is hidden in the model) |
| $confirmed: fields that will need to be confirmed twice                                                              +-+
| $encrypted: Fields that will be encrypted using: Crypt::encrypt(); when they are saved and decrypted when editing them +---------------------------+
| $hashed: Fields that will be hashed when they are saved in the database, will be empty on editing, and if saved as empty they will not be modified |
| $masked: Fields that will be displayed as a type='password', so their content when beeing modified won't be visible +------------------------------+
| $default_random: Fields that if no data is set, they will be randomly generated (10 characters) +-------------------+
| $su_hidden: Columns that will be added to the hidden array if the user is su +------------------+
| $columns: the row columns 												+--+
| $fields: get the available fields         								|
|																			|
+---------------------------------------------------------------------------+
|																			|
| This file creates the variables nessesary to make                         |
| the dynamic field edition avialable to all the controllers				|
| regardless of it's differences.                            				|
|																			|
+---------------------------------------------------------------------------+
*/

require('../app/Http/Controllers/Laralum/Data/DevData.php');


if(array_key_exists($name, $data)) {
    $data = $data[$name];
    if(!array_key_exists('create', $data)){
        $data['create'] = [];
    }
    if(!array_key_exists('edit', $data)){
        $data['edit'] = [];
    }
} else {
    $data = ['create' => [], 'edit' => []];
}




# Get the table data
$table = $name;

if(array_key_exists('allow', $data['edit'])) {
    $allow = $data['edit']['allow'];
} else {
    $allow = true;
}

if(array_key_exists('hidden', $data['edit'])) {
    $hidden = $data['edit']['hidden'];
} else {
    $hidden = [];
}

if(array_key_exists('empty', $data['edit'])) {
    $empty = $data['edit']['empty'];
} else {
    $empty = [];
}

if(array_key_exists('default_random', $data['edit'])) {
    $default_random = $data['edit']['default_random'];
} else {
    $default_random = [];
}

if(array_key_exists('confirmed', $data['edit'])) {
    $confirmed = $data['edit']['confirmed'];
} else {
    $confirmed = [];
}

if(array_key_exists('encrypted', $data['edit'])) {
    $encrypted = $data['edit']['encrypted'];
} else {
    $encrypted = [];
}

if(array_key_exists('hashed', $data['edit'])) {
    $hashed = $data['edit']['hashed'];
} else {
    $hashed = [];
}

if(array_key_exists('masked', $data['edit'])) {
    $masked = $data['edit']['masked'];
} else {
    $masked = [];
}

if(array_key_exists('validator', $data['edit'])) {
    $validator = $data['edit']['validator'];
} else {
    $validator = [];
}

if(array_key_exists('su_hidden', $data['edit'])) {
    $su_hidden = $data['edit']['su_hidden'];
} else {
    $su_hidden = [];
}

if(array_key_exists('code', $data['edit'])) {
    $code = $data['edit']['code'];
} else {
    $code = [];
}

if(array_key_exists('wysiwyg', $data['edit'])) {
    $wysiwyg = $data['edit']['wysiwyg'];
} else {
    $wysiwyg = [];
}


if(array_key_exists('relations', $data['edit'])) {
    $relations = $data['edit']['relations'];
} else {
    $relations = [];
}













# Get the row table columns
$columns = Schema::getColumnListing($table);

# Add su_hidden to hidden if the row is su
if(Schema::hasColumn($table, 'su') and $row->su) {
    # Add the su_hidden fields to the hiden variable
    foreach($su_hidden as $su_hid) {
        array_push($hidden, $su_hid);
    }
}

# Gets the fields available to edit / update
$final_columns = [];
foreach($columns as $column) {
    $add = true;
    foreach($hidden as $hide) {
        if($column == $hide) {
            $add = false;
        }
    }
    if($add) {
        array_push($final_columns, $column);
    }
}
$fields = $final_columns;
