<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SectorExperiencia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ExperienciaProfesional extends Model
{
    use HasFactory;

    protected $table = 'experiencias_profesionales';

    protected $fillable = [
        'servidor_publico_id', 'empresa', 'cargo', 'sector', 'fecha_inicio', 'fecha_fin', 'funciones',
    ];

    protected $casts = [
        'sector' => SectorExperiencia::class,
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function servidor(): BelongsTo
    {
        return $this->belongsTo(ServidorPublico::class, 'servidor_publico_id');
    }
}
