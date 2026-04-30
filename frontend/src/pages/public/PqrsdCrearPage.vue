<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/yup'
import { useToast } from 'vue-toastification'
import { PqrsdApi } from '@/api/pqrsd.api'
import { pqrsdSchema } from '@/schemas/pqrsd.schemas'

const toast = useToast()
const radicadoGenerado = ref<string | null>(null)
const fechaLimite = ref<string | null>(null)

const { defineField, handleSubmit, errors, isSubmitting, values } = useForm({
  validationSchema: toTypedSchema(pqrsdSchema),
  initialValues: {
    tipo: 'PETICION',
    asunto: '',
    descripcion: '',
    anonima: false,
    solicitante_nombre: '',
    solicitante_documento: '',
    solicitante_correo: '',
    solicitante_telefono: ''
  }
})

const [tipo, tipoAttrs] = defineField('tipo')
const [asunto, asuntoAttrs] = defineField('asunto')
const [descripcion, descripcionAttrs] = defineField('descripcion')
const [anonima, anonimaAttrs] = defineField('anonima')
const [nombre, nombreAttrs] = defineField('solicitante_nombre')
const [doc, docAttrs] = defineField('solicitante_documento')
const [correo, correoAttrs] = defineField('solicitante_correo')
const [tel, telAttrs] = defineField('solicitante_telefono')

const onSubmit = handleSubmit(async (v) => {
  try {
    const r = await PqrsdApi.crear({
      tipo: v.tipo,
      asunto: v.asunto,
      descripcion: v.descripcion,
      anonima: v.anonima,
      solicitante_nombre: v.solicitante_nombre || undefined,
      solicitante_documento: v.solicitante_documento || undefined,
      solicitante_correo: v.solicitante_correo || undefined,
      solicitante_telefono: v.solicitante_telefono || undefined
    })
    toast.success(r.message)
    radicadoGenerado.value = r.data.radicado
    fechaLimite.value = r.data.fecha_limite_respuesta
  } catch {
    toast.error('No se pudo radicar la PQRSD.')
  }
})
</script>

<template>
  <h1 class="h3"><FaIcon icon="envelope" class="me-2" />Radicar PQRSD</h1>
  <p class="text-muted">Peticiones, Quejas, Reclamos, Sugerencias o Denuncias (Ley 1755/2015).</p>

  <div v-if="radicadoGenerado" class="alert alert-success">
    <h2 class="h5"><FaIcon icon="check" class="me-2" />Radicación exitosa</h2>
    <p class="mb-1">Su número de radicado es <strong>{{ radicadoGenerado }}</strong>.</p>
    <p class="mb-0">Fecha límite de respuesta: <strong>{{ fechaLimite }}</strong></p>
  </div>

  <form v-else novalidate @submit="onSubmit" class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label for="tipo" class="form-label">Tipo</label>
          <select id="tipo" class="form-select" v-model="tipo" v-bind="tipoAttrs">
            <option value="PETICION">Petición</option>
            <option value="QUEJA">Queja</option>
            <option value="RECLAMO">Reclamo</option>
            <option value="SUGERENCIA">Sugerencia</option>
            <option value="DENUNCIA">Denuncia</option>
            <option value="INFORMACION">Solicitud de información (Ley 1712)</option>
          </select>
        </div>
        <div class="col-md-8">
          <label for="asunto" class="form-label">Asunto</label>
          <input id="asunto" class="form-control" :class="{ 'is-invalid': errors.asunto }"
            v-model="asunto" v-bind="asuntoAttrs" />
          <div v-if="errors.asunto" class="invalid-feedback">{{ errors.asunto }}</div>
        </div>
        <div class="col-12">
          <label for="descripcion" class="form-label">Descripción</label>
          <textarea id="descripcion" rows="5" class="form-control" :class="{ 'is-invalid': errors.descripcion }"
            v-model="descripcion" v-bind="descripcionAttrs"></textarea>
          <div v-if="errors.descripcion" class="invalid-feedback">{{ errors.descripcion }}</div>
        </div>
        <div class="col-12">
          <div class="form-check">
            <input id="anonima" type="checkbox" class="form-check-input" v-model="anonima" v-bind="anonimaAttrs" />
            <label for="anonima" class="form-check-label">Radicar de forma anónima</label>
          </div>
        </div>

        <template v-if="!values.anonima">
          <div class="col-md-6">
            <label class="form-label">Nombre completo</label>
            <input class="form-control" :class="{ 'is-invalid': errors.solicitante_nombre }"
              v-model="nombre" v-bind="nombreAttrs" />
            <div v-if="errors.solicitante_nombre" class="invalid-feedback">{{ errors.solicitante_nombre }}</div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Documento</label>
            <input class="form-control" :class="{ 'is-invalid': errors.solicitante_documento }"
              v-model="doc" v-bind="docAttrs" />
            <div v-if="errors.solicitante_documento" class="invalid-feedback">{{ errors.solicitante_documento }}</div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Correo</label>
            <input type="email" class="form-control" :class="{ 'is-invalid': errors.solicitante_correo }"
              v-model="correo" v-bind="correoAttrs" />
            <div v-if="errors.solicitante_correo" class="invalid-feedback">{{ errors.solicitante_correo }}</div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input class="form-control" v-model="tel" v-bind="telAttrs" />
          </div>
        </template>
      </div>
    </div>
    <div class="card-footer">
      <button class="btn btn-primary" :disabled="isSubmitting">
        <FaIcon v-if="isSubmitting" icon="spinner" spin class="me-2" />
        Radicar
      </button>
    </div>
  </form>
</template>
