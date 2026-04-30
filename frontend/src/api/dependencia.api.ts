import { http } from './http'
import type { ApiEnvelope } from '@/types/auth'
import type { Paginated } from '@/types/servidor'
import type { Dependencia, DependenciaForm } from '@/types/dependencia'

export interface ListarDepParams {
  q?: string
  activo?: boolean
  padre_id?: number
  page?: number
  per_page?: number
}

export const DependenciaApi = {
  async organigramaPublico() {
    const { data } = await http.get<{ data: Dependencia[]; message: string }>('/organigrama')
    return data.data
  },
  async listarAdmin(params: ListarDepParams = {}) {
    const { data } = await http.get<Paginated<Dependencia>>('/admin/dependencias', { params })
    return data
  },
  async obtenerAdmin(id: number) {
    const { data } = await http.get<ApiEnvelope<Dependencia>>(`/admin/dependencias/${id}`)
    return data.data
  },
  async crear(payload: DependenciaForm) {
    const { data } = await http.post<ApiEnvelope<Dependencia>>('/admin/dependencias', payload)
    return data
  },
  async actualizar(id: number, payload: Partial<DependenciaForm>) {
    const { data } = await http.put<ApiEnvelope<Dependencia>>(`/admin/dependencias/${id}`, payload)
    return data
  },
  async eliminar(id: number) {
    const { data } = await http.delete<{ message: string }>(`/admin/dependencias/${id}`)
    return data
  }
}
