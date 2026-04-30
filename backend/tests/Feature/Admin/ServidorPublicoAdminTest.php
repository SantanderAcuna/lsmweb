<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Dependencia;
use App\Models\Municipio;
use App\Models\Pais;
use App\Models\ServidorPublico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

final class ServidorPublicoAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedAuthAndCatalog();
    }

    public function test_no_autenticado_recibe_401(): void
    {
        $this->postJson('/api/v1/admin/servidores-publicos', [])
            ->assertUnauthorized();
    }

    public function test_sin_permiso_recibe_403(): void
    {
        $user = User::factory()->create();
        $user->assignRole('ciudadano');
        Passport::actingAs($user);

        $this->getJson('/api/v1/admin/servidores-publicos')
            ->assertForbidden();
    }

    public function test_editor_rrhh_crea_servidor_completo(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor_rrhh');
        Passport::actingAs($user);

        $municipio = Municipio::query()->first();
        $dependencia = Dependencia::factory()->create();
        $pais = Pais::query()->first();

        $payload = [
            'nombres' => 'Carlos', 'apellidos' => 'Pérez Acuña',
            'tipo_documento' => 'CC', 'documento_identidad' => '1082001234',
            'municipio_nacimiento_id' => $municipio->id,
            'fecha_nacimiento' => '1985-05-12', 'genero' => 'M',
            'dependencia_id' => $dependencia->id,
            'cargo' => 'Profesional Universitario',
            'naturaleza_cargo' => 'CARRERA',
            'salario_basico' => 4500000,
            'correo_institucional' => 'carlos.perez@santamarta.gov.co',
            'telefono_oficina' => '+57 5 4200100',
            'extension' => '101',
            'sigep_url' => 'https://www.funcionpublica.gov.co/sigep/abc',
            'publicado' => true,
            'formaciones_academicas' => [[
                'nivel' => 'PROFESIONAL', 'titulo' => 'Ingeniería de Sistemas',
                'institucion' => 'Universidad del Magdalena',
                'pais_id' => $pais->id, 'ano_grado' => 2010,
            ]],
            'experiencias_profesionales' => [[
                'empresa' => 'Gobernación del Magdalena',
                'cargo' => 'Analista TI', 'sector' => 'PUBLICO',
                'fecha_inicio' => '2015-01-01', 'fecha_fin' => '2020-12-31',
                'funciones' => 'Soporte y desarrollo.',
            ]],
        ];

        $this->postJson('/api/v1/admin/servidores-publicos', $payload)
            ->assertCreated()
            ->assertJsonPath('message', 'Servidor público creado exitosamente.');

        $this->assertDatabaseHas('servidores_publicos', [
            'correo_institucional' => 'carlos.perez@santamarta.gov.co',
        ]);
        $this->assertDatabaseCount('formaciones_academicas', 1);
        $this->assertDatabaseCount('experiencias_profesionales', 1);
    }

    public function test_correo_dominio_invalido_falla(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor_rrhh');
        Passport::actingAs($user);

        $municipio = Municipio::query()->first();
        $dependencia = Dependencia::factory()->create();

        $this->postJson('/api/v1/admin/servidores-publicos', [
            'nombres' => 'A', 'apellidos' => 'B',
            'tipo_documento' => 'CC', 'documento_identidad' => '1',
            'municipio_nacimiento_id' => $municipio->id,
            'dependencia_id' => $dependencia->id,
            'cargo' => 'X', 'naturaleza_cargo' => 'CARRERA',
            'correo_institucional' => 'x@gmail.com',
        ])->assertStatus(422)
          ->assertJsonValidationErrors(['correo_institucional']);
    }

    public function test_solo_admin_puede_eliminar(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole('editor_rrhh');
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $servidor = ServidorPublico::factory()->create([
            'created_by' => $admin->id, 'updated_by' => $admin->id,
            'municipio_nacimiento_id' => Municipio::query()->first()->id,
            'dependencia_id' => Dependencia::factory()->create()->id,
        ]);

        Passport::actingAs($editor);
        $this->deleteJson("/api/v1/admin/servidores-publicos/{$servidor->id}")
            ->assertForbidden();

        Passport::actingAs($admin);
        $this->deleteJson("/api/v1/admin/servidores-publicos/{$servidor->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Servidor público eliminado correctamente.');
    }
}
