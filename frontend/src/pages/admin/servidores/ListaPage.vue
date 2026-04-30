<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useMutation, useQuery, useQueryClient } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { ServidorApi } from '@/api/servidor.api'
import { useAuthStore } from '@/stores/auth.store'

const auth = useAuthStore()
const toast = useToast()
const qc = useQueryClient()

const q = ref('')
const page = ref(1)

const params = computed(() => ({ q: q.value || undefined, page: page.value }))

const { data, isLoading } = useQuery({
  queryKey: ['admin-servidores', params],
  queryFn: () => ServidorApi.listarAdmin(params.value),
  placeholderData: (prev) => prev
})

const eliminarMutation = useMutation({
  mutationFn: (id: number) => ServidorApi.eliminar(id),
  onSuccess: (resp) => {
    toast.success(resp.message)
    void qc.invalidateQueries({ queryKey: ['admin-servidores'] })
  }
})

function eliminar(id: number, nombre: string): void {
  if (confirm(`¿Eliminar al servidor ${nombre}?`)) {
    eliminarMutation.mutate(id)
  }
}
</script>

<template>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><FaIcon icon="users" class="me-2" />Servidores Públicos</h1>
    <RouterLink v-if="auth.hasPermission('servidores.create')"
      :to="{ name: 'admin.servidores.nuevo' }" class="btn btn-primary">
      <FaIcon icon="plus" class="me-1" />Nuevo
    </RouterLink>
  </div>

  <div class="mb-3">
    <input v-model="q" type="search" class="form-control" placeholder="Buscar..." />
  </div>

  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <div v-else-if="data" class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Servidor</th><th>Cargo</th><th>Dependencia</th><th>Estado</th><th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="s in data.data" :key="s.id">
          <td>{{ s.nombre_completo }}</td>
          <td>{{ s.cargo }}</td>
          <td>{{ s.dependencia?.nombre ?? '—' }}</td>
          <td>
            <span :class="['badge', s.publicado ? 'bg-success' : 'bg-secondary']">
              {{ s.publicado ? 'Publicado' : 'Borrador' }}
            </span>
          </td>
          <td class="text-end">
            <RouterLink v-if="auth.hasPermission('servidores.update')"
              class="btn btn-sm btn-outline-primary me-1"
              :to="{ name: 'admin.servidores.editar', params: { id: s.id } }">
              <FaIcon icon="pen" />
            </RouterLink>
            <button v-if="auth.hasPermission('servidores.delete')"
              class="btn btn-sm btn-outline-danger"
              :disabled="eliminarMutation.isPending.value"
              @click="eliminar(s.id, s.nombre_completo)">
              <FaIcon icon="trash" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
