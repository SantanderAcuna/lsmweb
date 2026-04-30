<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Dependencia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'dependencias';

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'dependencia_padre_id', 'extension', 'correo', 'activo',
        'created_by', 'updated_by',
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

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function actualizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
