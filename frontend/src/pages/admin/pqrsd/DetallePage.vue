<script setup lang="ts">
import { ref } from 'vue'
import { useMutation, useQuery, useQueryClient } from '@tanstack/vue-query'
import { useToast } from 'vue-toastification'
import { PqrsdApi } from '@/api/pqrsd.api'
import { useAuthStore } from '@/stores/auth.store'

const props = defineProps<{ id: string }>()
const auth = useAuthStore()
const toast = useToast()
const qc = useQueryClient()

const { data, isLoading } = useQuery({
  queryKey: ['admin-pqrsd', props.id],
  queryFn: () => PqrsdApi.obtenerAdmin(Number(props.id))
})

const respuesta = ref('')
const dependenciaId = ref<number | null>(null)
const usuarioId = ref<number | null>(null)

const responder = useMutation({
  mutationFn: () => PqrsdApi.responder(Number(props.id), respuesta.value),
  onSuccess: (r) => {
    toast.success(r.message ?? 'Respondida.')
    qc.invalidateQueries({ queryKey: ['admin-pqrsd', props.id] })
    respuesta.value = ''
  }
})

const asignar = useMutation({
  mutationFn: () => PqrsdApi.asignar(Number(props.id), {
    asignado_a: usuarioId.value!,
    dependencia_asignada_id: dependenciaId.value!
  }),
  onSuccess: (r) => {
    toast.success(r.message ?? 'Asignada.')
    qc.invalidateQueries({ queryKey: ['admin-pqrsd', props.id] })
  }
})
</script>

<template>
  <div v-if="isLoading" class="text-center my-4"><FaIcon icon="spinner" spin class="fa-2x text-primary" /></div>
  <article v-else-if="data">
    <h1 class="h4">{{ data.asunto }}</h1>
    <p class="text-muted small">
      <code>{{ data.radicado }}</code> · {{ data.tipo }} · estado: <strong>{{ data.estado }}</strong>
    </p>

    <p>{{ data.descripcion }}</p>

    <div v-if="data.solicitante_nombre" class="alert alert-light">
      <strong>Solicitante:</strong> {{ data.solicitante_nombre }}
    </div>

    <section v-if="auth.hasPermission('pqrsd.assign') && data.estado === 'RECIBIDA'" class="card mb-3">
      <div class="card-header"><FaIcon icon="user-shield" class="me-2" />Asignar</div>
      <div class="card-body row g-2">
        <div class="col-md-5">
          <label class="form-label" for="dep">Dependencia (ID)</label>
          <input id="dep" type="number" class="form-control" v-model.number="dependenciaId" />
        </div>
        <div class="col-md-5">
          <label class="form-label" for="uid">Usuario (ID)</label>
          <input id="uid" type="number" class="form-control" v-model.number="usuarioId" />
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button class="btn btn-primary w-100"
            :disabled="!dependenciaId || !usuarioId || asignar.isPending.value"
            @click="asignar.mutate()">Asignar</button>
        </div>
      </div>
    </section>

    <section v-if="auth.hasPermission('pqrsd.respond') && data.estado !== 'RESPONDIDA' && data.estado !== 'CERRADA'" class="card">
      <div class="card-header"><FaIcon icon="envelope" class="me-2" />Responder</div>
      <div class="card-body">
        <label for="resp" class="form-label">Respuesta oficial</label>
        <textarea id="resp" rows="6" class="form-control" v-model="respuesta"></textarea>
        <button class="btn btn-success mt-2"
          :disabled="respuesta.length < 10 || responder.isPending.value"
          @click="responder.mutate()">
          <FaIcon icon="check" class="me-1" />Enviar respuesta
        </button>
      </div>
    </section>

    <section v-if="data.respuesta" class="alert alert-success mt-3">
      <h2 class="h6"><FaIcon icon="check" class="me-2" />Respuesta registrada</h2>
      <p class="mb-0">{{ data.respuesta }}</p>
    </section>
  </article>
</template>
