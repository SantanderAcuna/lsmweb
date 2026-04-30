import * as yup from 'yup'

export const loginSchema = yup.object({
  email: yup.string().required('El correo es obligatorio.').email('Formato de correo inválido.'),
  password: yup.string().required('La contraseña es obligatoria.')
})

export const registerSchema = yup.object({
  name: yup.string().required('El nombre es obligatorio.').max(120),
  email: yup.string().required('El correo es obligatorio.').email().max(150),
  password: yup
    .string()
    .required('La contraseña es obligatoria.')
    .min(12, 'Mínimo 12 caracteres.')
    .matches(/[A-Z]/, 'Debe incluir mayúscula.')
    .matches(/[a-z]/, 'Debe incluir minúscula.')
    .matches(/\d/, 'Debe incluir un número.')
    .matches(/[^\w\s]/, 'Debe incluir un símbolo.'),
  password_confirmation: yup
    .string()
    .required('Confirmación obligatoria.')
    .oneOf([yup.ref('password')], 'Las contraseñas no coinciden.')
})

export const forgotSchema = yup.object({
  email: yup.string().required().email()
})

export const resetSchema = yup.object({
  token: yup.string().required(),
  email: yup.string().required().email(),
  password: yup.string().required().min(12),
  password_confirmation: yup.string().required().oneOf([yup.ref('password')], 'No coinciden.')
})
