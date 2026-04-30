<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/yup'
import { useToast } from 'vue-toastification'
import { useAuthStore } from '@/stores/auth.store'
import { registerSchema } from '@/schemas/auth.schemas'

const auth = useAuthStore()
const router = useRouter()
const toast = useToast()

const { defineField, handleSubmit, errors, isSubmitting } = useForm({
  validationSchema: toTypedSchema(registerSchema)
})

const [name, nameAttrs] = defineField('name')
const [email, emailAttrs] = defineField('email')
const [password, passwordAttrs] = defineField('password')
const [passwordConfirmation, pcAttrs] = defineField('password_confirmation')

const onSubmit = handleSubmit(async (values) => {
  try {
    await auth.register({
      name: values.name,
      email: values.email,
      password: values.password,
      password_confirmation: values.password_confirmation
    })
    toast.success('Registro exitoso.')
    void router.push({ name: 'home' })
  } catch {
    toast.error('No fue posible registrar la cuenta.')
  }
})
</script>

<template>
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h1 class="h4 mb-4"><FaIcon icon="user-plus" class="me-2" />Registro</h1>

          <form novalidate @submit="onSubmit">
            <div class="mb-3">
              <label for="name" class="form-label">Nombre completo</label>
              <input id="name" class="form-control" :class="{ 'is-invalid': errors.name }"
                v-model="name" v-bind="nameAttrs" required />
              <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input id="email" type="email" class="form-control" :class="{ 'is-invalid': errors.email }"
                v-model="email" v-bind="emailAttrs" required />
              <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Contraseña (mín. 12, mayúsculas, minúsculas, número, símbolo)</label>
              <input id="password" type="password" class="form-control" :class="{ 'is-invalid': errors.password }"
                v-model="password" v-bind="passwordAttrs" required />
              <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
              <input id="password_confirmation" type="password" class="form-control"
                :class="{ 'is-invalid': errors.password_confirmation }"
                v-model="passwordConfirmation" v-bind="pcAttrs" required />
              <div v-if="errors.password_confirmation" class="invalid-feedback">{{ errors.password_confirmation }}</div>
            </div>

            <button class="btn btn-primary w-100" type="submit" :disabled="isSubmitting">
              <FaIcon v-if="isSubmitting" icon="spinner" spin class="me-2" />
              Crear cuenta
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
