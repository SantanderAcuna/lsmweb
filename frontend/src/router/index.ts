import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const routes: RouteRecordRaw[] = [
  // Inicio
  { path: '/', name: 'home', component: () => import('@/pages/public/HomePage.vue'), meta: { title: 'Inicio' } },

  // Transparencia / Servidores
  { path: '/servidores', name: 'public.servidores.lista', component: () => import('@/pages/public/ServidoresListaPage.vue'), meta: { title: 'Directorio de servidores' } },
  { path: '/servidores/:id(\\d+)', name: 'public.servidores.detalle', component: () => import('@/pages/public/ServidorDetallePage.vue'), props: true, meta: { title: 'Detalle servidor' } },

  // Trámites
  { path: '/tramites', name: 'public.tramites.lista', component: () => import('@/pages/public/TramitesListaPage.vue'), meta: { title: 'Trámites y servicios' } },
  { path: '/tramites/:slug', name: 'public.tramites.detalle', component: () => import('@/pages/public/TramiteDetallePage.vue'), props: true, meta: { title: 'Detalle trámite' } },

  // PQRSD
  { path: '/pqrsd', name: 'public.pqrsd.crear', component: () => import('@/pages/public/PqrsdCrearPage.vue'), meta: { title: 'Radicar PQRSD' } },
  { path: '/pqrsd/consultar', name: 'public.pqrsd.consultar', component: () => import('@/pages/public/PqrsdConsultarPage.vue'), meta: { title: 'Consultar PQRSD' } },

  // Noticias
  { path: '/noticias', name: 'public.noticias.lista', component: () => import('@/pages/public/NoticiasListaPage.vue'), meta: { title: 'Noticias' } },
  { path: '/noticias/:slug', name: 'public.noticias.detalle', component: () => import('@/pages/public/NoticiaDetallePage.vue'), props: true, meta: { title: 'Noticia' } },

  // Auth
  { path: '/login', name: 'auth.login', component: () => import('@/pages/auth/LoginPage.vue'), meta: { title: 'Iniciar sesión', guest: true } },
  { path: '/registro', name: 'auth.register', component: () => import('@/pages/auth/RegisterPage.vue'), meta: { title: 'Registro', guest: true } },
  { path: '/recuperar', name: 'auth.forgot', component: () => import('@/pages/auth/ForgotPage.vue'), meta: { guest: true } },
  { path: '/restablecer', name: 'auth.reset', component: () => import('@/pages/auth/ResetPage.vue'), meta: { guest: true } },

  // Errors
  { path: '/403', name: 'forbidden', component: () => import('@/pages/public/ForbiddenPage.vue') },

  // Admin
  {
    path: '/admin',
    component: () => import('@/components/layout/AdminLayout.vue'),
    meta: { requiresAuth: true, permission: 'panel.access' },
    children: [
      { path: '', redirect: { name: 'admin.servidores.lista' } },

      { path: 'perfil', name: 'admin.perfil', component: () => import('@/pages/admin/perfil/PerfilPage.vue'), meta: { title: 'Mi perfil' } },

      { path: 'servidores', name: 'admin.servidores.lista', component: () => import('@/pages/admin/servidores/ListaPage.vue'), meta: { permission: 'servidores.view', title: 'Servidores' } },
      { path: 'servidores/nuevo', name: 'admin.servidores.nuevo', component: () => import('@/pages/admin/servidores/FormPage.vue'), meta: { permission: 'servidores.create', title: 'Nuevo servidor' } },
      { path: 'servidores/:id(\\d+)/editar', name: 'admin.servidores.editar', component: () => import('@/pages/admin/servidores/FormPage.vue'), props: true, meta: { permission: 'servidores.update', title: 'Editar servidor' } },

      { path: 'dependencias', name: 'admin.dependencias.lista', component: () => import('@/pages/admin/dependencias/ListaPage.vue'), meta: { permission: 'dependencias.view', title: 'Dependencias' } },
      { path: 'dependencias/nuevo', name: 'admin.dependencias.nuevo', component: () => import('@/pages/admin/dependencias/FormPage.vue'), meta: { permission: 'dependencias.create', title: 'Nueva dependencia' } },
      { path: 'dependencias/:id(\\d+)/editar', name: 'admin.dependencias.editar', component: () => import('@/pages/admin/dependencias/FormPage.vue'), props: true, meta: { permission: 'dependencias.update', title: 'Editar dependencia' } },

      { path: 'tramites', name: 'admin.tramites.lista', component: () => import('@/pages/admin/tramites/ListaPage.vue'), meta: { permission: 'tramites.view', title: 'Trámites' } },
      { path: 'tramites/nuevo', name: 'admin.tramites.nuevo', component: () => import('@/pages/admin/tramites/FormPage.vue'), meta: { permission: 'tramites.create', title: 'Nuevo trámite' } },
      { path: 'tramites/:id(\\d+)/editar', name: 'admin.tramites.editar', component: () => import('@/pages/admin/tramites/FormPage.vue'), props: true, meta: { permission: 'tramites.update', title: 'Editar trámite' } },

      { path: 'noticias', name: 'admin.noticias.lista', component: () => import('@/pages/admin/noticias/ListaPage.vue'), meta: { permission: 'noticias.view', title: 'Noticias' } },
      { path: 'noticias/nuevo', name: 'admin.noticias.nuevo', component: () => import('@/pages/admin/noticias/FormPage.vue'), meta: { permission: 'noticias.create', title: 'Nueva noticia' } },
      { path: 'noticias/:id(\\d+)/editar', name: 'admin.noticias.editar', component: () => import('@/pages/admin/noticias/FormPage.vue'), props: true, meta: { permission: 'noticias.update', title: 'Editar noticia' } },

      { path: 'pqrsd', name: 'admin.pqrsd.lista', component: () => import('@/pages/admin/pqrsd/ListaPage.vue'), meta: { permission: 'pqrsd.view', title: 'PQRSD' } },
      { path: 'pqrsd/:id(\\d+)', name: 'admin.pqrsd.detalle', component: () => import('@/pages/admin/pqrsd/DetallePage.vue'), props: true, meta: { permission: 'pqrsd.view', title: 'Detalle PQRSD' } },

      { path: 'sedes', name: 'admin.sedes.lista', component: () => import('@/pages/admin/sedes/ListaPage.vue'), meta: { permission: 'sedes.view', title: 'Sedes' } },
      { path: 'sedes/nuevo', name: 'admin.sedes.nuevo', component: () => import('@/pages/admin/sedes/FormPage.vue'), meta: { permission: 'sedes.create', title: 'Nueva sede' } },
      { path: 'sedes/:id(\\d+)/editar', name: 'admin.sedes.editar', component: () => import('@/pages/admin/sedes/FormPage.vue'), props: true, meta: { permission: 'sedes.update', title: 'Editar sede' } },

      { path: 'usuarios', name: 'admin.usuarios.lista', component: () => import('@/pages/admin/usuarios/ListaPage.vue'), meta: { permission: 'usuarios.view', title: 'Usuarios' } },
      { path: 'usuarios/nuevo', name: 'admin.usuarios.nuevo', component: () => import('@/pages/admin/usuarios/FormPage.vue'), meta: { permission: 'usuarios.create', title: 'Nuevo usuario' } },
      { path: 'usuarios/:id(\\d+)/editar', name: 'admin.usuarios.editar', component: () => import('@/pages/admin/usuarios/FormPage.vue'), props: true, meta: { permission: 'usuarios.update', title: 'Editar usuario' } },

      { path: 'roles', name: 'admin.roles.lista', component: () => import('@/pages/admin/roles/ListaPage.vue'), meta: { permission: 'roles.view', title: 'Roles y permisos' } }
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
    return { name: 'forbidden' }
  }
  return true
})

router.afterEach((to) => {
  const t = (to.meta.title as string | undefined) ?? 'Alcaldía de Santa Marta'
  document.title = `${t} — Alcaldía Distrital de Santa Marta`
})

export default router
