<script setup lang="ts">
import { useQuery } from '@tanstack/vue-query'
import { ServidorApi } from '@/api/servidor.api'

const props = defineProps<{ id: string }>()

const { data, isLoading, isError } = useQuery({
  queryKey: ['servidor-publico', props.id],
  queryFn: () => ServidorApi.obtenerPublico(Number(props.id))
})

function formatearMoneda(v: number | null): string {
  if (v === null) return '—'
  return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP' }).format(v)
}
</script>

<template>
  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <div v-else-if="isError" class="alert alert-danger">No fue posible obtener el detalle.</div>

  <article v-else-if="data">
    <header class="mb-4">
      <h1 class="h3">{{ data.nombre_completo }}</h1>
      <p class="text-muted mb-0">
        <FaIcon icon="briefcase" class="me-1" />
        <strong>{{ data.cargo }}</strong> · {{ data.naturaleza_cargo_label }}
      </p>
    </header>

    <section class="mb-4" aria-labelledby="basicos">
      <h2 id="basicos" class="h5"><FaIcon icon="id-card" class="me-2" />Datos básicos</h2>
      <dl class="row">
        <dt class="col-sm-4">País de nacimiento</dt><dd class="col-sm-8">{{ data.pais_nacimiento ?? '—' }}</dd>
        <dt class="col-sm-4">Departamento</dt><dd class="col-sm-8">{{ data.depto_nacimiento ?? '—' }}</dd>
        <dt class="col-sm-4">Ciudad</dt><dd class="col-sm-8">{{ data.ciudad_nacimiento ?? '—' }}</dd>
        <dt class="col-sm-4">Dependencia</dt><dd class="col-sm-8">{{ data.dependencia?.nombre ?? '—' }}</dd>
        <dt class="col-sm-4">Correo institucional</dt>
        <dd class="col-sm-8"><a :href="`mailto:${data.correo_institucional}`">{{ data.correo_institucional }}</a></dd>
        <template v-if="data.telefono_oficina">
          <dt class="col-sm-4">Teléfono</dt>
          <dd class="col-sm-8">
            <a :href="`tel:${data.telefono_oficina.replace(/\s/g,'')}`">
              {{ data.telefono_oficina }}<span v-if="data.extension"> ext. {{ data.extension }}</span>
            </a>
          </dd>
        </template>
        <dt class="col-sm-4">Salario básico</dt><dd class="col-sm-8">{{ formatearMoneda(data.salario_basico) }}</dd>
        <template v-if="data.sigep_url">
          <dt class="col-sm-4">SIGEP</dt>
          <dd class="col-sm-8">
            <a :href="data.sigep_url" target="_blank" rel="noopener">
              <FaIcon icon="globe" class="me-1" />Hoja de vida en SIGEP
              <span class="visually-hidden">(se abre en nueva pestaña)</span>
            </a>
          </dd>
        </template>
      </dl>
    </section>

    <section class="mb-4" aria-labelledby="formacion">
      <h2 id="formacion" class="h5"><FaIcon icon="graduation-cap" class="me-2" />Formación académica</h2>
      <ul v-if="data.formaciones_academicas.length" class="list-group">
        <li v-for="(f, i) in data.formaciones_academicas" :key="i" class="list-group-item">
          <strong>{{ f.titulo }}</strong> — {{ f.institucion }} ({{ f.pais }}, {{ f.ano_grado }})
          <span class="badge bg-secondary ms-2">{{ f.nivel }}</span>
        </li>
      </ul>
      <p v-else class="text-muted">Sin información registrada.</p>
    </section>

    <section aria-labelledby="experiencia">
      <h2 id="experiencia" class="h5"><FaIcon icon="briefcase" class="me-2" />Experiencia profesional</h2>
      <ul v-if="data.experiencias_profesionales.length" class="list-group">
        <li v-for="(e, i) in data.experiencias_profesionales" :key="i" class="list-group-item">
          <strong>{{ e.cargo }}</strong> en {{ e.empresa }}
          <span class="badge bg-info ms-2">{{ e.sector }}</span>
          <div class="small text-muted">
            {{ e.fecha_inicio }} — {{ e.fecha_fin ?? 'actual' }}
          </div>
          <p v-if="e.funciones" class="mb-0 mt-1">{{ e.funciones }}</p>
        </li>
      </ul>
      <p v-else class="text-muted">Sin información registrada.</p>
    </section>
  </article>
</template>
