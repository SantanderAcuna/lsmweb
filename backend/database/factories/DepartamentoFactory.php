<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Departamento;
use App\Models\Pais;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Departamento> */
class DepartamentoFactory extends Factory
{
    protected $model = Departamento::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'pais_id' => Pais::factory(),
            'codigo_dane' => (string) fake()->unique()->numberBetween(10, 99),
            'nombre' => fake()->unique()->state(),
        ];
    }
}
