<script setup lang="ts">
import { ref } from 'vue'
import { useToast } from 'vue-toastification'
import { PqrsdApi } from '@/api/pqrsd.api'
import type { Pqrsd } from '@/types/pqrsd'

const toast = useToast()
const radicado = ref('')
const resultado = ref<Pqrsd | null>(null)
const cargando = ref(false)

async function consultar(): Promise<void> {
  if (radicado.value.trim() === '') return
  cargando.value = true
  try {
    resultado.value = await PqrsdApi.consultar(radicado.value.trim())
  } catch {
    resultado.value = null
    toast.error('Radicado no encontrado.')
  } finally {
    cargando.value = false
  }
}
</script>

<template>
  <h1 class="h3"><FaIcon icon="search" class="me-2" />Consultar PQRSD</h1>

  <form class="row g-2 mb-4" @submit.prevent="consultar">
    <div class="col-md-9">
      <label for="rad" class="form-label">Número de radicado</label>
      <input id="rad" class="form-control" v-model="radicado" placeholder="PQRSD-YYYYMMDD-XXXXX" required />
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button class="btn btn-primary w-100" :disabled="cargando">
        <FaIcon v-if="cargando" icon="spinner" spin class="me-2" />Consultar
      </button>
    </div>
  </form>

  <article v-if="resultado" class="card">
    <div class="card-body">
      <h2 class="h5">{{ resultado.asunto }}</h2>
      <p class="text-muted small">Radicado: {{ resultado.radicado }} · Tipo: {{ resultado.tipo }}</p>
      <p>{{ resultado.descripcion }}</p>
      <dl class="row">
        <dt class="col-sm-4">Estado</dt>
        <dd class="col-sm-8">
          <span class="badge bg-info">{{ resultado.estado }}</span>
        </dd>
        <dt class="col-sm-4">Fecha límite respuesta</dt>
        <dd class="col-sm-8">{{ resultado.fecha_limite_respuesta ?? '—' }}</dd>
        <template v-if="resultado.respuesta">
          <dt class="col-sm-4">Respuesta</dt>
          <dd class="col-sm-8">{{ resultado.respuesta }}</dd>
        </template>
      </dl>
    </div>
  </article>
</template>
