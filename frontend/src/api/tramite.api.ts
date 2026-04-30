import { http } from './http'
import type { ApiEnvelope } from '@/types/auth'
import type { Paginated } from '@/types/servidor'
import type { Tramite } from '@/types/tramite'

export const TramiteApi = {
  async listarPublico(params: { q?: string; categoria?: string; page?: number; per_page?: number } = {}) {
    const { data } = await http.get<Paginated<Tramite>>('/tramites', { params })
    return data
  },
  async obtenerPublico(slug: string) {
    const { data } = await http.get<ApiEnvelope<Tramite>>(`/tramites/${slug}`)
    return data.data
  }
}
