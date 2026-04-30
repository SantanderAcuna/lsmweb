<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NaturalezaCargo;
use App\Enums\TipoDocumento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Servidor Público — Art. 8 Ley 1712/2014.
 *
 * Datos privados (Habeas Data, Ley 1581/2012) cifrados:
 *  - documento_identidad
 *  - correo_personal
 */
final class ServidorPublico extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'servidores_publicos';

    protected $fillable = [
        'nombres', 'apellidos',
        'tipo_documento', 'documento_identidad',
        'municipio_nacimiento_id',
        'fecha_nacimiento', 'genero',
        'dependencia_id',
        'cargo', 'naturaleza_cargo', 'salario_basico',
        'correo_institucional', 'correo_personal',
        'telefono_oficina', 'extension',
        'sigep_url', 'foto_url',
        'publicado',
        'created_by', 'updated_by',
    ];

    protected $hidden = ['documento_identidad', 'correo_personal'];

    protected $casts = [
        'tipo_documento' => TipoDocumento::class,
        'naturaleza_cargo' => NaturalezaCargo::class,
        'fecha_nacimiento' => 'date',
        'salario_basico' => 'decimal:2',
        'publicado' => 'boolean',
        'documento_identidad' => 'encrypted',
        'correo_personal' => 'encrypted',
    ];

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function municipioNacimiento(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipio_nacimiento_id');
    }

    public function formacionesAcademicas(): HasMany
    {
        return $this->hasMany(FormacionAcademica::class);
    }

    public function experienciasProfesionales(): HasMany
    {
        return $this->hasMany(ExperienciaProfesional::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function actualizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePublicados(Builder $query): Builder
    {
        return $query->where('publicado', true);
    }

    public function scopeBuscar(Builder $query, ?string $term): Builder
    {
        if ($term === null || $term === '') {
            return $query;
        }

        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $term) . '%';

        return $query->where(function (Builder $q) use ($like): void {
            $q->where('nombres', 'like', $like)
                ->orWhere('apellidos', 'like', $like)
                ->orWhere('cargo', 'like', $like);
        });
    }

    protected function nombreCompleto(): Attribute
    {
        return Attribute::get(fn (): string => trim("{$this->nombres} {$this->apellidos}"));
    }
}
