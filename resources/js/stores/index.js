//stores/index.js
import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token'), // Cambiado a 'auth_token' para consistencia
        isAuthenticated: !!localStorage.getItem('auth_token'),
        loading: false,
        error: null
    }),

    getters: {
        // Getters básicos
        getUser: (state) => state.user,
        getToken: (state) => state.token,
        isLoggedIn: (state) => state.isAuthenticated,

        // Verificar si es administrador
        isAdmin: (state) => {
            if (!state.user) return false
            return state.user.roles?.some(role =>
                role.nombre === 'Administrador' || role.nombre === 'admin'
            )
        },

        // Obtener todos los permisos del usuario
        userPermissions: (state) => {
            if (!state.user) return []
            // Extraer permisos de los roles
            const permissions = state.user.roles?.flatMap(role => role.permisos || []) || []
            return [...new Set(permissions)]
        },

        // Obtener permisos agrupados por módulo
        permissionsByModule: (state) => {
            if (!state.user) return {}
            const permissions = {}
            state.user.roles?.forEach(role => {
                role.permisos?.forEach(permiso => {
                    if (!permissions[permiso.modulo]) {
                        permissions[permiso.modulo] = []
                    }
                    if (!permissions[permiso.modulo].includes(permiso.accion)) {
                        permissions[permiso.modulo].push(permiso.accion)
                    }
                })
            })
            return permissions
        },

        // Obtener nombre completo del usuario
        userFullName: (state) => {
            if (!state.user) return ''
            return state.user.nombre_completo || state.user.nombre || state.user.usuario || ''
        },

        // Obtener roles del usuario
        userRoles: (state) => {
            if (!state.user) return []
            return state.user.roles?.map(role => role.nombre) || []
        }
    },

    actions: {
        async login(credentials) {
            try {
                this.loading = true
                this.error = null

                const response = await axios.post('/api/login', credentials)

                // Verificar estructura de respuesta
                if (response.data.success) {
                    const { token, user } = response.data.data

                    // Guardar token y usuario
                    this.token = token
                    this.user = user
                    this.isAuthenticated = true

                    // Persistir en localStorage
                    localStorage.setItem('auth_token', token)

                    // Configurar axios para futuras peticiones
                    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

                    return { success: true, user }
                } else {
                    // Si no hay success, pero hay datos
                    if (response.data.token) {
                        this.token = response.data.token
                        this.user = response.data.user
                        this.isAuthenticated = true
                        localStorage.setItem('auth_token', this.token)
                        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
                        return { success: true, user: this.user }
                    }
                    throw new Error('Respuesta inválida del servidor')
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Error al iniciar sesión'
                console.error('Login error:', error)

                // Limpiar estado en caso de error
                this.clearAuth()

                return {
                    success: false,
                    message: error.response?.data?.message || 'Error al iniciar sesión',
                    errors: error.response?.data?.errors
                }
            } finally {
                this.loading = false
            }
        },

        async logout() {
            try {
                // Intentar cerrar sesión en el servidor
                await axios.post('/api/logout')
            } catch (error) {
                console.error('Logout error:', error)
            } finally {
                // Siempre limpiar estado local
                this.clearAuth()
            }
        },

        clearAuth() {
            this.token = null
            this.user = null
            this.isAuthenticated = false
            this.error = null
            localStorage.removeItem('auth_token')
            delete axios.defaults.headers.common['Authorization']
        },

        async fetchUser() {
            try {
                if (!this.token) {
                    this.clearAuth()
                    return null
                }

                const response = await axios.get('/api/user', {
                    headers: {
                        'Authorization': `Bearer ${this.token}`
                    }
                })

                if (response.data.success) {
                    this.user = response.data.data
                    this.isAuthenticated = true
                    return this.user
                } else {
                    // Si la respuesta no es exitosa, limpiar
                    this.clearAuth()
                    return null
                }
            } catch (error) {
                console.error('Error fetching user:', error)
                if (error.response?.status === 401) {
                    this.clearAuth()
                }
                return null
            }
        },

        // ==========================================
        // MÉTODOS DE PERMISOS Y ROLES
        // ==========================================

        /**
         * Verificar si el usuario tiene un permiso específico
         * @param {string} permission - Permiso en formato "modulo:accion" o "modulo.accion"
         */
        hasPermission(permission) {
            if (!this.isAuthenticated) return false

            // Si es administrador, tiene todos los permisos
            if (this.isAdmin) return true

            // Verificar si el permiso existe en la lista de permisos del usuario
            return this.userPermissions.includes(permission)
        },

        /**
         * Verificar si el usuario tiene permiso para un módulo y acción específicos
         * @param {string} modulo - Nombre del módulo
         * @param {string} accion - Acción a realizar (ver, crear, editar, eliminar)
         */
        can(modulo, accion) {
            if (!this.isAuthenticated) return false
            if (this.isAdmin) return true

            const permissions = this.permissionsByModule
            return permissions[modulo]?.includes(accion) || false
        },

        /**
         * Verificar si el usuario tiene acceso a un módulo
         * @param {string} modulo - Nombre del módulo
         */
        canAccessModule(modulo) {
            if (!this.isAuthenticated) return false
            if (this.isAdmin) return true

            const permissions = this.permissionsByModule
            return !!permissions[modulo] && permissions[modulo].length > 0
        },

        /**
         * Verificar si el usuario tiene alguno de los roles especificados
         * @param {string|string[]} roles - Rol o array de roles
         */
        hasAnyRole(roles) {
            if (!this.isAuthenticated || !this.user) return false
            if (this.isAdmin) return true

            const rolesArray = Array.isArray(roles) ? roles : [roles]
            return rolesArray.some(role =>
                this.user.roles?.some(userRole =>
                    userRole.nombre === role || userRole.nombre.toLowerCase() === role.toLowerCase()
                )
            )
        },

        /**
         * Verificar si el usuario tiene todos los roles especificados
         * @param {string[]} roles - Array de roles
         */
        hasAllRoles(roles) {
            if (!this.isAuthenticated || !this.user) return false
            if (this.isAdmin) return true

            const userRoles = this.user.roles?.map(r => r.nombre) || []
            return roles.every(role => userRoles.includes(role))
        },

        /**
         * Verificar si el usuario tiene un rol específico
         * @param {string} role - Nombre del rol
         */
        hasRole(role) {
            return this.hasAnyRole(role)
        },

        // ==========================================
        // MÉTODOS DE UTILIDAD
        // ==========================================

        /**
         * Inicializar la autenticación al cargar la aplicación
         */
        async initializeAuth() {
            // Si hay token, intentar obtener el usuario
            if (this.token && !this.user) {
                await this.fetchUser()
            }

            // Si no hay token, asegurar que el estado esté limpio
            if (!this.token) {
                this.clearAuth()
            }

            return this.isAuthenticated
        },

        /**
         * Verificar si el token es válido
         */
        async verifyToken() {
            if (!this.token) return false
            try {
                const user = await this.fetchUser()
                return !!user
            } catch (error) {
                return false
            }
        },

        /**
         * Obtener el estado de autenticación con loading
         */
        async checkAuth() {
            this.loading = true
            try {
                const isValid = await this.verifyToken()
                return isValid
            } finally {
                this.loading = false
            }
        }
    },

    // Configuración de persistencia (opcional)
    persist: {
        key: 'auth_state',
        paths: ['token', 'user', 'isAuthenticated'],
        storage: localStorage
    }
})
