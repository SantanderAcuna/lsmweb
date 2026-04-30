import * as yup from 'yup'

export const tramiteSchema = yup.object({
  codigo: yup.string().required('El código es obligatorio.').max(30),
  nombre: yup.string().required().max(200),
  slug: yup.string().required().matches(/^[a-z0-9-]+$/, 'Solo minúsculas, números y guiones.').max(220),
  descripcion: yup.string().required().min(20),
  requisitos: yup.array().of(yup.string().required()).min(1, 'Debe registrar al menos un requisito.').required(),
  documentos_requeridos: yup.array().of(yup.string().required()).nullable(),
  tiempo_estimado: yup.string().nullable().max(100),
  costo: yup.number().nullable().min(0),
  costo_variable: yup.boolean().required(),
  categoria: yup.string().nullable().max(80),
  dependencia_id: yup.number().required().integer().positive(),
  canal_atencion: yup.string().nullable().max(100),
  url_govco: yup.string().url().nullable(),
  publicado: yup.boolean().required()
})
