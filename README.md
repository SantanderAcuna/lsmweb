# Sitio Web Alcaldía Distrital de Santa Marta

Sistema desarrollado **desde cero** para subsanar los hallazgos críticos de la auditoría ITA Septiembre 2025 (puntaje 47/100). Cumple Ley 1712/2014, Decreto 1081/2015, Resolución 1519/2020, Decreto 767/2022 y Ley 1581/2012.

## Stack

### Backend (`/backend`) — Laravel 12
- **PHP 8.2+, Laravel 12** ([laravel.com/docs/12.x](https://laravel.com/docs/12.x))
- **Laravel Passport 12.x** — autenticación OAuth2 / Personal Access Tokens
- **spatie/laravel-permission 6.x** — roles y permisos granulares
- **PHPUnit 11** — pruebas unitarias e integración
- **Eloquent + Repository + Service + Policy + Form Request + API Resource**

### Frontend (`/frontend`) — Vue 3 + TypeScript
- **Vue 3.5 + TS 5.5** ([vuejs.org](https://vuejs.org))
- **Vue Router 4** ([router.vuejs.org](https://router.vuejs.org))
- **Pinia 2** ([pinia.vuejs.org](https://pinia.vuejs.org))
- **TanStack Vue Query 5** ([tanstack.com/query](https://tanstack.com/query/latest/docs/vue/overview))
- **Axios 1.7**
- **vee-validate 4 + Yup 1** ([vee-validate.logaretm.com](https://vee-validate.logaretm.com))
- **vue-toastification**
- **Bootstrap 5.3** ([getbootstrap.com](https://getbootstrap.com))
- **FontAwesome Free 6** ([fontawesome.com](https://fontawesome.com))
- **Vitest 2 + @vue/test-utils** — pruebas

## Arquitectura
- **MVC + POO + SOLID + Clean Code** en backend (Models, Controllers, Services, Repositories, Policies).
- **Spec-Driven Development** — ver `/specs/001-directorio-servidores-publicos/` (`spec.md`, `data-model.md`, `contracts/openapi.yaml`).
- **Base de datos en 4FN**: catálogo geográfico `paises → departamentos → municipios`; relaciones multivaluadas (formaciones, experiencias) en sus propias tablas.

## Estructura del módulo (orden de construcción)
1. **Auth** (Passport): `Register, Login, Logout, Me, Forgot/Reset password`.
2. **Roles y permisos** granulares por sección (`servidores.*`, `dependencias.*`, `usuarios.*`, `roles.*`, `permisos.*`, `geografia.*`, `reportes.*`, `panel.access`).
3. **Catálogo geográfico (4FN)**.
4. **Módulos**: Dependencia + ServidorPublico (con sub-recursos formaciones académicas y experiencias profesionales).
5. **Repository contracts + Eloquent implementations**.
6. **Service contracts + implementations**.
7. **Form Requests** (Store/Update con `messages()`).
8. **API Resources** (`index`/`show` con relaciones cargadas).
9. **Controladores** (consumen servicios, protegidos con middleware `permission:*`).
10. **Routes + Service Providers** (binding interfaces ↔ implementaciones).
11. **Tests** (Auth + Public + Admin + Unit Rules).
12. **Policies** (al final; encadenan a permisos granulares).

## Cumplimiento normativo

| Hallazgo de auditoría | Cubierto por |
|------------------------|--------------|
| País/Depto/Ciudad de nacimiento | Catálogo 4FN + FK `municipio_nacimiento_id` |
| Formación académica | Tabla `formaciones_academicas` |
| Experiencia profesional | Tabla `experiencias_profesionales` |
| Integración SIGEP | Campo `sigep_url` |
| Teléfonos sin +57 | `App\Rules\TelefonoColombia` |
| Datos sensibles | Casts `encrypted` + `$hidden` + filtrado en API pública |
| Auditoría de cambios | `created_by`/`updated_by` + soft delete |

## Cómo correr

### Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan passport:install
php artisan serve  # http://localhost:8000
```

### Frontend
```bash
cd frontend
npm install
npm run dev  # http://localhost:5173
```

### Tests backend
```bash
cd backend
php artisan test
```

## Credenciales de demo (seed)
- `admin@santamarta.gov.co` / `Sm@2026Secure!` — rol `admin`
- `rrhh@santamarta.gov.co` / `Sm@2026Secure!` — rol `editor_rrhh`

## Módulos del sistema (ver `specs/002-modulos-completos/spec.md`)

| Sección del sitio oficial | Módulo | Estado |
|---------------------------|--------|--------|
| Transparencia → Directorio servidores | `servidores_publicos` (+ formaciones, experiencias) | ✅ Completo |
| Transparencia → Organigrama / Gabinete | `dependencias` | ✅ |
| Servicios → Trámites y servicios | `tramites` | ✅ CRUD completo |
| Servicios → PQRSD | `pqrsd` (radicar/consultar/asignar/responder) | ✅ Completo |
| Servicios → Sedes | `sedes` | ✅ Migración + modelo |
| Comunicaciones → Noticias | `noticias` | ✅ CRUD completo |
| Catálogo geográfico (4FN) | `paises`, `departamentos`, `municipios` | ✅ |

### Roles
| Rol | Uso |
|-----|-----|
| `admin` | Acceso total |
| `editor_rrhh` | Servidores y dependencias |
| `editor_comunicaciones` | Noticias |
| `editor_servicios` | Trámites y sedes |
| `oficial_pqrsd` | Asignar/responder PQRSD |
| `auditor` | Solo lectura + exportes |
| `ciudadano` | Público + radicar PQRSD |
