<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Dependencia;
use App\Models\ExperienciaProfesional;
use App\Models\FormacionAcademica;
use App\Models\Municipio;
use App\Models\Pais;
use App\Models\ServidorPublico;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GeografiaSeeder::class,
            RolesPermissionsSeeder::class,
        ]);

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@santamarta.gov.co'],
            ['name' => 'Administrador TI', 'password' => Hash::make('Sm@2026Secure!'), 'estado' => 'ACTIVO'],
        );
        $admin->syncRoles(['admin']);

        $editor = User::query()->updateOrCreate(
            ['email' => 'rrhh@santamarta.gov.co'],
            ['name' => 'Editor RRHH', 'password' => Hash::make('Sm@2026Secure!'), 'estado' => 'ACTIVO'],
        );
        $editor->syncRoles(['editor_rrhh']);

        $colombia = Pais::query()->where('codigo_iso', 'COL')->first();
        $municipios = Municipio::query()->pluck('id');

        $dependencias = Dependencia::factory()->count(8)->create();

        ServidorPublico::factory()
            ->count(40)
            ->state(fn () => [
                'dependencia_id' => $dependencias->random()->id,
                'municipio_nacimiento_id' => $municipios->random(),
                'created_by' => $editor->id,
                'updated_by' => $editor->id,
            ])
            ->create()
            ->each(function (ServidorPublico $servidor) use ($colombia): void {
                FormacionAcademica::factory()->count(2)
                    ->state(['pais_id' => $colombia->id])
                    ->for($servidor, 'servidor')
                    ->create();
                ExperienciaProfesional::factory()->count(3)
                    ->for($servidor, 'servidor')
                    ->create();
            });
    }
}
