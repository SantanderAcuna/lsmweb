<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class RoleAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedAuthAndCatalog();
    }

    public function test_admin_lista_roles(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        $this->getJson('/api/v1/admin/roles')
            ->assertOk()
            ->assertJsonStructure(['data' => [['name', 'permissions']]]);
    }

    public function test_admin_crea_rol_con_permisos(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        $this->postJson('/api/v1/admin/roles', [
            'name' => 'editor_demo',
            'permissions' => ['servidores.view', 'panel.access'],
        ])->assertCreated()
          ->assertJsonPath('data.name', 'editor_demo');

        $this->assertCount(2, Role::findByName('editor_demo', 'api')->permissions);
    }

    public function test_no_eliminar_rol_admin(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Passport::actingAs($admin);

        $rolAdmin = Role::findByName('admin', 'api');

        $this->deleteJson("/api/v1/admin/roles/{$rolAdmin->id}")
            ->assertStatus(409);
    }
}
