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
        'enabled'   =>  true,
        'author'    =>  "Aitor Riba & Xavier Jorba",
        'website'   =>  "http://aitorriba.ml",
    ],
    'ca'    =>  [
        'name'  =>  "Català",
        'type'  =>  'image',
        'type_data' => asset(Laralum::publicPath() . '/images/ca.png'),
        'enabled'   =>  true,
        'author'    =>  "Aitor Riba & Xavier Jorba",
        'website'   =>  "http://aitorriba.ml",
    ],
];
