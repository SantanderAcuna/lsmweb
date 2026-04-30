<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/yup'
import { useToast } from 'vue-toastification'
import { SedeApi } from '@/api/sede.api'
import { sedeSchema } from '@/schemas/sede.schemas'
import type { SedeForm } from '@/types/sede'

const props = defineProps<{ id?: string }>()
const router = useRouter()
const toast = useToast()

const { defineField, handleSubmit, errors, setValues, isSubmitting } = useForm<SedeForm>({
  validationSchema: toTypedSchema(sedeSchema),
  initialValues: {
    nombre: '', municipio_id: 0, direccion: '',
    latitud: null, longitud: null,
    telefono: null, correo: null,
    horario_atencion: 'Lun-Vie 8:00-12:00 y 14:00-18:00',
    activo: true
  }
})

const [nombre, nombreAttrs] = defineField('nombre')
const [muni, muniAttrs] = defineField('municipio_id')
const [direccion, direccionAttrs] = defineField('direccion')
const [latitud, latitudAttrs] = defineField('latitud')
const [longitud, longitudAttrs] = defineField('longitud')
const [tel, telAttrs] = defineField('telefono')
const [correo, correoAttrs] = defineField('correo')
const [horario, horarioAttrs] = defineField('horario_atencion')
const [activo, activoAttrs] = defineField('activo')

onMounted(async () => {
  if (!props.id) return
  const s = await SedeApi.obtenerAdmin(Number(props.id))
  setValues({
    nombre: s.nombre, municipio_id: s.municipio_id, direccion: s.direccion,
    latitud: s.latitud, longitud: s.longitud,
    telefono: s.telefono, correo: s.correo,
    horario_atencion: s.horario_atencion, activo: s.activo
  })
})

const onSubmit = handleSubmit(async (values) => {
  try {
    if (props.id) {
      const r = await SedeApi.actualizar(Number(props.id), values)
      toast.success(r.message ?? 'Actualizada.')
    } else {
      const r = await SedeApi.crear(values)
      toast.success(r.message ?? 'Creada.')
    }
    void router.push({ name: 'admin.sedes.lista' })
  } catch {
    toast.error('Errores en el formulario.')
  }
})
</script>

<template>
  <h1 class="h4 mb-3">
    <FaIcon icon="map-marker-alt" class="me-2" />{{ id ? 'Editar' : 'Nueva' }} sede
  </h1>

  <form novalidate @submit="onSubmit" class="card">
    <div class="card-body row g-3">
      <div class="col-md-8">
        <label class="form-label" for="nombre">Nombre</label>
        <input id="nombre" class="form-control" :class="{ 'is-invalid': errors.nombre }"
          v-model="nombre" v-bind="nombreAttrs" />
        <div v-if="errors.nombre" class="invalid-feedback">{{ errors.nombre }}</div>
      </div>
      <div class="col-md-4">
        <label class="form-label" for="muni">Municipio (ID)</label>
        <input id="muni" type="number" class="form-control" :class="{ 'is-invalid': errors.municipio_id }"
          v-model.number="muni" v-bind="muniAttrs" />
        <div v-if="errors.municipio_id" class="invalid-feedback">{{ errors.municipio_id }}</div>
      </div>
      <div class="col-12">
        <label class="form-label" for="dir">Dirección</label>
        <input id="dir" class="form-control" v-model="direccion" v-bind="direccionAttrs" />
      </div>
      <div class="col-md-6">
        <label class="form-label" for="lat">Latitud</label>
        <input id="lat" type="number" step="0.0000001" class="form-control"
          v-model.number="latitud" v-bind="latitudAttrs" />
      </div>
      <div class="col-md-6">
        <label class="form-label" for="lng">Longitud</label>
        <input id="lng" type="number" step="0.0000001" class="form-control"
          v-model.number="longitud" v-bind="longitudAttrs" />
      </div>
      <div class="col-md-4">
        <label class="form-label" for="tel">Teléfono</label>
        <input id="tel" class="form-control" :class="{ 'is-invalid': errors.telefono }"
          v-model="tel" v-bind="telAttrs" />
        <div v-if="errors.telefono" class="invalid-feedback">{{ errors.telefono }}</div>
      </div>
      <div class="col-md-4">
        <label class="form-label" for="correo">Correo</label>
        <input id="correo" type="email" class="form-control" v-model="correo" v-bind="correoAttrs" />
      </div>
      <div class="col-md-4">
        <label class="form-label" for="horario">Horario</label>
        <input id="horario" class="form-control" v-model="horario" v-bind="horarioAttrs" />
      </div>
      <div class="col-12">
        <div class="form-check">
          <input id="activo" type="checkbox" class="form-check-input" v-model="activo" v-bind="activoAttrs" />
          <label class="form-check-label" for="activo">Activa</label>
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
