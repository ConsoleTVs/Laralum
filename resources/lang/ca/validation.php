<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted"             => "El camp :attribute ha de ser acceptat.",
    "active_url"           => "El camp :attribute no és una URL vàlida.",
    "after"                => "El camp :attribute ha de ser una data posterior a :date.",
    "alpha"                => "El camp :attribute només pot contenir lletres.",
    "alpha_dash"           => "El camp :attribute només pot contenir lletres, nombres, i guions.",
    "alpha_num"            => "El camp :attribute només pot contenir lletres i nombres.",
    "array"                => "El camp :attribute ha de ser una array.", 
    "before"               => "El camp :attribute ha de ser una data anterior a :date.",
    "between"              => [
        "numeric" => "El camp :attribute ha d'estar entre :min i :max.",
        "file"    => "El camp :attribute ha de ser d'entre :min i :max kilobytes.",
        "string"  => "El camp :attribute ha de contenir entre :min i :max caràcters.",
        "array"   => "El camp :attribute ha de contenir entre :min i :max elements.",
    ],
    "boolean"              => "El camp :attribute ha de ser vertader o fals.",
    "confirmed"            => "El camp :attribute de confirmació no coincideix.",
    "date"                 => "El camp :attribute no es una data vàlida.",
    "date_format"          => "El camp :attribute no correspon al format :format.",
    "different"            => "El camp :attribute i :other han de ser diferents.",
    "digits"               => "El camp :attribute ha de ser de :digits dígits.",
    "digits_between"       => "El camp :attribute ha de contenir entre :min i :max dígits.",
    'dimensions'           => "El camp :attribute té unes dimensions d'imatge invàlides.",
    'distinct'             => "El camp :attribute té un valor duplicat.",
    "email"                => "El camp :attribute ha de ser una direcció de correu electrònic vàlida.",
    "exists"               => "El camp :attribute seleccionat és invàlid.",
    'file'                 => 'El camp :attribute ha de ser un arxiu.',
    "filled"               => "Es requereix el camp :attribute.",
    "image"                => "El camp :attribute ha de ser una imatge.",
    "in"                   => "El camp :attribute seleccionat és invàlid.",
    'in_array'             => 'El camp :attribute no existeix a :other.',
    "integer"              => "El camp :attribute ha de ser un enter.",
    "ip"                   => "El camp :attribute ha de ser una direcció IP vàlida.",
    "json"                 => "El camp :attribute ha de ser un string JSON vàlid.",
    "max"                  => [
        "numeric" => "El camp :attribute no pot ser major que :max.",
        "file"    => "El camp :attribute no pot ser de més de :max kilobytes.",
        "string"  => "El camp :attribute no pot ser de més de :max caràcters.",
        "array"   => "El camp :attribute no pot contenir més de :max elements.",
    ],
    "mimes"                => "El camp :attribute ha de ser un fitxer del tipus: :values.",
    "min"                  => [
        "numeric" => "El camp :attribute ha de ser major que :min.",
        "file"    => "El camp :attribute ha de ser major que :min kilobytes.",
        "string"  => "El camp :attribute ha de ser com a mínim de :min caràcters.",
        "array"   => "El camp :attribute ha de ser com a mínim de :min elements.",
    ],
    "not_in"               => "El camp :attribute seleccionat és invàlid.",
    "numeric"              => "El camp :attribute ha de ser un nombre.",
    "regex"                => "El format del camp :attribute és invàlid.",
    "required"             => "Es requereix el camp :attribute.",
    "required_if"          => "Es requereix el camp :attribute quan el camp :other és :value.",
    "required_unless"      => "Es requereix el camp :attribute llevat que el camp :other estigui en :values.",
    "required_with"        => "Es requereix el camp :attribute quan :values està present.",
    "required_with_all"    => "Es requereix el camp :attribute quan :values està present.",
    "required_without"     => "Es requereix el camp :attribute quan :values no està present.",
    "required_without_all" => "Es requereix el camp :attribute quan cap dels :values està present.",
    "same"                 => "El camp :attribute i el camp :other han de coincidir.",
    "size"                 => [
        "numeric" => "El camp :attribute ha de ser de :size.",
        "file"    => "El camp :attribute ha de ser de :size kilobytes.",
        "string"  => "El camp :attribute ha de ser de :size caràcters.",
        "array"   => "El camp :attribute ha de ser de :size elements.",
    ],
    "string"               => "El camp :attribute ha de ser un string.",
    "timezone"             => "El camp :attribute ha de ser una zona vàlida.",
    "unique"               => "El camp :attribute ja està en ús.",
    "url"                  => "El format del camp :attribute és invàlid.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    "custom" => [
        "attribute-name" => [
            "rule-name" => "custom-message",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of 'email'. This simply helps us make messages a little cleaner.
    |
    */

    "attributes" => [],

];
