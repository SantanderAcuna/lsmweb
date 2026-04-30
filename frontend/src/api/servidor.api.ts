import { http } from './http'
import type { ApiEnvelope, Paginated, ServidorPublico, ServidorPublicoForm } from '@/types/servidor'

export interface ListarParams {
  q?: string
  dependencia_id?: number
  publicado?: boolean
  page?: number
  per_page?: number
}

export const ServidorApi = {
  async listarPublico(params: ListarParams = {}) {
    const { data } = await http.get<Paginated<ServidorPublico>>('/servidores-publicos', { params })
    return data
  },

  async obtenerPublico(id: number) {
    const { data } = await http.get<ApiEnvelope<ServidorPublico>>(`/servidores-publicos/${id}`)
    return data.data
  },

  async exportar(format: 'csv' | 'json') {
    const { data } = await http.get('/servidores-publicos/export', { params: { format }, responseType: 'blob' })
    return data as Blob
  },

  async listarAdmin(params: ListarParams = {}) {
    const { data } = await http.get<Paginated<ServidorPublico>>('/admin/servidores-publicos', { params })
    return data
  },

  async obtenerAdmin(id: number) {
    const { data } = await http.get<ApiEnvelope<ServidorPublico>>(`/admin/servidores-publicos/${id}`)
    return data.data
  },

  async crear(payload: ServidorPublicoForm) {
    const { data } = await http.post<ApiEnvelope<ServidorPublico>>('/admin/servidores-publicos', payload)
    return data
  },

  async actualizar(id: number, payload: Partial<ServidorPublicoForm>) {
    const { data } = await http.put<ApiEnvelope<ServidorPublico>>(`/admin/servidores-publicos/${id}`, payload)
    return data
  },

  async eliminar(id: number) {
    const { data } = await http.delete<{ message: string }>(`/admin/servidores-publicos/${id}`)
    return data
  }
}
