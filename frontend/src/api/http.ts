import axios, { AxiosError, type AxiosInstance } from 'axios'
import { useToast } from 'vue-toastification'

const baseURL = (import.meta.env.VITE_API_BASE_URL as string | undefined) ?? '/api/v1'

export const http: AxiosInstance = axios.create({
  baseURL,
  headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
})

http.interceptors.request.use((config) => {
  const token = localStorage.getItem('access_token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

http.interceptors.response.use(
  (r) => r,
  (error: AxiosError<{ message?: string }>) => {
    const toast = useToast()
    const status = error.response?.status
    const message = error.response?.data?.message

    if (status === 401) {
      localStorage.removeItem('access_token')
      toast.warning(message ?? 'Sesión expirada. Inicie sesión nuevamente.')
    } else if (status === 403) {
      toast.error(message ?? 'No tiene permisos para esta acción.')
    } else if (status && status >= 500) {
      toast.error('Error del servidor. Intente más tarde.')
    }
    return Promise.reject(error)
  }
)
