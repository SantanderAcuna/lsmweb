<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useQuery } from '@tanstack/vue-query'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/yup'
import { useToast } from 'vue-toastification'
import { RolApi, UsuarioApi } from '@/api/usuario.api'
import { usuarioCrearSchema } from '@/schemas/usuario.schemas'

const props = defineProps<{ id?: string }>()
const router = useRouter()
const toast = useToast()

const editando = !!props.id

const { data: roles } = useQuery({
  queryKey: ['roles'],
  queryFn: () => RolApi.listar({ per_page: 100 })
})

const { defineField, handleSubmit, errors, setValues, isSubmitting } = useForm({
  validationSchema: editando ? undefined : toTypedSchema(usuarioCrearSchema),
  initialValues: {
    name: '', email: '', password: '',
    estado: 'ACTIVO' as const, roles: [] as string[]
  }
})

const [name, nameAttrs] = defineField('name')
const [email, emailAttrs] = defineField('email')
const [password, passwordAttrs] = defineField('password')
const [estado, estadoAttrs] = defineField('estado')
const rolesSeleccionados = ref<string[]>([])

onMounted(async () => {
  if (!props.id) return
  const u = await UsuarioApi.obtener(Number(props.id))
  setValues({ name: u.name, email: u.email, password: '', estado: u.estado, roles: u.roles })
  rolesSeleccionados.value = [...u.roles]
})

const onSubmit = handleSubmit(async (values) => {
  try {
    const payload = { ...values, roles: rolesSeleccionados.value }
    if (props.id) {
      // En edición no enviar password vacío
      const update = { ...payload, password: payload.password || undefined }
      const r = await UsuarioApi.actualizar(Number(props.id), update)
      toast.success(r.message ?? 'Actualizado.')
    } else {
      const r = await UsuarioApi.crear(payload)
      toast.success(r.message ?? 'Creado.')
    }
    void router.push({ name: 'admin.usuarios.lista' })
  } catch {
    toast.error('Errores en el formulario.')
  }
})
</script>

<template>
  <h1 class="h4 mb-3">
    <FaIcon icon="user-plus" class="me-2" />{{ editando ? 'Editar' : 'Nuevo' }} usuario
  </h1>

  <form novalidate @submit="onSubmit" class="card">
    <div class="card-body row g-3">
      <div class="col-md-6">
        <label class="form-label" for="name">Nombre</label>
        <input id="name" class="form-control" :class="{ 'is-invalid': errors.name }"
          v-model="name" v-bind="nameAttrs" />
        <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
      </div>
      <div class="col-md-6">
        <label class="form-label" for="email">Correo</label>
        <input id="email" type="email" class="form-control" :class="{ 'is-invalid': errors.email }"
          v-model="email" v-bind="emailAttrs" />
        <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
      </div>
      <div class="col-md-6">
        <label class="form-label" for="password">
          Contraseña
          <small v-if="editando" class="text-muted">(deje vacío para no cambiar)</small>
        </label>
        <input id="password" type="password" class="form-control" :class="{ 'is-invalid': errors.password }"
          v-model="password" v-bind="passwordAttrs" />
        <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
      </div>
      <div class="col-md-6">
        <label class="form-label" for="estado">Estado</label>
        <select id="estado" class="form-select" v-model="estado" v-bind="estadoAttrs">
          <option value="ACTIVO">Activo</option>
          <option value="INACTIVO">Inactivo</option>
          <option value="BLOQUEADO">Bloqueado</option>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label">Roles</label>
        <div class="row g-2">
          <div v-for="r in (roles?.data ?? [])" :key="r.id" class="col-md-3">
            <div class="form-check">
              <input :id="`r-${r.id}`" type="checkbox" class="form-check-input"
                :value="r.name" v-model="rolesSeleccionados" />
              <label :for="`r-${r.id}`" class="form-check-label">{{ r.name }}</label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <button class="btn btn-primary" :disabled="isSubmitting">
        <FaIcon v-if="isSubmitting" icon="spinner" spin class="me-2" />
        <FaIcon v-else icon="save" class="me-2" />Guardar
      </button>
    </div>
  </form>
</template>
