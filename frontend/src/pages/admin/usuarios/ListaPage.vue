<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useMutation, useQuery, useQueryClient } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { UsuarioApi } from '@/api/usuario.api'
import { useAuthStore } from '@/stores/auth.store'
import { useConfirm } from '@/composables/useConfirm'
import Paginator from '@/components/ui/Paginator.vue'

const auth = useAuthStore()
const toast = useToast()
const qc = useQueryClient()
const { confirm } = useConfirm()

const q = ref('')
const estado = ref('')
const page = ref(1)
const params = computed(() => ({ q: q.value || undefined, estado: estado.value || undefined, page: page.value }))

const { data, isLoading } = useQuery({
  queryKey: ['admin-usuarios', params],
  queryFn: () => UsuarioApi.listar(params.value),
  placeholderData: (prev) => prev
})

const eliminar = useMutation({
  mutationFn: (id: number) => UsuarioApi.eliminar(id),
  onSuccess: (r) => { toast.success(r.message); qc.invalidateQueries({ queryKey: ['admin-usuarios'] }) },
  onError: (e: { response?: { data?: { message?: string } } }) => {
    toast.error(e.response?.data?.message ?? 'No se pudo eliminar.')
  }
})

async function pedirEliminar(id: number, name: string): Promise<void> {
  const ok = await confirm({
    title: 'Eliminar usuario',
    message: `¿Está seguro que desea eliminar a "${name}"?`,
    confirmText: 'Eliminar',
    confirmVariant: 'danger'
  })
  if (ok) eliminar.mutate(id)
}
</script>

<template>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><FaIcon icon="users" class="me-2" />Usuarios</h1>
    <RouterLink v-if="auth.hasPermission('usuarios.create')"
      :to="{ name: 'admin.usuarios.nuevo' }" class="btn btn-primary">
      <FaIcon icon="user-plus" class="me-1" />Nuevo
    </RouterLink>
  </div>

  <div class="row g-2 mb-3">
    <div class="col-md-8">
      <label for="u-search" class="visually-hidden">Buscar usuario</label>
      <input id="u-search" v-model="q" class="form-control" placeholder="Buscar..." />
    </div>
    <div class="col-md-4">
      <label for="u-estado" class="visually-hidden">Filtrar por estado</label>
      <select id="u-estado" v-model="estado" class="form-select" aria-label="Filtrar por estado">
        <option value="">Todos los estados</option>
        <option value="ACTIVO">Activo</option>
        <option value="INACTIVO">Inactivo</option>
        <option value="BLOQUEADO">Bloqueado</option>
      </select>
    </div>
  </div>

  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <div v-else-if="data" class="table-responsive">
    <table class="table table-striped">
      <thead><tr><th>Nombre</th><th>Correo</th><th>Roles</th><th>Estado</th><th></th></tr></thead>
      <tbody>
        <tr v-for="u in data.data" :key="u.id">
          <td>{{ u.name }}</td>
          <td>{{ u.email }}</td>
          <td>
            <span v-for="r in u.roles" :key="r" class="badge bg-info text-dark me-1">{{ r }}</span>
          </td>
          <td><span :class="['badge', u.estado === 'ACTIVO' ? 'bg-success' : 'bg-secondary']">
            {{ u.estado }}</span></td>
          <td class="text-end">
            <RouterLink v-if="auth.hasPermission('usuarios.update')"
              class="btn btn-sm btn-outline-primary me-1"
              :to="{ name: 'admin.usuarios.editar', params: { id: u.id } }"
              :aria-label="`Editar ${u.name}`">
              <FaIcon icon="pen" />
            </RouterLink>
            <button v-if="auth.hasPermission('usuarios.delete')"
              type="button"
              class="btn btn-sm btn-outline-danger"
              :disabled="eliminar.isPending.value"
              :aria-label="`Eliminar ${u.name}`"
              @click="pedirEliminar(u.id, u.name)">
              <FaIcon icon="trash" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <Paginator :meta="data.meta" @page-change="(p) => page = p" />
  </div>
</template>
