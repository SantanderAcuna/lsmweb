<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useQuery } from '@tanstack/vue-query'
import { TramiteApi } from '@/api/tramite.api'
import { useDebouncedRef } from '@/composables/useDebouncedRef'
import Paginator from '@/components/ui/Paginator.vue'

const termino = ref('')
const terminoDebounced = useDebouncedRef('', 400)
const page = ref(1)

function syncTermino(): void { terminoDebounced.value = termino.value; page.value = 1 }

const params = computed(() => ({ q: terminoDebounced.value || undefined, page: page.value }))

const { data, isLoading, isError } = useQuery({
  queryKey: ['tramites-publicos', params],
  queryFn: () => TramiteApi.listarPublico(params.value),
  placeholderData: (prev) => prev
})
</script>

<template>
  <h1 class="h3 mb-3"><FaIcon icon="briefcase" class="me-2" />Trámites y servicios</h1>
  <p class="text-muted">Catálogo de trámites de la Alcaldía Distrital de Santa Marta.</p>

  <div class="mb-3">
    <label for="t-pub-search" class="visually-hidden">Buscar trámite</label>
    <input id="t-pub-search" v-model="termino" type="search" class="form-control" placeholder="Buscar trámite..." @input="syncTermino" />
  </div>

  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <div v-else-if="isError" class="alert alert-danger">No fue posible cargar los trámites.</div>
  <template v-else-if="data">
    <div class="row g-3">
      <div v-for="t in data.data" :key="t.id" class="col-md-6 col-lg-4">
        <article class="card h-100">
          <div class="card-body">
            <h2 class="h5">{{ t.nombre }}</h2>
            <p class="small text-muted mb-2">
              <FaIcon icon="building" class="me-1" />{{ t.dependencia?.nombre ?? '—' }}
            </p>
            <p>{{ t.descripcion.substring(0, 140) }}…</p>
            <RouterLink :to="{ name: 'public.tramites.detalle', params: { slug: t.slug } }"
              class="btn btn-outline-primary btn-sm">
              <FaIcon icon="eye" class="me-1" />Ver detalle
            </RouterLink>
          </div>
        </article>
      </div>
      <div v-if="data.data.length === 0" class="col-12 text-center text-muted py-4">
        No se encontraron trámites.
      </div>
    </div>
    <Paginator :meta="data.meta" @page-change="(p) => page = p" />
  </template>
</template>
