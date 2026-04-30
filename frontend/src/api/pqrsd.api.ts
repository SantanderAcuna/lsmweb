import { http } from './http'
import type { ApiEnvelope } from '@/types/auth'
import type { CrearPqrsdPayload, Pqrsd } from '@/types/pqrsd'

export const PqrsdApi = {
  async crear(payload: CrearPqrsdPayload) {
    const { data } = await http.post<{ message: string; data: { radicado: string; fecha_limite_respuesta: string | null } }>(
      '/pqrsd', payload
    )
    return data
  },
  async consultar(radicado: string) {
    const { data } = await http.get<ApiEnvelope<Pqrsd>>(`/pqrsd/consultar/${radicado}`)
    return data.data
  }
}
