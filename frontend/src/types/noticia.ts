export interface Noticia {
  id: number
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
  autor: { id: number; name: string } | null
  created_at: string
}
