<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Dependencia;
use App\Models\Tramite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Tramite> */
class TramiteFactory extends Factory
{
    protected $model = Tramite::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $nombre = fake()->unique()->words(3, true);

        return [
            'codigo' => 'TR-' . strtoupper(fake()->unique()->bothify('???###')),
            'nombre' => ucfirst($nombre),
            'slug' => Str::slug($nombre) . '-' . fake()->unique()->randomNumber(4),
            'descripcion' => fake()->paragraph(),
            'requisitos' => [
                'Cédula de ciudadanía',
                'Formulario diligenciado',
                fake()->sentence(),
            ],
            'documentos_requeridos' => ['Cédula', 'Recibo de pago'],
            'tiempo_estimado' => fake()->randomElement(['1 día', '3 días hábiles', '15 días hábiles']),
            'costo' => fake()->randomElement([0, 5000, 15000, 50000]),
            'costo_variable' => false,
            'categoria' => fake()->randomElement(['Salud', 'Movilidad', 'Tributario', 'Habitat']),
            'dependencia_id' => Dependencia::factory(),
            'canal_atencion' => fake()->randomElement(['Presencial', 'Virtual', 'Mixto']),
            'url_govco' => null,
            'publicado' => true,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }

    public function noPublicado(): static
    {
        return $this->state(fn () => ['publicado' => false]);
    }
}
