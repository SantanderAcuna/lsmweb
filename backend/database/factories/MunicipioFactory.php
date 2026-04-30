<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Municipio> */
class MunicipioFactory extends Factory
{
    protected $model = Municipio::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'departamento_id' => Departamento::factory(),
            'codigo_dane' => (string) fake()->unique()->numberBetween(10000, 99999),
            'nombre' => fake()->unique()->city(),
        ];
    }
}
