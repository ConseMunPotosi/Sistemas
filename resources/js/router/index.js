// router/index.js
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

// ==========================================================
// IMPORTACIONES DINÁMICAS PÚBLICAS
// ==========================================================

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

// ==========================================================
// GESTIÓN ADMINISTRATIVA
// ==========================================================

const GestionNormas = () =>
    import('@/components/gaceta/GestionNormas.vue');

const GestionSesiones = () =>
    import('../pages/GestionSesiones.vue');

const GestionConcejales = () =>
    import('@/components/institucional/GestionConcejales.vue');


// ==========================================================
// RUTAS
// ==========================================================

const routes = [

    // ======================================================
    // RUTAS PÚBLICAS
    // ======================================================

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
                path: 'boletines',
                name: 'boletines',
                component: Boletines
            },

            {
                path: 'comunicados',
                name: 'comunicados',
                component: Comunicados
            },

            {
                path: 'sesiones',
                name: 'sesiones',
                component: Sesiones
            }
        ]
    },


    // ======================================================
    // LOGIN
    // ======================================================

    {
        path: '/loginCMP',
        name: 'loginCMP',
        component: LoginCMP
    },


    // ======================================================
    // DASHBOARD PROTEGIDO
    // ======================================================

    {
        path: '/dashboard',

        component: DashboardLayout,

        meta: {
            requiresAuth: true
        },

        children: [

            // ------------------------------------------------
            // DASHBOARD
            // ------------------------------------------------

            {
                path: '',
                name: 'dashboard',
                component: Dashboard
            },


            // ------------------------------------------------
            // PERFIL
            // ------------------------------------------------

            {
                path: 'profile',
                name: 'profile',
                component: Profile
            },


            // ------------------------------------------------
            // CONFIGURACIÓN
            // ------------------------------------------------

            {
                path: 'settings',
                name: 'settings',
                component: Settings
            },


            // ------------------------------------------------
            // USUARIOS
            // ------------------------------------------------

            {
                path: 'users',
                name: 'users',
                component: Users
            },


            // =================================================
            // GESTIÓN COMUNICACIONAL
            // =================================================

            {
                path: 'comunicacion/informacion',
                name: 'informacion',
                component: Informacion
            },


            // =================================================
            // GACETA Y ARCHIVOS
            // =================================================

            {
                path: 'gestion-normas',
                name: 'gestionNormas',
                component: GestionNormas
            },


            // =================================================
            // SESIONES
            // =================================================

            {
                path: 'gestion-sesiones',
                name: 'gestionSesiones',
                component: GestionSesiones
            },


            // =================================================
            // CONCEJALES
            // =================================================

            {
                path: 'gestion-concejales',
                name: 'gestionConcejales',
                component: GestionConcejales
            }

        ]
    },


    // ======================================================
    // 404
    // ======================================================

    {
        path: '/:pathMatch(.*)*',
        redirect: '/'
    }
];


// ==========================================================
// CREAR ROUTER
// ==========================================================

const router = createRouter({

    history: createWebHistory(),

    routes

});


// ==========================================================
// GUARDIA DE NAVEGACIÓN
// ==========================================================

router.beforeEach((to, from, next) => {

    const authStore = useAuthStore();

    // ------------------------------------------------------
    // ACTUALIZAR AUTENTICACIÓN DESDE LOCALSTORAGE
    // ------------------------------------------------------

    const token = localStorage.getItem('auth_token');

    if (token && !authStore.isAuthenticated) {

        authStore.isAuthenticated = true;

        authStore.token = token;
    }

    if (!token && authStore.isAuthenticated) {

        authStore.clearAuth();
    }


    // ------------------------------------------------------
    // RUTAS PÚBLICAS
    // ------------------------------------------------------

    const publicPaths = [

        '/',
        '/inicio',

        '/directiva',
        '/concejales',
        '/comisiones',

        '/leyes',
        '/resoluciones',
        '/ordenanzas',

        '/noticias',
        '/boletines',
        '/comunicados',

        '/sesiones',

        '/loginCMP'
    ];


    // ------------------------------------------------------
    // PERMITIR RUTAS PÚBLICAS
    // ------------------------------------------------------

    if (publicPaths.includes(to.path)) {

        next();

        return;
    }


    // ------------------------------------------------------
    // RUTAS DEL DASHBOARD
    // ------------------------------------------------------

    if (to.path.startsWith('/dashboard')) {

        if (!authStore.isAuthenticated) {

            next({
                path: '/loginCMP',
                replace: true
            });

            return;
        }

        next();

        return;
    }


    // ------------------------------------------------------
    // CUALQUIER OTRA RUTA
    // ------------------------------------------------------

    next();
});


export default router;
