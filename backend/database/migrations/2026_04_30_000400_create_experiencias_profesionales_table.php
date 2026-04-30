<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('experiencias_profesionales', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('servidor_publico_id')->constrained('servidores_publicos')->cascadeOnDelete();
            $table->string('empresa', 200);
            $table->string('cargo', 150);
            $table->enum('sector', ['PUBLICO', 'PRIVADO', 'MIXTO', 'ONG', 'ACADEMICO']);
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->text('funciones')->nullable();
            $table->timestamps();

            $table->index('servidor_publico_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiencias_profesionales');
    }
};
