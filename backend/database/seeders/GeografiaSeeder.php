<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Pais;
use Illuminate\Database\Seeder;

final class GeografiaSeeder extends Seeder
{
    public function run(): void
    {
        $colombia = Pais::query()->updateOrCreate(
            ['codigo_iso' => 'COL'],
            ['nombre' => 'Colombia'],
        );

        $magdalena = Departamento::query()->updateOrCreate(
            ['pais_id' => $colombia->id, 'nombre' => 'Magdalena'],
            ['codigo_dane' => '47'],
        );

        $municipios = [
            ['nombre' => 'Santa Marta', 'codigo_dane' => '47001'],
            ['nombre' => 'Ciénaga', 'codigo_dane' => '47189'],
            ['nombre' => 'El Banco', 'codigo_dane' => '47245'],
            ['nombre' => 'Aracataca', 'codigo_dane' => '47053'],
        ];

        foreach ($municipios as $m) {
            Municipio::query()->updateOrCreate(
                ['departamento_id' => $magdalena->id, 'nombre' => $m['nombre']],
                ['codigo_dane' => $m['codigo_dane']],
            );
        }
    }
}
