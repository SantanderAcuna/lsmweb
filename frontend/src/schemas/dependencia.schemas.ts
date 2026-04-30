import * as yup from 'yup'

export const dependenciaSchema = yup.object({
  codigo: yup.string().required().max(20),
  nombre: yup.string().required().max(150),
  descripcion: yup.string().nullable(),
  dependencia_padre_id: yup.number().nullable().integer().positive(),
  extension: yup.string().nullable().max(20),
  correo: yup.string().email().nullable().max(150)
    .matches(/@santamarta\.gov\.co$/, { message: 'Debe pertenecer al dominio @santamarta.gov.co', excludeEmptyString: true }),
  activo: yup.boolean().required()
})
