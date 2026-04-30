<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('servidores_publicos', function (Blueprint $table): void {
            $table->id();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->enum('tipo_documento', ['CC', 'CE', 'PA', 'TI']);
            $table->string('documento_identidad', 255)->unique();
            $table->foreignId('municipio_nacimiento_id')->constrained('municipios')->restrictOnDelete();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['M', 'F', 'O', 'NR'])->nullable();
            $table->foreignId('dependencia_id')->constrained('dependencias')->restrictOnDelete();
            $table->string('cargo', 150);
            $table->enum('naturaleza_cargo', [
                'LIBRE_NOMBRAMIENTO', 'CARRERA', 'PROVISIONAL', 'CONTRATO_PRESTACION', 'ELECCION_POPULAR',
            ]);
            $table->decimal('salario_basico', 12, 2)->nullable();
            $table->string('correo_institucional', 150)->unique();
            $table->string('correo_personal', 255)->nullable();
            $table->string('telefono_oficina', 20)->nullable();
            $table->string('extension', 10)->nullable();
            $table->string('sigep_url', 255)->nullable();
            $table->string('foto_url', 255)->nullable();
            $table->boolean('publicado')->default(false);
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['dependencia_id', 'publicado']);
            $table->index(['apellidos', 'nombres']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servidores_publicos');
    }
};
