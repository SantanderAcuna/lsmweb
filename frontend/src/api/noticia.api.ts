import { http } from './http'
import type { ApiEnvelope } from '@/types/auth'
import type { Paginated } from '@/types/servidor'
import type { Noticia } from '@/types/noticia'

export const NoticiaApi = {
  async listarPublico(params: { q?: string; categoria?: string; page?: number; per_page?: number } = {}) {
    const { data } = await http.get<Paginated<Noticia>>('/noticias', { params })
    return data
  },
  async obtenerPublica(slug: string) {
    const { data } = await http.get<ApiEnvelope<Noticia>>(`/noticias/${slug}`)
    return data.data
  }
}
