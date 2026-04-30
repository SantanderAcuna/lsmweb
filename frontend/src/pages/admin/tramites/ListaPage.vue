<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useMutation, useQuery, useQueryClient } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { TramiteApi } from '@/api/tramite.api'
import { useAuthStore } from '@/stores/auth.store'

const auth = useAuthStore()
const toast = useToast()
const qc = useQueryClient()

const q = ref('')
const page = ref(1)
const params = computed(() => ({ q: q.value || undefined, page: page.value }))

const { data, isLoading } = useQuery({
  queryKey: ['admin-tramites', params],
  queryFn: () => TramiteApi.listarAdmin(params.value),
  placeholderData: (prev) => prev
})

const eliminar = useMutation({
  mutationFn: (id: number) => TramiteApi.eliminar(id),
  onSuccess: (r) => { toast.success(r.message); qc.invalidateQueries({ queryKey: ['admin-tramites'] }) }
})
</script>

<template>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><FaIcon icon="briefcase" class="me-2" />Trámites</h1>
    <RouterLink v-if="auth.hasPermission('tramites.create')"
      :to="{ name: 'admin.tramites.nuevo' }" class="btn btn-primary">
      <FaIcon icon="plus" class="me-1" />Nuevo
    </RouterLink>
  </div>

  <input v-model="q" type="search" class="form-control mb-3" placeholder="Buscar..." />

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
              :to="{ name: 'admin.tramites.editar', params: { id: t.id } }">
              <FaIcon icon="pen" />
            </RouterLink>
            <button v-if="auth.hasPermission('tramites.delete')"
              class="btn btn-sm btn-outline-danger"
              :disabled="eliminar.isPending.value"
              @click="confirm(`¿Eliminar ${t.nombre}?`) && eliminar.mutate(t.id)">
              <FaIcon icon="trash" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
