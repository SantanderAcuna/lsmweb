<script setup lang="ts">
import { useQuery } from '@tanstack/vue-query'
import { TramiteApi } from '@/api/tramite.api'

const props = defineProps<{ slug: string }>()

const { data, isLoading, isError } = useQuery({
  queryKey: ['tramite', props.slug],
  queryFn: () => TramiteApi.obtenerPublico(props.slug)
})

function formatearMoneda(v: number | null): string {
  if (v === null || v === 0) return 'Gratuito'
  return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP' }).format(v)
}
</script>

<template>
  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <div v-else-if="isError" class="alert alert-danger">No se pudo cargar el trámite.</div>
  <article v-else-if="data">
    <h1 class="h3">{{ data.nombre }}</h1>
    <p class="text-muted">Código {{ data.codigo }} · {{ data.dependencia?.nombre }}</p>
    <p>{{ data.descripcion }}</p>

    <div class="row g-3">
      <div class="col-md-6">
        <h2 class="h5"><FaIcon icon="check" class="me-2 text-success" />Requisitos</h2>
        <ul>
          <li v-for="(r, i) in data.requisitos" :key="i">{{ r }}</li>
        </ul>
      </div>
      <div class="col-md-6" v-if="data.documentos_requeridos?.length">
        <h2 class="h5"><FaIcon icon="id-card" class="me-2 text-primary" />Documentos</h2>
        <ul>
          <li v-for="(d, i) in data.documentos_requeridos" :key="i">{{ d }}</li>
        </ul>
      </div>
    </div>

    <dl class="row mt-3">
      <dt class="col-sm-3">Tiempo estimado</dt><dd class="col-sm-9">{{ data.tiempo_estimado ?? '—' }}</dd>
      <dt class="col-sm-3">Costo</dt><dd class="col-sm-9">
        {{ formatearMoneda(data.costo) }}
        <small v-if="data.costo_variable" class="text-muted">(variable)</small>
      </dd>
      <dt class="col-sm-3">Canal de atención</dt><dd class="col-sm-9">{{ data.canal_atencion ?? '—' }}</dd>
      <template v-if="data.url_govco">
        <dt class="col-sm-3">GOV.CO</dt>
        <dd class="col-sm-9">
          <a :href="data.url_govco" target="_blank" rel="noopener">
            <FaIcon icon="globe" class="me-1" />Realizar trámite en GOV.CO
            <span class="visually-hidden">(se abre en nueva pestaña)</span>
          </a>
        </dd>
      </template>
    </dl>
  </article>
</template>
