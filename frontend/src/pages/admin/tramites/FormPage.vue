<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useFieldArray, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/yup'
import { useToast } from 'vue-toastification'
import { TramiteApi, type TramiteForm } from '@/api/tramite.api'
import { tramiteSchema } from '@/schemas/tramite.schemas'

const props = defineProps<{ id?: string }>()
const router = useRouter()
const toast = useToast()

const { defineField, handleSubmit, errors, setValues, isSubmitting } = useForm<TramiteForm>({
  validationSchema: toTypedSchema(tramiteSchema),
  initialValues: {
    codigo: '', nombre: '', slug: '', descripcion: '',
    requisitos: [],
    documentos_requeridos: null,
    tiempo_estimado: null, costo: 0, costo_variable: false,
    categoria: null, dependencia_id: 0, canal_atencion: null,
    url_govco: null, publicado: false
  }
})

const [codigo, codigoAttrs] = defineField('codigo')
const [nombre, nombreAttrs] = defineField('nombre')
const [slug, slugAttrs] = defineField('slug')
const [descripcion, descripcionAttrs] = defineField('descripcion')
const [tiempo, tiempoAttrs] = defineField('tiempo_estimado')
const [costo, costoAttrs] = defineField('costo')
const [costoVariable, costoVariableAttrs] = defineField('costo_variable')
const [categoria, categoriaAttrs] = defineField('categoria')
const [depId, depIdAttrs] = defineField('dependencia_id')
const [canal, canalAttrs] = defineField('canal_atencion')
const [urlGovco, urlGovcoAttrs] = defineField('url_govco')
const [pub, pubAttrs] = defineField('publicado')

const requisitos = useFieldArray<string>('requisitos')

onMounted(async () => {
  if (!props.id) return
  const t = await TramiteApi.obtenerAdmin(Number(props.id))
  setValues({
    codigo: t.codigo, nombre: t.nombre, slug: t.slug, descripcion: t.descripcion,
    requisitos: t.requisitos, documentos_requeridos: t.documentos_requeridos,
    tiempo_estimado: t.tiempo_estimado, costo: t.costo, costo_variable: t.costo_variable,
    categoria: t.categoria, dependencia_id: t.dependencia?.id ?? 0,
    canal_atencion: t.canal_atencion, url_govco: t.url_govco, publicado: t.publicado
  })
})

const onSubmit = handleSubmit(async (values) => {
  try {
    if (props.id) {
      const r = await TramiteApi.actualizar(Number(props.id), values)
      toast.success(r.message ?? 'Actualizado.')
    } else {
      const r = await TramiteApi.crear(values)
      toast.success(r.message ?? 'Creado.')
    }
    void router.push({ name: 'admin.tramites.lista' })
  } catch {
    toast.error('Errores en el formulario.')
  }
})
</script>

<template>
  <h1 class="h4 mb-3">
    <FaIcon icon="briefcase" class="me-2" />{{ id ? 'Editar' : 'Nuevo' }} trámite
  </h1>

  <form novalidate @submit="onSubmit" class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-3">
          <label for="codigo" class="form-label">Código</label>
          <input id="codigo" class="form-control" :class="{ 'is-invalid': errors.codigo }"
            v-model="codigo" v-bind="codigoAttrs" />
          <div v-if="errors.codigo" class="invalid-feedback">{{ errors.codigo }}</div>
        </div>
        <div class="col-md-9">
          <label for="nombre" class="form-label">Nombre</label>
          <input id="nombre" class="form-control" :class="{ 'is-invalid': errors.nombre }"
            v-model="nombre" v-bind="nombreAttrs" />
          <div v-if="errors.nombre" class="invalid-feedback">{{ errors.nombre }}</div>
        </div>
        <div class="col-12">
          <label for="slug" class="form-label">Slug</label>
          <input id="slug" class="form-control" :class="{ 'is-invalid': errors.slug }"
            v-model="slug" v-bind="slugAttrs" />
          <div v-if="errors.slug" class="invalid-feedback">{{ errors.slug }}</div>
        </div>
        <div class="col-12">
          <label for="descripcion" class="form-label">Descripción</label>
          <textarea id="descripcion" rows="4" class="form-control"
            :class="{ 'is-invalid': errors.descripcion }"
            v-model="descripcion" v-bind="descripcionAttrs"></textarea>
          <div v-if="errors.descripcion" class="invalid-feedback">{{ errors.descripcion }}</div>
        </div>

        <div class="col-12">
          <label class="form-label">Requisitos</label>
          <div v-for="(r, i) in requisitos.fields.value" :key="r.key" class="input-group mb-2">
            <input class="form-control" v-model="r.value" />
            <button type="button" class="btn btn-outline-danger" @click="requisitos.remove(i)">
              <FaIcon icon="trash" />
            </button>
          </div>
          <button type="button" class="btn btn-sm btn-outline-primary" @click="requisitos.push('')">
            <FaIcon icon="plus" /> Agregar requisito
          </button>
          <div v-if="errors.requisitos" class="text-danger small mt-1">{{ errors.requisitos }}</div>
        </div>

        <div class="col-md-3">
          <label class="form-label" for="tiempo">Tiempo estimado</label>
          <input id="tiempo" class="form-control" v-model="tiempo" v-bind="tiempoAttrs" />
        </div>
        <div class="col-md-3">
          <label class="form-label" for="costo">Costo (COP)</label>
          <input id="costo" type="number" min="0" class="form-control" v-model.number="costo" v-bind="costoAttrs" />
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <div class="form-check">
            <input id="cvar" type="checkbox" class="form-check-input" v-model="costoVariable" v-bind="costoVariableAttrs" />
            <label class="form-check-label" for="cvar">Costo variable</label>
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label" for="categoria">Categoría</label>
          <input id="categoria" class="form-control" v-model="categoria" v-bind="categoriaAttrs" />
        </div>
        <div class="col-md-3">
          <label class="form-label" for="depId">Dependencia (ID)</label>
          <input id="depId" type="number" class="form-control" v-model.number="depId" v-bind="depIdAttrs" />
        </div>
        <div class="col-md-3">
          <label class="form-label" for="canal">Canal de atención</label>
          <input id="canal" class="form-control" v-model="canal" v-bind="canalAttrs" />
        </div>
        <div class="col-md-6">
          <label class="form-label" for="govco">URL GOV.CO</label>
          <input id="govco" type="url" class="form-control" v-model="urlGovco" v-bind="urlGovcoAttrs" />
        </div>
        <div class="col-12">
          <div class="form-check">
            <input id="pub" type="checkbox" class="form-check-input" v-model="pub" v-bind="pubAttrs" />
            <label class="form-check-label" for="pub">Publicar</label>
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
