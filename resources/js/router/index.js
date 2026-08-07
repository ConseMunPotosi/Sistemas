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
            },
            {
                path: 'login',
                name: 'login',
                component: LoginCMP,
                meta: {
                    requiresGuest: true,
                    layout: 'empty'
                }
            }
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
                    requiresPermission: 'configuracion:ver' // Ejemplo de permiso
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
                    requiresPermission: 'usuarios:ver' // Permiso específico
                }
            },
            /* Agregar más rutas protegidas según necesidad
            {
                path: 'noticias-admin',
                name: 'noticias-admin',
                component: () => import('../pages/NoticiasAdmin.vue'),
                meta: {
                    title: 'Gestionar Noticias',
                    icon: 'news',
                    requiresAuth: true,
                    requiresPermission: 'noticias:ver'
                }
            }*/
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
    },
});

// ==========================================
// GUARDIA DE NAVEGACIÓN
// ==========================================
router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // Si hay token pero no hay usuario, intentar obtenerlo
    if (authStore.token && !authStore.user) {
        await authStore.fetchUser();
    }

    // Verificar si la ruta requiere autenticación
    if (to.meta.requiresAuth) {
        if (!authStore.isAuthenticated) {
            // Redirigir a login con return URL
            next({
                name: 'loginCMP',
                query: { redirect: to.fullPath }
            });
            return;
        }

        // Verificar si el usuario está activo
        if (authStore.user && !authStore.user.estado === false) {
            await authStore.logout();
            next({
                name: 'loginCMP',
                query: { message: 'Cuenta inactiva' }
            });
            return;
        }

        // Verificar permisos específicos
        if (to.meta.requiresPermission) {
            const hasPermission = authStore.hasPermission(to.meta.requiresPermission);
            if (!hasPermission) {
                // Redirigir a dashboard con mensaje de error
                next({
                    name: 'Dashboard',
                    query: { error: 'No tienes permisos para acceder a esta sección' }
                });
                return;
            }
        }
    }

    // Si está autenticado y va a login, redirigir a dashboard
    if (to.meta.requiresGuest && authStore.isAuthenticated) {
        // Verificar si hay una URL de redirección
        const redirect = to.query.redirect || '/dashboard';
        next(redirect);
        return;
    }

    // Verificar roles específicos (opcional)
    if (to.meta.requiresRole) {
        const hasRole = authStore.hasRole(to.meta.requiresRole);
        if (!hasRole) {
            next({
                name: 'Dashboard',
                query: { error: 'No tienes el rol necesario' }
            });
            return;
        }
    }

    // Establecer título de página (opcional)
    if (to.meta.title) {
        document.title = `${to.meta.title} - Sistema de Gestión`;
    }

    // Todo está bien, continuar
    next();
});

// ==========================================
// ERROR HANDLER (OPCIONAL)
// ==========================================
router.onError((error) => {
    console.error('Error en el router:', error);
    // Redirigir a página de error o login
    if (error.message.includes('auth')) {
        const authStore = useAuthStore();
        authStore.clearAuth();
        router.push('/login');
    }
});

export default router;
