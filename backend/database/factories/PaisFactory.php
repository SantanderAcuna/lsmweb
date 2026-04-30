<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pais;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Pais> */
class PaisFactory extends Factory
{
    protected $model = Pais::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'codigo_iso' => strtoupper(fake()->unique()->lexify('???')),
            'nombre' => fake()->unique()->country(),
        ];
    }
}
