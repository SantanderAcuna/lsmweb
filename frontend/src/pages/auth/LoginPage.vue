<script setup lang="ts">
import { useRouter, useRoute } from 'vue-router'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/yup'
import { useToast } from 'vue-toastification'
import { useAuthStore } from '@/stores/auth.store'
import { loginSchema } from '@/schemas/auth.schemas'

const auth = useAuthStore()
const router = useRouter()
const route = useRoute()
const toast = useToast()

const { defineField, handleSubmit, errors, isSubmitting } = useForm({
  validationSchema: toTypedSchema(loginSchema)
})

const [email, emailAttrs] = defineField('email')
const [password, passwordAttrs] = defineField('password')

const onSubmit = handleSubmit(async (values) => {
  try {
    await auth.login(values.email, values.password)
    toast.success('Inicio de sesión exitoso.')
    const redirect = (route.query.redirect as string | undefined) ?? '/'
    void router.push(redirect)
  } catch {
    toast.error('Credenciales inválidas.')
  }
})
</script>

<template>
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h1 class="h4 mb-4">
            <FaIcon icon="sign-in-alt" class="me-2" />Iniciar sesión
          </h1>

          <form novalidate @submit="onSubmit">
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input
                id="email" type="email"
                class="form-control" :class="{ 'is-invalid': errors.email }"
                v-model="email" v-bind="emailAttrs" autocomplete="email" required />
              <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input
                id="password" type="password"
                class="form-control" :class="{ 'is-invalid': errors.password }"
                v-model="password" v-bind="passwordAttrs" autocomplete="current-password" required />
              <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
            </div>

            <button class="btn btn-primary w-100" type="submit" :disabled="isSubmitting">
              <FaIcon v-if="isSubmitting" icon="spinner" spin class="me-2" />
              Entrar
            </button>
          </form>

          <hr />
          <div class="d-flex justify-content-between small">
            <RouterLink :to="{ name: 'auth.register' }">Crear cuenta</RouterLink>
            <RouterLink :to="{ name: 'auth.forgot' }">Olvidé mi contraseña</RouterLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
