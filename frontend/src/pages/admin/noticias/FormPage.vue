<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/yup'
import { useToast } from 'vue-toastification'
import { NoticiaApi, type NoticiaForm } from '@/api/noticia.api'
import { noticiaSchema } from '@/schemas/noticia.schemas'

const props = defineProps<{ id?: string }>()
const router = useRouter()
const toast = useToast()

const { defineField, handleSubmit, errors, setValues, isSubmitting } = useForm<NoticiaForm>({
  validationSchema: toTypedSchema(noticiaSchema),
  initialValues: {
    titulo: '', slug: '', resumen: '', contenido: '',
    imagen_destacada_url: null, imagen_destacada_alt: null,
    categoria: 'Institucional', etiquetas: null,
    publicado: false, publicado_en: null
  }
})

const [titulo, tituloAttrs] = defineField('titulo')
const [slug, slugAttrs] = defineField('slug')
const [resumen, resumenAttrs] = defineField('resumen')
const [contenido, contenidoAttrs] = defineField('contenido')
const [imgUrl, imgUrlAttrs] = defineField('imagen_destacada_url')
const [imgAlt, imgAltAttrs] = defineField('imagen_destacada_alt')
const [categoria, categoriaAttrs] = defineField('categoria')
const [pub, pubAttrs] = defineField('publicado')
const [pubEn, pubEnAttrs] = defineField('publicado_en')

onMounted(async () => {
  if (!props.id) return
  const n = await NoticiaApi.obtenerAdmin(Number(props.id))
  setValues({
    titulo: n.titulo, slug: n.slug, resumen: n.resumen, contenido: n.contenido,
    imagen_destacada_url: n.imagen_destacada_url, imagen_destacada_alt: n.imagen_destacada_alt,
    categoria: n.categoria, etiquetas: n.etiquetas,
    publicado: n.publicado, publicado_en: n.publicado_en
  })
})

const onSubmit = handleSubmit(async (values) => {
  try {
    if (props.id) {
      const r = await NoticiaApi.actualizar(Number(props.id), values)
      toast.success(r.message ?? 'Actualizada.')
    } else {
      const r = await NoticiaApi.crear(values)
      toast.success(r.message ?? 'Creada.')
    }
    void router.push({ name: 'admin.noticias.lista' })
  } catch {
    toast.error('Errores en el formulario.')
  }
})
</script>

<template>
  <h1 class="h4 mb-3">
    <FaIcon icon="globe" class="me-2" />{{ id ? 'Editar' : 'Nueva' }} noticia
  </h1>

  <form novalidate @submit="onSubmit" class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-8">
          <label for="titulo" class="form-label">Título</label>
          <input id="titulo" class="form-control" :class="{ 'is-invalid': errors.titulo }"
            v-model="titulo" v-bind="tituloAttrs" />
          <div v-if="errors.titulo" class="invalid-feedback">{{ errors.titulo }}</div>
        </div>
        <div class="col-md-4">
          <label for="categoria" class="form-label">Categoría</label>
          <input id="categoria" class="form-control" v-model="categoria" v-bind="categoriaAttrs" />
        </div>
        <div class="col-12">
          <label for="slug" class="form-label">Slug</label>
          <input id="slug" class="form-control" :class="{ 'is-invalid': errors.slug }"
            v-model="slug" v-bind="slugAttrs" />
          <div v-if="errors.slug" class="invalid-feedback">{{ errors.slug }}</div>
        </div>
        <div class="col-12">
          <label for="resumen" class="form-label">Resumen</label>
          <textarea id="resumen" rows="2" class="form-control" :class="{ 'is-invalid': errors.resumen }"
            v-model="resumen" v-bind="resumenAttrs"></textarea>
          <div v-if="errors.resumen" class="invalid-feedback">{{ errors.resumen }}</div>
        </div>
        <div class="col-12">
          <label for="contenido" class="form-label">Contenido (HTML)</label>
          <textarea id="contenido" rows="10" class="form-control"
            :class="{ 'is-invalid': errors.contenido }"
            v-model="contenido" v-bind="contenidoAttrs"></textarea>
          <div v-if="errors.contenido" class="invalid-feedback">{{ errors.contenido }}</div>
        </div>
        <div class="col-md-6">
          <label for="imgUrl" class="form-label">URL imagen destacada</label>
          <input id="imgUrl" type="url" class="form-control" v-model="imgUrl" v-bind="imgUrlAttrs" />
        </div>
        <div class="col-md-6">
          <label for="imgAlt" class="form-label">Texto alternativo (alt)</label>
          <input id="imgAlt" class="form-control" :class="{ 'is-invalid': errors.imagen_destacada_alt }"
            v-model="imgAlt" v-bind="imgAltAttrs" />
          <div v-if="errors.imagen_destacada_alt" class="invalid-feedback">{{ errors.imagen_destacada_alt }}</div>
        </div>
        <div class="col-md-4">
          <label for="pubEn" class="form-label">Fecha de publicación</label>
          <input id="pubEn" type="datetime-local" class="form-control"
            v-model="pubEn" v-bind="pubEnAttrs" />
        </div>
        <div class="col-md-8 d-flex align-items-end">
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
