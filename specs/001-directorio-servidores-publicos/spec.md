# Spec 001 — Sistema Sitio Web Alcaldía de Santa Marta

**Estado:** Aprobada · **Versión:** 1.0 · **Fecha:** 2026-04-29
**Marco normativo:** Ley 1712/2014 Art. 8 · Decreto 1081/2015 Art. 2.2.1.8.8 · Resolución 1519/2020 · Decreto 767/2022 · Ley 1581/2012

## 1. Contexto
El sitio web actual obtuvo 47/100 en la auditoría ITA Septiembre 2025. Hallazgo crítico: directorio de servidores públicos incompleto (faltan país/departamento/ciudad de nacimiento, formación académica, experiencia profesional, integración SIGEP), teléfonos sin prefijo +57, organigrama sin descripción, certificados de cumplimiento ausentes.

Fuentes consultadas en la entrega: `MATRIZ_CUMPLIMIENTO_HALLAZGOS.md` y `GUIA_NORMATIVA_SITIO_WEB_GOBIERNOS_LOCALES.md`.

## 2. Objetivo
Construir desde cero el sistema (backend Laravel 12 + frontend Vue 3 TS) que cumpla 100% los campos exigidos por el Art. 8, con autenticación basada en Passport, autorización por roles y permisos granulares (spatie/laravel-permission), catálogo geográfico normalizado a 4FN y panel administrativo profesional.

## 3. Alcance del MVP
- **Auth (primero):** registro, login, logout, perfil, recuperación de contraseña.
- **Roles & permisos granulares por sección.**
- **Catálogo geográfico 4FN** (paises → departamentos → municipios).
- **Módulos:** Dependencias, Servidores Públicos (con sub-recursos formaciones académicas y experiencias profesionales).
- **Acceso público** (consulta + datos abiertos) y **acceso por credenciales** (gestión).
- **Frontend** con Bootstrap 5, FontAwesome Free, Vue Query, Pinia, vee-validate + yup, vue-toastification.

## 4. Actores y roles
| Rol | Permisos |
|-----|----------|
| `ciudadano` | Sin permisos administrativos. Solo público. |
| `auditor` | `panel.access`, `*.view`, `reportes.export` |
| `editor_rrhh` | `panel.access`, `servidores.*` excepto delete, `dependencias.view`, `geografia.view`, `reportes.*` |
| `admin` | Todos |

## 5. Requisitos funcionales
| ID | Requisito | Norma |
|----|-----------|-------|
| RF-01 | País/Depto/Ciudad de nacimiento por FK al catálogo geográfico | Ley 1712/2014 Art. 8 |
| RF-02 | Formación académica (1..N) | Ley 1712/2014 Art. 8 |
| RF-03 | Experiencia profesional (0..N) | Ley 1712/2014 Art. 8 |
| RF-04 | Cargo, naturaleza, salario, dependencia, correo institucional, extensión | Ley 1712/2014 Art. 8 |
| RF-05 | Enlace SIGEP | Decreto 1081/2015 Art. 2.2.1.8.8 |
| RF-06 | Consulta pública sin autenticación, paginada y filtrable | Ley 1712/2014 Art. 3 |
| RF-07 | Datos abiertos (CSV/JSON) | Decreto 767/2022 |
| RF-08 | Validación teléfono `+57 X XXXXXXX` | Resolución 1519/2020 Anexo 2 ítem 4.a |
| RF-09 | Auditoría (created_by/updated_by) y soft delete | Ley 594/2000 |
| RF-10 | Datos sensibles cifrados y filtrados | Ley 1581/2012 |
| RF-11 | Autenticación Passport con tokens y refresh | Doc. Laravel Passport 12.x |
| RF-12 | Autorización con permisos granulares (spatie/laravel-permission) | Doc. spatie 6.x |

## 6. Requisitos no funcionales
- Cobertura de tests ≥ 80%.
- WCAG 2.1 AA (axe-core 0 violaciones).
- Latencia p95 < 500 ms en listado público.
- OpenAPI 3.1 generado y publicado.

## 7. Reglas de negocio
- BR-01 Soft delete; histórico se conserva (Ley 594).
- BR-02 Solo `publicado=true` aparece en API pública.
- BR-03 Correo institucional debe terminar en `@santamarta.gov.co`.
- BR-04 Los campos `documento_identidad` y `correo_personal` son privados; cifrados en BD y omitidos en API pública.
- BR-05 Una dependencia activa por servidor.

## 8. Criterios de aceptación
1. Un ciudadano consulta sin login.
2. Un editor RRHH crea un servidor con todos los campos del Art. 8.
3. La respuesta pública nunca expone datos sensibles.
4. Export CSV cumple RFC 4180; export JSON cumple RFC 8259.
5. Validación teléfono pasa `+57 5 4200100` y rechaza `5 4200100`.
6. Suite verde: `php artisan test`, `npm run test`.
