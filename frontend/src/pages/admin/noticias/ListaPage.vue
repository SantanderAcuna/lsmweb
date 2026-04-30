<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useMutation, useQuery, useQueryClient } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { NoticiaApi } from '@/api/noticia.api'
import { useAuthStore } from '@/stores/auth.store'
import { useConfirm } from '@/composables/useConfirm'
import Paginator from '@/components/ui/Paginator.vue'

const auth = useAuthStore()
const toast = useToast()
const qc = useQueryClient()
const { confirm } = useConfirm()

const q = ref('')
const categoria = ref('')
const publicado = ref<'' | 'true' | 'false'>('')
const page = ref(1)

const params = computed(() => ({
  q: q.value || undefined,
  categoria: categoria.value || undefined,
  publicado: publicado.value === '' ? undefined : publicado.value === 'true',
  page: page.value
}))

const { data, isLoading } = useQuery({
  queryKey: ['admin-noticias', params],
  queryFn: () => NoticiaApi.listarAdmin(params.value),
  placeholderData: (prev) => prev
})

const eliminar = useMutation({
  mutationFn: (id: number) => NoticiaApi.eliminar(id),
  onSuccess: (r) => { toast.success(r.message); qc.invalidateQueries({ queryKey: ['admin-noticias'] }) }
})

async function pedirEliminar(id: number, titulo: string): Promise<void> {
  const ok = await confirm({
    title: 'Eliminar noticia',
    message: `¿Está seguro que desea eliminar "${titulo}"?`,
    confirmText: 'Eliminar',
    confirmVariant: 'danger'
  })
  if (ok) eliminar.mutate(id)
}
</script>

<template>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><FaIcon icon="globe" class="me-2" />Noticias</h1>
    <RouterLink v-if="auth.hasPermission('noticias.create')"
      :to="{ name: 'admin.noticias.nuevo' }" class="btn btn-primary">
      <FaIcon icon="plus" class="me-1" />Nueva
    </RouterLink>
  </div>

  <div class="row g-2 mb-3">
    <div class="col-md-6">
      <label for="n-search" class="visually-hidden">Buscar noticia</label>
      <input id="n-search" v-model="q" type="search" class="form-control" placeholder="Buscar..." />
    </div>
    <div class="col-md-3">
      <label for="n-categoria" class="visually-hidden">Filtrar por categoría</label>
      <select id="n-categoria" v-model="categoria" class="form-select" aria-label="Filtrar por categoría">
        <option value="">Todas las categorías</option>
        <option value="Institucional">Institucional</option>
        <option value="Comunidad">Comunidad</option>
        <option value="Eventos">Eventos</option>
        <option value="Convocatorias">Convocatorias</option>
        <option value="Seguridad">Seguridad</option>
      </select>
    </div>
    <div class="col-md-3">
      <label for="n-publicado" class="visually-hidden">Filtrar por estado</label>
      <select id="n-publicado" v-model="publicado" class="form-select" aria-label="Filtrar por estado de publicación">
        <option value="">Todos los estados</option>
        <option value="true">Publicadas</option>
        <option value="false">Borradores</option>
      </select>
    </div>
  </div>

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
              :to="{ name: 'admin.noticias.editar', params: { id: n.id } }"
              :aria-label="`Editar ${n.titulo}`">
              <FaIcon icon="pen" />
            </RouterLink>
            <button v-if="auth.hasPermission('noticias.delete')"
              type="button"
              class="btn btn-sm btn-outline-danger"
              :disabled="eliminar.isPending.value"
              :aria-label="`Eliminar ${n.titulo}`"
              @click="pedirEliminar(n.id, n.titulo)">
              <FaIcon icon="trash" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <Paginator :meta="data.meta" @page-change="(p) => page = p" />
  </div>
</template>
