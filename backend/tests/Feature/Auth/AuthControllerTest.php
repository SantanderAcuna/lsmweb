<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

final class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedAuthAndCatalog();
        // Crear cliente personal de Passport para emisión de tokens
        app(ClientRepository::class)->createPersonalAccessClient(null, 'Test', 'http://localhost');
    }

    public function test_register_crea_usuario_y_emite_token(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Juan Tester',
            'email' => 'juan@example.com',
            'password' => 'Sm@2026Secure!',
            'password_confirmation' => 'Sm@2026Secure!',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['message', 'data' => ['user' => ['id', 'email', 'roles'], 'token', 'token_type']]);

        $this->assertDatabaseHas('users', ['email' => 'juan@example.com']);
    }

    public function test_login_valida_credenciales(): void
    {
        $user = User::factory()->create();
        $user->assignRole('ciudadano');

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'Sm@2026Secure!',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.user.email', $user->email);
    }

    public function test_login_credenciales_invalidas_devuelve_401(): void
    {
        User::factory()->create(['email' => 'x@example.com']);

        $this->postJson('/api/v1/auth/login', [
            'email' => 'x@example.com',
            'password' => 'incorrecta',
        ])->assertUnauthorized();
    }

    public function test_me_requiere_autenticacion(): void
    {
        $this->getJson('/api/v1/auth/me')->assertUnauthorized();
    }

    public function test_me_devuelve_perfil(): void
    {
        $user = User::factory()->create();
        $user->assignRole('ciudadano');
        Passport::actingAs($user);

        $this->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.email', $user->email);
    }
}
