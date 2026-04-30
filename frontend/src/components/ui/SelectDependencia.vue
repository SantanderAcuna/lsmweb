<script setup lang="ts">
import { computed } from 'vue'
import { useQuery } from '@tanstack/vue-query'
import { CatalogosApi } from '@/api/catalogos.api'

interface Props {
  modelValue: number | null
  id?: string
  label?: string
  required?: boolean
  disabled?: boolean
  /** Etiqueta para la opción "ninguna" (cuando el campo es opcional). */
  emptyLabel?: string
  /** Si `true` no se incluye opción vacía. */
  hideEmpty?: boolean
  invalid?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  id: 'select-dependencia',
  label: 'Dependencia',
  required: false,
  disabled: false,
  emptyLabel: '— Sin asignar —',
  hideEmpty: false,
  invalid: false
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: number | null): void
}>()

const { data, isLoading, isError } = useQuery({
  queryKey: ['catalogos-dependencias-activas'],
  queryFn: () => CatalogosApi.dependenciasActivas(),
  staleTime: 5 * 60 * 1000
})

const value = computed({
  get: (): string => (props.modelValue == null ? '' : String(props.modelValue)),
  set: (v: string) => {
    if (v === '') emit('update:modelValue', null)
    else emit('update:modelValue', Number(v))
  }
})
</script>

<template>
  <label :for="id" class="form-label">
    {{ label }}<span v-if="required" class="required-indicator" aria-hidden="true">*</span>
  </label>
  <select
    :id="id"
    v-model="value"
    class="form-select"
    :class="{ 'is-invalid': invalid }"
    :disabled="disabled || isLoading"
    :aria-invalid="invalid"
    :aria-busy="isLoading"
    :aria-required="required"
  >
    <option v-if="!hideEmpty" value="">{{ emptyLabel }}</option>
    <option v-if="isLoading" value="" disabled>Cargando dependencias…</option>
    <option v-else-if="isError" value="" disabled>Error cargando dependencias</option>
    <option v-for="d in (data ?? [])" :key="d.id" :value="String(d.id)">
      {{ d.codigo }} — {{ d.nombre }}
    </option>
  </select>
</template>
