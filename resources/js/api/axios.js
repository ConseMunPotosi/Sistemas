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

// Interceptor para manejar respuestas
api.interceptors.response.use(
    (response) => response,
    async (error) => {
        const originalRequest = error.config;

        // Si el token expiró (401)
        if (error.response?.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;

            // Limpiar autenticación
            const { useAuthStore } = await import('./auth.js');
            const authStore = useAuthStore();
            authStore.clearAuth();

            // Redirigir a login
            if (typeof window !== 'undefined') {
                window.location.href = '/loginCMP';
            }
        }

        return Promise.reject(error);
    }
);

export default api;
