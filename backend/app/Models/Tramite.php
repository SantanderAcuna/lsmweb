<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Tramite extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'codigo', 'nombre', 'slug', 'descripcion',
        'requisitos', 'documentos_requeridos',
        'tiempo_estimado', 'costo', 'costo_variable',
        'categoria', 'dependencia_id', 'canal_atencion',
        'url_govco', 'publicado', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'requisitos' => 'array',
        'documentos_requeridos' => 'array',
        'costo' => 'decimal:2',
        'costo_variable' => 'boolean',
        'publicado' => 'boolean',
    ];

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function scopePublicados(Builder $q): Builder
    {
        return $q->where('publicado', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
