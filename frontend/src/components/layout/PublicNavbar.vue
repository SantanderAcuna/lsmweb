<script setup lang="ts">
import { useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'
import { useToast } from 'vue-toastification'

const auth = useAuthStore()
const router = useRouter()
const toast = useToast()

async function handleLogout(): Promise<void> {
  await auth.logout()
  toast.success('Sesión cerrada.')
  void router.push({ name: 'home' })
}
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary" aria-label="Navegación principal">
    <div class="container">
      <RouterLink class="navbar-brand fw-bold" :to="{ name: 'home' }">
        <FaIcon icon="house" class="me-2" />
        Alcaldía Distrital de Santa Marta
      </RouterLink>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"
        aria-controls="navMain" aria-expanded="false" aria-label="Alternar navegación">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div id="navMain" class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><RouterLink class="nav-link" :to="{ name: 'public.servidores.lista' }">
            <FaIcon icon="users" class="me-1" /> Directorio
          </RouterLink></li>
        </ul>
        <ul class="navbar-nav">
          <template v-if="!auth.isAuthenticated">
            <li class="nav-item"><RouterLink class="nav-link" :to="{ name: 'auth.login' }">
              <FaIcon icon="sign-in-alt" class="me-1" /> Iniciar sesión
            </RouterLink></li>
            <li class="nav-item"><RouterLink class="nav-link" :to="{ name: 'auth.register' }">
              <FaIcon icon="user-plus" class="me-1" /> Registrarse
            </RouterLink></li>
          </template>
          <template v-else>
            <li v-if="auth.hasPermission('panel.access')" class="nav-item">
              <RouterLink class="nav-link" :to="{ name: 'admin.servidores.lista' }">
                <FaIcon icon="user-shield" class="me-1" /> Panel
              </RouterLink>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <FaIcon icon="user" class="me-1" /> {{ auth.user?.name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><button class="dropdown-item" @click="handleLogout">
                  <FaIcon icon="sign-out-alt" class="me-2" /> Salir
                </button></li>
              </ul>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>
