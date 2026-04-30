import type { AxiosError } from 'axios'
import type { FormContext } from 'vee-validate'

/**
 * Errores en formato Laravel 422.
 * { errors: { campo: ['línea1', 'línea2'] } }
 */
interface LaravelValidationErrors {
  message?: string
  errors?: Record<string, string[]>
}

/**
 * Type guard: detecta si un objeto es un AxiosError.
 */
function isAxiosError(err: unknown): err is AxiosError<LaravelValidationErrors> {
  return (
    typeof err === 'object' &&
    err !== null &&
    'isAxiosError' in err &&
    (err as { isAxiosError?: boolean }).isAxiosError === true
  )
}

/**
 * Composable que fusiona errores de validación 422 (Laravel) con el form de vee-validate.
 *
 * @returns Una función `apply(form, error)` que:
 *   - Si el error es 422 con `response.data.errors`, llama `form.setErrors()` con la primera línea de cada campo y retorna `true`.
 *   - En caso contrario retorna `false` para que el caller maneje el toast genérico.
 */
export function useApiErrors() {
  function apply<TValues extends Record<string, unknown>>(
    form: Pick<FormContext<TValues>, 'setErrors'>,
    error: unknown
  ): boolean {
    if (!isAxiosError(error)) return false
    const status = error.response?.status
    const data = error.response?.data
    if (status !== 422 || !data?.errors) return false

    const fieldErrors: Record<string, string> = {}
    for (const [field, lines] of Object.entries(data.errors)) {
      if (Array.isArray(lines) && lines.length > 0) {
        fieldErrors[field] = lines[0]
      }
    }
    // setErrors acepta un Record<string, string> con paths como llaves.
    form.setErrors(fieldErrors as Parameters<typeof form.setErrors>[0])
    return true
  }

  return { apply }
}
