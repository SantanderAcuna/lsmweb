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
        <div class="list-group list-group-flush">
          <RouterLink
            v-if="auth.hasPermission('servidores.view')"
            :to="{ name: 'admin.servidores.lista' }"
            class="list-group-item list-group-item-action">
            <FaIcon icon="users" class="me-2" /> Servidores
          </RouterLink>
          <span v-if="auth.hasPermission('tramites.view')" class="list-group-item text-muted small">
            <FaIcon icon="briefcase" class="me-2" /> Trámites <span class="badge bg-secondary">próximamente</span>
          </span>
          <span v-if="auth.hasPermission('noticias.view')" class="list-group-item text-muted small">
            <FaIcon icon="globe" class="me-2" /> Noticias <span class="badge bg-secondary">próximamente</span>
          </span>
          <span v-if="auth.hasPermission('pqrsd.view')" class="list-group-item text-muted small">
            <FaIcon icon="envelope" class="me-2" /> PQRSD <span class="badge bg-secondary">próximamente</span>
          </span>
        </div>
      </div>
    </aside>
    <section class="col-lg-9 col-md-8">
      <RouterView />
    </section>
  </div>
</template>
