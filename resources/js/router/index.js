//import { comment } from 'postcss';
import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../api/auth.js';

// Layout
import DashboardLayout from '@/components/layout/DashboardLayout.vue';
import IndexLayout from '../components/InicioLayout.vue'

// Páginas
import Dashboard from '@/components/dashboard/Dashboard.vue';
import Login from '@/pages/LoginCMP.vue';
import Profile from '@/components/dashboard/Profile.vue';
import Settings from '@/components/dashboard/Settings.vue';
import Users from '@/components/dashboard/Users.vue';

const routes = [
    /*{ path: '/', name: 'inicio', component: () => import('../components/InicioLayout.vue') },
    { path: '/directiva', name: 'directiva', component: () => import('../pages/Directiva.vue') },
    { path: '/concejales', name: 'concejales', component: () => import('../pages/Concejales.vue') },
    { path: '/comisiones', name: 'comisiones', component: () => import('../pages/Comisiones.vue') },
    { path: '/leyes', name: 'leyes', component: () => import('../pages/Leyes.vue') },
    { path: '/resoluciones', name: 'resoluciones', component: () => import('../pages/Resoluciones.vue') },
    { path: '/ordenanzas', name: 'ordenanzas', component: () => import('../pages/Ordenanzas.vue') },
    { path: '/noticias', name: 'noticias', component: () => import('../pages/Noticias.vue') },
    { path: '/sesiones', name: 'sesiones', component: () => import('../pages/Sesiones.vue') },*/

    {
        path: '/',
        component: IndexLayout, // 👈 Layout con Header y Footer
        children: [
        {
            path: '',
            name: 'inicio',
            component: () => import('../pages/Inicio.vue')
        },
        {
            path: 'directiva',
            name: 'directiva',
            component: () => import('../pages/Directiva.vue')
        },
        {
            path: 'concejales',
            name: 'concejales',
            component: () => import('../pages/Concejales.vue')
        },
        {
            path: 'comisiones',
            name: 'comisiones',
            component: () => import('../pages/Comisiones.vue')
        },
        {
            path: 'leyes',
            name: 'leyes',
            component: () => import('../pages/Leyes.vue')
        },
        {
            path: 'resoluciones',
            name: 'resoluciones',
            component: () => import('../pages/Resoluciones.vue')
        },
        {
            path: 'ordenanzas',
            name: 'ordenanzas',
            component: () => import('../pages/Ordenanzas.vue')
        },
        {
            path: 'noticias',
            name: 'noticias',
            component: () => import('../pages/Noticias.vue')
        },
        {
            path: 'sesiones',
            name: 'sesiones',
            component: () => import('../pages/Sesiones.vue')
        },
        ]
    },

    {
        path: '/loginCMP',
        name: 'loginCMP',
        component: () => import('../pages/LoginCMP.vue'),
        meta: { layout: 'empty', hidden:true }
    },

    {
        path: '/dashboard',
        component: DashboardLayout,
        meta: { requiresAuth: true },
        children: [
        {
            path: '',
            name: 'Dashboard',
            component: Dashboard
        },
        {
            path: 'profile',
            name: 'Profile',
            component: Profile
        },
        {
            path: 'settings',
            name: 'Settings',
            component: Settings
        },
        {
            path: 'users',
            name: 'Users',
            component: Users
        }
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/dashboard'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { top: 0 };
        }
    },
});

//Guardia de navegación
/*
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // Verificar si tiene token y no está autenticado
  if (authStore.token && !authStore.isAuthenticated) {
    await authStore.fetchUser();
  }

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login');
  } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next('/dashboard');
  } else {
    next();
  }
});*/
export default router;
