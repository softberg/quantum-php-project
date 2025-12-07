<?php

return [

    // ───── Basic validation rules ───────────────────────────
    'required' => 'El campo {%1} es obligatorio',
    'email' => 'El campo {%1} debe ser una dirección de correo válida',
    'numeric' => 'El campo {%1} debe ser un número',
    'integer' => 'El campo {%1} debe ser un número sin decimales',
    'float' => 'El campo {%1} debe ser un número con punto decimal',
    'boolean' => 'El campo {%1} debe ser verdadero o falso',
    'date' => 'El campo {%1} debe ser una fecha válida',
    'regex' => 'El campo {%1} debe contener un valor con un formato válido',
    'jsonString' => 'El campo {%1} debe contener una cadena en formato JSON válido',

    // ───── Min/Max rules ────────────────────────────────────
    'minLen' => 'El campo {%1} debe tener al menos {%2} caracteres',
    'maxLen' => 'El campo {%1} debe tener {%2} caracteres o menos',
    'exactLen' => 'El campo {%1} debe tener exactamente {%2} caracteres',
    'minNumeric' => 'El campo {%1} debe ser numérico y ser >= {%2}',
    'maxNumeric' => 'El campo {%1} debe ser numérico y ser <= {%2}',

    // ───── String type rules ────────────────────────────────
    'alpha' => 'El campo {%1} solo puede contener letras',
    'alphaNumeric' => 'El campo {%1} solo puede contener letras y números',
    'alphaDash' => 'El campo {%1} solo puede contener letras, guiones y guiones bajos',
    'alphaSpace' => 'El campo {%1} solo puede contener letras y espacios',

    // ───── Special content rules ─────────────────────────────
    'url' => 'El campo {%1} debe ser una URL',
    'urlExists' => 'La URL en {%1} no existe',

    'ip' => 'El campo {%1} debe ser una dirección IP válida',
    'ipv4' => 'El campo {%1} debe ser una dirección IPv4 válida',
    'ipv6' => 'El campo {%1} debe ser una dirección IPv6 válida',

    'creditCard' => 'El campo {%1} no es un número de tarjeta de crédito válido',
    'streetAddress' => 'El campo {%1} debe ser una dirección válida',

    'iban' => 'El campo {%1} debe contener un IBAN válido',

    // ───── Comparison rules ──────────────────────────────────
    'same' => 'El campo {%1} debe ser igual al campo {%2}',
    'same_password' => 'El campo {%1} debe coincidir con la nueva contraseña',
    'nonEqualValues' => 'Los valores no son iguales',

    // ───── Contains rules ────────────────────────────────────
    'contains' => 'El campo {%1} solo puede ser uno de: {%2}',
    'containsList' => 'El valor del campo {%1} no es una opción válida',
    'doesntContainsList' => 'El campo {%1} contiene un valor que no es aceptado',

    // ───── Custom project-specific rules ─────────────────────
    'token_exists' => 'El token de activación es inválido o ha expirado',
    'exists' => 'El valor de {%1} no existe en nuestros registros',
    'uniqueUser' => 'El valor del campo {%1} ya existe en nuestra base de datos',
    'nonExistingRecord' => 'No hay ningún registro que coincida con {%1}',
    'unauthorizedRequest' => 'Solicitud no autorizada',

    // ───── File rules ─────────────────────────────────────────
    'fileSize' => 'El tamaño del archivo debe corresponder a {%1}',
    'fileMimeType' => 'El tipo MIME del archivo debe corresponder a {%1}',
    'fileExtension' => 'La extensión del archivo debe corresponder a {%1}',
    'imageDimensions' => 'Las dimensiones de la imagen deben corresponder a {%1}',

    // ───── Auth/password rules ────────────────────────────────
    'passwordsDoNotMatch' => 'Las contraseñas no coinciden',
    'passwordCheck' => 'La contraseña actual es incorrecta',

    // These are labels, not errors
    'new_password' => 'Nueva contraseña',
    'current_password' => 'Contraseña actual',
    'confirm_password' => 'Confirmar contraseña',

    // ───── Captcha ───────────────────────────────────────────
    'captcha' => [
        'timeout-or-duplicate' => 'Tiempo agotado o duplicado.',
        'missing-input-secret' => 'Falta el parámetro secreto.',
        'invalid-input-secret' => 'El parámetro secreto es inválido o tiene un formato incorrecto.',
        'missing-input-response' => 'Falta el parámetro de respuesta.',
        'invalid-input-response' => 'El parámetro de respuesta es inválido o tiene un formato incorrecto.',
        'bad-request' => 'La solicitud es inválida o tiene un formato incorrecto.',
        'internal-empty-response' => 'La respuesta del captcha es obligatoria.',
        'replay-attack' => 'Se detectó un posible ataque de repetición.',
    ],
];