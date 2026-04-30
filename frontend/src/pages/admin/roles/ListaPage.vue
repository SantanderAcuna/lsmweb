<script setup lang="ts">
import { ref } from 'vue'
import { useMutation, useQuery, useQueryClient } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { PermisoApi, RolApi } from '@/api/usuario.api'
import { useAuthStore } from '@/stores/auth.store'
import { useConfirm } from '@/composables/useConfirm'

const auth = useAuthStore()
const toast = useToast()
const qc = useQueryClient()
const { confirm } = useConfirm()

const { data: roles, isLoading: loadingRoles } = useQuery({
  queryKey: ['roles'],
  queryFn: () => RolApi.listar({ per_page: 100 })
})

const { data: permisos } = useQuery({
  queryKey: ['permisos'],
  queryFn: () => PermisoApi.listar()
})

const editandoId = ref<number | null>(null)
const nombreNuevo = ref('')
const permisosSeleccionados = ref<string[]>([])

function comenzarEdicion(id: number, nombre: string, perms: string[]): void {
  editandoId.value = id
  nombreNuevo.value = nombre
  permisosSeleccionados.value = [...perms]
}

const guardar = useMutation({
  mutationFn: () => editandoId.value === null
    ? RolApi.crear({ name: nombreNuevo.value, permissions: permisosSeleccionados.value })
    : RolApi.actualizar(editandoId.value, { name: nombreNuevo.value, permissions: permisosSeleccionados.value }),
  onSuccess: (r) => {
    toast.success(r.message ?? 'Guardado.')
    qc.invalidateQueries({ queryKey: ['roles'] })
    editandoId.value = null
    nombreNuevo.value = ''
    permisosSeleccionados.value = []
  }
})

const eliminar = useMutation({
  mutationFn: (id: number) => RolApi.eliminar(id),
  onSuccess: (r) => { toast.success(r.message); qc.invalidateQueries({ queryKey: ['roles'] }) },
  onError: (e: { response?: { data?: { message?: string } } }) => {
    toast.error(e.response?.data?.message ?? 'No se pudo eliminar.')
  }
})

function nuevo(): void {
  editandoId.value = null
  nombreNuevo.value = ''
  permisosSeleccionados.value = []
}

async function pedirEliminar(id: number, nombre: string): Promise<void> {
  const ok = await confirm({
    title: 'Eliminar rol',
    message: `¿Está seguro que desea eliminar el rol "${nombre}"? Los usuarios pierden este rol.`,
    confirmText: 'Eliminar',
    confirmVariant: 'danger'
  })
  if (ok) eliminar.mutate(id)
}
</script>

<template>
  <h1 class="h4 mb-3"><FaIcon icon="user-shield" class="me-2" />Roles y permisos</h1>

  <div class="row">
    <div class="col-md-5">
      <h2 class="h6">Roles</h2>
      <div v-if="loadingRoles" class="text-center my-3">
        <FaIcon icon="spinner" spin class="text-primary" />
      </div>
      <ul v-else class="list-group">
        <li v-for="r in (roles?.data ?? [])" :key="r.id" class="list-group-item d-flex justify-content-between">
          <span>
            <strong>{{ r.name }}</strong>
            <br /><small class="text-muted">{{ r.permissions.length }} permisos</small>
          </span>
          <span>
            <button v-if="auth.hasPermission('roles.update')" class="btn btn-sm btn-outline-primary me-1"
              @click="comenzarEdicion(r.id, r.name, r.permissions)">
              <FaIcon icon="pen" />
            </button>
            <button v-if="auth.hasPermission('roles.delete')"
              type="button"
              class="btn btn-sm btn-outline-danger"
              :disabled="eliminar.isPending.value"
              :aria-label="`Eliminar ${r.name}`"
              @click="pedirEliminar(r.id, r.name)">
              <FaIcon icon="trash" />
            </button>
          </span>
        </li>
      </ul>
      <button v-if="auth.hasPermission('roles.create')" class="btn btn-primary btn-sm mt-2" @click="nuevo">
        <FaIcon icon="plus" class="me-1" />Nuevo rol
      </button>
    </div>

    <div class="col-md-7" v-if="auth.hasPermission('roles.update') || auth.hasPermission('roles.create')">
      <h2 class="h6">{{ editandoId ? 'Editar rol' : 'Nuevo rol' }}</h2>
      <div class="card">
        <div class="card-body">
          <label for="rname" class="form-label">Nombre del rol</label>
          <input id="rname" class="form-control mb-3" v-model="nombreNuevo" />

          <fieldset>
            <legend class="form-label">Permisos</legend>
            <div class="row g-1" style="max-height: 400px; overflow-y: auto;">
              <div v-for="p in (permisos ?? [])" :key="p.id" class="col-md-6">
                <div class="form-check">
                  <input :id="`p-${p.id}`" type="checkbox" class="form-check-input"
                    :value="p.name" v-model="permisosSeleccionados" />
                  <label :for="`p-${p.id}`" class="form-check-label small">
                    <code>{{ p.name }}</code>
                  </label>
                </div>
              </div>
            </div>
          </fieldset>
          <button class="btn btn-primary mt-3"
            :disabled="!nombreNuevo || guardar.isPending.value"
            @click="guardar.mutate()">
            <FaIcon icon="save" class="me-1" />Guardar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
