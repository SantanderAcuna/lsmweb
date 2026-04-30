import * as yup from 'yup'

const telefonoRegex = /^\+57\s(?:\d\s\d{7}|1\s8000\s\d{4,5}|3\d{2}\s\d{7})$/

export const sedeSchema = yup.object({
  nombre: yup.string().required().max(150),
  municipio_id: yup.number().required().integer().positive(),
  direccion: yup.string().required().max(200),
  latitud: yup.number().nullable().min(-90).max(90),
  longitud: yup.number().nullable().min(-180).max(180),
  telefono: yup.string().nullable().matches(telefonoRegex, { message: 'Formato +57 X XXXXXXX', excludeEmptyString: true }),
  correo: yup.string().email().nullable().max(150),
  horario_atencion: yup.string().nullable().max(200),
  activo: yup.boolean().required()
})
