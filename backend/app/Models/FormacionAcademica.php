<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NivelFormacion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class FormacionAcademica extends Model
{
    use HasFactory;

    protected $table = 'formaciones_academicas';

    protected $fillable = [
        'servidor_publico_id', 'nivel', 'titulo', 'institucion', 'pais_id', 'ano_grado',
    ];

    protected $casts = [
        'nivel' => NivelFormacion::class,
        'ano_grado' => 'integer',
    ];

    public function servidor(): BelongsTo
    {
        return $this->belongsTo(ServidorPublico::class, 'servidor_publico_id');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }
}
