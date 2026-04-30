<script setup lang="ts">
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth.store'

const auth = useAuthStore()

onMounted(() => {
  if (auth.isAuthenticated) {
    void auth.refreshMe()
  }
})
</script>

<template>
  <h1 class="h4 mb-3"><FaIcon icon="user" class="me-2" />Mi perfil</h1>

  <div v-if="auth.user" class="card">
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-3">Nombre</dt><dd class="col-sm-9">{{ auth.user.name }}</dd>
        <dt class="col-sm-3">Correo</dt><dd class="col-sm-9">{{ auth.user.email }}</dd>
        <dt class="col-sm-3">Estado</dt><dd class="col-sm-9">
          <span class="badge bg-success">{{ auth.user.estado }}</span>
        </dd>
        <dt class="col-sm-3">Roles</dt>
        <dd class="col-sm-9">
          <span v-for="r in auth.roles" :key="r" class="badge bg-info text-dark me-1">{{ r }}</span>
        </dd>
        <dt class="col-sm-3">Permisos</dt>
        <dd class="col-sm-9">
          <details>
            <summary>{{ auth.permissions.length }} permisos asignados</summary>
            <ul class="mt-2">
              <li v-for="p in auth.permissions" :key="p"><code>{{ p }}</code></li>
            </ul>
          </details>
        </dd>
      </dl>
    </div>
  </div>
</template>
