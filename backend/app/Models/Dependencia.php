<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Dependencia extends Model
{
    use HasFactory;

    protected $table = 'dependencias';

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'dependencia_padre_id', 'extension', 'correo', 'activo',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function padre(): BelongsTo
    {
        return $this->belongsTo(self::class, 'dependencia_padre_id');
    }

    public function hijos(): HasMany
    {
        return $this->hasMany(self::class, 'dependencia_padre_id');
    }

    public function servidores(): HasMany
    {
        return $this->hasMany(ServidorPublico::class);
    }

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
