import axios from 'axios';

// Configuración base
const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL || '/api',
    timeout: 30000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
    withCredentials: true,
});

// Interceptor para agregar token automáticamente
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// 🔧 Interceptor para manejar respuestas - CORREGIDO
api.interceptors.response.use(
    (response) => response,
    async (error) => {
        const originalRequest = error.config;

        // 🔧 Identificar si es una petición de login
        const isLoginRequest = originalRequest.url === '/login' ||
                               originalRequest.url === '/api/login' ||
                               originalRequest.url.includes('login');


        // 🔧 Solo manejar 401 si NO es una petición de login
        if (error.response?.status === 401 && !originalRequest._retry && !isLoginRequest) {
            originalRequest._retry = true;

            console.log('🔍 Axios - Token expirado, limpiando autenticación');

            // Limpiar autenticación
            const { useAuthStore } = await import('./auth.js');
            const authStore = useAuthStore();
            authStore.clearAuth();

            // Redirigir a login solo si no estamos ya en login
            if (typeof window !== 'undefined' && !window.location.pathname.includes('/login')) {
                console.log('🔍 Axios - Redirigiendo a login');
                window.location.href = '/loginCMP';
            }
        }

        return Promise.reject(error);
    }
);

export default api;
