<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Municipio;
use App\Models\Sede;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Sede> */
class SedeFactory extends Factory
{
    protected $model = Sede::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'nombre' => 'Sede ' . fake()->unique()->company(),
            'municipio_id' => Municipio::factory(),
            'direccion' => fake()->streetAddress(),
            'latitud' => fake()->randomFloat(7, 11.0, 11.5),
            'longitud' => fake()->randomFloat(7, -74.5, -74.0),
            'telefono' => '+57 5 ' . fake()->numerify('#######'),
            'correo' => fake()->unique()->safeEmail(),
            'horario_atencion' => 'Lun-Vie 8:00-12:00 y 14:00-18:00',
            'activo' => true,
        ];
    }
}
