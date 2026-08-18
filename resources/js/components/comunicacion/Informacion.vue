<template>
  <div class="inf-page">
    <div class="manager-container">
      <div class="manager-header">
        <h2>Gestión de Noticias y Categorías</h2>
        <button v-if="activeTab === 'noticias'" class="btn-primary" @click="openCreateNoticia">
          + Nueva Noticia
        </button>
        <button v-else class="btn-primary" @click="openCreateCategoria">
          + Nueva Categoría
        </button>
      </div>

      <!-- Pestañas (Tabs) -->
      <div class="tabs-container">
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'noticias' }"
          @click="activeTab = 'noticias'"
        >
          📰 Noticias
        </button>
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'categorias' }"
          @click="activeTab = 'categorias'"
        >
          🏷️ Categorías
        </button>
      </div>

      <!-- Contenido de las pestañas -->
      <div class="tab-content">

        <!-- ========================================== -->
        <!-- PESTAÑA 1: LISTA DE NOTICIAS               -->
        <!-- ========================================== -->
        <div v-if="activeTab === 'noticias'" class="tab-panel">
          <div class="table-wrapper">
                        <table class="data-table">
              <thead>
                <tr>
                  <th class="col-auto">Título</th>
                  <th class="col-auto">Categoría</th>
                  <th class="col-auto">Estado</th>
                  <th class="col-auto">Fecha Pub.</th>
                  <th class="col-actions">Acciones</th>
                </tr>
              </thead>
              <tbody v-if="!store.noticias || !Array.isArray(store.noticias) || store.noticias.length === 0">
                <tr>
                  <td colspan="5" class="empty-text">No hay noticias registradas.</td>
                </tr>
              </tbody>
              <tbody v-else>
              <tr v-for="noticia in store.noticias" :key="noticia.id_noticia || noticia.id">
                <td class="col-auto">{{ noticia.titulo || 'Sin título' }}</td>
                <td class="col-auto">{{ getCategoriaNombre(noticia.id_categoria) }}</td>
                <td class="col-auto">
                  <span class="status-badge" :class="noticia.estado_publicacion || 'borrador'">
                    {{ noticia.estado_publicacion || 'Borrador' }}
                  </span>
                </td>
                <td class="col-auto">{{ formatDate(noticia.fecha_publicacion) }}</td>
                <td class="col-actions">
                  <div class="action-buttons">
                    <button class="btn-edit" @click="openEditNoticia(noticia)" title="Editar esta noticia">✏️</button>
                    <button class="btn-delete" @click="confirmDeleteNoticia(noticia)" title="Eliminar esta noticia">🗑️</button>
                  </div>
                </td>
              </tr>
            </tbody>
            </table>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- PESTAÑA 2: LISTA DE CATEGORÍAS             -->
        <!-- ========================================== -->
        <div v-else class="tab-panel">
          <div class="table-wrapper">
            <table class="data-table">
              <thead>
                <tr>
                  <th class="col-auto">Nombre</th>
                  <th class="col-rest">Descripción</th>
                  <th class="col-auto">Estado</th>
                  <th class="col-actions">Acciones</th>
                </tr>
              </thead>
              <tbody v-if="!store.categorias || !Array.isArray(store.categorias) || store.categorias.length === 0">
                <tr>
                  <td colspan="4" class="empty-text">No hay categorías registradas.</td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr v-for="cat in store.categorias" :key="cat.id_categoria || cat.id">
                    <td class="col-auto">{{ cat.nombre || 'Sin nombre' }}</td>
                    <td class="col-rest">{{ cat.descripcion || '-' }}</td>
                    <td class="col-auto">
                    <span class="status-badge" :class="cat.estado ? 'active' : 'inactive'">
                        {{ cat.estado ? 'Activo' : 'Inactivo' }}
                    </span>
                    </td>
                    <td class="col-actions">
                    <div class="action-buttons">
                        <button class="btn-edit" @click="openEditCategoria(cat)" title="Editar esta categoría">✏️</button>
                        <button class="btn-delete" @click="confirmDeleteCategoria(cat)" title="Eliminar esta categoría">🗑️</button>
                    </div>
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- ========================================== -->
      <!-- MODAL DE NOTICIAS (COMPARTIDO)             -->
      <!-- ========================================== -->
      <NoticiaForm
        v-model:show="showNoticiaModal"
        :noticia="selectedNoticia"
        @saved="refreshNoticias"
      />

      <!-- ========================================== -->
      <!-- MODAL DE CATEGORÍAS (COMPARTIDO)           -->
      <!-- ========================================== -->
      <CategoriaForm
        v-model:show="showCategoriaModal"
        :categoria="selectedCategoria"
        @saved="refreshCategorias"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import { useNoticiasStore } from '../../stores/noticias.js';
import NoticiaForm from './NoticiasForm.vue';
import CategoriaForm from './CategoriaForm.vue';

const store = useNoticiasStore();
const activeTab = ref('noticias');

// Estado de los modales
const showNoticiaModal = ref(false);
const showCategoriaModal = ref(false);
const selectedNoticia = ref(null);
const selectedCategoria = ref(null);

// 🟢 BANDERA PARA EVITAR EL BUCLE DE CARGA
const isFirstLoad = ref(true);

// ==========================================
// MÉTODOS PARA NOTICIAS
// ==========================================
const openCreateNoticia = () => { selectedNoticia.value = null; showNoticiaModal.value = true; };
const openEditNoticia = (noticia) => { selectedNoticia.value = noticia; showNoticiaModal.value = true; };
const refreshNoticias = () => { store.fetchNoticias(); };

const confirmDeleteNoticia = async (noticia) => {
  if (confirm(`¿Estás seguro de eliminar la noticia "${noticia.titulo}"?`)) {
    await store.deleteNoticia(noticia.id_noticia);
  }
};

// ==========================================
// MÉTODOS PARA CATEGORÍAS
// ==========================================
const openCreateCategoria = () => { selectedCategoria.value = null; showCategoriaModal.value = true; };
const openEditCategoria = (cat) => { selectedCategoria.value = cat; showCategoriaModal.value = true; };
const refreshCategorias = () => { store.fetchCategorias(); };

const confirmDeleteCategoria = async (cat) => {
  if (confirm(`¿Estás seguro de eliminar la categoría "${cat.nombre}"?`)) {
    await store.deleteCategoria(cat.id_categoria);
  }
};

// ==========================================
// UTILIDADES (A prueba de fallos)
// ==========================================
const getCategoriaNombre = (id) => {
  if (!id) return 'Sin categoría';
  if (!Array.isArray(store.categorias)) return 'Sin categoría';

  const cat = store.categorias.find(c => c.id_categoria === id);
  return cat ? cat.nombre : 'Sin categoría';
};

const formatDate = (date) => {
  if (!date) return '-';
  return new Date(date).toLocaleDateString('es-ES');
};

// 🟢 WATCH: Cuando cambia la pestaña, limpiamos los datos de la pestaña que no se ve
watch(activeTab, (newTab) => {
  if (newTab === 'noticias') {
    store.fetchNoticias();
  } else {
    store.fetchCategorias();
  }
});

// ==========================================
// CICLO DE VIDA
// ==========================================
onMounted(() => {
  // 🟢 Solo cargamos los datos la PRIMERA vez que se monta el componente
  if (isFirstLoad.value) {
    isFirstLoad.value = false;
    store.fetchNoticias();
    store.fetchCategorias();
  }
});
</script>

<style scoped>
.inf-page {
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  padding: 20px;
  margin: -20px;
  height: auto;
  min-height: 100vh;
}

.manager-container {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.manager-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}
.manager-header h2 {
  margin: 0;
  font-size: 20px;
  color: #1a1a2e;
}

.btn-primary {
  padding: 8px 20px;
  background: #cc0000;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.btn-primary:hover {
  background: #a30000;
}

/* Tabs */
.tabs-container {
  display: flex;
  border-bottom: 2px solid #eee;
  margin-bottom: 20px;
}
.tab-btn {
  padding: 10px 20px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  color: #6b7280;
  position: relative;
  transition: all 0.3s;
}
.tab-btn:hover {
  color: #1a1a2e;
}
.tab-btn.active {
  color: #cc0000;
}
.tab-btn.active::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  right: 0;
  height: 2px;
  background: #cc0000;
}

/* Tablas */
.table-wrapper {
  overflow-x: auto;
}
.data-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: auto;
}
.data-table th,
.data-table td {
  padding: 14px 16px;
  text-align: left;
  border-bottom: 1px solid #eee;
  vertical-align: middle;
}

.data-table th.col-auto,
.data-table td.col-auto {
  width: auto;
  white-space: nowrap; /* No romper el texto en varias líneas */
}

.data-table td.col-rest {
  width: 100%; /* Ocupa todo el ancho sobrante */
  max-width: 0; /* Truco para que el texto largo se corte y no se salga de la tabla */
  white-space: normal; /* Permite saltos de línea */
  word-wrap: break-word; /* Rompe palabras largas si es necesario */
}

.data-table th.col-actions,
.data-table td.col-actions {
  width: 120px; /* Un ancho fijo pequeño para los botones */
  text-align: center;
}

.data-table th {
  background: #f9fafb;
  font-weight: 600;
  color: #374151;
}
.data-table tr:hover {
  background: #f9fafb;
}

.text-center {
  text-align: center !important;
}

/* Estados y badges */
.status-badge {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
  display: inline-block;
}
.status-badge.borrador {
  background: #fef2f2;
  color: #991b1b;
}
.status-badge.programado {
  background: #fffaf0;
  color: #c05621;
}
.status-badge.publicado {
  background: #d1fae5;
  color: #065f46;
}
.status-badge.active {
  background: #d1fae5;
  color: #065f46;
}
.status-badge.inactive {
  background: #fef2f2;
  color: #991b1b;
}

.actions-cell {
  height: 100%;
  padding: 0 !important;
}

.action-buttons {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-height: 60px;
  padding: 4px 10px;
}

.btn-edit {
  background: white;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}
.btn-edit:hover {
  background: #FF5959;
}
.btn-delete {
  background: white;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}
.btn-delete:hover {
  background: #FF5959;
}

.loading-text,
.empty-text {
  text-align: center;
  padding: 20px;
  color: #6b7280;
}
</style>
