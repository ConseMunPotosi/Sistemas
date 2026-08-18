//router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores';

// Layouts
import DashboardLayout from '@/components/layout/DashboardLayout.vue';
import IndexLayout from '../components/InicioLayout.vue';
import Informacion from '../components/comunicacion/Informacion.vue';

// Páginas
import Dashboard from '@/components/dashboard/Dashboard.vue';
import LoginCMP from '@/pages/LoginCMP.vue';
import Profile from '@/components/dashboard/Profile.vue';
import Settings from '@/components/dashboard/Settings.vue';
import Users from '@/components/dashboard/Users.vue';
import Inicio from '@/pages/Inicio.vue';

// Importaciones dinámicas
const Directiva = () => import('../pages/Directiva.vue');
const Concejales = () => import('../pages/Concejales.vue');
const Comisiones = () => import('../pages/Comisiones.vue');
const Leyes = () => import('../pages/Leyes.vue');
const Resoluciones = () => import('../pages/Resoluciones.vue');
const Ordenanzas = () => import('../pages/Ordenanzas.vue');
const Noticias = () => import('../pages/Noticias.vue');
const Sesiones = () => import('../pages/Sesiones.vue');
const Boletines = () => import('../pages/BoletinPrensa.vue');
const Comunicados = () => import('../pages/Comunicados.vue');
const Audiovisuales = () => import('../pages/AudioVisual.vue');

const routes = [
    // RUTAS PÚBLICAS
    {
        path: '/',
        component: IndexLayout,
        children: [
            { path: '', name: 'inicio', component: Inicio },
            { path: 'directiva', name: 'directiva', component: Directiva },
            { path: 'concejales', name: 'concejales', component: Concejales },
            { path: 'comisiones', name: 'comisiones', component: Comisiones },
            { path: 'leyes', name: 'leyes', component: Leyes },
            { path: 'resoluciones', name: 'resoluciones', component: Resoluciones },
            { path: 'ordenanzas', name: 'ordenanzas', component: Ordenanzas },
            { path: 'noticias', name: 'noticias', component: Noticias },
            { path: 'boletines', name: 'boletines', component: Boletines },
            { path: 'comunicados', name: 'comunicados', component: Comunicados },
            { path: 'audiovisuales', name: 'audiovisuales', component: Audiovisuales },
            { path: 'sesiones', name: 'sesiones', component: Sesiones },
        ]
    },
    // LOGIN
    {
        path: '/loginCMP',
        name: 'loginCMP',
        component: LoginCMP
    },
    // DASHBOARD (Protegido)
    {
        path: '/dashboard',
        component: DashboardLayout,
        meta: { requiresAuth: true },
        children: [
            // Rutas existentes
            { path: '', name: 'dashboard', component: Dashboard },
            { path: 'profile', name: 'profile', component: Profile },
            { path: 'settings', name: 'settings', component: Settings },
            { path: 'users', name: 'users', component: Users },

            // ==========================================
            // 🟢 NUEVAS RUTAS PARA EL SIDEBAR 🟢
            // ==========================================

            // 1. GESTIÓN INSTITUCIONAL
            { path: 'institucional/funcionarios', name: 'funcionarios', component: () => import('@/components/institucional/FuncionariosList.vue') },
            { path: 'institucional/unidades', name: 'unidades', component: () => import('@/components/institucional/UnidadesList.vue') },
            { path: 'institucional/cargos', name: 'cargos', component: () => import('@/components/institucional/CargosList.vue') },
            { path: 'institucional/roles', name: 'roles', component: () => import('@/components/institucional/RolesList.vue') },
            { path: 'institucional/permisos', name: 'permisos', component: () => import('@/components/institucional/PermisosList.vue') },

            // 2. GESTIÓN COMUNICACIONAL
            { path: 'comunicacion/informacion', name: 'informacion', component: Informacion }
        ]
    },
    // 404
    { path: '/:pathMatch(.*)*', redirect: '/' }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// ==========================================
// GUARDIA DE NAVEGACIÓN - QUE FUNCIONA
// ==========================================
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    // 🔧 Actualizar estado de autenticación desde localStorage
    const token = localStorage.getItem('auth_token');
    if (token && !authStore.isAuthenticated) {
        authStore.isAuthenticated = true;
        authStore.token = token;
    }

    if (!token && authStore.isAuthenticated) {
        authStore.clearAuth();
    }

    // Rutas públicas
    const publicPaths = [
        '/', '/inicio', '/directiva', '/concejales',
        '/comisiones', '/leyes', '/resoluciones',
        '/ordenanzas', '/noticias', '/boletines', '/sesiones', '/loginCMP', '/comunicados', '/audiovisuales'
    ];

    // Si es ruta pública, permitir
    if (publicPaths.includes(to.path)) {
        next();
        return;
    }

    // Si es dashboard, verificar autenticación
    if (to.path.startsWith('/dashboard')) {
        if (!authStore.isAuthenticated) {
            // 🔧 Usar replace para evitar que el usuario vuelva atrás
            next({ path: '/loginCMP', replace: true });
            return;
        }
        next();
        return;
    }

    next();
});
export default router;
