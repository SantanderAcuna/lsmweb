<script setup lang="ts">
import ConfirmModal from './ConfirmModal.vue'
import { confirmState, resolveConfirm } from '@/composables/useConfirm'
</script>

<template>
  <ConfirmModal
    modal-id="global-confirm"
    :show="confirmState.show"
    :title="confirmState.title"
    :message="confirmState.message"
    :confirm-text="confirmState.confirmText"
    :cancel-text="confirmState.cancelText"
    :confirm-variant="confirmState.confirmVariant"
    @confirm="resolveConfirm(true)"
    @cancel="resolveConfirm(false)"
  />
</template>
