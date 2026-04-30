# Spec 002 — Catálogo completo de módulos

**Estado:** Aprobada · **Versión:** 1.0 · **Fuente oficial:** https://www.santamarta.gov.co (consultada 2026-04-30 vía búsqueda web — el sitio devuelve 403 a fetch directo, por lo que las URLs se confirmaron mediante búsqueda).

## 1. Mapa de módulos

### 1.1 Transparencia y Acceso a la Información Pública (Ley 1712/2014)

| # | Módulo | Ruta sugerida (frontend) | Estado | Norma |
|---|--------|-------------------------|--------|-------|
| 1 | Información de la entidad (misión, visión) | `/transparencia/entidad` | Estática | Art. 8 |
| 2 | Estructura orgánica y organigrama | `/transparencia/organigrama` | Implementado (Dependencias) | Art. 8 |
| 3 | Directorio de servidores públicos | `/servidores` | **Implementado spec 001** | Art. 8 |
| 4 | Gabinete | `/gabinete` | Vista derivada de Dependencias | Art. 8 |
| 5 | Directorio Distrital | `/transparencia/directorio-distrital` | Vista derivada | Art. 8 |
| 6 | Normograma | `/transparencia/normograma` | Stub `Normativa` | Art. 8 |
| 7 | Presupuesto | `/transparencia/presupuesto` | Stub `Documento` | Art. 8 numeral 7 |
| 8 | Contratación pública | `/transparencia/contratacion` | Enlace a SECOP/CCE | Art. 9 |
| 9 | Plan Anticorrupción y de Atención al Ciudadano | `/transparencia/plan-anticorrupcion` | Stub `Documento` | Ley 1474/2011 |
| 10 | Plan de Acción / Plan de Desarrollo | `/transparencia/plan-desarrollo` | Stub `Documento` | Art. 8 |
| 11 | Rendición de cuentas | `/transparencia/rendicion-cuentas` | Stub `Documento` | Decreto 1499/2017 |
| 12 | Informes de auditoría | `/transparencia/informes-auditoria` | Stub `Documento` | Art. 8 |
| 13 | Datos abiertos | `/transparencia/datos-abiertos` | Implementado en Servidores | Decreto 767/2022 |
| 14 | Solicitar información pública | `/transparencia/solicitud-informacion` | **Stub PQRSD** | Art. 24 |

### 1.2 Atención y Servicios a la Ciudadanía

| # | Módulo | Ruta | Estado |
|---|--------|------|--------|
| 1 | Catálogo de trámites y servicios | `/servicios/tramites` | **Implementado en spec 002 (Tramite)** |
| 2 | Detalle de trámite | `/servicios/tramites/:id` | Implementado |
| 3 | Localización física, sucursales, horarios | `/servicios/sedes` | **Stub Sede** |
| 4 | PQRSD (Peticiones, Quejas, Reclamos, Sugerencias y Denuncias) | `/servicios/pqrsd` | **Implementado** |
| 5 | Consultar PQRSD | `/servicios/pqrsd/consultar` | Implementado |
| 6 | Preguntas frecuentes | `/servicios/preguntas-frecuentes` | Stub |
| 7 | Predial | URL externa | Enlace externo |

### 1.3 Participa

| # | Módulo | Ruta | Estado |
|---|--------|------|--------|
| 1 | Consultas y encuestas | `/participa/consultas` | Stub |
| 2 | Audiencias públicas | `/participa/audiencias` | Stub |
| 3 | Diagnóstico participativo | `/participa/diagnostico` | Stub |
| 4 | Presupuesto participativo | `/participa/presupuesto-participativo` | Stub |

### 1.4 Comunicaciones

| # | Módulo | Ruta | Estado |
|---|--------|------|--------|
| 1 | Noticias | `/noticias` | **Implementado** |
| 2 | Detalle de noticia | `/noticias/:slug` | Implementado |
| 3 | Galería multimedia | `/galeria` | Stub |
| 4 | Boletines / Comunicados | `/comunicados` | Stub |

### 1.5 Cuenta de usuario

| Módulo | Estado |
|--------|--------|
| Login | Implementado spec 001 |
| Registro | Implementado |
| Recuperar contraseña | Implementado |
| Mi perfil | Implementado |

## 2. Convención de permisos por módulo (granular)

Por cada módulo se generan permisos `<modulo>.<accion>` (`view`, `create`, `update`, `delete`) más permisos especiales:

- `servidores.publish`
- `noticias.publish`
- `tramites.publish`
- `pqrsd.respond` (tipo response/respuesta a una solicitud)
- `pqrsd.assign`
- `reportes.export`
- `panel.access`

## 3. Roles

| Rol | Permisos |
|-----|----------|
| `admin` | Todos |
| `editor_rrhh` | servidores.* sin delete, dependencias.view, geografia.view, reportes.* |
| `editor_comunicaciones` | noticias.*, tramites.view |
| `editor_servicios` | tramites.*, sedes.view |
| `oficial_pqrsd` | pqrsd.view, pqrsd.update, pqrsd.respond, pqrsd.assign |
| `auditor` | *.view, reportes.export |
| `ciudadano` | acceso público + crear PQRSD, consultar el suyo |

## 4. Trazabilidad

Esta spec extiende y hace referencia a **spec 001 - Directorio de Servidores Públicos**.
