<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SectorExperiencia;
use App\Models\ExperienciaProfesional;
use App\Models\ServidorPublico;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ExperienciaProfesional> */
class ExperienciaProfesionalFactory extends Factory
{
    protected $model = ExperienciaProfesional::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $inicio = fake()->dateTimeBetween('-20 years', '-1 years');
        $fin = fake()->boolean(70) ? fake()->dateTimeBetween($inicio, 'now') : null;

        return [
            'servidor_publico_id' => ServidorPublico::factory(),
            'empresa' => fake()->company(),
            'cargo' => fake()->jobTitle(),
            'sector' => fake()->randomElement(SectorExperiencia::cases())->value,
            'fecha_inicio' => $inicio->format('Y-m-d'),
            'fecha_fin' => $fin?->format('Y-m-d'),
            'funciones' => fake()->paragraph(),
        ];
    }
}
