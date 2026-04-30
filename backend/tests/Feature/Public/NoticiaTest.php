<?php

declare(strict_types=1);

namespace Tests\Feature\Public;

use App\Models\Noticia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class NoticiaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedAuthAndCatalog();
    }

    public function test_listar_publicadas(): void
    {
        $user = User::factory()->create();
        Noticia::factory()->count(3)->create([
            'autor_id' => $user->id, 'actualizado_por' => $user->id,
            'publicado' => true, 'publicado_en' => now()->subDay(),
        ]);
        Noticia::factory()->borrador()->count(2)->create([
            'autor_id' => $user->id, 'actualizado_por' => $user->id,
        ]);

        $this->getJson('/api/v1/noticias')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_detalle_por_slug(): void
    {
        $user = User::factory()->create();
        $n = Noticia::factory()->create([
            'autor_id' => $user->id, 'actualizado_por' => $user->id,
            'publicado' => true, 'publicado_en' => now()->subDay(),
            'slug' => 'evento-distrital',
        ]);

        $this->getJson('/api/v1/noticias/evento-distrital')
            ->assertOk()
            ->assertJsonPath('data.slug', 'evento-distrital');
    }

    public function test_borrador_404(): void
    {
        $user = User::factory()->create();
        Noticia::factory()->borrador()->create([
            'autor_id' => $user->id, 'actualizado_por' => $user->id,
            'slug' => 'borrador-1',
        ]);

        $this->getJson('/api/v1/noticias/borrador-1')->assertNotFound();
    }
}
