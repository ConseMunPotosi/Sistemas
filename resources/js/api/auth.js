import { defineStore } from 'pinia';
import api from './axios.js';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token') || null,
        isAuthenticated: !!localStorage.getItem('auth_token'),
        loading: false,
        error: null
    }),

    getters: {
        isLoggedIn: (state) => state.isAuthenticated,
        currentUser: (state) => state.user,
        userPermissions: (state) => {
            if (!state.user) return {};
            return state.user.permissions || {};
        },
        userRoles: (state) => {
            if (!state.user) return [];
            return state.user.roles?.map(r => r.nombre) || [];
        }
    },

    actions: {
        async login(credentials) {
            try {
                this.loading = true;
                this.error = null;

                const loginData = {
                    usuario: credentials.username || credentials.usuario,
                    password: credentials.password
                };

                const response = await api.post('/login', loginData);

                if (response.data.success) {
                    const { token, user } = response.data.data;

                    this.token = token;
                    this.user = user;
                    this.isAuthenticated = true;

                    localStorage.setItem('auth_token', token);
                    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                    return { success: true, user };
                }

                return { success: false, message: 'Error al iniciar sesión' };

            } catch (error) {
                console.error('❌ Login Error:', error.response?.data);
                return {
                    success: false,
                    message: error.response?.data?.message || 'Error al iniciar sesión',
                    errors: error.response?.data?.errors
                };
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            try {
                await api.post('/logout').catch(() => {
                    console.log('⚠️ Logout en servidor falló, continuando...');
                });
            } catch (error) {
                console.log('⚠️ Error en logout:', error);
            } finally {
                this.clearAuth();
            }
        },

        clearAuth() {
            this.token = null;
            this.user = null;
            this.isAuthenticated = false;
            this.error = null;

            localStorage.removeItem('auth_token');
            localStorage.removeItem('remember_me');
            localStorage.removeItem('saved_username');
            localStorage.removeItem('saved_password');

            delete api.defaults.headers.common['Authorization'];
        },

        async fetchUser() {
            try {
                if (!this.token) {
                    this.clearAuth();
                    return { success: false };
                }
                const response = await api.get('/user');

                if (response.data.success) {
                    this.user = response.data.data.user;
                    this.isAuthenticated = true;
                    return { success: true, user: this.user };
                }

                this.clearAuth();
                return { success: false };
            } catch (error) {
                console.error('❌ Error obteniendo usuario:', error);
                if (error.response?.status === 401) {
                    this.clearAuth();
                }
                return { success: false, error: error.message };
            }
        },

        // ==========================================
        // MÉTODOS DE PERMISOS - CORREGIDOS
        // ==========================================

        hasPermission(permission) {
            if (!this.user || !this.user.permissions) return false;

            if (typeof permission === 'string') {
                for (const [modulo, acciones] of Object.entries(this.user.permissions)) {
                    if (acciones.includes(permission) || acciones.includes('*')) {
                        return true;
                    }
                }
                return false;
            }

            if (typeof permission === 'object') {
                const { modulo, accion } = permission;
                if (!this.user.permissions[modulo]) return false;
                return this.user.permissions[modulo].includes(accion) ||
                       this.user.permissions[modulo].includes('*');
            }

            return false;
        },

        hasRole(roleName) {
            if (!this.user || !this.user.roles) return false;
            return this.user.roles.some(role => role.nombre === roleName);
        },

        hasAnyRole(roles) {
            if (!Array.isArray(roles)) return false;
            return roles.some(r => this.hasRole(r));
        },

        hasAllRoles(roles) {
            if (!Array.isArray(roles)) return false;
            return roles.every(r => this.hasRole(r));
        },

        canAccessModule(modulo) {
            if (!this.user || !this.user.permissions) return false;
            return Object.keys(this.user.permissions).includes(modulo);
        }
    }
});
