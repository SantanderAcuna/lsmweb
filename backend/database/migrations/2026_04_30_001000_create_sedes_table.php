<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sedes', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 150);
            $table->foreignId('municipio_id')->constrained('municipios')->restrictOnDelete();
            $table->string('direccion', 200);
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('correo', 150)->nullable();
            $table->string('horario_atencion', 200)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('municipio_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sedes');
    }
};
