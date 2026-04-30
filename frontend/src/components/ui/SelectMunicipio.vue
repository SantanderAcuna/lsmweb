<script setup lang="ts">
import { computed } from 'vue'
import { useQuery } from '@tanstack/vue-query'
import { CatalogosApi } from '@/api/catalogos.api'

/**
 * Selector de municipio.
 *
 * El backend aún no expone `/municipios`. Mientras eso no exista, este componente
 * cae automáticamente al modo input numérico (fallback) para no bloquear los
 * formularios. Cuando `CatalogosApi.municipiosTodos()` retorne un array, el
 * componente cambia a `<select>` automáticamente.
 */
interface Props {
  modelValue: number | null
  id?: string
  label?: string
  required?: boolean
  disabled?: boolean
  invalid?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  id: 'select-municipio',
  label: 'Municipio',
  required: false,
  disabled: false,
  invalid: false
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: number | null): void
}>()

const { data, isLoading } = useQuery({
  queryKey: ['catalogos-municipios'],
  queryFn: () => CatalogosApi.municipiosTodos(),
  staleTime: 60 * 60 * 1000
})

const usaSelect = computed(() => Array.isArray(data.value) && data.value.length > 0)

const valueSelect = computed({
  get: (): string => (props.modelValue == null ? '' : String(props.modelValue)),
  set: (v: string) => {
    if (v === '') emit('update:modelValue', null)
    else emit('update:modelValue', Number(v))
  }
})

const valueNumber = computed({
  get: (): number | null => props.modelValue,
  set: (v: number | null) => emit('update:modelValue', v)
})
</script>

<template>
  <label :for="id" class="form-label">
    {{ label }}<span v-if="required" class="required-indicator" aria-hidden="true">*</span>
  </label>
  <select
    v-if="usaSelect"
    :id="id"
    v-model="valueSelect"
    class="form-select"
    :class="{ 'is-invalid': invalid }"
    :disabled="disabled || isLoading"
    :aria-invalid="invalid"
    :aria-busy="isLoading"
    :aria-required="required"
  >
    <option value="">— Seleccione un municipio —</option>
    <option v-for="m in (data ?? [])" :key="m.id" :value="String(m.id)">
      {{ m.nombre }}
    </option>
  </select>
  <template v-else>
    <input
      :id="id"
      v-model.number="valueNumber"
      type="number"
      min="1"
      class="form-control"
      :class="{ 'is-invalid': invalid }"
      :disabled="disabled"
      :aria-invalid="invalid"
      :aria-required="required"
      :aria-describedby="`${id}-help`"
    />
    <div :id="`${id}-help`" class="form-text">
      Ingrese el ID del municipio (DIVIPOLA). Catálogo aún no disponible.
    </div>
  </template>
</template>
