<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dependencias', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->foreignId('dependencia_padre_id')->nullable()
                ->constrained('dependencias')->nullOnDelete();
            $table->string('extension', 20)->nullable();
            $table->string('correo', 150)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('dependencia_padre_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dependencias');
    }
};
