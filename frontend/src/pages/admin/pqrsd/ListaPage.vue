<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useQuery } from '@tanstack/vue-query'
import { PqrsdApi } from '@/api/pqrsd.api'
import type { EstadoPqrsd } from '@/types/pqrsd'

const q = ref('')
const estado = ref<EstadoPqrsd | ''>('')
const tipo = ref('')
const page = ref(1)

const params = computed(() => ({
  q: q.value || undefined,
  tipo: tipo.value || undefined,
  estado: estado.value || undefined,
  page: page.value
}))

const { data, isLoading } = useQuery({
  queryKey: ['admin-pqrsd', params],
  queryFn: () => PqrsdApi.listarAdmin(params.value),
  placeholderData: (prev) => prev
})

const estadoBadge: Record<EstadoPqrsd, string> = {
  RECIBIDA: 'bg-info', EN_TRAMITE: 'bg-warning text-dark',
  RESPONDIDA: 'bg-success', CERRADA: 'bg-secondary', RECHAZADA: 'bg-danger'
}
</script>

<template>
  <h1 class="h4 mb-3"><FaIcon icon="envelope" class="me-2" />Bandeja de PQRSD</h1>

  <div class="row g-2 mb-3">
    <div class="col-md-5"><input v-model="q" class="form-control" placeholder="Buscar..." /></div>
    <div class="col-md-3">
      <select v-model="tipo" class="form-select">
        <option value="">Todos los tipos</option>
        <option v-for="t in ['PETICION','QUEJA','RECLAMO','SUGERENCIA','DENUNCIA','INFORMACION']" :key="t">{{ t }}</option>
      </select>
    </div>
    <div class="col-md-4">
      <select v-model="estado" class="form-select">
        <option value="">Todos los estados</option>
        <option v-for="e in ['RECIBIDA','EN_TRAMITE','RESPONDIDA','CERRADA','RECHAZADA']" :key="e">{{ e }}</option>
      </select>
    </div>
  </div>

  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <div v-else-if="data" class="table-responsive">
    <table class="table table-striped">
      <thead><tr><th>Radicado</th><th>Tipo</th><th>Asunto</th><th>Estado</th><th>Fecha límite</th><th></th></tr></thead>
      <tbody>
        <tr v-for="p in data.data" :key="p.id">
          <td><code>{{ p.radicado }}</code></td>
          <td>{{ p.tipo }}</td>
          <td>{{ p.asunto }}</td>
          <td><span :class="['badge', estadoBadge[p.estado]]">{{ p.estado }}</span></td>
          <td>{{ p.fecha_limite_respuesta ?? '—' }}</td>
          <td class="text-end">
            <RouterLink class="btn btn-sm btn-outline-primary"
              :to="{ name: 'admin.pqrsd.detalle', params: { id: p.id } }">
              <FaIcon icon="eye" />
            </RouterLink>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
