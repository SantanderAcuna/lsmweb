<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Sede extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nombre', 'municipio_id', 'direccion', 'latitud', 'longitud',
        'telefono', 'correo', 'horario_atencion', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
    ];

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function scopeActivas(Builder $q): Builder
    {
        return $q->where('activo', true);
    }
}
