<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('paises', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo_iso', 3)->unique();
            $table->string('nombre', 100)->unique();
            $table->timestamps();
        });

        Schema::create('departamentos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pais_id')->constrained('paises')->restrictOnDelete();
            $table->string('codigo_dane', 5)->nullable();
            $table->string('nombre', 100);
            $table->timestamps();
            $table->unique(['pais_id', 'nombre']);
        });

        Schema::create('municipios', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('departamento_id')->constrained('departamentos')->restrictOnDelete();
            $table->string('codigo_dane', 8)->nullable();
            $table->string('nombre', 120);
            $table->timestamps();
            $table->unique(['departamento_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
        Schema::dropIfExists('departamentos');
        Schema::dropIfExists('paises');
    }
};
