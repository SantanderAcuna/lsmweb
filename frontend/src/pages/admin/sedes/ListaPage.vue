<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useMutation, useQuery, useQueryClient } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { SedeApi } from '@/api/sede.api'
import { useAuthStore } from '@/stores/auth.store'
import { useConfirm } from '@/composables/useConfirm'
import Paginator from '@/components/ui/Paginator.vue'

const auth = useAuthStore()
const toast = useToast()
const qc = useQueryClient()
const { confirm } = useConfirm()

const q = ref('')
const activo = ref<'' | 'true' | 'false'>('')
const page = ref(1)

const params = computed(() => ({
  q: q.value || undefined,
  activo: activo.value === '' ? undefined : activo.value === 'true',
  page: page.value
}))

const { data, isLoading } = useQuery({
  queryKey: ['admin-sedes', params],
  queryFn: () => SedeApi.listarAdmin(params.value),
  placeholderData: (prev) => prev
})

const eliminar = useMutation({
  mutationFn: (id: number) => SedeApi.eliminar(id),
  onSuccess: (r) => { toast.success(r.message); qc.invalidateQueries({ queryKey: ['admin-sedes'] }) }
})

async function pedirEliminar(id: number, nombre: string): Promise<void> {
  const ok = await confirm({
    title: 'Eliminar sede',
    message: `¿Está seguro que desea eliminar la sede "${nombre}"?`,
    confirmText: 'Eliminar',
    confirmVariant: 'danger'
  })
  if (ok) eliminar.mutate(id)
}
</script>

<template>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><FaIcon icon="map-marker-alt" class="me-2" />Sedes</h1>
    <RouterLink v-if="auth.hasPermission('sedes.create')"
      :to="{ name: 'admin.sedes.nuevo' }" class="btn btn-primary">
      <FaIcon icon="plus" class="me-1" />Nueva
    </RouterLink>
  </div>

  <div class="row g-2 mb-3">
    <div class="col-md-9">
      <label for="s-search" class="visually-hidden">Buscar sede</label>
      <input id="s-search" v-model="q" type="search" class="form-control" placeholder="Buscar..." />
    </div>
    <div class="col-md-3">
      <label for="s-activo" class="visually-hidden">Filtrar por estado</label>
      <select id="s-activo" v-model="activo" class="form-select" aria-label="Filtrar por estado de la sede">
        <option value="">Todos los estados</option>
        <option value="true">Activas</option>
        <option value="false">Inactivas</option>
      </select>
    </div>
  </div>

  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <div v-else-if="data" class="table-responsive">
    <table class="table table-striped">
      <thead><tr><th>Nombre</th><th>Municipio</th><th>Dirección</th><th>Teléfono</th><th>Estado</th><th></th></tr></thead>
      <tbody>
        <tr v-for="s in data.data" :key="s.id">
          <td>{{ s.nombre }}</td>
          <td>{{ s.municipio ?? '—' }}</td>
          <td>{{ s.direccion }}</td>
          <td>{{ s.telefono ?? '—' }}</td>
          <td><span :class="['badge', s.activo ? 'bg-success' : 'bg-secondary']">
            {{ s.activo ? 'Activa' : 'Inactiva' }}</span></td>
          <td class="text-end">
            <RouterLink v-if="auth.hasPermission('sedes.update')"
              class="btn btn-sm btn-outline-primary me-1"
              :to="{ name: 'admin.sedes.editar', params: { id: s.id } }"
              :aria-label="`Editar ${s.nombre}`">
              <FaIcon icon="pen" />
            </RouterLink>
            <button v-if="auth.hasPermission('sedes.delete')"
              type="button"
              class="btn btn-sm btn-outline-danger"
              :disabled="eliminar.isPending.value"
              :aria-label="`Eliminar ${s.nombre}`"
              @click="pedirEliminar(s.id, s.nombre)">
              <FaIcon icon="trash" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <Paginator :meta="data.meta" @page-change="(p) => page = p" />
  </div>
</template>
