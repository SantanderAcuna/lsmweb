<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { Modal } from 'bootstrap'

interface Props {
  /** Identificador único del modal (para aria-labelledby). */
  modalId?: string
  title?: string
  message?: string
  confirmText?: string
  cancelText?: string
  /** Variante visual del botón confirmar: 'danger', 'primary', 'warning', etc. */
  confirmVariant?: 'primary' | 'danger' | 'warning' | 'success'
  /** Cuando es true se muestra el modal. */
  show: boolean
}

const props = withDefaults(defineProps<Props>(), {
  modalId: 'confirm-modal',
  title: 'Confirmar acción',
  message: '¿Está seguro que desea continuar?',
  confirmText: 'Confirmar',
  cancelText: 'Cancelar',
  confirmVariant: 'danger'
})

const emit = defineEmits<{
  (e: 'confirm'): void
  (e: 'cancel'): void
}>()

const modalEl = ref<HTMLDivElement | null>(null)
const confirmBtn = ref<HTMLButtonElement | null>(null)
let modalInstance: Modal | null = null
/**
 * Si el usuario confirmó hicimos `emit('confirm')` en el click; cuando bootstrap
 * dispara `hidden.bs.modal` comprobamos esta bandera para no emitir cancel.
 */
let confirmed = false

function onConfirmClick(): void {
  confirmed = true
  modalInstance?.hide()
}

function onHidden(): void {
  if (!confirmed) emit('cancel')
  else emit('confirm')
  confirmed = false
}

onMounted(() => {
  if (!modalEl.value) return
  modalInstance = new Modal(modalEl.value, { backdrop: 'static', keyboard: true })
  modalEl.value.addEventListener('hidden.bs.modal', onHidden)
  modalEl.value.addEventListener('shown.bs.modal', () => {
    // Mover foco al botón confirmar al abrir.
    confirmBtn.value?.focus()
  })
  if (props.show) modalInstance.show()
})

onBeforeUnmount(() => {
  modalEl.value?.removeEventListener('hidden.bs.modal', onHidden)
  modalInstance?.dispose()
  modalInstance = null
})

watch(
  () => props.show,
  (val) => {
    if (!modalInstance) return
    if (val) {
      confirmed = false
      modalInstance.show()
    } else {
      modalInstance.hide()
    }
  }
)
</script>

<template>
  <div
    :id="modalId"
    ref="modalEl"
    class="modal fade"
    tabindex="-1"
    role="dialog"
    :aria-labelledby="`${modalId}-title`"
    :aria-describedby="`${modalId}-body`"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" role="document">
        <div class="modal-header">
          <h2 :id="`${modalId}-title`" class="modal-title h5">{{ title }}</h2>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            :aria-label="cancelText"
          ></button>
        </div>
        <div :id="`${modalId}-body`" class="modal-body">
          <slot>
            <p class="mb-0">{{ message }}</p>
          </slot>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            {{ cancelText }}
          </button>
          <button
            ref="confirmBtn"
            type="button"
            :class="['btn', `btn-${confirmVariant}`]"
            @click="onConfirmClick"
          >
            {{ confirmText }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
