<?php

declare(strict_types=1);

namespace Tests\Feature\Public;

use App\Models\Dependencia;
use App\Models\Tramite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TramiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedAuthAndCatalog();
    }

    public function test_listar_publicados(): void
    {
        $user = User::factory()->create();
        $dep = Dependencia::factory()->create();

        Tramite::factory()->count(3)->create([
            'publicado' => true, 'created_by' => $user->id, 'updated_by' => $user->id,
            'dependencia_id' => $dep->id,
        ]);
        Tramite::factory()->noPublicado()->count(2)->create([
            'created_by' => $user->id, 'updated_by' => $user->id,
            'dependencia_id' => $dep->id,
        ]);

        $this->getJson('/api/v1/tramites')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_detalle_por_slug(): void
    {
        $user = User::factory()->create();
        $tramite = Tramite::factory()->create([
            'publicado' => true,
            'slug' => 'cedula-de-vecindad',
            'created_by' => $user->id, 'updated_by' => $user->id,
            'dependencia_id' => Dependencia::factory()->create()->id,
        ]);

        $this->getJson('/api/v1/tramites/cedula-de-vecindad')
            ->assertOk()
            ->assertJsonPath('data.slug', 'cedula-de-vecindad');
    }

    public function test_no_publicado_404(): void
    {
        $user = User::factory()->create();
        Tramite::factory()->noPublicado()->create([
            'slug' => 'oculto',
            'created_by' => $user->id, 'updated_by' => $user->id,
            'dependencia_id' => Dependencia::factory()->create()->id,
        ]);

        $this->getJson('/api/v1/tramites/oculto')->assertNotFound();
    }
}
