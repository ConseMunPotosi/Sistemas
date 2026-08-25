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
                  <th class="col-rest">Resumen</th>
                  <th class="col-auto">Fecha Pub.</th>
                  <th class="col-actions">Acciones</th>
                </tr>
              </thead>
              <tbody v-if="!store.noticias || !Array.isArray(store.noticias) || store.noticias.length === 0">
                <tr>
                  <td colspan="4" class="empty-text">No hay noticias registradas.</td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr v-for="noticia in store.noticias" :key="noticia.id_noticia || noticia.id">

                  <!-- ========================================== -->
                  <!-- 📁 ÍCONO ÚNICO REPRESENTATIVO             -->
                  <!-- ========================================== -->
                  <td class="col-icon text-center">
                    <div
                      v-if="noticia.archivos && noticia.archivos.length > 0"
                      class="file-icon-wrapper"
                      @click="openGalleryModal(noticia)"
                      title="Ver archivos adjuntos"
                    >
                      <span class="file-badge-icon">
                        {{ getFolderIcon(noticia.archivos) }}
                      </span>
                      <span class="file-count-badge">{{ noticia.archivos.length }}</span>
                    </div>
                    <span v-else class="text-gray-400">-</span>
                  </td>

                  <!-- ========================================== -->
                  <!-- 📌 NUEVA COLUMNA: NOTICIA (Título + Badges) -->
                  <!-- ========================================== -->
                  <td class="col-rest">
                    <div class="noticia-content">
                      <div class="noticia-header">
                        <span class="font-bold">{{ noticia.titulo || 'Sin título' }}</span>
                        <span class="status-badge" :class="noticia.estado_publicacion || 'borrador'">
                          {{ noticia.estado_publicacion || 'Borrador' }}
                        </span>
                      </div>
                      <div class="noticia-footer">
                        <span class="categoria-badge">{{ getCategoriaNombre(noticia.id_categoria) }}</span>
                        <span class="text-sm text-gray-600 truncate">{{ noticia.resumen || '-' }}</span>
                      </div>
                    </div>
                  </td>

                  <!-- ========================================== -->
                  <!-- 📅 FECHA DE PUBLICACIÓN                   -->
                  <!-- ========================================== -->
                  <td class="col-auto">{{ formatDate(noticia.fecha_publicacion) }}</td>

                  <!-- ========================================== -->
                  <!-- ⚙️ ACCIONES                              -->
                  <!-- ========================================== -->
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

      <!-- MODALS -->
      <NoticiaForm v-model:show="showNoticiaModal" :noticia="selectedNoticia" @saved="refreshNoticias" />
      <CategoriaForm v-model:show="showCategoriaModal" :categoria="selectedCategoria" @saved="refreshCategorias" />

      <!-- GALERÍA DE ARCHIVOS -->
      <div v-if="showGalleryModal" class="modal-overlay" @click.self="closeGalleryModal">
        <div class="modal-container-file">
          <div class="modal-header-file">
            <h3>📂 Archivos de: {{ galleryNoticia.titulo }}</h3>
            <button class="close-btn" @click="closeGalleryModal">×</button>
          </div>
          <div class="modal-body-file gallery-mode">
            <div v-if="galleryNoticia.archivos && galleryNoticia.archivos.length > 0" class="gallery-grid">
              <div v-for="(archivo, index) in galleryNoticia.archivos" :key="archivo.id_archivo || index" class="gallery-item">
                <div v-if="isImage(archivo)" class="gallery-image-wrapper" @click="openSingleFileModal(archivo)">
                  <img :src="getFileUrl(archivo.ruta_archivo)" :alt="archivo.nombre_archivo" class="gallery-img" />
                  <div class="gallery-overlay"><span>🖼️ Ver imagen</span></div>
                </div>
                <div v-else-if="isVideo(archivo)" class="gallery-media-wrapper" @click="openSingleFileModal(archivo)">
                  <div class="gallery-icon-large">🎬</div>
                  <div class="gallery-filename">{{ archivo.titulo }}</div>
                  <div class="gallery-overlay"><span>▶️ Reproducir</span></div>
                </div>
                <div v-else-if="isPdf(archivo)" class="gallery-media-wrapper" @click="openSingleFileModal(archivo)">
                  <div class="gallery-icon-large">📕</div>
                  <div class="gallery-filename">{{ archivo.nombre_archivo }}</div>
                  <div class="gallery-overlay"><span>📄 Ver PDF</span></div>
                </div>
                <div v-else class="gallery-media-wrapper" @click="openSingleFileModal(archivo)">
                  <div class="gallery-icon-large">📄</div>
                  <div class="gallery-filename">{{ archivo.nombre_archivo }}</div>
                  <div class="gallery-overlay"><span>⬇️ Descargar</span></div>
                </div>
              </div>
            </div>
            <div v-else class="empty-text">Esta noticia no tiene archivos.</div>
          </div>
        </div>
      </div>

      <!-- MODAL INDIVIDUAL -->
      <div v-if="showSingleFileModal" class="modal-overlay" @click.self="closeSingleFileModal">
        <div class="modal-container-file">
          <div class="modal-header-file">
            <h3>{{ singleSelectedFile.tituloNoticia || singleSelectedFile.nombre_archivo }}</h3>
            <button class="close-btn" @click="closeSingleFileModal">×</button>
          </div>
          <div class="modal-body-file">
            <div v-if="isImage(singleSelectedFile)" class="file-viewer">
              <img :src="getFileUrl(singleSelectedFile.ruta_archivo)" :alt="singleSelectedFile.nombre_archivo" />
            </div>
            <div v-else-if="isVideo(singleSelectedFile)" class="file-viewer">
              <video controls autoplay class="video-player">
                <source :src="getFileUrl(singleSelectedFile.ruta_archivo)" :type="singleSelectedFile.tipo_mime" />
              </video>
            </div>
            <div v-else-if="isPdf(singleSelectedFile)" class="file-viewer">
              <iframe :src="getFileUrl(singleSelectedFile.ruta_archivo)" class="pdf-viewer"></iframe>
              <div class="pdf-download-link">
                <a :href="getFileUrl(singleSelectedFile.ruta_archivo)" target="_blank" class="btn-download-pdf">⬇️ Descargar PDF</a>
              </div>
            </div>
            <div v-else class="file-viewer unknown-file">
              <div class="unknown-icon">📄</div>
              <p>No se puede previsualizar.</p>
              <a :href="getFileUrl(singleSelectedFile.ruta_archivo)" target="_blank" class="btn-download-unknown">⬇️ Descargar</a>
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

const categoriasCount = computed(() => store.categorias?.length || 0);
const noticiasCount = computed(() => store.noticias?.length || 0);

const showNoticiaModal = ref(false);
const showCategoriaModal = ref(false);
const selectedNoticia = ref(null);
const selectedCategoria = ref(null);

// ==========================================
// ESTADO: GALERÍA Y ARCHIVO INDIVIDUAL
// ==========================================
const showGalleryModal = ref(false);
const galleryNoticia = ref({});
const showSingleFileModal = ref(false);
const singleSelectedFile = ref({});

const openGalleryModal = (noticia) => {
  galleryNoticia.value = noticia;
  showGalleryModal.value = true;
};
const closeGalleryModal = () => {
  showGalleryModal.value = false;
  galleryNoticia.value = {};
};
const openSingleFileModal = (archivo) => {
  // 🔥 CAMBIO AQUÍ: Guardamos el título de la noticia en el archivo seleccionado
  singleSelectedFile.value = {
    ...archivo,
    tituloNoticia: galleryNoticia.value.titulo || ''
  };
  showSingleFileModal.value = true;
};
const closeSingleFileModal = () => {
  showSingleFileModal.value = false;
  singleSelectedFile.value = {};
};

// ==========================================
// MÉTODOS PARA ÍCONOS Y TIPOS DE ARCHIVOS
// ==========================================
const getFileIcon = (extension) => {
  if (!extension) return '📄';
  const ext = extension.toLowerCase();
  if (['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'].includes(ext)) return '🎬';
  if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(ext)) return '🖼️';
  if (ext === 'pdf') return '📕';
  if (['doc', 'docx'].includes(ext)) return '📘';
  if (['xls', 'xlsx'].includes(ext)) return '📗';
  if (ext === 'zip') return '📦';
  return '📄';
};

const getFolderIcon = (archivos) => {
  if (!archivos || archivos.length === 0) return '📁';
  if (archivos.some(a => a.tipo_mime?.startsWith('image/'))) return '🖼️';
  if (archivos.some(a => a.tipo_mime?.startsWith('video/'))) return '🎬';
  if (archivos.some(a => a.tipo_mime?.includes('pdf'))) return '📕';
  return '📁';
};

const isImage = (file) => file?.tipo_mime?.startsWith('image/') || false;
const isVideo = (file) => file?.tipo_mime?.startsWith('video/') || false;
const isPdf = (file) => file?.tipo_mime?.includes('pdf') || false;

const getFileUrl = (ruta) => {
  if (!ruta) return '#';
  return `/${ruta}`;
};

// ==========================================
// MÉTODOS PRINCIPALES
// ==========================================
const openCreateNoticia = () => { selectedNoticia.value = null; showNoticiaModal.value = true; };
const openEditNoticia = (noticia) => { selectedNoticia.value = noticia; showNoticiaModal.value = true; };
const refreshNoticias = () => { store.fetchNoticias(); };
const confirmDeleteNoticia = async (noticia) => {
  if (confirm(`¿Eliminar "${noticia.titulo}"?`)) await store.deleteNoticia(noticia.id_noticia);
};

const openCreateCategoria = () => { selectedCategoria.value = null; showCategoriaModal.value = true; };
const openEditCategoria = (cat) => { selectedCategoria.value = cat; showCategoriaModal.value = true; };
const refreshCategorias = () => { store.fetchCategorias(); };
const confirmDeleteCategoria = async (cat) => {
  if (confirm(`¿Eliminar "${cat.nombre}"?`)) await store.deleteCategoria(cat.id_categoria);
};

// ==========================================
// UTILIDADES
// ==========================================
const getCategoriaNombre = (id) => {
  if (!id) return 'Sin categoría';
  const cat = store.categorias.find(c => c.id_categoria === id);
  return cat ? cat.nombre : 'Sin categoría';
};

const formatDate = (date) => {
  if (!date) return '-';
  try {
    const d = new Date(date);
    return isNaN(d.getTime()) ? '-' : d.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
  } catch { return '-'; }
};

// ==========================================
// CICLO DE VIDA
// ==========================================
onMounted(async () => {
  try { await Promise.all([store.fetchNoticias(), store.fetchCategorias()]); }
  catch (error) { console.error('❌ Error al cargar datos:', error); }
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
.manager-header h2 { margin: 0; font-size: 20px; color: #1a1a2e; }

.btn-primary {
  padding: 8px 20px;
  background: #cc0000;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.btn-primary:hover { background: #a30000; }

/* Tabs */
.tabs-container { display: flex; border-bottom: 2px solid #eee; margin-bottom: 20px; }
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
.tab-btn:hover { color: #1a1a2e; }
.tab-btn.active { color: #cc0000; }
.tab-btn.active::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  right: 0;
  height: 2px;
  background: #cc0000;
}

/* ========================================= */
/* NUEVO ESTILO DE TABLA MÁS LIMPIO          */
/* ========================================= */
.table-wrapper { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; table-layout: auto; }
.data-table th, .data-table td {
  padding: 14px 16px;
  text-align: left;
  border-bottom: 1px solid #eee;
  vertical-align: middle;
}

.data-table th.col-auto, .data-table td.col-auto { width: auto; white-space: nowrap; }
.data-table td.col-rest { width: 100%; max-width: 0; white-space: normal; word-wrap: break-word; }

.data-table th.col-icon, .data-table td.col-icon { width: 100px; text-align: center; }
.data-table th.col-actions, .data-table td.col-actions { width: 120px; text-align: center; }
.data-table th { background: #f9fafb; font-weight: 600; color: #374151; }
.data-table tr:hover { background: #f9fafb; }

/* Textos auxiliares */
.text-center { text-align: center !important; }
.font-bold { font-weight: 600; }
.text-sm { font-size: 0.875rem; }
.text-gray-400 { color: #9ca3af; }
.text-gray-600 { color: #6b7280; }
.truncate { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 400px; display: inline-block; }

/* 📁 ÍCONO ÚNICO DE ARCHIVOS */
.file-icon-wrapper {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  position: relative;
  transition: transform 0.2s;
  padding: 8px;
  border-radius: 8px;
}
.file-icon-wrapper:hover { transform: scale(1.1); background: #f3f4f6; }
.file-badge-icon { font-size: 32px; }
.file-count-badge {
  position: absolute;
  top: -6px;
  right: -6px;
  background: #cc0000;
  color: white;
  font-size: 11px;
  font-weight: bold;
  width: 22px;
  height: 22px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* 📌 CONTENIDO DE LA NOTICIA (Título + Badges) */
.noticia-content {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.noticia-header {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}
.noticia-footer {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

/* 🏷️ BADGE DE CATEGORÍA (Color único) */
.categoria-badge {
  display: inline-block;
  padding: 2px 10px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 500;
  background: #e0f2fe;      /* Azul claro */
  color: #0369a1;           /* Azul oscuro */
  border: 1px solid #bae6fd;
}

/* 📊 BADGE DE ESTADO (Colores según estado) */
.status-badge {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
}
.status-badge.borrador {
  background: #fef2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
}
.status-badge.programado {
  background: #ffedd5;
  color: #c2410c;
  border: 1px solid #fed7aa;
}
.status-badge.publicado {
  background: #dcfce7;
  color: #166534;
  border: 1px solid #bbf7d0;
}
.status-badge.active {
  background: #dcfce7;
  color: #166534;
  border: 1px solid #bbf7d0;
}
.status-badge.inactive {
  background: #fef2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

/* ⚙️ ACCIONES */
.action-buttons {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-height: 60px;
  padding: 4px 10px;
}
.btn-edit { background: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn-edit:hover { background: #ffe4e4; }
.btn-delete { background: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn-delete:hover { background: #ffe4e4; }

/* ========================================= */
/* MODAL DE ARCHIVOS                          */
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
  from { transform: translateY(-30px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
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
.modal-header-file h3 { margin: 0; font-size: 16px; color: #1a1a2e; max-width: 80%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.close-btn { background: none; border: none; font-size: 28px; color: #6b7280; cursor: pointer; padding: 0 8px; transition: color 0.2s; }
.close-btn:hover { color: #cc0000; }

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

.gallery-mode { display: block !important; padding: 20px; overflow-y: auto; }
.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 16px;
  width: 100%;
}

.gallery-item {
  position: relative;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid #e5e7eb;
  background: white;
  cursor: pointer;
  aspect-ratio: 1 / 1;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.gallery-item:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }

.gallery-image-wrapper, .gallery-media-wrapper {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: #f3f4f6;
  position: relative;
}
.gallery-img { width: 100%; height: 100%; object-fit: cover; }
.gallery-icon-large { font-size: 48px; margin-bottom: 8px; }
.gallery-filename { font-size: 12px; text-align: center; padding: 0 8px; color: #374151; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100%; }
.gallery-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0;
  transition: opacity 0.3s ease;
  color: white;
  font-weight: 500;
}
.gallery-item:hover .gallery-overlay { opacity: 1; }

.file-viewer { width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; }
.file-viewer img { max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.video-player { width: 100%; max-height: 70vh; border-radius: 4px; background: #000; }
.pdf-viewer { width: 100%; height: 70vh; border: none; border-radius: 4px; }
.pdf-download-link { margin-top: 16px; }
.btn-download-pdf, .btn-download-unknown { display: inline-block; padding: 10px 20px; background: #cc0000; color: white; text-decoration: none; border-radius: 6px; font-weight: 500; transition: background 0.2s; }
.btn-download-pdf:hover, .btn-download-unknown:hover { background: #a30000; }
.unknown-file { text-align: center; }
.unknown-icon { font-size: 64px; margin-bottom: 16px; }
.unknown-file p { color: #6b7280; margin-bottom: 20px; }

/* ========================================= */
/* RESPONSIVE                                */
/* ========================================= */
@media (max-width: 768px) {
  .data-table th.col-icon, .data-table td.col-icon { width: 80px; }
  .gallery-grid { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); }
}

.loading-text, .empty-text { text-align: center; padding: 20px; color: #6b7280; }
</style>
