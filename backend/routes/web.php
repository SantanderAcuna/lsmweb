<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', static fn () => response()->json([
    'service' => 'Alcaldía Distrital de Santa Marta — API',
    'version' => '1.0.0',
    'docs' => '/api/v1',
]));
