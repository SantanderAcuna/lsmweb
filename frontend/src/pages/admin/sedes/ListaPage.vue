<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useMutation, useQuery, useQueryClient } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { SedeApi } from '@/api/sede.api'
import { useAuthStore } from '@/stores/auth.store'

const auth = useAuthStore()
const toast = useToast()
const qc = useQueryClient()

const q = ref('')
const page = ref(1)
const params = computed(() => ({ q: q.value || undefined, page: page.value }))

const { data, isLoading } = useQuery({
  queryKey: ['admin-sedes', params],
  queryFn: () => SedeApi.listarAdmin(params.value),
  placeholderData: (prev) => prev
})

const eliminar = useMutation({
  mutationFn: (id: number) => SedeApi.eliminar(id),
  onSuccess: (r) => { toast.success(r.message); qc.invalidateQueries({ queryKey: ['admin-sedes'] }) }
})
</script>

<template>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><FaIcon icon="map-marker-alt" class="me-2" />Sedes</h1>
    <RouterLink v-if="auth.hasPermission('sedes.create')"
      :to="{ name: 'admin.sedes.nuevo' }" class="btn btn-primary">
      <FaIcon icon="plus" class="me-1" />Nueva
    </RouterLink>
  </div>

  <input v-model="q" type="search" class="form-control mb-3" placeholder="Buscar..." />

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
              :to="{ name: 'admin.sedes.editar', params: { id: s.id } }">
              <FaIcon icon="pen" />
            </RouterLink>
            <button v-if="auth.hasPermission('sedes.delete')"
              class="btn btn-sm btn-outline-danger"
              :disabled="eliminar.isPending.value"
              @click="confirm(`¿Eliminar ${s.nombre}?`) && eliminar.mutate(s.id)">
              <FaIcon icon="trash" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
