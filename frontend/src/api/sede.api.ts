import { http } from './http'
import type { ApiEnvelope } from '@/types/auth'
import type { Paginated } from '@/types/servidor'
import type { Sede, SedeForm } from '@/types/sede'

export interface ListarSedeParams {
  q?: string
  municipio_id?: number
  activo?: boolean
  page?: number
  per_page?: number
}

export const SedeApi = {
  async listarPublico() {
    const { data } = await http.get<{ data: Sede[]; message: string }>('/sedes')
    return data.data
  },
  async listarAdmin(params: ListarSedeParams = {}) {
    const { data } = await http.get<Paginated<Sede>>('/admin/sedes', { params })
    return data
  },
  async obtenerAdmin(id: number) {
    const { data } = await http.get<ApiEnvelope<Sede>>(`/admin/sedes/${id}`)
    return data.data
  },
  async crear(payload: SedeForm) {
    const { data } = await http.post<ApiEnvelope<Sede>>('/admin/sedes', payload)
    return data
  },
  async actualizar(id: number, payload: Partial<SedeForm>) {
    const { data } = await http.put<ApiEnvelope<Sede>>(`/admin/sedes/${id}`, payload)
    return data
  },
  async eliminar(id: number) {
    const { data } = await http.delete<{ message: string }>(`/admin/sedes/${id}`)
    return data
  }
}
