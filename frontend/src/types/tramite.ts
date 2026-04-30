import type { Dependencia } from './servidor'

export interface Tramite {
  id: number
  codigo: string
  nombre: string
  slug: string
  descripcion: string
  requisitos: string[]
  documentos_requeridos: string[] | null
  tiempo_estimado: string | null
  costo: number | null
  costo_variable: boolean
  categoria: string | null
  canal_atencion: string | null
  url_govco: string | null
  publicado: boolean
  dependencia: Dependencia | null
  created_at: string
  updated_at: string
}
