import { http } from './http'
import type { ApiEnvelope } from '@/types/auth'
import type { Paginated } from '@/types/servidor'
import type { Noticia } from '@/types/noticia'

export interface NoticiaForm {
  titulo: string
  slug: string
  resumen: string
  contenido: string
  imagen_destacada_url: string | null
  imagen_destacada_alt: string | null
  categoria: string
  etiquetas: string[] | null
  publicado: boolean
  publicado_en: string | null
}

export const NoticiaApi = {
  async listarPublico(params: { q?: string; categoria?: string; page?: number; per_page?: number } = {}) {
    const { data } = await http.get<Paginated<Noticia>>('/noticias', { params })
    return data
  },
  async obtenerPublica(slug: string) {
    const { data } = await http.get<ApiEnvelope<Noticia>>(`/noticias/${slug}`)
    return data.data
  },

  async listarAdmin(params: { q?: string; categoria?: string; publicado?: boolean; page?: number; per_page?: number } = {}) {
    const { data } = await http.get<Paginated<Noticia>>('/admin/noticias', { params })
    return data
  },
  async obtenerAdmin(id: number) {
    const { data } = await http.get<ApiEnvelope<Noticia>>(`/admin/noticias/${id}`)
    return data.data
  },
  async crear(payload: NoticiaForm) {
    const { data } = await http.post<ApiEnvelope<Noticia>>('/admin/noticias', payload)
    return data
  },
  async actualizar(id: number, payload: Partial<NoticiaForm>) {
    const { data } = await http.put<ApiEnvelope<Noticia>>(`/admin/noticias/${id}`, payload)
    return data
  },
  async eliminar(id: number) {
    const { data } = await http.delete<{ message: string }>(`/admin/noticias/${id}`)
    return data
  }
}
