<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

final class UserAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedAuthAndCatalog();
    }

    public function test_admin_lista_usuarios(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        User::factory()->count(3)->create();

        $this->getJson('/api/v1/admin/usuarios')
            ->assertOk()
            ->assertJsonStructure(['data', 'meta']);
    }

    public function test_no_admin_recibe_403(): void
    {
        $u = User::factory()->create();
        $u->assignRole('ciudadano');
        Passport::actingAs($u);

        $this->getJson('/api/v1/admin/usuarios')->assertForbidden();
    }

    public function test_crea_usuario_y_asigna_rol(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        $this->postJson('/api/v1/admin/usuarios', [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@santamarta.gov.co',
            'password' => 'Sm@2026Secure!',
            'roles' => ['editor_rrhh'],
        ])->assertCreated()
          ->assertJsonPath('data.email', 'nuevo@santamarta.gov.co')
          ->assertJsonPath('data.roles', ['editor_rrhh']);
    }

    public function test_no_puede_eliminar_a_si_mismo(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        $this->deleteJson("/api/v1/admin/usuarios/{$admin->id}")
            ->assertStatus(409);
    }
}
