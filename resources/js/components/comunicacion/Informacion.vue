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
                  <th class="col-icon">Archivos</th>
                  <th class="col-auto">Título</th>
                  <th class="col-rest">Resumen</th>
                  <th class="col-auto">Categoría</th>
                  <th class="col-auto">Estado</th>
                  <th class="col-auto">Fecha Pub.</th>
                  <th class="col-actions">Acciones</th>
                </tr>
              </thead>
              <tbody v-if="!store.noticias || !Array.isArray(store.noticias) || store.noticias.length === 0">
                <tr>
                  <td colspan="7" class="empty-text">No hay noticias registradas.</td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr v-for="noticia in store.noticias" :key="noticia.id_noticia || noticia.id">

                  <!-- ========================================== -->
                  <!-- ✨ NUEVO: COLLAGE DE IMÁGENES E ÍCONOS    -->
                  <!-- ========================================== -->
                  <td class="col-icon text-center">
                    <div v-if="noticia.archivos && noticia.archivos.length > 0" class="file-collage">
                      <span
                        v-for="(archivo, index) in noticia.archivos"
                        :key="archivo.id_archivo || index"
                        class="file-thumb-wrapper"
                        :title="archivo.nombre_archivo"
                        @click="openFileModal(archivo)"
                      >
                        <!-- Si es imagen, mostrar miniatura -->
                        <img
                          v-if="isImage(archivo)"
                          :src="getFileUrl(archivo.ruta_archivo)"
                          class="file-thumb"
                          @error="handleThumbError"
                        />
                        <!-- Si es video, mostrar ícono de video -->
                        <span v-else-if="isVideo(archivo)" class="file-icon-large">🎬</span>
                        <!-- Si es PDF, mostrar ícono de PDF -->
                        <span v-else-if="isPdf(archivo)" class="file-icon-large">📕</span>
                        <!-- Cualquier otro archivo -->
                        <span v-else class="file-icon-large">📄</span>
                      </span>
                    </div>
                    <span v-else class="text-gray-400">-</span>
                  </td>

                  <td class="col-auto font-bold">{{ noticia.titulo || 'Sin título' }}</td>
                  <td class="col-rest text-sm text-gray-600">
                    {{ noticia.resumen ? (noticia.resumen.length > 50 ? noticia.resumen.substring(0, 50) + '...' : noticia.resumen) : '-' }}
                  </td>
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

      <!-- ========================================== -->
      <!-- MODAL PARA VISUALIZAR ARCHIVOS             -->
      <!-- ========================================== -->
      <div v-if="showFileModal" class="modal-overlay" @click.self="closeFileModal">
        <div class="modal-container-file">
          <div class="modal-header-file">
            <h3>{{ selectedFile.nombre_archivo }}</h3>
            <button class="close-btn" @click="closeFileModal">×</button>
          </div>
          <div class="modal-body-file">

            <!-- Renderizado según el tipo de archivo -->
            <div v-if="isImage(selectedFile)" class="file-viewer">
              <img
                :src="getFileUrl(selectedFile.ruta_archivo)"
                :alt="selectedFile.nombre_archivo"
                @error="handleImageError"
              />
              <p v-if="imageLoadError" class="text-error">No se pudo cargar la imagen.</p>
            </div>

            <div v-else-if="isVideo(selectedFile)" class="file-viewer">
              <video controls autoplay class="video-player">
                <source :src="getFileUrl(selectedFile.ruta_archivo)" :type="selectedFile.tipo_mime" />
                Tu navegador no soporta video.
              </video>
            </div>

            <div v-else-if="isPdf(selectedFile)" class="file-viewer">
              <iframe :src="getFileUrl(selectedFile.ruta_archivo)" class="pdf-viewer"></iframe>
              <div class="pdf-download-link">
                <a :href="getFileUrl(selectedFile.ruta_archivo)" target="_blank" class="btn-download-pdf">
                  ⬇️ Descargar PDF
                </a>
              </div>
            </div>

            <div v-else class="file-viewer unknown-file">
              <div class="unknown-icon">📄</div>
              <p>No se puede previsualizar este archivo.</p>
              <a :href="getFileUrl(selectedFile.ruta_archivo)" target="_blank" class="btn-download-unknown">
                ⬇️ Descargar Archivo
              </a>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useNoticiasStore } from '../../stores/noticias.js';
import NoticiaForm from './NoticiasForm.vue';
import CategoriaForm from './CategoriaForm.vue';

const store = useNoticiasStore();
const activeTab = ref('noticias');

// Computed para depuración
const categoriasCount = computed(() => store.categorias?.length || 0);
const noticiasCount = computed(() => store.noticias?.length || 0);

// Estado de los modales (Noticia y Categoría)
const showNoticiaModal = ref(false);
const showCategoriaModal = ref(false);
const selectedNoticia = ref(null);
const selectedCategoria = ref(null);

// ==========================================
// ESTADO DEL MODAL DE ARCHIVOS
// ==========================================
const showFileModal = ref(false);
const selectedFile = ref({});
const imageLoadError = ref(false);

const openFileModal = (archivo) => {
  selectedFile.value = archivo;
  showFileModal.value = true;
  imageLoadError.value = false;
};

const closeFileModal = () => {
  showFileModal.value = false;
  selectedFile.value = {};
  imageLoadError.value = false;
};

const handleImageError = () => {
  imageLoadError.value = true;
};

// Manejar errores de las miniaturas (si la imagen no carga, no se rompe la tabla)
const handleThumbError = (event) => {
  event.target.style.display = 'none'; // Ocultar la miniatura rota
};

// ==========================================
// MÉTODOS PARA ÍCONOS Y TIPOS DE ARCHIVOS
// ==========================================
const getFileIcon = (extension) => {
  if (!extension) return '📄';
  const ext = extension.toLowerCase();

  const videoExts = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];
  if (videoExts.includes(ext)) return '🎬';

  const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
  if (imageExts.includes(ext)) return '🖼️';

  if (ext === 'pdf') return '📕';
  if (['doc', 'docx'].includes(ext)) return '📘';
  if (['xls', 'xlsx'].includes(ext)) return '📗';
  if (ext === 'zip') return '📦';

  return '📄';
};

const isImage = (file) => {
  if (!file || !file.tipo_mime) return false;
  return file.tipo_mime.startsWith('image/');
};

const isVideo = (file) => {
  if (!file || !file.tipo_mime) return false;
  return file.tipo_mime.startsWith('video/');
};

const isPdf = (file) => {
  if (!file || !file.tipo_mime) return false;
  return file.tipo_mime.includes('pdf');
};

// 🔥 RUTA CORRECTA PARA ARCHIVOS
const getFileUrl = (ruta) => {
  if (!ruta) return '#';
  return `/${ruta}`;
};

// ==========================================
// MÉTODOS PARA NOTICIAS
// ==========================================
const openCreateNoticia = () => {
  selectedNoticia.value = null;
  showNoticiaModal.value = true;
};

const openEditNoticia = (noticia) => {
  selectedNoticia.value = noticia;
  showNoticiaModal.value = true;
};

const refreshNoticias = () => {
  store.fetchNoticias();
};

const confirmDeleteNoticia = async (noticia) => {
  if (confirm(`¿Estás seguro de eliminar la noticia "${noticia.titulo}"?`)) {
    await store.deleteNoticia(noticia.id_noticia);
  }
};

// ==========================================
// MÉTODOS PARA CATEGORÍAS
// ==========================================
const openCreateCategoria = () => {
  selectedCategoria.value = null;
  showCategoriaModal.value = true;
};

const openEditCategoria = (cat) => {
  selectedCategoria.value = cat;
  showCategoriaModal.value = true;
};

const refreshCategorias = () => {
  store.fetchCategorias();
};

const confirmDeleteCategoria = async (cat) => {
  if (confirm(`¿Estás seguro de eliminar la categoría "${cat.nombre}"?`)) {
    await store.deleteCategoria(cat.id_categoria);
  }
};

// ==========================================
// UTILIDADES
// ==========================================
const getCategoriaNombre = (id) => {
  if (!id) return 'Sin categoría';
  if (!Array.isArray(store.categorias)) return 'Sin categoría';
  const cat = store.categorias.find(c => c.id_categoria === id);
  return cat ? cat.nombre : 'Sin categoría';
};

const formatDate = (date) => {
  if (!date) return '-';
  try {
    const d = new Date(date);
    if (isNaN(d.getTime())) return '-';
    return d.toLocaleDateString('es-ES', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
  } catch (e) {
    return '-';
  }
};

// ==========================================
// CICLO DE VIDA
// ==========================================
onMounted(async () => {
  try {
    await Promise.all([
      store.fetchNoticias(),
      store.fetchCategorias()
    ]);
  } catch (error) {
    console.error('❌ Error al cargar datos:', error);
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
  white-space: nowrap;
}

.data-table td.col-rest {
  width: 100%;
  max-width: 0;
  white-space: normal;
  word-wrap: break-word;
}

/* Columna para los íconos e imágenes */
.data-table th.col-icon,
.data-table td.col-icon {
  width: 140px; /* Un poco más ancha para el collage */
  text-align: center;
}

.data-table th.col-actions,
.data-table td.col-actions {
  width: 120px;
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

/* Textos auxiliares */
.text-center { text-align: center !important; }
.font-bold { font-weight: 600; }
.text-sm { font-size: 0.875rem; }
.text-gray-400 { color: #9ca3af; }
.text-gray-600 { color: #6b7280; }
.text-error { color: #dc2626; font-weight: bold; margin-top: 10px; }

/* ========================================= */
/* ✨ ESTILOS DEL COLLAGE DE ARCHIVOS        */
/* ========================================= */
.file-collage {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 6px;
}

.file-thumb-wrapper {
  cursor: pointer;
  transition: transform 0.2s;
  display: inline-block;
}

.file-thumb-wrapper:hover {
  transform: scale(1.15);
  z-index: 10;
}

/* Miniaturas para imágenes */
.file-thumb {
  width: 40px;
  height: 40px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid #e5e7eb;
  background: #f9fafb;
}

/* Íconos grandes para PDF, Videos, etc. */
.file-icon-large {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  font-size: 24px;
  border-radius: 6px;
  border: 1px solid #e5e7eb;
  background: #f9fafb;
}

/* ========================================= */

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
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}
.btn-edit:hover {
  background: #ffe4e4;
}
.btn-delete {
  background: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}
.btn-delete:hover {
  background: #ffe4e4;
}

/* ========================================= */
/* ESTILOS DEL MODAL DE ARCHIVOS             */
/* ========================================= */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 99999;
  backdrop-filter: blur(4px);
}

.modal-container-file {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 900px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideIn 0.3s ease;
}

@keyframes slideIn {
  from {
    transform: translateY(-30px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-header-file {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 24px;
  border-bottom: 1px solid #e5e7eb;
  background: #f9fafb;
  border-radius: 12px 12px 0 0;
}

.modal-header-file h3 {
  margin: 0;
  font-size: 16px;
  color: #1a1a2e;
  max-width: 80%;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.close-btn {
  background: none;
  border: none;
  font-size: 28px;
  color: #6b7280;
  cursor: pointer;
  padding: 0 8px;
  transition: color 0.2s;
}

.close-btn:hover {
  color: #cc0000;
}

.modal-body-file {
  flex: 1;
  overflow: auto;
  padding: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #f3f4f6;
  min-height: 300px;
  border-radius: 0 0 12px 12px;
}

.file-viewer {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.file-viewer img {
  max-width: 100%;
  max-height: 70vh;
  object-fit: contain;
  border-radius: 4px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.video-player {
  width: 100%;
  max-height: 70vh;
  border-radius: 4px;
  background: #000;
}

.pdf-viewer {
  width: 100%;
  height: 70vh;
  border: none;
  border-radius: 4px;
}

.pdf-download-link {
  margin-top: 16px;
}

.btn-download-pdf,
.btn-download-unknown {
  display: inline-block;
  padding: 10px 20px;
  background: #cc0000;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-weight: 500;
  transition: background 0.2s;
}

.btn-download-pdf:hover,
.btn-download-unknown:hover {
  background: #a30000;
}

.unknown-file {
  text-align: center;
}
.unknown-icon {
  font-size: 64px;
  margin-bottom: 16px;
}
.unknown-file p {
  color: #6b7280;
  margin-bottom: 20px;
}

/* ========================================= */
/* RESPONSIVE                               */
/* ========================================= */
.loading-text,
.empty-text {
  text-align: center;
  padding: 20px;
  color: #6b7280;
}
</style>
