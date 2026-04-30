<script setup lang="ts">
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/yup'
import { useToast } from 'vue-toastification'
import { AuthApi } from '@/api/auth.api'
import { forgotSchema } from '@/schemas/auth.schemas'

const toast = useToast()
const { defineField, handleSubmit, errors, isSubmitting } = useForm({
  validationSchema: toTypedSchema(forgotSchema)
})
const [email, emailAttrs] = defineField('email')

const onSubmit = handleSubmit(async (values) => {
  const r = await AuthApi.forgotPassword({ email: values.email })
  toast.info(r.message)
})
</script>

<template>
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h1 class="h4 mb-3"><FaIcon icon="key" class="me-2" />Recuperar contraseña</h1>
          <p>Ingresa tu correo y enviaremos un enlace de recuperación.</p>
          <form novalidate @submit="onSubmit">
            <label for="email" class="form-label">Correo</label>
            <input id="email" type="email" class="form-control mb-3" :class="{ 'is-invalid': errors.email }"
              v-model="email" v-bind="emailAttrs" required />
            <div v-if="errors.email" class="invalid-feedback d-block mb-2">{{ errors.email }}</div>
            <button class="btn btn-primary w-100" :disabled="isSubmitting">Enviar enlace</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
