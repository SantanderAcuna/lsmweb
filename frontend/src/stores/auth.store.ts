import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { AuthApi } from '@/api/auth.api'
import type { AuthSession, AuthUser } from '@/types/auth'

const TOKEN_KEY = 'access_token'
const USER_KEY = 'auth_user'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem(TOKEN_KEY))
  const user = ref<AuthUser | null>(JSON.parse(localStorage.getItem(USER_KEY) ?? 'null'))

  const isAuthenticated = computed(() => token.value !== null)
  const roles = computed(() => user.value?.roles ?? [])
  const permissions = computed(() => user.value?.permissions ?? [])

  function persist(session: AuthSession): void {
    token.value = session.token
    user.value = session.user
    localStorage.setItem(TOKEN_KEY, session.token)
    localStorage.setItem(USER_KEY, JSON.stringify(session.user))
  }

  function clear(): void {
    token.value = null
    user.value = null
    localStorage.removeItem(TOKEN_KEY)
    localStorage.removeItem(USER_KEY)
  }

  async function login(email: string, password: string): Promise<void> {
    const session = await AuthApi.login({ email, password })
    persist(session)
  }

  async function register(payload: { name: string; email: string; password: string; password_confirmation: string }) {
    const session = await AuthApi.register(payload)
    persist(session)
  }

  async function refreshMe(): Promise<void> {
    if (!token.value) return
    const me = await AuthApi.me()
    user.value = me
    localStorage.setItem(USER_KEY, JSON.stringify(me))
  }

  async function logout(): Promise<void> {
    try {
      if (token.value) await AuthApi.logout()
    } finally {
      clear()
    }
  }

  function hasPermission(perm: string): boolean {
    return permissions.value.includes(perm)
  }

  function hasRole(role: string): boolean {
    return roles.value.includes(role)
  }

  return {
    token, user, isAuthenticated, roles, permissions,
    login, register, refreshMe, logout, hasPermission, hasRole
  }
})
