<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pqrsd', function (Blueprint $table): void {
            $table->id();
            $table->string('radicado', 30)->unique();
            $table->enum('tipo', ['PETICION', 'QUEJA', 'RECLAMO', 'SUGERENCIA', 'DENUNCIA', 'INFORMACION'])
                ->index();
            $table->string('asunto', 200);
            $table->text('descripcion');
            $table->boolean('anonima')->default(false);

            // Datos del solicitante (cuando no es anónima); cifrados como Habeas Data (Ley 1581)
            $table->string('solicitante_nombre', 150)->nullable();
            $table->string('solicitante_documento', 255)->nullable(); // encrypted
            $table->string('solicitante_correo', 255)->nullable(); // encrypted
            $table->string('solicitante_telefono', 30)->nullable();

            $table->foreignId('dependencia_asignada_id')->nullable()
                ->constrained('dependencias')->nullOnDelete();
            $table->foreignId('asignado_a')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->enum('estado', ['RECIBIDA', 'EN_TRAMITE', 'RESPONDIDA', 'CERRADA', 'RECHAZADA'])
                ->default('RECIBIDA')
                ->index();
            $table->date('fecha_limite_respuesta')->nullable();
            $table->timestamp('respondida_en')->nullable();
            $table->text('respuesta')->nullable();
            $table->timestamps();

            $table->index(['tipo', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pqrsd');
    }
};
