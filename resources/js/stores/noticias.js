// api/noticias.js
import { defineStore } from 'pinia';
import api from '../api/axios.js';

export const useNoticiasStore = defineStore('noticias', {
    state: () => ({
        noticias: [],
        categorias: [],
        loading: false,
        error: null
    }),

    actions: {
        // ==========================================
        // CRUD DE CATEGORÍAS
        // ==========================================
        async fetchCategorias() {
            this.loading = true;
            try {
                const response = await api.get('/noticias/categorias');
                if (response.data && response.data.data) {
                    this.categorias = response.data.data;
                } else if (Array.isArray(response.data)) {
                    this.categorias = response.data;
                } else {
                    this.categorias = []; // Si no es un array, que sea vacío
                }

                this.error = null;
            } catch (error) {
                this.error = 'Error al cargar categorías';
                console.error(error);
            } finally {
                this.loading = false;
            }
        },

        async createCategoria(data) {
            try {
                const response = await api.post('/noticias/categorias', data);
                await this.fetchCategorias();
                return { success: true, data: response.data };
            } catch (error) {
                return { success: false, errors: error.response?.data?.errors };
            }
        },

        async updateCategoria(id, data) {
            try {
                const response = await api.put(`/noticias/categorias/${id}`, data);
                await this.fetchCategorias();
                return { success: true, data: response.data };
            } catch (error) {
                return { success: false, errors: error.response?.data?.errors };
            }
        },

        async deleteCategoria(id) {
            try {
                await api.delete(`/noticias/categorias/${id}`);
                await this.fetchCategorias();
                return { success: true };
            } catch (error) {
                return { success: false, message: error.response?.data?.message };
            }
        },

        // ==========================================
        // CRUD DE NOTICIAS
        // ==========================================
        async fetchNoticias() {
            this.loading = true;
            try {
                const response = await api.get('/noticias');
                this.noticias = response.data.data || response.data;
            } catch (error) {
                this.error = 'Error al cargar noticias';
            } finally {
                this.loading = false;
            }
        },

        async createNoticia(data) {
            try {
                const response = await api.post('/noticias', data);
                await this.fetchNoticias();
                return { success: true, data: response.data };
            } catch (error) {
                return { success: false, errors: error.response?.data?.errors };
            }
        },

        async updateNoticia(id, data) {
            try {
                const response = await api.put(`/noticias/${id}`, data);
                await this.fetchNoticias();
                return { success: true, data: response.data };
            } catch (error) {
                return { success: false, errors: error.response?.data?.errors };
            }
        },

        async deleteNoticia(id) {
            try {
                await api.delete(`/noticias/${id}`);
                await this.fetchNoticias();
                return { success: true };
            } catch (error) {
                return { success: false, message: error.response?.data?.message };
            }
        }
    }
});
