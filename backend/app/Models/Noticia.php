<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Noticia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'titulo', 'slug', 'resumen', 'contenido',
        'imagen_destacada_url', 'imagen_destacada_alt',
        'categoria', 'etiquetas',
        'publicado', 'publicado_en',
        'autor_id', 'actualizado_por',
    ];

    protected $casts = [
        'etiquetas' => 'array',
        'publicado' => 'boolean',
        'publicado_en' => 'datetime',
    ];

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function actualizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }

    public function scopePublicadas(Builder $q): Builder
    {
        return $q->where('publicado', true)->whereNotNull('publicado_en')
            ->where('publicado_en', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
