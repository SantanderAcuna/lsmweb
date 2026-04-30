<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useMutation, useQuery, useQueryClient } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { NoticiaApi } from '@/api/noticia.api'
import { useAuthStore } from '@/stores/auth.store'

const auth = useAuthStore()
const toast = useToast()
const qc = useQueryClient()

const q = ref('')
const page = ref(1)
const params = computed(() => ({ q: q.value || undefined, page: page.value }))

const { data, isLoading } = useQuery({
  queryKey: ['admin-noticias', params],
  queryFn: () => NoticiaApi.listarAdmin(params.value),
  placeholderData: (prev) => prev
})

const eliminar = useMutation({
  mutationFn: (id: number) => NoticiaApi.eliminar(id),
  onSuccess: (r) => { toast.success(r.message); qc.invalidateQueries({ queryKey: ['admin-noticias'] }) }
})
</script>

<template>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><FaIcon icon="globe" class="me-2" />Noticias</h1>
    <RouterLink v-if="auth.hasPermission('noticias.create')"
      :to="{ name: 'admin.noticias.nuevo' }" class="btn btn-primary">
      <FaIcon icon="plus" class="me-1" />Nueva
    </RouterLink>
  </div>

  <input v-model="q" type="search" class="form-control mb-3" placeholder="Buscar..." />

  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <div v-else-if="data" class="table-responsive">
    <table class="table table-striped">
      <thead><tr><th>Título</th><th>Categoría</th><th>Publicado</th><th>Fecha</th><th></th></tr></thead>
      <tbody>
        <tr v-for="n in data.data" :key="n.id">
          <td>{{ n.titulo }}</td>
          <td><span class="badge bg-light text-dark">{{ n.categoria }}</span></td>
          <td>
            <span :class="['badge', n.publicado ? 'bg-success' : 'bg-secondary']">
              {{ n.publicado ? 'Publicada' : 'Borrador' }}
            </span>
          </td>
          <td>{{ n.publicado_en ? new Date(n.publicado_en).toLocaleDateString('es-CO') : '—' }}</td>
          <td class="text-end">
            <RouterLink v-if="auth.hasPermission('noticias.update')"
              class="btn btn-sm btn-outline-primary me-1"
              :to="{ name: 'admin.noticias.editar', params: { id: n.id } }">
              <FaIcon icon="pen" />
            </RouterLink>
            <button v-if="auth.hasPermission('noticias.delete')"
              class="btn btn-sm btn-outline-danger"
              :disabled="eliminar.isPending.value"
              @click="confirm(`¿Eliminar ${n.titulo}?`) && eliminar.mutate(n.id)">
              <FaIcon icon="trash" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
