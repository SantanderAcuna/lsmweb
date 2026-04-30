import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const routes: RouteRecordRaw[] = [
  // Públicas
  { path: '/', name: 'home', component: () => import('@/pages/public/HomePage.vue'), meta: { title: 'Inicio' } },
  { path: '/servidores', name: 'public.servidores.lista', component: () => import('@/pages/public/ServidoresListaPage.vue'), meta: { title: 'Directorio' } },
  { path: '/servidores/:id(\\d+)', name: 'public.servidores.detalle', component: () => import('@/pages/public/ServidorDetallePage.vue'), props: true, meta: { title: 'Detalle servidor' } },

  // Auth
  { path: '/login', name: 'auth.login', component: () => import('@/pages/auth/LoginPage.vue'), meta: { title: 'Iniciar sesión', guest: true } },
  { path: '/registro', name: 'auth.register', component: () => import('@/pages/auth/RegisterPage.vue'), meta: { title: 'Registro', guest: true } },
  { path: '/recuperar', name: 'auth.forgot', component: () => import('@/pages/auth/ForgotPage.vue'), meta: { guest: true } },
  { path: '/restablecer', name: 'auth.reset', component: () => import('@/pages/auth/ResetPage.vue'), meta: { guest: true } },

  // Admin (requieren panel.access)
  {
    path: '/admin',
    component: () => import('@/components/layout/AdminLayout.vue'),
    meta: { requiresAuth: true, permission: 'panel.access' },
    children: [
      { path: '', redirect: { name: 'admin.servidores.lista' } },
      {
        path: 'servidores',
        name: 'admin.servidores.lista',
        component: () => import('@/pages/admin/servidores/ListaPage.vue'),
        meta: { permission: 'servidores.view', title: 'Servidores (admin)' }
      },
      {
        path: 'servidores/nuevo',
        name: 'admin.servidores.nuevo',
        component: () => import('@/pages/admin/servidores/FormPage.vue'),
        meta: { permission: 'servidores.create', title: 'Nuevo servidor' }
      },
      {
        path: 'servidores/:id(\\d+)/editar',
        name: 'admin.servidores.editar',
        component: () => import('@/pages/admin/servidores/FormPage.vue'),
        props: true,
        meta: { permission: 'servidores.update', title: 'Editar servidor' }
      }
    ]
  },

  { path: '/:pathMatch(.*)*', name: 'not-found', component: () => import('@/pages/public/NotFoundPage.vue') }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 })
})

router.beforeEach((to) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'auth.login', query: { redirect: to.fullPath } }
  }

  if (to.meta.guest && auth.isAuthenticated) {
    return { name: 'home' }
  }

  const required = to.meta.permission as string | undefined
  if (required && !auth.hasPermission(required)) {
    return { name: 'home' }
  }

  return true
})

router.afterEach((to) => {
  const t = (to.meta.title as string | undefined) ?? 'Alcaldía de Santa Marta'
  document.title = `${t} — Alcaldía Distrital de Santa Marta`
})

export default router
