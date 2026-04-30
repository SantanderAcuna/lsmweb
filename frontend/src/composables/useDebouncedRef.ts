import { customRef } from 'vue'

export function useDebouncedRef<T>(value: T, delay = 400) {
  let timeout: ReturnType<typeof setTimeout> | null = null
  return customRef<T>((track, trigger) => ({
    get() {
      track()
      return value
    },
    set(newValue: T) {
      if (timeout) clearTimeout(timeout)
      timeout = setTimeout(() => {
        value = newValue
        trigger()
      }, delay)
    }
  }))
}
