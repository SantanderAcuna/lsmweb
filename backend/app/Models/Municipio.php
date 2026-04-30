<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Municipio extends Model
{
    use HasFactory;

    protected $table = 'municipios';

    protected $fillable = ['departamento_id', 'codigo_dane', 'nombre'];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }
}
