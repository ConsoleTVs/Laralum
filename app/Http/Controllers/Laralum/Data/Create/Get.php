<?php

/*
+---------------------------------------------------------------------------+
| Laralum Data Fetcher														|
+---------------------------------------------------------------------------+
|                                                               			|
| * Requires:                                                               |
|																			|
| $row - The row information                                                |
| $data_index - The Data array index for the table configuration            |
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
| $columns: the row columns 												+---------------------+
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

include('SimpleGet.php');

# Get the row table columns
$columns = Schema::getColumnListing($table);

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
