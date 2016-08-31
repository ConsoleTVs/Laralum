<?php

$locales = [
    'en'    =>  [
        'name'  =>  "English",
        'type'  =>  'flag',
        'type_data' => 'gb',
        'enabled'   =>  true,
        'author'    =>  "Èrik Campobadal Forés",
        'website'   =>  "https://erik.cat",
    ],
    'es'    =>  [
        'name'  =>  "Castellano",
        'type'  =>  'flag',
        'type_data' => 'es',
        'enabled'   =>  false,
        'author'    =>  "Èrik Campobadal Forés",
        'website'   =>  "https://erik.cat",
    ],
    'ca'    =>  [
        'name'  =>  "Català",
        'type'  =>  'image',
        'type_data' => asset(Laralum::publicPath() . '/images/ca.png'),
        'enabled'   =>  false,
        'author'    =>  "Èrik Campobadal Forés",
        'website'   =>  "https://erik.cat",
    ],
];
