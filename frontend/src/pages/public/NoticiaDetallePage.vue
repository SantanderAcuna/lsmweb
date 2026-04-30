<script setup lang="ts">
import { useQuery } from '@tanstack/vue-query'
import { NoticiaApi } from '@/api/noticia.api'

const props = defineProps<{ slug: string }>()

const { data, isLoading } = useQuery({
  queryKey: ['noticia', props.slug],
  queryFn: () => NoticiaApi.obtenerPublica(props.slug)
})
</script>

<template>
  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <article v-else-if="data">
    <header class="mb-3">
      <span class="badge bg-secondary mb-2">{{ data.categoria }}</span>
      <h1 class="h2">{{ data.titulo }}</h1>
      <p class="text-muted">
        Por {{ data.autor?.name ?? 'Comunicaciones' }}
        <span v-if="data.publicado_en"> · {{ new Date(data.publicado_en).toLocaleDateString('es-CO') }}</span>
      </p>
    </header>

    <img v-if="data.imagen_destacada_url"
      :src="data.imagen_destacada_url"
      :alt="data.imagen_destacada_alt ?? ''"
      class="img-fluid rounded mb-4" />

    <p class="lead">{{ data.resumen }}</p>

    <div class="content" v-html="data.contenido" />

    <div v-if="data.etiquetas?.length" class="mt-4">
      <span v-for="t in data.etiquetas" :key="t" class="badge bg-light text-dark me-1">#{{ t }}</span>
    </div>
  </article>
</template>
