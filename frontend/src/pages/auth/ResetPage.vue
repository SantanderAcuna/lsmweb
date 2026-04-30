<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/yup'
import { useToast } from 'vue-toastification'
import { AuthApi } from '@/api/auth.api'
import { resetSchema } from '@/schemas/auth.schemas'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const { defineField, handleSubmit, errors, isSubmitting } = useForm({
  validationSchema: toTypedSchema(resetSchema),
  initialValues: {
    token: (route.query.token as string | undefined) ?? '',
    email: (route.query.email as string | undefined) ?? ''
  }
})

const [token, tokenAttrs] = defineField('token')
const [email, emailAttrs] = defineField('email')
const [password, passwordAttrs] = defineField('password')
const [passwordConfirmation, pcAttrs] = defineField('password_confirmation')

const onSubmit = handleSubmit(async (values) => {
  const r = await AuthApi.resetPassword({
    token: values.token,
    email: values.email,
    password: values.password,
    password_confirmation: values.password_confirmation
  })
  toast.success(r.message)
  void router.push({ name: 'auth.login' })
})
</script>

<template>
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h1 class="h4 mb-3"><FaIcon icon="key" class="me-2" />Restablecer contraseña</h1>
          <form novalidate @submit="onSubmit">
            <input type="hidden" v-model="token" v-bind="tokenAttrs" />
            <label for="email" class="form-label">Correo</label>
            <input id="email" type="email" class="form-control mb-3" :class="{ 'is-invalid': errors.email }"
              v-model="email" v-bind="emailAttrs" required />
            <label for="password" class="form-label">Nueva contraseña</label>
            <input id="password" type="password" class="form-control mb-3" :class="{ 'is-invalid': errors.password }"
              v-model="password" v-bind="passwordAttrs" required />
            <label for="password_confirmation" class="form-label">Confirmar</label>
            <input id="password_confirmation" type="password" class="form-control mb-3"
              :class="{ 'is-invalid': errors.password_confirmation }"
              v-model="passwordConfirmation" v-bind="pcAttrs" required />
            <button class="btn btn-primary w-100" :disabled="isSubmitting">Restablecer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
