<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useQuery } from '@tanstack/vue-query'
import { NoticiaApi } from '@/api/noticia.api'

const page = ref(1)
const params = computed(() => ({ page: page.value, per_page: 9 }))

const { data, isLoading } = useQuery({
  queryKey: ['noticias-publicas', params],
  queryFn: () => NoticiaApi.listarPublico(params.value),
  placeholderData: (prev) => prev
})

function fechaCorta(iso: string | null): string {
  if (!iso) return ''
  return new Intl.DateTimeFormat('es-CO', { dateStyle: 'long' }).format(new Date(iso))
}
</script>

<template>
  <h1 class="h3 mb-3"><FaIcon icon="globe" class="me-2" />Noticias</h1>

  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <div v-else-if="data" class="row g-3">
    <article v-for="n in data.data" :key="n.id" class="col-md-6 col-lg-4">
      <div class="card h-100">
        <img v-if="n.imagen_destacada_url"
          :src="n.imagen_destacada_url"
          :alt="n.imagen_destacada_alt ?? ''"
          class="card-img-top"
          loading="lazy" />
        <div class="card-body">
          <span class="badge bg-secondary mb-2">{{ n.categoria }}</span>
          <h2 class="h5">
            <RouterLink :to="{ name: 'public.noticias.detalle', params: { slug: n.slug } }">
              {{ n.titulo }}
            </RouterLink>
          </h2>
          <p class="small text-muted">{{ fechaCorta(n.publicado_en) }}</p>
          <p>{{ n.resumen }}</p>
        </div>
      </div>
    </article>

    <div v-if="data.data.length === 0" class="col-12 text-center text-muted py-4">
      No hay noticias publicadas.
    </div>
  </div>
</template>
