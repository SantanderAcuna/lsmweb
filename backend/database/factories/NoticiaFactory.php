<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Noticia;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Noticia> */
class NoticiaFactory extends Factory
{
    protected $model = Noticia::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $titulo = fake()->unique()->sentence(8);

        return [
            'titulo' => $titulo,
            'slug' => Str::slug($titulo) . '-' . fake()->unique()->randomNumber(4),
            'resumen' => fake()->paragraph(2),
            'contenido' => '<p>' . fake()->paragraphs(5, true) . '</p>',
            'imagen_destacada_url' => null,
            'imagen_destacada_alt' => null,
            'categoria' => fake()->randomElement(['Institucional', 'Comunidad', 'Eventos', 'Cultura']),
            'etiquetas' => fake()->words(3),
            'publicado' => true,
            'publicado_en' => now(),
            'autor_id' => User::factory(),
            'actualizado_por' => User::factory(),
        ];
    }

    public function borrador(): static
    {
        return $this->state(fn () => ['publicado' => false, 'publicado_en' => null]);
    }
}
