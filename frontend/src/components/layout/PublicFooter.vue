<template>
  <footer class="bg-light border-top mt-5 py-4" role="contentinfo">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h2 class="h6">Alcaldía Distrital de Santa Marta</h2>
          <address class="mb-0">
            Cra 8A entre calle 12 y 12B, Magdalena<br />
            <FaIcon icon="phone" class="me-1" />
            <a href="tel:+5754200100">+57 5 4200100</a><br />
            <FaIcon icon="envelope" class="me-1" />
            <a href="mailto:contacto@santamarta.gov.co">contacto@santamarta.gov.co</a>
          </address>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
          <a href="https://www.facebook.com" aria-label="Facebook" class="me-2"><FaIcon :icon="['fab','facebook']" /></a>
          <a href="https://twitter.com" aria-label="Twitter" class="me-2"><FaIcon :icon="['fab','twitter']" /></a>
          <a href="https://instagram.com" aria-label="Instagram"><FaIcon :icon="['fab','instagram']" /></a>
        </div>
      </div>
    </div>
  </footer>
</template>
