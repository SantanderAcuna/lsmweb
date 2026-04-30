export type TipoDocumento = 'CC' | 'CE' | 'PA' | 'TI'
export type Genero = 'M' | 'F' | 'O' | 'NR'

export type NaturalezaCargo =
  | 'LIBRE_NOMBRAMIENTO' | 'CARRERA' | 'PROVISIONAL'
  | 'CONTRATO_PRESTACION' | 'ELECCION_POPULAR'

export type NivelFormacion =
  | 'BACHILLER' | 'TECNICO' | 'TECNOLOGO' | 'PROFESIONAL'
  | 'ESPECIALIZACION' | 'MAESTRIA' | 'DOCTORADO'

export type SectorExperiencia = 'PUBLICO' | 'PRIVADO' | 'MIXTO' | 'ONG' | 'ACADEMICO'

export interface Dependencia {
  id: number
  codigo: string
  nombre: string
  descripcion: string | null
  extension: string | null
  correo: string | null
  activo: boolean
  dependencia_padre_id: number | null
}

export interface FormacionAcademica {
  id?: number
  nivel: NivelFormacion
  titulo: string
  institucion: string
  pais_id: number
  pais?: string
  ano_grado: number
}

export interface ExperienciaProfesional {
  id?: number
  empresa: string
  cargo: string
  sector: SectorExperiencia
  fecha_inicio: string
  fecha_fin: string | null
  funciones: string | null
}

export interface ServidorPublico {
  id: number
  nombres: string
  apellidos: string
  nombre_completo: string
  pais_nacimiento: string | null
  depto_nacimiento: string | null
  ciudad_nacimiento: string | null
  fecha_nacimiento: string | null
  genero: Genero | null
  cargo: string
  naturaleza_cargo: NaturalezaCargo
  naturaleza_cargo_label: string
  salario_basico: number | null
  correo_institucional: string
  telefono_oficina: string | null
  extension: string | null
  sigep_url: string | null
  foto_url: string | null
  publicado: boolean
  dependencia: Dependencia | null
  formaciones_academicas: FormacionAcademica[]
  experiencias_profesionales: ExperienciaProfesional[]
  created_at: string
  updated_at: string
}

export interface ServidorPublicoForm {
  nombres: string
  apellidos: string
  tipo_documento: TipoDocumento
  documento_identidad: string
  municipio_nacimiento_id: number
  fecha_nacimiento: string | null
  genero: Genero | null
  dependencia_id: number
  cargo: string
  naturaleza_cargo: NaturalezaCargo
  salario_basico: number | null
  correo_institucional: string
  correo_personal: string | null
  telefono_oficina: string | null
  extension: string | null
  sigep_url: string | null
  foto_url: string | null
  publicado: boolean
  formaciones_academicas: FormacionAcademica[]
  experiencias_profesionales: ExperienciaProfesional[]
}

export interface Paginated<T> {
  data: T[]
  meta: {
    current_page: number
    per_page: number
    total: number
    last_page: number
  }
  links: {
    first: string | null
    last: string | null
    prev: string | null
    next: string | null
  }
  message?: string
}
