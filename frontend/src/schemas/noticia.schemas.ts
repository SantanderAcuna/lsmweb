import * as yup from 'yup'

export const noticiaSchema = yup.object({
  titulo: yup.string().required().max(200),
  slug: yup.string().required().matches(/^[a-z0-9-]+$/, 'Solo minúsculas, números y guiones.').max(220),
  resumen: yup.string().required().max(500),
  contenido: yup.string().required().min(20),
  imagen_destacada_url: yup.string().url().nullable(),
  imagen_destacada_alt: yup.string().nullable().when('imagen_destacada_url', {
    is: (v: string | null) => !!v,
    then: (s) => s.required('El texto alternativo (alt) es obligatorio cuando se proporciona imagen.')
  }),
  categoria: yup.string().required().max(80),
  etiquetas: yup.array().of(yup.string().required()).nullable(),
  publicado: yup.boolean().required(),
  publicado_en: yup.string().nullable()
})
