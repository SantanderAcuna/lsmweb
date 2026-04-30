<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dependencias', function (Blueprint $table): void {
            $table->foreignId('created_by')->nullable()->after('activo')
                ->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')
                ->constrained('users')->restrictOnDelete();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('dependencias', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('updated_by');
            $table->dropSoftDeletes();
        });
    }
};
