<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useFieldArray, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/yup'
import { useToast } from 'vue-toastification'
import { ServidorApi } from '@/api/servidor.api'
import { servidorSchema } from '@/schemas/servidor.schemas'
import type { ServidorPublicoForm } from '@/types/servidor'

const props = defineProps<{ id?: string }>()
const router = useRouter()
const toast = useToast()

const { defineField, handleSubmit, errors, setValues, isSubmitting } = useForm<ServidorPublicoForm>({
  validationSchema: toTypedSchema(servidorSchema),
  initialValues: {
    nombres: '', apellidos: '',
    tipo_documento: 'CC', documento_identidad: '',
    municipio_nacimiento_id: 0,
    fecha_nacimiento: null, genero: null,
    dependencia_id: 0,
    cargo: '', naturaleza_cargo: 'CARRERA', salario_basico: null,
    correo_institucional: '', correo_personal: null,
    telefono_oficina: null, extension: null,
    sigep_url: null, foto_url: null,
    publicado: false,
    formaciones_academicas: [],
    experiencias_profesionales: []
  }
})

const [nombres, nombresAttrs] = defineField('nombres')
const [apellidos, apellidosAttrs] = defineField('apellidos')
const [docTipo, docTipoAttrs] = defineField('tipo_documento')
const [doc, docAttrs] = defineField('documento_identidad')
const [muni, muniAttrs] = defineField('municipio_nacimiento_id')
const [dep, depAttrs] = defineField('dependencia_id')
const [cargo, cargoAttrs] = defineField('cargo')
const [natur, naturAttrs] = defineField('naturaleza_cargo')
const [correoInst, correoInstAttrs] = defineField('correo_institucional')
const [tel, telAttrs] = defineField('telefono_oficina')
const [sigep, sigepAttrs] = defineField('sigep_url')
const [pub, pubAttrs] = defineField('publicado')

const formaciones = useFieldArray<ServidorPublicoForm['formaciones_academicas'][number]>('formaciones_academicas')
const experiencias = useFieldArray<ServidorPublicoForm['experiencias_profesionales'][number]>('experiencias_profesionales')

onMounted(async () => {
  if (!props.id) return
  const s = await ServidorApi.obtenerAdmin(Number(props.id))
  setValues({
    nombres: s.nombres, apellidos: s.apellidos,
    tipo_documento: 'CC', documento_identidad: '',
    municipio_nacimiento_id: 0,
    fecha_nacimiento: s.fecha_nacimiento, genero: s.genero,
    dependencia_id: s.dependencia?.id ?? 0,
    cargo: s.cargo, naturaleza_cargo: s.naturaleza_cargo,
    salario_basico: s.salario_basico,
    correo_institucional: s.correo_institucional,
    correo_personal: null,
    telefono_oficina: s.telefono_oficina, extension: s.extension,
    sigep_url: s.sigep_url, foto_url: s.foto_url,
    publicado: s.publicado,
    formaciones_academicas: s.formaciones_academicas.map((f) => ({
      nivel: f.nivel, titulo: f.titulo, institucion: f.institucion,
      pais_id: f.pais_id, ano_grado: f.ano_grado
    })),
    experiencias_profesionales: s.experiencias_profesionales.map((e) => ({
      empresa: e.empresa, cargo: e.cargo, sector: e.sector,
      fecha_inicio: e.fecha_inicio, fecha_fin: e.fecha_fin, funciones: e.funciones
    }))
  })
})

const onSubmit = handleSubmit(async (values) => {
  try {
    if (props.id) {
      const r = await ServidorApi.actualizar(Number(props.id), values)
      toast.success(r.message ?? 'Actualizado.')
    } else {
      const r = await ServidorApi.crear(values)
      toast.success(r.message ?? 'Creado.')
    }
    void router.push({ name: 'admin.servidores.lista' })
  } catch {
    toast.error('Errores de validación. Revisa el formulario.')
  }
})
</script>

<template>
  <h1 class="h4 mb-3">
    <FaIcon icon="save" class="me-2" />{{ id ? 'Editar' : 'Nuevo' }} servidor público
  </h1>

  <form novalidate @submit="onSubmit">
    <fieldset class="border rounded p-3 mb-3">
      <legend class="h6">Datos personales (Ley 1712/2014 Art. 8)</legend>

      <div class="row g-2">
        <div class="col-md-6">
          <label for="nombres" class="form-label">Nombres<span class="required-indicator">*</span></label>
          <input id="nombres" class="form-control" :class="{ 'is-invalid': errors.nombres }"
            v-model="nombres" v-bind="nombresAttrs" />
          <div v-if="errors.nombres" class="invalid-feedback">{{ errors.nombres }}</div>
        </div>
        <div class="col-md-6">
          <label for="apellidos" class="form-label">Apellidos<span class="required-indicator">*</span></label>
          <input id="apellidos" class="form-control" :class="{ 'is-invalid': errors.apellidos }"
            v-model="apellidos" v-bind="apellidosAttrs" />
          <div v-if="errors.apellidos" class="invalid-feedback">{{ errors.apellidos }}</div>
        </div>
        <div class="col-md-3">
          <label for="dt" class="form-label">Tipo documento</label>
          <select id="dt" class="form-select" v-model="docTipo" v-bind="docTipoAttrs">
            <option value="CC">CC</option><option value="CE">CE</option>
            <option value="PA">PA</option><option value="TI">TI</option>
          </select>
        </div>
        <div class="col-md-5">
          <label for="doc" class="form-label">Número de documento</label>
          <input id="doc" class="form-control" :class="{ 'is-invalid': errors.documento_identidad }"
            v-model="doc" v-bind="docAttrs" />
          <div v-if="errors.documento_identidad" class="invalid-feedback">{{ errors.documento_identidad }}</div>
        </div>
        <div class="col-md-4">
          <label for="muni" class="form-label">Municipio nacimiento (ID)</label>
          <input id="muni" type="number" class="form-control" :class="{ 'is-invalid': errors.municipio_nacimiento_id }"
            v-model.number="muni" v-bind="muniAttrs" />
          <div v-if="errors.municipio_nacimiento_id" class="invalid-feedback">{{ errors.municipio_nacimiento_id }}</div>
        </div>
      </div>
    </fieldset>

    <fieldset class="border rounded p-3 mb-3">
      <legend class="h6">Cargo</legend>
      <div class="row g-2">
        <div class="col-md-4">
          <label for="dep" class="form-label">Dependencia (ID)</label>
          <input id="dep" type="number" class="form-control" v-model.number="dep" v-bind="depAttrs" />
        </div>
        <div class="col-md-4">
          <label for="cargo" class="form-label">Cargo</label>
          <input id="cargo" class="form-control" v-model="cargo" v-bind="cargoAttrs" />
        </div>
        <div class="col-md-4">
          <label for="natur" class="form-label">Naturaleza</label>
          <select id="natur" class="form-select" v-model="natur" v-bind="naturAttrs">
            <option value="CARRERA">Carrera</option>
            <option value="LIBRE_NOMBRAMIENTO">Libre nombramiento</option>
            <option value="PROVISIONAL">Provisional</option>
            <option value="CONTRATO_PRESTACION">Contrato prestación</option>
            <option value="ELECCION_POPULAR">Elección popular</option>
          </select>
        </div>
        <div class="col-md-6">
          <label for="ci" class="form-label">Correo institucional</label>
          <input id="ci" type="email" class="form-control" :class="{ 'is-invalid': errors.correo_institucional }"
            v-model="correoInst" v-bind="correoInstAttrs" />
          <div v-if="errors.correo_institucional" class="invalid-feedback">{{ errors.correo_institucional }}</div>
        </div>
        <div class="col-md-3">
          <label for="tel" class="form-label">Teléfono (+57 X XXXXXXX)</label>
          <input id="tel" class="form-control" :class="{ 'is-invalid': errors.telefono_oficina }"
            v-model="tel" v-bind="telAttrs" />
          <div v-if="errors.telefono_oficina" class="invalid-feedback">{{ errors.telefono_oficina }}</div>
        </div>
        <div class="col-md-3">
          <label for="sigep" class="form-label">URL SIGEP</label>
          <input id="sigep" type="url" class="form-control" v-model="sigep" v-bind="sigepAttrs" />
        </div>
      </div>
    </fieldset>

    <fieldset class="border rounded p-3 mb-3">
      <legend class="h6">Formación académica</legend>
      <div v-for="(f, i) in formaciones.fields.value" :key="f.key" class="border rounded p-2 mb-2">
        <div class="row g-2">
          <div class="col-md-3">
            <label class="form-label">Nivel</label>
            <select class="form-select" v-model="f.value.nivel">
              <option v-for="n in ['BACHILLER','TECNICO','TECNOLOGO','PROFESIONAL','ESPECIALIZACION','MAESTRIA','DOCTORADO']"
                :key="n" :value="n">{{ n }}</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Título</label>
            <input class="form-control" v-model="f.value.titulo" />
          </div>
          <div class="col-md-3">
            <label class="form-label">Institución</label>
            <input class="form-control" v-model="f.value.institucion" />
          </div>
          <div class="col-md-1">
            <label class="form-label">País ID</label>
            <input type="number" class="form-control" v-model.number="f.value.pais_id" />
          </div>
          <div class="col-md-1">
            <label class="form-label">Año</label>
            <input type="number" class="form-control" v-model.number="f.value.ano_grado" />
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger mt-2" @click="formaciones.remove(i)">
          <FaIcon icon="trash" /> Eliminar
        </button>
      </div>
      <button type="button" class="btn btn-sm btn-outline-primary"
        @click="formaciones.push({ nivel: 'PROFESIONAL', titulo: '', institucion: '', pais_id: 1, ano_grado: new Date().getFullYear() })">
        <FaIcon icon="plus" /> Agregar formación
      </button>
    </fieldset>

    <fieldset class="border rounded p-3 mb-3">
      <legend class="h6">Experiencia profesional</legend>
      <div v-for="(e, i) in experiencias.fields.value" :key="e.key" class="border rounded p-2 mb-2">
        <div class="row g-2">
          <div class="col-md-4">
            <label class="form-label">Empresa</label>
            <input class="form-control" v-model="e.value.empresa" />
          </div>
          <div class="col-md-3">
            <label class="form-label">Cargo</label>
            <input class="form-control" v-model="e.value.cargo" />
          </div>
          <div class="col-md-2">
            <label class="form-label">Sector</label>
            <select class="form-select" v-model="e.value.sector">
              <option v-for="s in ['PUBLICO','PRIVADO','MIXTO','ONG','ACADEMICO']" :key="s">{{ s }}</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Inicio</label>
            <input type="date" class="form-control" v-model="e.value.fecha_inicio" />
          </div>
          <div class="col-md-1">
            <label class="form-label">Fin</label>
            <input type="date" class="form-control" v-model="e.value.fecha_fin" />
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger mt-2" @click="experiencias.remove(i)">
          <FaIcon icon="trash" /> Eliminar
        </button>
      </div>
      <button type="button" class="btn btn-sm btn-outline-primary"
        @click="experiencias.push({ empresa: '', cargo: '', sector: 'PUBLICO', fecha_inicio: '', fecha_fin: null, funciones: null })">
        <FaIcon icon="plus" /> Agregar experiencia
      </button>
    </fieldset>

    <div class="form-check mb-3">
      <input id="publicado" type="checkbox" class="form-check-input" v-model="pub" v-bind="pubAttrs" />
      <label for="publicado" class="form-check-label">Publicar en el directorio público</label>
    </div>

    <button class="btn btn-primary" :disabled="isSubmitting">
      <FaIcon v-if="isSubmitting" icon="spinner" spin class="me-2" />
      <FaIcon v-else icon="save" class="me-2" /> Guardar
    </button>
  </form>
</template>
