export type TipoPqrsd = 'PETICION' | 'QUEJA' | 'RECLAMO' | 'SUGERENCIA' | 'DENUNCIA' | 'INFORMACION'
export type EstadoPqrsd = 'RECIBIDA' | 'EN_TRAMITE' | 'RESPONDIDA' | 'CERRADA' | 'RECHAZADA'

export interface Pqrsd {
  id: number
  radicado: string
  tipo: TipoPqrsd
  asunto: string
  descripcion: string
  anonima: boolean
  solicitante_nombre?: string
  estado: EstadoPqrsd
  fecha_limite_respuesta: string | null
  respondida_en: string | null
  respuesta?: string
  dependencia_asignada: { id: number; nombre: string } | null
  created_at: string
}

export interface CrearPqrsdPayload {
  tipo: TipoPqrsd
  asunto: string
  descripcion: string
  anonima: boolean
  solicitante_nombre?: string
  solicitante_documento?: string
  solicitante_correo?: string
  solicitante_telefono?: string
}
