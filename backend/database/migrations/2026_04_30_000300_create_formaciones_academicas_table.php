<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('formaciones_academicas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('servidor_publico_id')->constrained('servidores_publicos')->cascadeOnDelete();
            $table->enum('nivel', [
                'BACHILLER', 'TECNICO', 'TECNOLOGO', 'PROFESIONAL', 'ESPECIALIZACION', 'MAESTRIA', 'DOCTORADO',
            ]);
            $table->string('titulo', 200);
            $table->string('institucion', 200);
            $table->foreignId('pais_id')->constrained('paises')->restrictOnDelete();
            $table->smallInteger('ano_grado');
            $table->timestamps();

            $table->index('servidor_publico_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formaciones_academicas');
    }
};
