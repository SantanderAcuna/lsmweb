<script setup lang="ts">
import { computed } from 'vue'

export interface PaginatorMeta {
  current_page: number
  last_page: number
  total: number
  per_page: number
}

interface Props {
  meta: PaginatorMeta
  /** Cantidad máxima de números visibles a cada lado del actual. */
  windowSize?: number
  ariaLabel?: string
}

const props = withDefaults(defineProps<Props>(), {
  windowSize: 2,
  ariaLabel: 'Paginación'
})

const emit = defineEmits<{
  (e: 'page-change', page: number): void
}>()

const lastPage = computed(() => Math.max(1, props.meta.last_page))
const currentPage = computed(() => Math.min(Math.max(1, props.meta.current_page), lastPage.value))
const canPrev = computed(() => currentPage.value > 1)
const canNext = computed(() => currentPage.value < lastPage.value)

/**
 * Construye la lista de números visibles más los `…` de truncado.
 * Ejemplo (window=2, current=5, last=10): [1, '…', 3, 4, 5, 6, 7, '…', 10]
 */
const pages = computed<(number | 'ellipsis')[]>(() => {
  const total = lastPage.value
  const cur = currentPage.value
  const w = Math.max(1, props.windowSize)
  if (total <= 1) return [1]

  const result: (number | 'ellipsis')[] = []
  const left = Math.max(2, cur - w)
  const right = Math.min(total - 1, cur + w)

  result.push(1)
  if (left > 2) result.push('ellipsis')
  for (let i = left; i <= right; i++) result.push(i)
  if (right < total - 1) result.push('ellipsis')
  if (total > 1) result.push(total)
  return result
})

function go(page: number): void {
  if (page < 1 || page > lastPage.value || page === currentPage.value) return
  emit('page-change', page)
}
</script>

<template>
  <nav v-if="lastPage > 1" :aria-label="ariaLabel" class="d-flex flex-column align-items-center mt-3">
    <ul class="pagination mb-1">
      <li class="page-item" :class="{ disabled: !canPrev }">
        <button
          type="button"
          class="page-link"
          :disabled="!canPrev"
          aria-label="Página anterior"
          @click="go(currentPage - 1)"
        >
          <FaIcon icon="chevron-left" class="me-1" aria-hidden="true" />
          <span>Anterior</span>
        </button>
      </li>
      <template v-for="(p, idx) in pages" :key="`${p}-${idx}`">
        <li v-if="p === 'ellipsis'" class="page-item disabled" aria-hidden="true">
          <span class="page-link">…</span>
        </li>
        <li
          v-else
          class="page-item"
          :class="{ active: p === currentPage }"
          :aria-current="p === currentPage ? 'page' : undefined"
        >
          <button
            type="button"
            class="page-link"
            :aria-label="`Ir a la página ${p}`"
            @click="go(p)"
          >
            {{ p }}
          </button>
        </li>
      </template>
      <li class="page-item" :class="{ disabled: !canNext }">
        <button
          type="button"
          class="page-link"
          :disabled="!canNext"
          aria-label="Página siguiente"
          @click="go(currentPage + 1)"
        >
          <span>Siguiente</span>
          <FaIcon icon="chevron-right" class="ms-1" aria-hidden="true" />
        </button>
      </li>
    </ul>
    <p class="text-muted small mb-0" aria-live="polite">
      Página {{ currentPage }} de {{ lastPage }} · {{ meta.total }} registro<span v-if="meta.total !== 1">s</span>
    </p>
  </nav>
</template>
