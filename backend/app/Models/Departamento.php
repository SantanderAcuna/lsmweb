<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamentos';

    protected $fillable = ['pais_id', 'codigo_dane', 'nombre'];

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class);
    }
}
