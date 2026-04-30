# Plan de ejecución — Calidad y funcionamiento (paquetes A → D)

**Fecha:** 2026-04-30
**Objetivo:** Cerrar las brechas identificadas en la auditoría interna, garantizando que el sistema arranque, sea consistente, usable y verificable.

## Paquetes

### A. Estabilidad (foundational)
Bloqueantes para que el sistema arranque sin tocar nada.
1. Migraciones Laravel 12 oficiales: cache, jobs, notifications.
2. README ampliado con `passport:keys` + paso de instalación completo.
3. CORS middleware aplicado explícitamente en `bootstrap/app.php`.
4. Throttle granular en endpoints sensibles: PQRSD público (5/min), login (5/min ya por defecto).
5. `ResetPassword` notification que apunte al frontend SPA.
6. Verificación de email con endpoint propio.

### B. Patrón consistente (backend uniforme)
1. Service + Interface para Tramite, Noticia, Sede, Dependencia, Usuario, Role.
2. Refactor de controladores admin: dejar de hablar al Repository, pasar por el Service.
3. Auditoría uniforme: `created_by`/`updated_by` en `noticias`, `sedes`, `dependencias`, `pqrsd`. Migración + actualización de modelos/factories/seeders.
4. Soft delete uniforme: `dependencias`, `pqrsd`.
5. `UserController` admin debe usar `UserRepository` (corregir).

### C. UX admin usable (frontend)
1. Composable `useApiErrors` que fusione errores 422 con vee-validate.
2. Selectores reactivos para `dependencia_id`, `municipio_id`, `pais_id`, `usuario_id` poblados desde Vue Query.
3. Modal de confirmación accesible (Bootstrap Modal) en lugar de `confirm()` nativo.
4. Paginador reutilizable en todas las listas admin.
5. Filtro por categoría/estado en listas admin que aplique.
6. Editor WYSIWYG para Noticias (HTML directo + sanitización en backend).
7. PQRSD detalle: dropdowns con búsqueda en lugar de IDs crudos.

### D. Tests + CI (calidad)
1. Tests Feature backend faltantes: Sede, Dependencia (admin/público), Tramite admin, Noticia admin.
2. Tests Unit: PqrsdService (radicado, plazos), ServidorPublicoService.
3. Tests Vitest frontend: stores Pinia, composables.
4. Configurar axe-core en suite de tests E2E (mínimo).
5. Dockerfile multi-stage backend + Dockerfile frontend + docker-compose.
6. GitHub Actions: pipeline que corre `composer test` + `npm run build`.

## Roles y agentes asignados

| Rol | Agente | Paquete |
|-----|--------|---------|
| Tech lead (yo) | claude-opus-4-7 | A — config sensible |
| Backend full-stack | general-purpose | B |
| Frontend full-stack | general-purpose | C |
| DevOps | general-purpose | D.5–D.6 |
| Documentación | general-purpose | OpenAPI completo + ADRs + README |
| QA | general-purpose | D.1–D.4 |
| Verificador | general-purpose | Auditoría final A→D |

## Orden temporal

```
A (yo, secuencial)
  └─> B + C + DevOps + Docs (paralelo)
        └─> QA (después de B+C; necesita el código estable)
              └─> Verificador (audita todo)
                    └─> Commit + push final
```

## Salida esperada
- Sistema que arranca con `composer install && npm install && php artisan migrate --seed && php artisan passport:keys`.
- 100% controladores admin pasan por Service.
- Auditoría y soft delete uniformes.
- Frontend admin sin inputs numéricos crudos para FK.
- ≥30 tests verdes.
- Dockerfile + CI listo.
- Reporte de verificación con tabla de cumplimiento.
