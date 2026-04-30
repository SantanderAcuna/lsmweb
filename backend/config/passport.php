<?php

declare(strict_types=1);

return [
    'guard' => 'web',
    'personal_access_token_expiration' => null,
    'token_expiration' => null,
    'refresh_token_expiration' => null,
    'private_key' => env('PASSPORT_PRIVATE_KEY'),
    'public_key' => env('PASSPORT_PUBLIC_KEY'),
    'client_uuids' => false,
];
