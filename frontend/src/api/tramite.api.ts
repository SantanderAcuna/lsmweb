import { http } from './http'
import type { ApiEnvelope } from '@/types/auth'
import type { Paginated } from '@/types/servidor'
import type { Tramite } from '@/types/tramite'

export interface TramiteForm {
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
  dependencia_id: number
  canal_atencion: string | null
  url_govco: string | null
  publicado: boolean
}

export const TramiteApi = {
  async listarPublico(params: { q?: string; categoria?: string; page?: number; per_page?: number } = {}) {
    const { data } = await http.get<Paginated<Tramite>>('/tramites', { params })
    return data
  },
  async obtenerPublico(slug: string) {
    const { data } = await http.get<ApiEnvelope<Tramite>>(`/tramites/${slug}`)
    return data.data
  },

  async listarAdmin(params: { q?: string; categoria?: string; publicado?: boolean; page?: number; per_page?: number } = {}) {
    const { data } = await http.get<Paginated<Tramite>>('/admin/tramites', { params })
    return data
  },
  async obtenerAdmin(id: number) {
    const { data } = await http.get<ApiEnvelope<Tramite>>(`/admin/tramites/${id}`)
    return data.data
  },
  async crear(payload: TramiteForm) {
    const { data } = await http.post<ApiEnvelope<Tramite>>('/admin/tramites', payload)
    return data
  },
  async actualizar(id: number, payload: Partial<TramiteForm>) {
    const { data } = await http.put<ApiEnvelope<Tramite>>(`/admin/tramites/${id}`, payload)
    return data
  },
  async eliminar(id: number) {
    const { data } = await http.delete<{ message: string }>(`/admin/tramites/${id}`)
    return data
  }
}
