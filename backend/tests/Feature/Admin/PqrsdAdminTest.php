<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Dependencia;
use App\Models\Pqrsd;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

final class PqrsdAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedAuthAndCatalog();
    }

    public function test_oficial_pqrsd_lista(): void
    {
        $u = User::factory()->create();
        $u->assignRole('oficial_pqrsd');
        Passport::actingAs($u);

        Pqrsd::factory()->count(3)->create();

        $this->getJson('/api/v1/admin/pqrsd')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_asignar(): void
    {
        $oficial = User::factory()->create();
        $oficial->assignRole('oficial_pqrsd');
        Passport::actingAs($oficial);

        $pqrsd = Pqrsd::factory()->create();
        $dep = Dependencia::factory()->create();

        $this->postJson("/api/v1/admin/pqrsd/{$pqrsd->id}/asignar", [
            'asignado_a' => $oficial->id,
            'dependencia_asignada_id' => $dep->id,
        ])->assertOk();

        $this->assertDatabaseHas('pqrsd', [
            'id' => $pqrsd->id,
            'asignado_a' => $oficial->id,
            'estado' => 'EN_TRAMITE',
        ]);
    }

    public function test_responder(): void
    {
        $oficial = User::factory()->create();
        $oficial->assignRole('oficial_pqrsd');
        Passport::actingAs($oficial);

        $pqrsd = Pqrsd::factory()->create();

        $this->postJson("/api/v1/admin/pqrsd/{$pqrsd->id}/responder", [
            'respuesta' => 'Esta es la respuesta oficial al ciudadano.',
        ])->assertOk();

        $this->assertDatabaseHas('pqrsd', [
            'id' => $pqrsd->id,
            'estado' => 'RESPONDIDA',
        ]);
    }
}
