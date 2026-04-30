<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServidorPublicoResource;
use App\Repositories\Contracts\ServidorPublicoRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Endpoints públicos del directorio (Ley 1712/2014 — Máxima Publicidad).
 */
final class ServidorPublicoController extends Controller
{
    public function __construct(
        private readonly ServidorPublicoRepositoryInterface $repository,
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'dependencia_id' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);
        $validated['publicado'] = true;

        return ServidorPublicoResource::collection(
            $this->repository->paginate($validated)
        )->additional(['message' => 'Listado obtenido correctamente.']);
    }

    public function show(int $id): JsonResponse
    {
        $servidor = $this->repository->findPublicado($id);

        if ($servidor === null) {
            return response()->json([
                'message' => 'Servidor público no encontrado o no publicado.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detalle obtenido correctamente.',
            'data' => new ServidorPublicoResource($servidor),
        ]);
    }

    public function export(Request $request): StreamedResponse|JsonResponse
    {
        $format = (string) $request->query('format', 'json');
        $registros = $this->repository->allPublicadosForExport();

        return match ($format) {
            'csv' => $this->csvStream($registros),
            'json' => response()->json(
                ServidorPublicoResource::collection($registros)->resolve(),
                200,
                ['Content-Disposition' => 'attachment; filename="servidores-publicos.json"']
            ),
            default => response()->json([
                'message' => 'Formato no soportado. Use csv o json.',
            ], 422),
        };
    }

    private function csvStream(\Illuminate\Database\Eloquent\Collection $registros): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="servidores-publicos.csv"',
        ];

        return response()->streamDownload(function () use ($registros): void {
            $out = fopen('php://output', 'wb');

            fputcsv($out, [
                'id', 'nombres', 'apellidos', 'pais_nacimiento', 'depto_nacimiento',
                'ciudad_nacimiento', 'dependencia', 'cargo', 'naturaleza_cargo',
                'salario_basico', 'correo_institucional', 'telefono_oficina',
                'extension', 'sigep_url',
            ]);

            foreach ($registros as $r) {
                $municipio = $r->municipioNacimiento;
                $depto = $municipio?->departamento;
                $pais = $depto?->pais;

                fputcsv($out, [
                    $r->id, $r->nombres, $r->apellidos,
                    $pais?->nombre, $depto?->nombre, $municipio?->nombre,
                    $r->dependencia?->nombre, $r->cargo, $r->naturaleza_cargo?->value,
                    $r->salario_basico, $r->correo_institucional,
                    $r->telefono_oficina, $r->extension, $r->sigep_url,
                ]);
            }

            fclose($out);
        }, 'servidores-publicos.csv', $headers);
    }
}
