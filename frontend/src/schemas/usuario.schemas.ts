import * as yup from 'yup'

const passwordRules = (s: yup.StringSchema) => s
  .min(12, 'Mínimo 12 caracteres.')
  .matches(/[A-Z]/, 'Debe incluir mayúscula.')
  .matches(/[a-z]/, 'Debe incluir minúscula.')
  .matches(/\d/, 'Debe incluir número.')
  .matches(/[^\w\s]/, 'Debe incluir símbolo.')

export const usuarioCrearSchema = yup.object({
  name: yup.string().required().max(120),
  email: yup.string().required().email().max(150),
  password: passwordRules(yup.string().required()),
  estado: yup.mixed<'ACTIVO' | 'INACTIVO' | 'BLOQUEADO'>().oneOf(['ACTIVO', 'INACTIVO', 'BLOQUEADO']).required(),
  roles: yup.array().of(yup.string().required()).default([])
})

export const usuarioActualizarSchema = yup.object({
  name: yup.string().max(120),
  email: yup.string().email().max(150),
  password: yup.string().nullable().test('strong-or-empty', 'Contraseña no cumple políticas.', (value) => {
    if (!value) return true
    return passwordRules(yup.string()).isValidSync(value)
  }),
  estado: yup.mixed<'ACTIVO' | 'INACTIVO' | 'BLOQUEADO'>().oneOf(['ACTIVO', 'INACTIVO', 'BLOQUEADO']),
  roles: yup.array().of(yup.string().required())
})

export const rolSchema = yup.object({
  name: yup.string().required().matches(/^[a-z0-9_]+$/, 'Solo minúsculas, números y guion bajo.').max(80),
  permissions: yup.array().of(yup.string().required()).default([])
})
