import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    { path: '/', name: 'inicio', component: () => import('../pages/Inicio.vue') },
    { path: '/directiva', name: 'directiva', component: () => import('../pages/Directiva.vue') },
    { path: '/concejales', name: 'concejales', component: () => import('../pages/Concejales.vue') },
    { path: '/comisiones', name: 'comisiones', component: () => import('../pages/Comisiones.vue') },
    { path: '/leyes', name: 'leyes', component: () => import('../pages/Leyes.vue') },
    { path: '/resoluciones', name: 'resoluciones', component: () => import('../pages/Resoluciones.vue') },
    { path: '/ordenanzas', name: 'ordenanzas', component: () => import('../pages/Ordenanzas.vue') },
    { path: '/noticias', name: 'noticias', component: () => import('../pages/Noticias.vue') },
    { path: '/sesiones', name: 'sesiones', component: () => import('../pages/Sesiones.vue') },
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

export default router;
