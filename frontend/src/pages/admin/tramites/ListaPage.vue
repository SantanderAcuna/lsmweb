<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useMutation, useQuery, useQueryClient } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { TramiteApi } from '@/api/tramite.api'
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
  queryKey: ['admin-tramites', params],
  queryFn: () => TramiteApi.listarAdmin(params.value),
  placeholderData: (prev) => prev
})

const eliminar = useMutation({
  mutationFn: (id: number) => TramiteApi.eliminar(id),
  onSuccess: (r) => { toast.success(r.message); qc.invalidateQueries({ queryKey: ['admin-tramites'] }) }
})

async function pedirEliminar(id: number, nombre: string): Promise<void> {
  const ok = await confirm({
    title: 'Eliminar trámite',
    message: `¿Está seguro que desea eliminar el trámite "${nombre}"?`,
    confirmText: 'Eliminar',
    confirmVariant: 'danger'
  })
  if (ok) eliminar.mutate(id)
}
</script>

<template>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><FaIcon icon="briefcase" class="me-2" />Trámites</h1>
    <RouterLink v-if="auth.hasPermission('tramites.create')"
      :to="{ name: 'admin.tramites.nuevo' }" class="btn btn-primary">
      <FaIcon icon="plus" class="me-1" />Nuevo
    </RouterLink>
  </div>

  <div class="row g-2 mb-3">
    <div class="col-md-6">
      <label for="t-search" class="visually-hidden">Buscar trámite</label>
      <input id="t-search" v-model="q" type="search" class="form-control" placeholder="Buscar..." />
    </div>
    <div class="col-md-3">
      <label for="t-categoria" class="visually-hidden">Filtrar por categoría</label>
      <select id="t-categoria" v-model="categoria" class="form-select" aria-label="Filtrar por categoría">
        <option value="">Todas las categorías</option>
        <option value="Salud">Salud</option>
        <option value="Educacion">Educación</option>
        <option value="Hacienda">Hacienda</option>
        <option value="Movilidad">Movilidad</option>
        <option value="Vivienda">Vivienda</option>
        <option value="Cultura">Cultura</option>
        <option value="Otros">Otros</option>
      </select>
    </div>
    <div class="col-md-3">
      <label for="t-publicado" class="visually-hidden">Filtrar por estado</label>
      <select id="t-publicado" v-model="publicado" class="form-select" aria-label="Filtrar por estado de publicación">
        <option value="">Todos los estados</option>
        <option value="true">Publicados</option>
        <option value="false">Borradores</option>
      </select>
    </div>
  </div>

  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <div v-else-if="data" class="table-responsive">
    <table class="table table-striped">
      <thead><tr><th>Código</th><th>Nombre</th><th>Categoría</th><th>Dependencia</th><th>Estado</th><th></th></tr></thead>
      <tbody>
        <tr v-for="t in data.data" :key="t.id">
          <td><code>{{ t.codigo }}</code></td>
          <td>{{ t.nombre }}</td>
          <td>{{ t.categoria ?? '—' }}</td>
          <td>{{ t.dependencia?.nombre ?? '—' }}</td>
          <td><span :class="['badge', t.publicado ? 'bg-success' : 'bg-secondary']">
            {{ t.publicado ? 'Publicado' : 'Borrador' }}</span></td>
          <td class="text-end">
            <RouterLink v-if="auth.hasPermission('tramites.update')"
              class="btn btn-sm btn-outline-primary me-1"
              :to="{ name: 'admin.tramites.editar', params: { id: t.id } }"
              :aria-label="`Editar ${t.nombre}`">
              <FaIcon icon="pen" />
            </RouterLink>
            <button v-if="auth.hasPermission('tramites.delete')"
              type="button"
              class="btn btn-sm btn-outline-danger"
              :disabled="eliminar.isPending.value"
              :aria-label="`Eliminar ${t.nombre}`"
              @click="pedirEliminar(t.id, t.nombre)">
              <FaIcon icon="trash" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <Paginator :meta="data.meta" @page-change="(p) => page = p" />
  </div>
</template>
