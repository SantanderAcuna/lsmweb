export interface AuthUser {
  id: number
  name: string
  email: string
  estado: 'ACTIVO' | 'INACTIVO' | 'BLOQUEADO'
  email_verified_at: string | null
  roles: string[]
  permissions: string[]
  created_at: string
}

export interface AuthSession {
  user: AuthUser
  token: string
  token_type: 'Bearer'
  expires_at: string
}

export interface ApiEnvelope<T> {
  message?: string
  data: T
}
