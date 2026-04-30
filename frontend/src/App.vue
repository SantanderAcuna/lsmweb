<script setup lang="ts">
import { onMounted } from 'vue'
import { RouterView } from 'vue-router'
import PublicNavbar from '@/components/layout/PublicNavbar.vue'
import PublicFooter from '@/components/layout/PublicFooter.vue'
import { useAuthStore } from '@/stores/auth.store'

const auth = useAuthStore()
onMounted(async () => {
  if (auth.isAuthenticated) {
    try { await auth.refreshMe() } catch { /* token inválido manejado por interceptor */ }
  }
})
</script>

<template>
  <a class="gov-topbar" href="https://www.gov.co" aria-label="Portal del Estado Colombiano GOV.CO">
    GOV.CO
  </a>
  <PublicNavbar />
  <main id="main" tabindex="-1" class="container py-4">
    <RouterView />
  </main>
  <PublicFooter />
</template>
