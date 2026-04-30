import { http } from './http'
import type { ApiEnvelope } from '@/types/auth'
import type { Paginated } from '@/types/servidor'
import type { CrearPqrsdPayload, EstadoPqrsd, Pqrsd } from '@/types/pqrsd'

export const PqrsdApi = {
  async crear(payload: CrearPqrsdPayload) {
    const { data } = await http.post<{ message: string; data: { radicado: string; fecha_limite_respuesta: string | null } }>(
      '/pqrsd', payload
    )
    return data
  },
  async consultar(radicado: string) {
    const { data } = await http.get<ApiEnvelope<Pqrsd>>(`/pqrsd/consultar/${radicado}`)
    return data.data
  },

  async listarAdmin(params: { q?: string; tipo?: string; estado?: EstadoPqrsd; page?: number; per_page?: number } = {}) {
    const { data } = await http.get<Paginated<Pqrsd>>('/admin/pqrsd', { params })
    return data
  },
  async obtenerAdmin(id: number) {
    const { data } = await http.get<ApiEnvelope<Pqrsd>>(`/admin/pqrsd/${id}`)
    return data.data
  },
  async asignar(id: number, payload: { asignado_a: number; dependencia_asignada_id: number }) {
    const { data } = await http.post<ApiEnvelope<Pqrsd>>(`/admin/pqrsd/${id}/asignar`, payload)
    return data
  },
  async responder(id: number, respuesta: string) {
    const { data } = await http.post<ApiEnvelope<Pqrsd>>(`/admin/pqrsd/${id}/responder`, { respuesta })
    return data
  },
  async cambiarEstado(id: number, estado: EstadoPqrsd) {
    const { data } = await http.patch<ApiEnvelope<Pqrsd>>(`/admin/pqrsd/${id}/estado`, { estado })
    return data
  }
}
