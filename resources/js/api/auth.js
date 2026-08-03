import { defineStore } from 'pinia';
import api from './axios.js';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token') || null,
        isAuthenticated: false,
    }),

    actions: {
        async login(credentials) {
            try {
                // Obtener CSRF (opcional con Sanctum)
                // await api.get('/csrf');

                const response = await api.post('/login', credentials);

                this.token = response.data.access_token;
                this.user = response.data.user;
                this.isAuthenticated = true;

                localStorage.setItem('auth_token', this.token);

                return { success: true };
            } catch (error) {
                return {
                    success: false,
                    error: error.response?.data?.errors || 'Error al iniciar sesión'
                };
            }
        },

        async logout() {
            try {
                await api.post('/logout');
            } finally {
                this.token = null;
                this.user = null;
                this.isAuthenticated = false;
                localStorage.removeItem('auth_token');
            }
        },

        async fetchUser() {
            try {
                const response = await api.get('/user');
                this.user = response.data;
                this.isAuthenticated = true;
            } catch (error) {
                this.logout();
            }
        }
    },

    getters: {
        isLoggedIn: (state) => state.isAuthenticated,
        currentUser: (state) => state.user,
    }
});
