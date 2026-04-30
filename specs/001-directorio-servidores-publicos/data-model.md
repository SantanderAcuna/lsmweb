# Modelo de Datos — 4FN

## Decisiones de normalización

- **1FN**: cada celda atómica.
- **2FN**: PKs simples; toda columna no clave depende de la PK.
- **3FN**: sin dependencias transitivas. El departamento del nacimiento se obtiene navegando `municipio → departamento → pais`.
- **BCNF**: cada determinante es clave candidata.
- **4FN**: las relaciones multivaluadas (formación académica, experiencia profesional) viven en sus propias tablas en cardinalidad 1:N.

## Tablas

### paises
| col | tipo | restricciones |
|-----|------|---------------|
| id | bigint PK | |
| codigo_iso | varchar(3) | UNIQUE |
| nombre | varchar(100) | UNIQUE |

### departamentos
| col | tipo | restricciones |
|-----|------|---------------|
| id | bigint PK | |
| pais_id | FK paises.id | restrictOnDelete |
| codigo_dane | varchar(5) | nullable |
| nombre | varchar(100) | UNIQUE(pais_id, nombre) |

### municipios
| col | tipo | restricciones |
|-----|------|---------------|
| id | bigint PK | |
| departamento_id | FK departamentos.id | restrictOnDelete |
| codigo_dane | varchar(8) | nullable |
| nombre | varchar(120) | UNIQUE(departamento_id, nombre) |

### dependencias
| col | tipo | restricciones |
|-----|------|---------------|
| id | bigint PK | |
| codigo | varchar(20) | UNIQUE |
| nombre | varchar(150) | NOT NULL |
| descripcion | text | nullable |
| dependencia_padre_id | FK self | nullOnDelete |
| extension | varchar(20) | nullable |
| correo | varchar(150) | nullable |
| activo | bool | default true |

### users
| col | tipo | restricciones |
|-----|------|---------------|
| id | bigint PK | |
| name | varchar(120) | NOT NULL |
| email | varchar(150) | UNIQUE |
| password | varchar(255) | hashed |
| estado | enum(ACTIVO,INACTIVO,BLOQUEADO) | default ACTIVO |
| email_verified_at | timestamp | nullable |
| remember_token | varchar(100) | |
| timestamps + softDeletes | | |

### servidores_publicos (4FN)
| col | tipo | restricciones |
|-----|------|---------------|
| id | bigint PK | |
| nombres / apellidos | varchar(100) | NOT NULL |
| tipo_documento | enum(CC,CE,PA,TI) | NOT NULL |
| documento_identidad | varchar(20) | UNIQUE, **encrypted** |
| municipio_nacimiento_id | FK municipios.id | restrictOnDelete |
| fecha_nacimiento | date | nullable |
| genero | enum(M,F,O,NR) | nullable |
| dependencia_id | FK dependencias.id | restrictOnDelete |
| cargo | varchar(150) | NOT NULL |
| naturaleza_cargo | enum (5 valores) | NOT NULL |
| salario_basico | decimal(12,2) | nullable |
| correo_institucional | varchar(150) | UNIQUE |
| correo_personal | varchar(150) | nullable, **encrypted** |
| telefono_oficina | varchar(20) | nullable, regex +57 |
| extension | varchar(10) | nullable |
| sigep_url | varchar(255) | nullable |
| foto_url | varchar(255) | nullable |
| publicado | bool | default false |
| created_by / updated_by | FK users.id | restrictOnDelete |
| timestamps + softDeletes | | |

### formaciones_academicas (4FN)
| col | tipo | restricciones |
|-----|------|---------------|
| id | bigint PK | |
| servidor_publico_id | FK | cascadeOnDelete |
| nivel | enum (7) | |
| titulo | varchar(200) | |
| institucion | varchar(200) | |
| pais_id | FK paises.id | |
| ano_grado | smallint | |

### experiencias_profesionales (4FN)
| col | tipo | restricciones |
|-----|------|---------------|
| id | bigint PK | |
| servidor_publico_id | FK | cascadeOnDelete |
| empresa | varchar(200) | |
| cargo | varchar(150) | |
| sector | enum(5) | |
| fecha_inicio | date | |
| fecha_fin | date | nullable |
| funciones | text | nullable |

### Tablas de seguridad (gestionadas por paquetes oficiales)
- **Passport** (`oauth_*`): publicadas por `php artisan passport:install`. (Doc. Laravel Passport 12.x.)
- **spatie/laravel-permission**: `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`. (Doc. spatie 6.x.)
