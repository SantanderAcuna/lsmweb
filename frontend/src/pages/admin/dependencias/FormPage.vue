<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/yup'
import { useToast } from 'vue-toastification'
import { DependenciaApi } from '@/api/dependencia.api'
import { dependenciaSchema } from '@/schemas/dependencia.schemas'
import type { DependenciaForm } from '@/types/dependencia'

const props = defineProps<{ id?: string }>()
const router = useRouter()
const toast = useToast()

const { defineField, handleSubmit, errors, setValues, isSubmitting } = useForm<DependenciaForm>({
  validationSchema: toTypedSchema(dependenciaSchema),
  initialValues: {
    codigo: '', nombre: '', descripcion: null,
    dependencia_padre_id: null, extension: null,
    correo: null, activo: true
  }
})

const [codigo, codigoAttrs] = defineField('codigo')
const [nombre, nombreAttrs] = defineField('nombre')
const [descripcion, descripcionAttrs] = defineField('descripcion')
const [extension, extensionAttrs] = defineField('extension')
const [correo, correoAttrs] = defineField('correo')
const [activo, activoAttrs] = defineField('activo')
const [padreId, padreIdAttrs] = defineField('dependencia_padre_id')

onMounted(async () => {
  if (!props.id) return
  const d = await DependenciaApi.obtenerAdmin(Number(props.id))
  setValues({
    codigo: d.codigo, nombre: d.nombre, descripcion: d.descripcion,
    dependencia_padre_id: d.dependencia_padre_id, extension: d.extension,
    correo: d.correo, activo: d.activo
  })
})

const onSubmit = handleSubmit(async (values) => {
  try {
    if (props.id) {
      const r = await DependenciaApi.actualizar(Number(props.id), values)
      toast.success(r.message ?? 'Actualizada.')
    } else {
      const r = await DependenciaApi.crear(values)
      toast.success(r.message ?? 'Creada.')
    }
    void router.push({ name: 'admin.dependencias.lista' })
  } catch {
    toast.error('Errores en el formulario.')
  }
})
</script>

<template>
  <h1 class="h4 mb-3">
    <FaIcon icon="building" class="me-2" />{{ id ? 'Editar' : 'Nueva' }} dependencia
  </h1>

  <form novalidate @submit="onSubmit" class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label" for="codigo">Código</label>
          <input id="codigo" class="form-control" :class="{ 'is-invalid': errors.codigo }"
            v-model="codigo" v-bind="codigoAttrs" />
          <div v-if="errors.codigo" class="invalid-feedback">{{ errors.codigo }}</div>
        </div>
        <div class="col-md-9">
          <label class="form-label" for="nombre">Nombre</label>
          <input id="nombre" class="form-control" :class="{ 'is-invalid': errors.nombre }"
            v-model="nombre" v-bind="nombreAttrs" />
          <div v-if="errors.nombre" class="invalid-feedback">{{ errors.nombre }}</div>
        </div>
        <div class="col-12">
          <label class="form-label" for="descripcion">Descripción</label>
          <textarea id="descripcion" rows="3" class="form-control"
            v-model="descripcion" v-bind="descripcionAttrs"></textarea>
        </div>
        <div class="col-md-4">
          <label class="form-label" for="ext">Extensión</label>
          <input id="ext" class="form-control" v-model="extension" v-bind="extensionAttrs" />
        </div>
        <div class="col-md-4">
          <label class="form-label" for="correo">Correo institucional</label>
          <input id="correo" type="email" class="form-control" :class="{ 'is-invalid': errors.correo }"
            v-model="correo" v-bind="correoAttrs" />
          <div v-if="errors.correo" class="invalid-feedback">{{ errors.correo }}</div>
        </div>
        <div class="col-md-4">
          <label class="form-label" for="padre">Dependencia padre (ID)</label>
          <input id="padre" type="number" class="form-control"
            v-model.number="padreId" v-bind="padreIdAttrs" />
        </div>
        <div class="col-12">
          <div class="form-check">
            <input id="activo" type="checkbox" class="form-check-input" v-model="activo" v-bind="activoAttrs" />
            <label class="form-check-label" for="activo">Activa</label>
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
