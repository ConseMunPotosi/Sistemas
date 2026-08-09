import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores';

// Layouts
import DashboardLayout from '@/components/layout/DashboardLayout.vue';
import IndexLayout from '../components/InicioLayout.vue';

// Páginas
import Dashboard from '@/components/dashboard/Dashboard.vue';
import LoginCMP from '@/pages/LoginCMP.vue';
import Profile from '@/components/dashboard/Profile.vue';
import Settings from '@/components/dashboard/Settings.vue';
import Users from '@/components/dashboard/Users.vue';
import Inicio from '@/pages/Inicio.vue';

// Importaciones dinámicas para páginas públicas
const Directiva = () => import('../pages/Directiva.vue');
const Concejales = () => import('../pages/Concejales.vue');
const Comisiones = () => import('../pages/Comisiones.vue');
const Leyes = () => import('../pages/Leyes.vue');
const Resoluciones = () => import('../pages/Resoluciones.vue');
const Ordenanzas = () => import('../pages/Ordenanzas.vue');
const Noticias = () => import('../pages/Noticias.vue');
const Sesiones = () => import('../pages/Sesiones.vue');

// ==========================================
// DEFINICIÓN DE RUTAS
// ==========================================
const routes = [
    // ==========================================
    // RUTAS PÚBLICAS (Layout Principal)
    // ==========================================
    {
        path: '/',
        component: IndexLayout,
        children: [
            {
                path: '',
                name: 'inicio',
                component: Inicio
            },
            {
                path: 'directiva',
                name: 'directiva',
                component: Directiva
            },
            {
                path: 'concejales',
                name: 'concejales',
                component: Concejales
            },
            {
                path: 'comisiones',
                name: 'comisiones',
                component: Comisiones
            },
            {
                path: 'leyes',
                name: 'leyes',
                component: Leyes
            },
            {
                path: 'resoluciones',
                name: 'resoluciones',
                component: Resoluciones
            },
            {
                path: 'ordenanzas',
                name: 'ordenanzas',
                component: Ordenanzas
            },
            {
                path: 'noticias',
                name: 'noticias',
                component: Noticias
            },
            {
                path: 'sesiones',
                name: 'sesiones',
                component: Sesiones
            }
            // ❌ ELIMINADO: loginCMP de aquí para evitar duplicados
        ]
    },

    // ==========================================
    // RUTA DE LOGIN (Independiente)
    // ==========================================
    {
        path: '/loginCMP',
        name: 'loginCMP',
        component: LoginCMP,
        meta: {
            requiresGuest: true,
            layout: 'empty',
            hidden: true
        }
    },

    // ==========================================
    // RUTAS PROTEGIDAS (Dashboard)
    // ==========================================
    {
        path: '/dashboard',
        component: DashboardLayout,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '',
                name: 'dashboard',
                component: Dashboard,
                meta: {
                    title: 'Dashboard',
                    icon: 'home'
                }
            },
            {
                path: 'profile',
                name: 'profile',
                component: Profile,
                meta: {
                    title: 'Mi Perfil',
                    icon: 'user',
                    requiresAuth: true
                }
            },
            {
                path: 'settings',
                name: 'settings',
                component: Settings,
                meta: {
                    title: 'Configuración',
                    icon: 'settings',
                    requiresAuth: true,
                    requiresPermission: 'configuracion:ver'
                }
            },
            {
                path: 'users',
                name: 'users',
                component: Users,
                meta: {
                    title: 'Usuarios',
                    icon: 'users',
                    requiresAuth: true,
                    requiresPermission: 'usuarios:ver'
                }
            }
        ]
    },

    // ==========================================
    // RUTA 404 - SIEMPRE AL FINAL
    // ==========================================
    {
        path: '/:pathMatch(.*)*',
        redirect: '/dashboard'
    }
];

// ==========================================
// CONFIGURACIÓN DEL ROUTER
// ==========================================
const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { top: 0 };
        }
    }
});

// ==========================================
// GUARDIA DE NAVEGACIÓN - VERSIÓN CORREGIDA
// ==========================================
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    // 🔧 Si es la misma ruta, no hacer nada
    if (to.path === from.path) {
        next(false);
        return;
    }

    // 🔧 RUTAS PÚBLICAS (NO requieren autenticación)
    const publicPaths = [
        '/', '/inicio', '/directiva', '/concejales',
        '/comisiones', '/leyes', '/resoluciones',
        '/ordenanzas', '/noticias', '/sesiones'
    ];

    // Si es una ruta pública
    if (publicPaths.includes(to.path) || to.path === '/') {
        // Si está autenticado, ir a dashboard
        if (authStore.isAuthenticated) {
            next('/dashboard');
            return;
        }
        next();
        return;
    }

    // 🔧 RUTA DE LOGIN
    if (to.path === '/loginCMP' || to.name === 'loginCMP') {
        if (authStore.isAuthenticated) {
            next('/dashboard');
            return;
        }
        next();
        return;
    }

    // 🔧 RUTAS PROTEGIDAS (requieren autenticación)
    if (to.meta.requiresAuth) {
        if (!authStore.isAuthenticated) {
            next('/loginCMP');
            return;
        }
        next();
        return;
    }

    // Si llegamos aquí, permitir acceso
    next();
});

// 🔧 Manejador de errores
router.onError((error) => {
    console.error('❌ Error en router:', error);
    window.location.href = '/loginCMP';
});

// ==========================================
// MANEJADOR DE ERRORES DE NAVEGACIÓN
// ==========================================
router.onError((error) => {
    console.error('❌ Error en router:', error);

    // Si es error de navegación, redirigir a login
    if (error.message && error.message.includes('navigation')) {
        const authStore = useAuthStore();
        authStore.clearAuth();
        window.location.href = '/loginCMP';
    }
});

export default router;
