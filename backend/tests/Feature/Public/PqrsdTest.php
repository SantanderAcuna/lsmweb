<?php

declare(strict_types=1);

namespace Tests\Feature\Public;

use App\Models\Pqrsd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PqrsdTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedAuthAndCatalog();
    }

    public function test_radicar_devuelve_radicado(): void
    {
        $payload = [
            'tipo' => 'PETICION',
            'asunto' => 'Solicito información sobre presupuesto',
            'descripcion' => 'Por favor enviar copia del presupuesto vigente.',
            'anonima' => false,
            'solicitante_nombre' => 'Juan Pérez',
            'solicitante_documento' => '1082001111',
            'solicitante_correo' => 'juan@example.com',
            'solicitante_telefono' => '+57 300 1234567',
        ];

        $this->postJson('/api/v1/pqrsd', $payload)
            ->assertCreated()
            ->assertJsonStructure(['message', 'data' => ['radicado', 'fecha_limite_respuesta']]);

        $this->assertDatabaseCount('pqrsd', 1);
    }

    public function test_anonima_no_requiere_solicitante(): void
    {
        $this->postJson('/api/v1/pqrsd', [
            'tipo' => 'DENUNCIA',
            'asunto' => 'Denuncia anónima',
            'descripcion' => 'Detalles de la denuncia.',
            'anonima' => true,
        ])->assertCreated();
    }

    public function test_descripcion_minima_falla(): void
    {
        $this->postJson('/api/v1/pqrsd', [
            'tipo' => 'QUEJA',
            'asunto' => 'Asunto',
            'descripcion' => 'corto',
            'anonima' => true,
        ])->assertStatus(422)->assertJsonValidationErrors(['descripcion']);
    }

    public function test_consultar_por_radicado(): void
    {
        $pqrsd = Pqrsd::factory()->create();

        $this->getJson("/api/v1/pqrsd/consultar/{$pqrsd->radicado}")
            ->assertOk()
            ->assertJsonPath('data.radicado', $pqrsd->radicado);
    }

    public function test_consultar_inexistente_404(): void
    {
        $this->getJson('/api/v1/pqrsd/consultar/INEXISTENTE')
            ->assertNotFound();
    }
}
