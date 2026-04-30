export interface Usuario {
  id: number
  name: string
  email: string
  estado: 'ACTIVO' | 'INACTIVO' | 'BLOQUEADO'
  email_verified_at: string | null
  roles: string[]
  permissions: string[]
  created_at: string
}

export interface UsuarioForm {
  name: string
  email: string
  password: string
  estado: 'ACTIVO' | 'INACTIVO' | 'BLOQUEADO'
  roles: string[]
}

export interface UsuarioUpdateForm {
  name?: string
  email?: string
  password?: string
  estado?: 'ACTIVO' | 'INACTIVO' | 'BLOQUEADO'
  roles?: string[]
}

export interface Rol {
  id: number
  name: string
  guard_name: string
  permissions: string[]
  created_at: string
}

export interface Permiso {
  id: number
  name: string
  guard_name: string
}
