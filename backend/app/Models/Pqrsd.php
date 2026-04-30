<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Pqrsd extends Model
{
    use HasFactory;

    protected $table = 'pqrsd';

    protected $fillable = [
        'radicado', 'tipo', 'asunto', 'descripcion', 'anonima',
        'solicitante_nombre', 'solicitante_documento', 'solicitante_correo', 'solicitante_telefono',
        'dependencia_asignada_id', 'asignado_a',
        'estado', 'fecha_limite_respuesta', 'respondida_en', 'respuesta',
    ];

    protected $hidden = [
        'solicitante_documento', 'solicitante_correo', 'solicitante_telefono',
    ];

    protected $casts = [
        'anonima' => 'boolean',
        'fecha_limite_respuesta' => 'date',
        'respondida_en' => 'datetime',
        'solicitante_documento' => 'encrypted',
        'solicitante_correo' => 'encrypted',
    ];

    public function dependenciaAsignada(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_asignada_id');
    }

    public function asignadoA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    public function scopePorRadicado(Builder $q, string $radicado): Builder
    {
        return $q->where('radicado', $radicado);
    }
}
