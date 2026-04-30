import { http } from './http'
import type { ApiEnvelope, AuthSession, AuthUser } from '@/types/auth'

export const AuthApi = {
  async register(payload: { name: string; email: string; password: string; password_confirmation: string }) {
    const { data } = await http.post<ApiEnvelope<AuthSession>>('/auth/register', payload)
    return data.data
  },

  async login(payload: { email: string; password: string }) {
    const { data } = await http.post<ApiEnvelope<AuthSession>>('/auth/login', payload)
    return data.data
  },

  async logout() {
    await http.post('/auth/logout')
  },

  async me() {
    const { data } = await http.get<ApiEnvelope<AuthUser>>('/auth/me')
    return data.data
  },

  async forgotPassword(payload: { email: string }) {
    const { data } = await http.post<{ message: string }>('/auth/forgot-password', payload)
    return data
  },

  async resetPassword(payload: { token: string; email: string; password: string; password_confirmation: string }) {
    const { data } = await http.post<{ message: string }>('/auth/reset-password', payload)
    return data
  }
}
