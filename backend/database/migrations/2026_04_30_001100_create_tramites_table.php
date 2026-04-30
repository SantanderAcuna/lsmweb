<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tramites', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('nombre', 200);
            $table->string('slug', 220)->unique();
            $table->text('descripcion');
            $table->json('requisitos');
            $table->json('documentos_requeridos')->nullable();
            $table->string('tiempo_estimado', 100)->nullable();
            $table->decimal('costo', 12, 2)->default(0);
            $table->boolean('costo_variable')->default(false);
            $table->string('categoria', 80)->nullable();
            $table->foreignId('dependencia_id')->constrained('dependencias')->restrictOnDelete();
            $table->string('canal_atencion', 100)->nullable();
            $table->string('url_govco', 255)->nullable();
            $table->boolean('publicado')->default(false);
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['categoria', 'publicado']);
            $table->index('dependencia_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tramites');
    }
};
