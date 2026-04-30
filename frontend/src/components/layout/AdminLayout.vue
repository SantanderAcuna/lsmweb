<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const auth = useAuthStore()
</script>

<template>
  <div class="row">
    <aside class="col-lg-3 col-md-4 mb-3">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <FaIcon icon="user-shield" class="me-2" /> Panel Administrativo
        </div>
        <nav aria-label="Menú admin">
          <div class="list-group list-group-flush">
            <RouterLink :to="{ name: 'admin.perfil' }"
              class="list-group-item list-group-item-action">
              <FaIcon icon="user" class="me-2" /> Mi perfil
            </RouterLink>

            <RouterLink v-if="auth.hasPermission('servidores.view')"
              :to="{ name: 'admin.servidores.lista' }"
              class="list-group-item list-group-item-action">
              <FaIcon icon="users" class="me-2" /> Servidores
            </RouterLink>

            <RouterLink v-if="auth.hasPermission('dependencias.view')"
              :to="{ name: 'admin.dependencias.lista' }"
              class="list-group-item list-group-item-action">
              <FaIcon icon="building" class="me-2" /> Dependencias
            </RouterLink>

            <RouterLink v-if="auth.hasPermission('tramites.view')"
              :to="{ name: 'admin.tramites.lista' }"
              class="list-group-item list-group-item-action">
              <FaIcon icon="briefcase" class="me-2" /> Trámites
            </RouterLink>

            <RouterLink v-if="auth.hasPermission('noticias.view')"
              :to="{ name: 'admin.noticias.lista' }"
              class="list-group-item list-group-item-action">
              <FaIcon icon="globe" class="me-2" /> Noticias
            </RouterLink>

            <RouterLink v-if="auth.hasPermission('pqrsd.view')"
              :to="{ name: 'admin.pqrsd.lista' }"
              class="list-group-item list-group-item-action">
              <FaIcon icon="envelope" class="me-2" /> PQRSD
            </RouterLink>

            <RouterLink v-if="auth.hasPermission('sedes.view')"
              :to="{ name: 'admin.sedes.lista' }"
              class="list-group-item list-group-item-action">
              <FaIcon icon="map-marker-alt" class="me-2" /> Sedes
            </RouterLink>

            <RouterLink v-if="auth.hasPermission('usuarios.view')"
              :to="{ name: 'admin.usuarios.lista' }"
              class="list-group-item list-group-item-action">
              <FaIcon icon="users" class="me-2" /> Usuarios
            </RouterLink>

            <RouterLink v-if="auth.hasPermission('roles.view')"
              :to="{ name: 'admin.roles.lista' }"
              class="list-group-item list-group-item-action">
              <FaIcon icon="user-shield" class="me-2" /> Roles y permisos
            </RouterLink>
          </div>
        </nav>
      </div>
    </aside>
    <section class="col-lg-9 col-md-8">
      <RouterView />
    </section>
  </div>
</template>
