<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pqrsd;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Pqrsd> */
class PqrsdFactory extends Factory
{
    protected $model = Pqrsd::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $tipo = fake()->randomElement(['PETICION', 'QUEJA', 'RECLAMO', 'SUGERENCIA', 'DENUNCIA', 'INFORMACION']);

        return [
            'radicado' => 'PQRSD-' . now()->format('Ymd') . '-' . fake()->unique()->numerify('#####'),
            'tipo' => $tipo,
            'asunto' => fake()->sentence(6),
            'descripcion' => fake()->paragraph(3),
            'anonima' => false,
            'solicitante_nombre' => fake()->name(),
            'solicitante_documento' => (string) fake()->unique()->numberBetween(10000000, 99999999),
            'solicitante_correo' => fake()->safeEmail(),
            'solicitante_telefono' => fake()->phoneNumber(),
            'estado' => 'RECIBIDA',
            'fecha_limite_respuesta' => now()->addDays(15)->toDateString(),
        ];
    }

    public function anonima(): static
    {
        return $this->state(fn () => [
            'anonima' => true,
            'solicitante_nombre' => null,
            'solicitante_documento' => null,
            'solicitante_correo' => null,
            'solicitante_telefono' => null,
        ]);
    }
}
