export interface Sede {
  id: number
  nombre: string
  municipio_id: number
  municipio: string | null
  departamento: string | null
  pais: string | null
  direccion: string
  latitud: number | null
  longitud: number | null
  telefono: string | null
  correo: string | null
  horario_atencion: string | null
  activo: boolean
  created_at: string
  updated_at: string
}

export interface SedeForm {
  nombre: string
  municipio_id: number
  direccion: string
  latitud: number | null
  longitud: number | null
  telefono: string | null
  correo: string | null
  horario_atencion: string | null
  activo: boolean
}
