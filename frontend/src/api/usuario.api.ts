import { http } from './http'
import type { ApiEnvelope } from '@/types/auth'
import type { Paginated } from '@/types/servidor'
import type { Permiso, Rol, Usuario, UsuarioForm, UsuarioUpdateForm } from '@/types/usuario'

export const UsuarioApi = {
  async listar(params: { q?: string; estado?: string; page?: number; per_page?: number } = {}) {
    const { data } = await http.get<Paginated<Usuario>>('/admin/usuarios', { params })
    return data
  },
  async obtener(id: number) {
    const { data } = await http.get<ApiEnvelope<Usuario>>(`/admin/usuarios/${id}`)
    return data.data
  },
  async crear(payload: UsuarioForm) {
    const { data } = await http.post<ApiEnvelope<Usuario>>('/admin/usuarios', payload)
    return data
  },
  async actualizar(id: number, payload: UsuarioUpdateForm) {
    const { data } = await http.put<ApiEnvelope<Usuario>>(`/admin/usuarios/${id}`, payload)
    return data
  },
  async eliminar(id: number) {
    const { data } = await http.delete<{ message: string }>(`/admin/usuarios/${id}`)
    return data
  }
}

export const RolApi = {
  async listar(params: { q?: string; page?: number; per_page?: number } = {}) {
    const { data } = await http.get<Paginated<Rol>>('/admin/roles', { params })
    return data
  },
  async crear(payload: { name: string; permissions: string[] }) {
    const { data } = await http.post<ApiEnvelope<Rol>>('/admin/roles', payload)
    return data
  },
  async actualizar(id: number, payload: { name?: string; permissions?: string[] }) {
    const { data } = await http.put<ApiEnvelope<Rol>>(`/admin/roles/${id}`, payload)
    return data
  },
  async eliminar(id: number) {
    const { data } = await http.delete<{ message: string }>(`/admin/roles/${id}`)
    return data
  }
}

export const PermisoApi = {
  async listar() {
    const { data } = await http.get<{ data: Permiso[]; message: string }>('/admin/permisos')
    return data.data
  }
}
