<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\NivelFormacion;
use App\Models\FormacionAcademica;
use App\Models\Pais;
use App\Models\ServidorPublico;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<FormacionAcademica> */
class FormacionAcademicaFactory extends Factory
{
    protected $model = FormacionAcademica::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'servidor_publico_id' => ServidorPublico::factory(),
            'pais_id' => Pais::factory(),
            'nivel' => fake()->randomElement(NivelFormacion::cases())->value,
            'titulo' => fake()->randomElement([
                'Ingeniería de Sistemas', 'Administración Pública', 'Derecho',
                'Contaduría Pública', 'Maestría en Gobierno',
            ]),
            'institucion' => fake()->randomElement([
                'Universidad del Magdalena', 'Universidad Nacional de Colombia',
                'Universidad de los Andes', 'ESAP',
            ]),
            'ano_grado' => fake()->numberBetween(1990, (int) date('Y')),
        ];
    }
}
