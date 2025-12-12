<?php

return [

    // ───── Basic validation rules ───────────────────────────
    'required' => 'The field {%1} is required',
    'email' => 'The {%1} field must be a valid email address',
    'numeric' => 'The {%1} field must be a number',
    'integer' => 'The {%1} field must be a number without a decimal',
    'float' => 'The {%1} field must be a number with a decimal point',
    'boolean' => 'The {%1} field has to be either true or false',
    'date' => 'The {%1} must be a valid date',
    'regex' => 'The {%1} field needs to contain a value with valid format',
    'jsonString' => 'The {%1} field needs to contain a valid JSON format string',

    // ───── Min/Max rules ────────────────────────────────────
    'minLen' => 'The {%1} field needs to be at least {%2} characters',
    'maxLen' => 'The {%1} field needs to be {%2} characters or less',
    'exactLen' => 'The {%1} field needs to be exactly {%2} characters',
    'minNumeric' => 'The {%1} field needs to be numeric and >= {%2}',
    'maxNumeric' => 'The {%1} field needs to be numeric and <= {%2}',

    // ───── String type rules ────────────────────────────────
    'alpha' => 'The {%1} field may only contain letters',
    'alphaNumeric' => 'The {%1} field may only contain letters and numbers',
    'alphaDash' => 'The {%1} field may only contain letters, dashes and underscores',
    'alphaSpace' => 'The {%1} field may only contain letters and spaces',

    // ───── Special content rules ─────────────────────────────
    'url' => 'The {%1} field has to be a URL',
    'urlExists' => 'The {%1} URL does not exist',

    'ip' => 'The {%1} field needs to be a valid IP address',
    'ipv4' => 'The {%1} field needs to be a valid IPv4 address',
    'ipv6' => 'The {%1} field needs to be a valid IPv6 address',

    'creditCard' => 'The {%1} is not a valid credit card number',
    'streetAddress' => 'The {%1} field needs to be a valid street address',

    'iban' => 'The {%1} field needs to contain a valid IBAN',

    // ───── Comparison rules ──────────────────────────────────
    'same' => 'The {%1} field should be same as the {%2}',
    'same_password' => 'The {%1} field should match the new password',
    'nonEqualValues' => 'Values are not equal',
    'unique' => 'The {%1} field should contain only unique value',

    // ───── Contains rules ────────────────────────────────────
    'contains' => 'The {%1} can only be one of: {%2}',
    'containsList' => 'The {%1} is not a valid option',
    'doesntContainsList' => 'The {%1} field contains a value that is not accepted',

    // ───── Custom project-specific rules ─────────────────────
    'token_exists' => 'The activation token is invalid or expired',
    'exists' => 'The {%1} does not exist in our records',
    'uniqueUser' => 'The value of {%1} field already exists in our database',
    'nonExistingRecord' => 'There is no record matching the {%1}',
    'unauthorizedRequest' => 'Unauthorized request',

    // ───── File rules ─────────────────────────────────────────
    'fileSize' => 'The file size should correspond to the {%1}',
    'fileMimeType' => 'The file mimetype should correspond to the {%1}',
    'fileExtension' => 'The file extension should correspond to the {%1}',
    'imageDimensions' => 'The image dimensions should correspond to the {%1}',

    // ───── Auth/password rules ────────────────────────────────
    'passwordsDoNotMatch' => 'Passwords do not match',
    'passwordCheck' => 'The current password is incorrect',

    // These are labels, not errors
    'new_password' => 'New password',
    'current_password' => 'Current Password',
    'confirm_password' => 'Confirm Password',

    // ───── Captcha ───────────────────────────────────────────
    'captcha' => [
        'timeout-or-duplicate' => 'Timeout or duplicate.',
        'missing-input-secret' => 'The secret parameter is missing.',
        'invalid-input-secret' => 'The secret parameter is invalid or malformed.',
        'missing-input-response' => 'The response parameter is missing.',
        'invalid-input-response' => 'The response parameter is invalid or malformed.',
        'bad-request' => 'The request is invalid or malformed.',
        'internal-empty-response' => 'The captcha response is required.',
        'replay-attack' => 'Potential replay attack detected.',
    ],
];