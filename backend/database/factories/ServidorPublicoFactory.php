<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\NaturalezaCargo;
use App\Enums\TipoDocumento;
use App\Models\Dependencia;
use App\Models\Municipio;
use App\Models\ServidorPublico;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<ServidorPublico> */
class ServidorPublicoFactory extends Factory
{
    protected $model = ServidorPublico::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $nombres = fake()->firstName();
        $apellidos = fake()->lastName() . ' ' . fake()->lastName();

        return [
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'tipo_documento' => TipoDocumento::CC->value,
            'documento_identidad' => (string) fake()->unique()->numberBetween(10_000_000, 99_999_999),
            'municipio_nacimiento_id' => Municipio::factory(),
            'fecha_nacimiento' => fake()->dateTimeBetween('-65 years', '-25 years')->format('Y-m-d'),
            'genero' => fake()->randomElement(['M', 'F', 'O', 'NR']),
            'dependencia_id' => Dependencia::factory(),
            'cargo' => fake()->jobTitle(),
            'naturaleza_cargo' => fake()->randomElement(NaturalezaCargo::cases())->value,
            'salario_basico' => fake()->numberBetween(1_800_000, 12_000_000),
            'correo_institucional' => Str::slug($nombres . '.' . explode(' ', $apellidos)[0])
                . fake()->numberBetween(1, 999) . '@santamarta.gov.co',
            'correo_personal' => fake()->safeEmail(),
            'telefono_oficina' => '+57 5 ' . fake()->numerify('#######'),
            'extension' => (string) fake()->numberBetween(100, 9999),
            'sigep_url' => 'https://www.funcionpublica.gov.co/sigep/' . fake()->uuid(),
            'foto_url' => null,
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
