<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Pais extends Model
{
    use HasFactory;

    protected $table = 'paises';

    protected $fillable = ['codigo_iso', 'nombre'];

    public function departamentos(): HasMany
    {
        return $this->hasMany(Departamento::class);
    }
}
