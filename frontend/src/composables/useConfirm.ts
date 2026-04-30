import { reactive } from 'vue'

export interface ConfirmOptions {
  title?: string
  message?: string
  confirmText?: string
  cancelText?: string
  confirmVariant?: 'primary' | 'danger' | 'warning' | 'success'
}

interface ConfirmState extends Required<ConfirmOptions> {
  show: boolean
  resolver: ((value: boolean) => void) | null
}

/**
 * Estado global compartido por el host `<ConfirmHost />` montado en `App.vue`.
 *
 * `useConfirm()` retorna una función que abre el modal y resuelve la promesa
 * con `true` (confirmar) o `false` (cancelar/cerrar).
 */
export const confirmState = reactive<ConfirmState>({
  show: false,
  title: 'Confirmar acción',
  message: '¿Está seguro que desea continuar?',
  confirmText: 'Confirmar',
  cancelText: 'Cancelar',
  confirmVariant: 'danger',
  resolver: null
})

export function useConfirm() {
  function confirm(options: ConfirmOptions = {}): Promise<boolean> {
    confirmState.title = options.title ?? 'Confirmar acción'
    confirmState.message = options.message ?? '¿Está seguro que desea continuar?'
    confirmState.confirmText = options.confirmText ?? 'Confirmar'
    confirmState.cancelText = options.cancelText ?? 'Cancelar'
    confirmState.confirmVariant = options.confirmVariant ?? 'danger'

    return new Promise<boolean>((resolve) => {
      confirmState.resolver = resolve
      confirmState.show = true
    })
  }

  return { confirm }
}

export function resolveConfirm(result: boolean): void {
  confirmState.show = false
  if (confirmState.resolver) {
    confirmState.resolver(result)
    confirmState.resolver = null
  }
}
