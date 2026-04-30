<template>
  <div class="text-center py-5">
    <h1 class="display-1 fw-bold">404</h1>
    <p class="lead">Página no encontrada.</p>
    <RouterLink :to="{ name: 'home' }" class="btn btn-primary">
      <FaIcon icon="house" class="me-2" />Volver al inicio
    </RouterLink>
  </div>
</template>
