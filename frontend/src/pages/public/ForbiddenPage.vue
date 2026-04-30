<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="text-center py-5">
    <FaIcon icon="triangle-exclamation" class="fa-3x text-warning mb-3" />
    <h1 class="display-4">403</h1>
    <p class="lead">No tiene permisos para acceder a este recurso.</p>
    <RouterLink :to="{ name: 'home' }" class="btn btn-primary">
      <FaIcon icon="house" class="me-2" />Volver al inicio
    </RouterLink>
  </div>
</template>
