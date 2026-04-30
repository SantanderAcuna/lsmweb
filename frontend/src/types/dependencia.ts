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

export interface DependenciaForm {
  codigo: string
  nombre: string
  descripcion: string | null
  extension: string | null
  correo: string | null
  dependencia_padre_id: number | null
  activo: boolean
}
