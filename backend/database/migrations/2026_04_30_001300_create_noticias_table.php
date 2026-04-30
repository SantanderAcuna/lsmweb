<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('noticias', function (Blueprint $table): void {
            $table->id();
            $table->string('titulo', 200);
            $table->string('slug', 220)->unique();
            $table->text('resumen');
            $table->longText('contenido');
            $table->string('imagen_destacada_url', 255)->nullable();
            $table->string('imagen_destacada_alt', 255)->nullable();
            $table->string('categoria', 80)->index();
            $table->json('etiquetas')->nullable();
            $table->boolean('publicado')->default(false);
            $table->timestamp('publicado_en')->nullable()->index();
            $table->foreignId('autor_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('actualizado_por')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};
