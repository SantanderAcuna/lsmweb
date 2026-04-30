<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <section class="bg-primary text-white p-5 rounded-3 mb-4">
    <h1 class="display-5 fw-bold">Alcaldía Distrital de Santa Marta</h1>
    <p class="lead">
      Portal oficial. Cumple con la Ley 1712/2014, el Decreto 767/2022 y la Resolución 1519/2020.
    </p>
    <RouterLink :to="{ name: 'public.servidores.lista' }" class="btn btn-light btn-lg">
      <FaIcon icon="users" class="me-2" /> Ver directorio de servidores
    </RouterLink>
  </section>

  <div class="row g-4">
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-body">
          <h2 class="h5"><FaIcon icon="user-shield" class="text-primary me-2" />Transparencia</h2>
          <p>Información publicada de manera proactiva conforme al Art. 8 de la Ley 1712/2014.</p>
          <RouterLink :to="{ name: 'public.servidores.lista' }">Ir al directorio</RouterLink>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-body">
          <h2 class="h5"><FaIcon icon="download" class="text-primary me-2" />Datos abiertos</h2>
          <p>Descargue el directorio en CSV o JSON (Decreto 767/2022).</p>
          <RouterLink :to="{ name: 'public.servidores.lista' }">Explorar</RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>
