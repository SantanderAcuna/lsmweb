<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useMutation, useQuery, useQueryClient } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { DependenciaApi } from '@/api/dependencia.api'
import { useAuthStore } from '@/stores/auth.store'

const auth = useAuthStore()
const toast = useToast()
const qc = useQueryClient()

const q = ref('')
const page = ref(1)
const params = computed(() => ({ q: q.value || undefined, page: page.value }))

const { data, isLoading } = useQuery({
  queryKey: ['admin-dependencias', params],
  queryFn: () => DependenciaApi.listarAdmin(params.value),
  placeholderData: (prev) => prev
})

const eliminar = useMutation({
  mutationFn: (id: number) => DependenciaApi.eliminar(id),
  onSuccess: (r) => { toast.success(r.message); qc.invalidateQueries({ queryKey: ['admin-dependencias'] }) },
  onError: (e: { response?: { data?: { message?: string } } }) => {
    toast.error(e.response?.data?.message ?? 'No se pudo eliminar.')
  }
})
</script>

<template>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><FaIcon icon="building" class="me-2" />Dependencias</h1>
    <RouterLink v-if="auth.hasPermission('dependencias.create')"
      :to="{ name: 'admin.dependencias.nuevo' }" class="btn btn-primary">
      <FaIcon icon="plus" class="me-1" />Nueva
    </RouterLink>
  </div>

  <input v-model="q" type="search" class="form-control mb-3" placeholder="Buscar..." />

  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <div v-else-if="data" class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr><th>Código</th><th>Nombre</th><th>Extensión</th><th>Correo</th><th>Estado</th><th></th></tr>
      </thead>
      <tbody>
        <tr v-for="d in data.data" :key="d.id">
          <td><code>{{ d.codigo }}</code></td>
          <td>{{ d.nombre }}</td>
          <td>{{ d.extension ?? '—' }}</td>
          <td>{{ d.correo ?? '—' }}</td>
          <td><span :class="['badge', d.activo ? 'bg-success' : 'bg-secondary']">
            {{ d.activo ? 'Activa' : 'Inactiva' }}</span></td>
          <td class="text-end">
            <RouterLink v-if="auth.hasPermission('dependencias.update')"
              class="btn btn-sm btn-outline-primary me-1"
              :to="{ name: 'admin.dependencias.editar', params: { id: d.id } }">
              <FaIcon icon="pen" />
            </RouterLink>
            <button v-if="auth.hasPermission('dependencias.delete')"
              class="btn btn-sm btn-outline-danger"
              :disabled="eliminar.isPending.value"
              @click="confirm(`¿Eliminar ${d.nombre}?`) && eliminar.mutate(d.id)">
              <FaIcon icon="trash" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
