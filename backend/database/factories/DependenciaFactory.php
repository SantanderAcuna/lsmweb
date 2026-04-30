<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Dependencia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Dependencia> */
class DependenciaFactory extends Factory
{
    protected $model = Dependencia::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $nombre = fake()->unique()->words(2, true);

        return [
            'codigo' => strtoupper(Str::slug($nombre, '-')),
            'nombre' => 'Secretaría de ' . ucwords($nombre),
            'descripcion' => fake()->paragraph(),
            'extension' => (string) fake()->numberBetween(100, 9999),
            'correo' => Str::slug($nombre) . '@santamarta.gov.co',
            'activo' => true,
        ];
    }
}
