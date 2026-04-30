<?php

declare(strict_types=1);

namespace Tests\Feature\Public;

use App\Models\Dependencia;
use App\Models\Municipio;
use App\Models\ServidorPublico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ServidorPublicoPublicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedAuthAndCatalog();
    }

    public function test_listado_publico_solo_muestra_publicados(): void
    {
        $user = User::factory()->create();
        $municipio = Municipio::query()->first();
        $dependencia = Dependencia::factory()->create();

        ServidorPublico::factory()->count(3)->create([
            'publicado' => true,
            'created_by' => $user->id, 'updated_by' => $user->id,
            'municipio_nacimiento_id' => $municipio->id,
            'dependencia_id' => $dependencia->id,
        ]);
        ServidorPublico::factory()->noPublicado()->count(2)->create([
            'created_by' => $user->id, 'updated_by' => $user->id,
            'municipio_nacimiento_id' => $municipio->id,
            'dependencia_id' => $dependencia->id,
        ]);

        $this->getJson('/api/v1/servidores-publicos')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_detalle_publico_no_expone_datos_sensibles(): void
    {
        $user = User::factory()->create();
        $servidor = ServidorPublico::factory()->create([
            'publicado' => true,
            'documento_identidad' => '1234567890',
            'correo_personal' => 'personal@gmail.com',
            'created_by' => $user->id, 'updated_by' => $user->id,
            'municipio_nacimiento_id' => Municipio::query()->first()->id,
            'dependencia_id' => Dependencia::factory()->create()->id,
        ]);

        $r = $this->getJson("/api/v1/servidores-publicos/{$servidor->id}")
            ->assertOk();

        $r->assertJsonMissing(['documento_identidad' => '1234567890']);
        $r->assertJsonMissing(['correo_personal' => 'personal@gmail.com']);
    }
}
