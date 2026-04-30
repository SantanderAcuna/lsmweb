import * as yup from 'yup'

const telefonoRegex = /^\+57\s(?:\d\s\d{7}|1\s8000\s\d{4,5}|3\d{2}\s\d{7})$/

export const formacionSchema = yup.object({
  nivel: yup.mixed<'BACHILLER' | 'TECNICO' | 'TECNOLOGO' | 'PROFESIONAL' | 'ESPECIALIZACION' | 'MAESTRIA' | 'DOCTORADO'>()
    .oneOf(['BACHILLER', 'TECNICO', 'TECNOLOGO', 'PROFESIONAL', 'ESPECIALIZACION', 'MAESTRIA', 'DOCTORADO'])
    .required(),
  titulo: yup.string().required().max(200),
  institucion: yup.string().required().max(200),
  pais_id: yup.number().required().integer().positive(),
  ano_grado: yup.number().required().integer().min(1950).max(new Date().getFullYear())
})

export const experienciaSchema = yup.object({
  empresa: yup.string().required().max(200),
  cargo: yup.string().required().max(150),
  sector: yup.mixed<'PUBLICO' | 'PRIVADO' | 'MIXTO' | 'ONG' | 'ACADEMICO'>()
    .oneOf(['PUBLICO', 'PRIVADO', 'MIXTO', 'ONG', 'ACADEMICO']).required(),
  fecha_inicio: yup.string().required().matches(/^\d{4}-\d{2}-\d{2}$/),
  fecha_fin: yup.string().nullable().matches(/^\d{4}-\d{2}-\d{2}$/, { excludeEmptyString: true }),
  funciones: yup.string().nullable()
})

export const servidorSchema = yup.object({
  nombres: yup.string().required().max(100),
  apellidos: yup.string().required().max(100),
  tipo_documento: yup.mixed<'CC' | 'CE' | 'PA' | 'TI'>().oneOf(['CC', 'CE', 'PA', 'TI']).required(),
  documento_identidad: yup.string().required().max(20),
  municipio_nacimiento_id: yup.number().required().integer().positive(),
  fecha_nacimiento: yup.string().nullable().matches(/^\d{4}-\d{2}-\d{2}$/, { excludeEmptyString: true }),
  genero: yup.mixed<'M' | 'F' | 'O' | 'NR'>().oneOf(['M', 'F', 'O', 'NR']).nullable(),
  dependencia_id: yup.number().required().integer().positive(),
  cargo: yup.string().required().max(150),
  naturaleza_cargo: yup.mixed<'LIBRE_NOMBRAMIENTO' | 'CARRERA' | 'PROVISIONAL' | 'CONTRATO_PRESTACION' | 'ELECCION_POPULAR'>()
    .oneOf(['LIBRE_NOMBRAMIENTO', 'CARRERA', 'PROVISIONAL', 'CONTRATO_PRESTACION', 'ELECCION_POPULAR']).required(),
  salario_basico: yup.number().nullable().min(0),
  correo_institucional: yup.string().required().email()
    .matches(/@santamarta\.gov\.co$/, 'Debe pertenecer al dominio @santamarta.gov.co'),
  correo_personal: yup.string().email().nullable(),
  telefono_oficina: yup.string().nullable()
    .matches(telefonoRegex, { message: 'Formato +57 X XXXXXXX (Resolución 1519/2020)', excludeEmptyString: true }),
  extension: yup.string().nullable().max(10),
  sigep_url: yup.string().url().nullable(),
  foto_url: yup.string().url().nullable(),
  publicado: yup.boolean().required(),
  formaciones_academicas: yup.array().of(formacionSchema).default([]),
  experiencias_profesionales: yup.array().of(experienciaSchema).default([])
})
