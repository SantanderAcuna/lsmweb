<script setup lang="ts">
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useQuery } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { ServidorApi, type ListarParams } from '@/api/servidor.api'
import { useDebouncedRef } from '@/composables/useDebouncedRef'
import Paginator from '@/components/ui/Paginator.vue'

const toast = useToast()
const termino = ref('')
const terminoDebounced = useDebouncedRef('', 400)
const page = ref(1)

function syncTermino(): void {
  terminoDebounced.value = termino.value
  page.value = 1
}

const params = computed<ListarParams>(() => ({
  q: terminoDebounced.value || undefined,
  page: page.value
}))

const { data, isLoading, isError } = useQuery({
  queryKey: ['servidores-publicos', params],
  queryFn: () => ServidorApi.listarPublico(params.value),
  placeholderData: (prev) => prev
})

async function descargar(formato: 'csv' | 'json'): Promise<void> {
  try {
    const blob = await ServidorApi.exportar(formato)
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `servidores-publicos.${formato}`
    document.body.appendChild(a); a.click(); a.remove()
    URL.revokeObjectURL(url)
    toast.success(`Descarga ${formato.toUpperCase()} iniciada.`)
  } catch {
    toast.error('No se pudo descargar el archivo.')
  }
}
</script>

<template>
  <h1 class="h3 mb-3"><FaIcon icon="users" class="me-2" />Directorio de Servidores Públicos</h1>
  <p class="text-muted">Ley 1712/2014 Art. 8 — Decreto 1081/2015 Art. 2.2.1.8.8.</p>

  <div class="row g-2 mb-3 align-items-end">
    <div class="col-md-6">
      <label class="form-label" for="search">Buscar por nombre, apellido o cargo</label>
      <div class="input-group">
        <span class="input-group-text"><FaIcon icon="search" /></span>
        <input id="search" type="search" class="form-control"
          v-model="termino" @input="syncTermino" maxlength="100" />
      </div>
    </div>
    <div class="col-md-6 text-md-end">
      <button class="btn btn-outline-primary me-2" @click="descargar('csv')">
        <FaIcon icon="download" class="me-1" /> CSV
      </button>
      <button class="btn btn-outline-primary" @click="descargar('json')">
        <FaIcon icon="download" class="me-1" /> JSON
      </button>
    </div>
  </div>

  <div v-if="isLoading" class="text-center my-4">
    <FaIcon icon="spinner" spin class="fa-2x text-primary" />
  </div>
  <div v-else-if="isError" class="alert alert-danger">
    <FaIcon icon="triangle-exclamation" class="me-2" />No fue posible cargar el directorio.
  </div>
  <template v-else-if="data">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <caption class="visually-hidden">Servidores públicos publicados</caption>
        <thead class="table-primary">
          <tr>
            <th scope="col">Servidor</th>
            <th scope="col">Cargo</th>
            <th scope="col">Dependencia</th>
            <th scope="col">Contacto</th>
            <th scope="col" class="text-end"><span class="visually-hidden">Acciones</span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="s in data.data" :key="s.id">
            <th scope="row">{{ s.nombre_completo }}</th>
            <td>
              {{ s.cargo }}<br />
              <small class="text-muted">{{ s.naturaleza_cargo_label }}</small>
            </td>
            <td>{{ s.dependencia?.nombre ?? '—' }}</td>
            <td>
              <a :href="`mailto:${s.correo_institucional}`">{{ s.correo_institucional }}</a>
              <template v-if="s.telefono_oficina">
                <br />
                <a :href="`tel:${s.telefono_oficina.replace(/\s/g,'')}`">
                  {{ s.telefono_oficina }}<span v-if="s.extension"> ext. {{ s.extension }}</span>
                </a>
              </template>
            </td>
            <td class="text-end">
              <RouterLink class="btn btn-sm btn-outline-primary"
                :to="{ name: 'public.servidores.detalle', params: { id: s.id } }">
                <FaIcon icon="eye" />
                <span class="visually-hidden">Ver detalle de {{ s.nombre_completo }}</span>
              </RouterLink>
            </td>
          </tr>
          <tr v-if="data.data.length === 0">
            <td colspan="5" class="text-center text-muted py-4">No se encontraron resultados.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <Paginator :meta="data.meta" @page-change="(p) => page = p" />
  </template>
</template>
