import * as yup from 'yup'

export const pqrsdSchema = yup.object({
  tipo: yup.mixed<'PETICION' | 'QUEJA' | 'RECLAMO' | 'SUGERENCIA' | 'DENUNCIA' | 'INFORMACION'>()
    .oneOf(['PETICION', 'QUEJA', 'RECLAMO', 'SUGERENCIA', 'DENUNCIA', 'INFORMACION']).required(),
  asunto: yup.string().required('El asunto es obligatorio.').max(200),
  descripcion: yup.string().required('La descripción es obligatoria.').min(10, 'Mínimo 10 caracteres.'),
  anonima: yup.boolean().required(),
  solicitante_nombre: yup.string().nullable().when('anonima', {
    is: false, then: (s) => s.required('El nombre es obligatorio.').max(150)
  }),
  solicitante_documento: yup.string().nullable().when('anonima', {
    is: false, then: (s) => s.required('El documento es obligatorio.').max(20)
  }),
  solicitante_correo: yup.string().nullable().when('anonima', {
    is: false, then: (s) => s.required('El correo es obligatorio.').email('Correo inválido.')
  }),
  solicitante_telefono: yup.string().nullable().max(30)
})
