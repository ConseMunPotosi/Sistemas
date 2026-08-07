import { defineStore } from 'pinia';
import api from './axios.js';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token') || null,
        isAuthenticated: !!localStorage.getItem('auth_token'), // ← CORREGIDO
        loading: false,
        error: null
    }),

    getters: {
        isLoggedIn: (state) => state.isAuthenticated,
        currentUser: (state) => state.user,
        getUser: (state) => state.user,
        getToken: (state) => state.token,
        isAdmin: (state) => {
            if (!state.user) return false;
            return state.user.roles?.some(role => role.nombre === 'Administrador') || false;
        },
        userPermissions: (state) => {
            if (!state.user) return {};
            return state.user.permissions || {};
        },
        userRoles: (state) => {
            if (!state.user) return [];
            return state.user.roles?.map(role => role.nombre) || [];
        }
    },

    actions: {
        async login(credentials) {
            try {
                this.loading = true;
                this.error = null;

                // 🔧 MAPEAR: 'username' → 'usuario' para el backend
                const loginData = {
                    usuario: credentials.username || credentials.usuario, // ← CLAVE: Mapeo de campos
                    password: credentials.password
                };

                const response = await api.post('/login', loginData);

                if (response.data.success) {
                    const { token, user, permissions } = response.data.data;

                    this.token = token;
                    this.user = user;
                    this.isAuthenticated = true;

                    // Guardar token en localStorage
                    localStorage.setItem('auth_token', token);

                    // Configurar axios para futuras peticiones
                    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                    return {
                        success: true,
                        user: user,
                        permissions: permissions
                    };
                }

                return {
                    success: false,
                    message: 'Error al iniciar sesión'
                };

            } catch (error) {
                console.error('❌ Login Error:', error.response?.data);

                let errorMessage = 'Error al iniciar sesión';
                let errors = null;

                if (error.response?.data) {
                    const data = error.response.data;

                    // Manejar diferentes formatos de error
                    if (data.errors) {
                        errors = data.errors;

                        // 🔧 Mapear 'usuario' a 'username' para el frontend
                        if (errors.usuario) {
                            errors.username = errors.usuario;
                            delete errors.usuario;
                        }

                        const firstError = Object.values(errors).flat()[0];
                        if (firstError) {
                            errorMessage = firstError;
                        }
                    } else if (data.message) {
                        errorMessage = data.message;
                    }
                }

                this.error = errorMessage;

                return {
                    success: false,
                    message: errorMessage,
                    errors: errors
                };
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            try {
                this.loading = true;
                await api.post('/logout');
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                this.clearAuth();
                this.loading = false;
            }
        },

        clearAuth() {
            this.token = null;
            this.user = null;
            this.isAuthenticated = false;
            this.error = null;
            localStorage.removeItem('auth_token');
            delete api.defaults.headers.common['Authorization'];

        },

        async fetchUser() {
            try {
                if (!this.token) {
                    this.clearAuth();
                    return { success: false };
                }

                const response = await api.get('/user', {
                    headers: {
                        'Authorization': `Bearer ${this.token}`
                    }
                });

                if (response.data.success) {
                    this.user = response.data.data.user;
                    this.isAuthenticated = true;
                    return { success: true, user: this.user };
                }

                return { success: false };
            } catch (error) {
                console.error('Error fetching user:', error);

                if (error.response?.status === 401) {
                    this.clearAuth();
                }

                return { success: false, error: error.message };
            }
        },

        // ==========================================
        // MÉTODOS DE VERIFICACIÓN DE PERMISOS
        // ==========================================

        hasPermission(permission) {
            if (!this.user?.permissions) return false;

            // Verificar si el permiso existe en algún módulo
            for (const [modulo, acciones] of Object.entries(this.user.permissions)) {
                if (acciones.includes(permission) || acciones.includes('*')) {
                    return true;
                }
            }
            return false;
        },

        hasAnyPermission(permissions) {
            if (!Array.isArray(permissions)) return false;
            return permissions.some(p => this.hasPermission(p));
        },

        hasAllPermissions(permissions) {
            if (!Array.isArray(permissions)) return false;
            return permissions.every(p => this.hasPermission(p));
        },

        hasRole(roleName) {
            if (!this.user?.roles) return false;
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

        // Método para verificar acceso a módulos
        canAccessModule(modulo) {
            if (!this.user?.permissions) return false;
            return Object.keys(this.user.permissions).includes(modulo);
        }
    },

    // Persistencia en localStorage
    persist: {
        key: 'auth_state',
        storage: localStorage,
        paths: ['token', 'user', 'isAuthenticated']
    }
});
