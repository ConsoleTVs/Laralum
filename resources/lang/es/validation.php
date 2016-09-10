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

    "accepted"             => "El campo :attribute debe ser aceptado.",
    "active_url"           => "El campo :attribute no es una URL válida.",
    "after"                => "El campo :attribute debe ser una fecha posterior a :date.",
    "alpha"                => "El campo :attribute solo puede contener letras.",
    "alpha_dash"           => "El campo :attribute solo puede contener letras, números, y guiones.",
    "alpha_num"            => "El campo :attribute solo puede contener letras y números.",
    "array"                => "El campo :attribute debe ser una array.",
    "before"               => "El campo :attribute debe ser una fecha anterior a :date.",
    "between"              => [
        "numeric" => "El campo :attribute debe estar entre :min y :max.",
        "file"    => "El campo :attribute debe de ser de entre :min y :max kilobytes.",
        "string"  => "El campo :attribute debe contener entre :min y :max caracteres.",
        "array"   => "El campo :attribute debe contener entre :min y :max elementos.",
    ],
    "boolean"              => "El campo :attribute debe ser verdadero o falso.",
    "confirmed"            => "El campo :attribute de confirmación no coincide.",
    "date"                 => "El campo :attribute no es una fecha válida.",
    "date_format"          => "El campo :attribute no corresponde con el formato :format.",
    "different"            => "El campo :attribute y :other deben ser diferentes.",
    "digits"               => "El campo :attribute debe ser de :digits digitos.",
    "digits_between"       => "El campo :attribute debe contener entre :min y :max digitos.",
    'dimensions'           => "El campo :attribute tiene unas dimensiones de imagen inválidas.",
    'distinct'             => "El campo :attribute tiene un valor duplicado.",
    "email"                => "El campo :attribute debe ser una dirección de correo electrónico válida.",
    "exists"               => "El campo :attribute seleccionado és inválido.",
    'file'                 => 'El campo :attribute debe ser un archivo.',
    "filled"               => "El campo :attribute es requerido.",
    "image"                => "El campo :attribute debe ser una imagen.",
    "in"                   => "El campo :attribute seleccionado és inválido.",
    'in_array'             => 'El campo :attribute no existe en :other.',
    "integer"              => "El campo :attribute debe ser un entero.",
    "ip"                   => "El campo :attribute debe ser una dirección IP válida.",
    "json"                 => "El campo :attribute debe ser un string JSON válido.",
    "max"                  => [
        "numeric" => "El campo :attribute no puede ser mayor que :max.",
        "file"    => "El campo :attribute no puede ser de más de :max kilobytes.",
        "string"  => "El campo :attribute no puede ser de más de :max caracteres.",
        "array"   => "El campo :attribute no puede contener más de :max elementos.",
    ],
    "mimes"                => "El campo :attribute debe ser un fichero del tipo: :values.",
    "min"                  => [
        "numeric" => "El campo :attribute debe ser mayor que :min.",
        "file"    => "El campo :attribute debe ser mayor que :min kilobytes.",
        "string"  => "El campo :attribute debe ser como mínimo de :min caracteres.",
        "array"   => "El campo :attribute debe ser como mínimo de :min elementos.",
    ],
    "not_in"               => "El campo :attribute seleccionado és inválido.",
    "numeric"              => "El campo :attribute debe ser un número.",
    "regex"                => "El formato del campo :attribute és inválido.",
    "required"             => "El campo :attribute es requerido.",
    "required_if"          => "El campo :attribute es requerido cuando el campo :other es :value.",
    "required_unless"      => "El campo :attribute es requerido a no ser que el campo :other esté en :values.",
    "required_with"        => "El campo :attribute es requerido cuando :values está presente.",
    "required_with_all"    => "El campo :attribute es requerido cuando :values está presente.",
    "required_without"     => "El campo :attribute es requerido cuando :values no está presente.",
    "required_without_all" => "El campo :attribute es requerido cuando ninguno de los :values está presente.",
    "same"                 => "El campo :attribute i el campo :other deben coincidir.",
    "size"                 => [
        "numeric" => "El campo :attribute debe ser de :size.",
        "file"    => "El campo :attribute debe ser de :size kilobytes.",
        "string"  => "El campo :attribute debe ser de :size caracteres.",
        "array"   => "El campo :attribute debe ser de :size elementos.",
    ],
    "string"               => "El campo :attribute debe ser un string.",
    "timezone"             => "El campo :attribute debe ser una zona válida.",
    "unique"               => "El campo :attribute ja està en uso.",
    "url"                  => "El formato del campo :attribute es inválido.",

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
