<?php

/*
+---------------------------------------------------------------------------+
| Laralum Simple Data Fetcher									      		|
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
|																			+--+
+---------------------------------------------------------------------------+
|																			|
| This file creates the variables nessesary to make                         |
| the dynamic field edition avialable to all the controllers				|
| regardless of it's differences.                            				|
|																			|
+---------------------------------------------------------------------------+
*/

# Get the table settings

require('../app/Http/Controllers/Admin/Data/Data.php');

if(!isset($data_index)) {
    die("<font color='red'>[LARALUM ERROR]</font> You need to provide a data_index variable");
}

if(array_key_exists($data_index, $data)) {
    $data = $data[$data_index];
} else {
    die("<font color='red'>[LARALUM ERROR]</font> data_index '" . $data_index . "' does not have any existing data on it (the key does not exist), check: app/Http/Controllers/Admin/Data/Data.php");
}

# Get the table data
if(array_key_exists('table', $data)) {
    $table = $data['table'];
} else {
    die("<font color='red'>[LARALUM ERROR]</font> data_index '" . $data_index . "' does not have any existing table name 'table' key, check: app/Http/Controllers/Admin/Data/Data.php");
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
