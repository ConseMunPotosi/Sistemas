<template>
  <div class="official-communications">
    <!-- Header -->
    <header class="communications-header">
      <div class="header-content">
        <div class="header-left">
          <h1 class="header-title">
            <span class="icon">📢</span>
            Comunicados Oficiales
          </h1>
          <p class="header-subtitle">Mantente informado con las últimas noticias y anuncios</p>
        </div>
      </div>
    </header>

    <!-- Filtros y Búsqueda -->
    <section class="filters-section">
      <div class="filters-container">
        <div class="search-wrapper">
          <span class="search-icon">🔍</span>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar comunicados..."
            class="search-input"
          />
        </div>

        <div class="filters-group">
          <select v-model="sortOrder" class="filter-select">
            <option value="desc">Más recientes</option>
            <option value="asc">Más antiguos</option>
          </select>
        </div>
      </div>
    </section>

    <!-- ========================================== -->
    <!-- CONTENIDO CONDICIONAL                      -->
    <!-- ========================================== -->
    <div class="content-container">

      <!-- Estado de Carga -->
      <div v-if="store.loading" class="loading-state">
        <div class="spinner"></div>
        <p>Cargando comunicados...</p>
      </div>

      <!-- Lista de Comunicados -->
      <section v-else-if="filteredCommunications.length > 0" class="communications-list">
        <div class="list-container">
          <div
            v-for="communication in paginatedCommunications"
            :key="communication.id_noticia"
            class="communication-card"
          >
            <!-- ========================================== -->
            <!-- ⚠️ CAMBIO: Si es video, mostramos video     -->
            <!-- ========================================== -->
            <div class="card-image-wrapper">
              <!-- Si es video, se reproduce automáticamente -->
              <video
                v-if="getMainMedia(communication).type === 'video'"
                :src="getMainMedia(communication).url"
                autoplay
                muted
                loop
                controls
                class="card-video"
              ></video>

              <!-- Si es imagen, se muestra normalmente -->
              <img
                v-else
                :src="getMainMedia(communication).url"
                :alt="communication.titulo"
                class="card-image"
                loading="lazy"
              />
            </div>

            <div class="card-content">
              <div class="card-header">
                <div class="card-title-group">
                  <h3 class="card-title">{{ communication.titulo }}</h3>
                </div>
              </div>

              <div class="card-meta">
                <span class="meta-item">
                  <span class="meta-icon">📅</span>
                  {{ formatDate(communication.fecha_creacion) }}
                </span>
              </div>

              <p class="card-summary">{{ communication.resumen || '-' }}</p>

              <div class="card-footer">
                <button
                  class="read-more-btn"
                  @click="toggleExpand(communication.id_noticia)"
                >
                  {{ expandedCommunications[communication.id_noticia] ? '📖 Ver menos' : '📖 Leer más' }}
                </button>
              </div>

              <!-- Contenido expandido -->
              <div v-if="expandedCommunications[communication.id_noticia]" class="card-expanded">
                <div class="expanded-content">
                  <p class="contenido-texto" v-html="communication.contenido?.replace(/\n/g, '<br>')"></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Estado vacío -->
      <div v-else class="empty-state">
        <div class="empty-icon">📭</div>
        <h3 class="empty-title">No hay comunicados</h3>
        <p class="empty-description">
          {{ searchQuery ? 'No se encontraron resultados para tu búsqueda' : 'No hay comunicados disponibles' }}
        </p>
        <button class="btn-primary" @click="resetFilters">
          Limpiar filtros
        </button>
      </div>

    </div>

    <!-- Paginación -->
    <div v-if="totalPages > 1" class="pagination">
      <div class="pagination-info">
        <span class="info-text">
          Mostrando {{ (currentPage - 1) * itemsPerPage + 1 }} -
          {{ Math.min(currentPage * itemsPerPage, filteredCommunications.length) }}
          de {{ filteredCommunications.length }} comunicados
        </span>
      </div>

      <div class="pagination-controls">
        <!-- Primera página -->
        <button
          class="page-btn"
          :disabled="currentPage === 1"
          @click="goToPage(1)"
          title="Primera página"
        >
          ⟪
        </button>

        <!-- Anterior -->
        <button
          class="page-btn"
          :disabled="currentPage === 1"
          @click="currentPage--"
          title="Página anterior"
        >
          ←
        </button>

        <!-- Números de página -->
        <div class="page-numbers">
          <button
            v-for="page in pageNumbers"
            :key="page"
            class="page-num"
            :class="{
              active: page === currentPage,
              dots: page === '...'
            }"
            :disabled="page === '...'"
            @click="page !== '...' && (currentPage = page)"
          >
            {{ page }}
          </button>
        </div>

        <!-- Siguiente -->
        <button
          class="page-btn"
          :disabled="currentPage === totalPages"
          @click="currentPage++"
          title="Página siguiente"
        >
          →
        </button>

        <!-- Última página -->
        <button
          class="page-btn"
          :disabled="currentPage === totalPages"
          @click="goToPage(totalPages)"
          title="Última página"
        >
          ⟫
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useNoticiasStore } from '@/stores/noticias.js'

const props = defineProps({
  initialCommunications: {
    type: Array,
    default: () => []
  }
})

const store = useNoticiasStore()

// 🔥 CAMBIA EL NÚMERO POR EL ID REAL DE TU CATEGORÍA "COMUNICADOS"
const CATEGORIA_COMUNICADOS_ID = 3

// State
const searchQuery = ref('')
const sortOrder = ref('desc')
const currentPage = ref(1)
const itemsPerPage = 5
const expandedCommunications = ref({})

// Computed
const filteredCommunications = computed(() => {
  let filtered = store.noticias ? [...store.noticias] : []

  filtered = filtered.filter(noticia => noticia.id_categoria === CATEGORIA_COMUNICADOS_ID)

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(comm =>
      comm.titulo.toLowerCase().includes(query) ||
      comm.resumen?.toLowerCase().includes(query) ||
      comm.contenido?.toLowerCase().includes(query)
    )
  }

  filtered.sort((a, b) => {
    const dateA = new Date(a.fecha_creacion || a.fecha_publicacion)
    const dateB = new Date(b.fecha_creacion || b.fecha_publicacion)
    return sortOrder.value === 'desc' ? dateB - dateA : dateA - dateB
  })

  return filtered
})

const totalPages = computed(() => {
  return Math.ceil(filteredCommunications.value.length / itemsPerPage)
})

const paginatedCommunications = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredCommunications.value.slice(start, end)
})

const pageNumbers = computed(() => {
  const pages = []
  const total = totalPages.value
  const current = currentPage.value

  if (total <= 5) {
    for (let i = 1; i <= total; i++) pages.push(i)
  } else {
    if (current <= 3) {
      pages.push(1, 2, 3, '...', total)
    } else if (current >= total - 2) {
      pages.push(1, '...', total - 2, total - 1, total)
    } else {
      pages.push(1, '...', current - 1, current, current + 1, '...', total)
    }
  }
  return pages
})

// Methods
const formatDate = (dateString) => {
  if (!dateString) return 'Fecha no disponible'
  try {
    const date = new Date(dateString)
    if (isNaN(date.getTime())) return 'Fecha inválida'
    const day = String(date.getDate()).padStart(2, '0')
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const year = date.getFullYear()
    return `${day}/${month}/${year}`
  } catch {
    return dateString
  }
}

const formatFileSize = (bytes) => {
  if (!bytes) return '0 B'
  const k = 1024
  const sizes = ['B', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const getFileUrl = (ruta) => {
  if (!ruta) return '#'
  return `/${ruta}`
}

// ==========================================
// 🔥 NUEVA FUNCIÓN: getMainMedia
// ==========================================
const getMainMedia = (comunicado) => {
  // Si no tiene archivos, mostrar imagen por defecto
  if (!comunicado.archivos || comunicado.archivos.length === 0) {
    return { type: 'image', url: '/images/default-comunicado.jpg' }
  }

  // Buscar el primer video
  const video = comunicado.archivos.find(a => a.tipo_mime?.startsWith('video/'))
  if (video) {
    return { type: 'video', url: getFileUrl(video.ruta_archivo) }
  }

  // Buscar la primera imagen
  const img = comunicado.archivos.find(a => a.tipo_mime?.startsWith('image/'))
  if (img) {
    return { type: 'image', url: getFileUrl(img.ruta_archivo) }
  }

  // Si no hay ni imagen ni video, mostrar imagen por defecto
  return { type: 'image', url: '/images/default-comunicado.jpg' }
}

const toggleExpand = (id) => {
  expandedCommunications.value[id] = !expandedCommunications.value[id]
}

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
    const container = document.querySelector('.list-container')
    if (container) {
      container.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }
}

const resetFilters = () => {
  searchQuery.value = ''
  sortOrder.value = 'desc'
  currentPage.value = 1
}

watch([searchQuery, sortOrder], () => {
  currentPage.value = 1
})

onMounted(async () => {
  if (props.initialCommunications && props.initialCommunications.length > 0) {
    store.noticias = props.initialCommunications
  } else {
    await store.fetchNoticias()
  }
})
</script>

<style scoped>
/* Estilos Globales */
.official-communications {
  margin: 0 auto;
  padding: 2rem;
  min-height: 100vh;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
}

/* Loading Spinner */
.loading-state {
  text-align: center;
  padding: 4rem 2rem;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 1rem;
}
.spinner {
  display: inline-block;
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #cc0000;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Header */
.communications-header {
  padding: 0.5rem;
  margin-bottom: 2rem;
  text-align: center;
}
.header-content {
  display: flex;
  justify-content: center;
  align-items: center;
}
.header-left { text-align: center; }
.header-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: #cc0000;
  margin: 0 0 0.5rem 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
  line-height: 1.2;
}
.header-title .icon { font-size: 2rem; }
.header-subtitle {
  color: #1a202c;
  font-size: 1.1rem;
  margin: 0;
}

/* Filtros */
.filters-section {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}
.filters-container {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}
.search-wrapper {
  flex: 1;
  min-width: 200px;
  position: relative;
}
.search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  font-size: 1.1rem;
}
.search-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 2.8rem;
  border: 2px solid #e2e8f0;
  border-radius: 0.75rem;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: white;
}
.search-input:focus {
  outline: none;
  border-color: #cc0000;
  box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
}
.filters-group {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}
.filter-select {
  padding: 0.75rem 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 0.75rem;
  font-size: 0.95rem;
  background: white;
  cursor: pointer;
  transition: all 0.3s ease;
  min-width: 140px;
}
.filter-select:focus {
  outline: none;
  border-color: #cc0000;
  box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
}

/* Lista de Comunicados */
.communications-list { margin-top: 2rem; }
.list-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
.communication-card {
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  border: 2px solid #e2e8f0;
  display: flex;
  flex-direction: row;
  min-height: 200px;
}
.communication-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
  border-left-color: #ef4444;
}

/* Imagen del comunicado */
.card-image-wrapper {
  flex: 0 0 280px;
  position: relative;
  overflow: hidden;
  min-height: 200px;
}

/* 🎬 VIDEO AUTOMÁTICO */
.card-video {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.communication-card:hover .card-video {
  transform: scale(1.05);
}

.card-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}
.communication-card:hover .card-image { transform: scale(1.05); }

/* Contenido */
.card-content {
  flex: 1;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
}
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 1rem;
}
.card-title-group {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}
.card-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
  line-height: 1.3;
}
.card-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  margin-bottom: 0.75rem;
}
.meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #64748b;
  font-size: 0.875rem;
}
.meta-icon { font-size: 1rem; }
.card-summary {
  color: #475569;
  line-height: 1.6;
  margin: 0 0 1rem 0;
  flex: 1;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.card-footer {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}
.read-more-btn {
  background: none;
  border: none;
  color: #cc0000;
  font-weight: 600;
  cursor: pointer;
  padding: 0;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}
.read-more-btn:hover {
  color: #8B0000;
  text-decoration: underline;
}
.card-expanded {
  margin-top: 0.75rem;
  padding-top: 0.75rem;
  border-top: 2px solid #f1f5f9;
  animation: slideDown 0.3s ease;
}
@keyframes slideDown {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
.expanded-content p {
  color: #334155;
  line-height: 1.8;
  margin-bottom: 1rem;
}
.attachments {
  margin-top: 1.5rem;
}
.attachments h4 {
  color: #1e293b;
  font-size: 1rem;
  margin-bottom: 0.75rem;
}
.attachment-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
.attachment-list li {
  padding: 0.5rem 0;
  border-bottom: 1px solid #f1f5f9;
}
.attachment-list li:last-child { border-bottom: none; }
.attachment-link {
  color: #3b82f6;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
}
.attachment-link:hover {
  color: #2563eb;
  text-decoration: underline;
}
.file-size {
  color: #94a3b8;
  font-size: 0.8rem;
  margin-left: 0.5rem;
}

/* Estado vacío */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
}
.empty-icon { font-size: 4rem; margin-bottom: 1rem; }
.empty-title {
  font-size: 1.5rem;
  color: #1e293b;
  margin: 0 0 0.5rem 0;
}
.empty-description {
  color: #64748b;
  margin-bottom: 1.5rem;
}
.btn-primary {
  background: linear-gradient(135deg, #cc0000 0%, #8B0000 100%);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(204, 0, 0, 0.3);
}

/* Paginación */
.pagination {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-top: 2rem;
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(10px);
  padding: 1rem 1.5rem;
  border-radius: 1rem;
  align-items: center;
}
.pagination-info {
  width: 100%;
  text-align: center;
}
.info-text {
  color: #4a5568;
  font-size: 0.9rem;
  font-weight: 500;
}
.pagination-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
  justify-content: center;
}
.page-btn {
  padding: 0.5rem 0.75rem;
  border: 2px solid #e2e8f0;
  background: white;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;
  min-width: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.page-btn:hover:not(:disabled) {
  background: #cc0000;
  color: white;
  border-color: #cc0000;
  transform: translateY(-2px);
}
.page-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
  transform: none;
}
.page-numbers {
  display: flex;
  gap: 0.25rem;
  flex-wrap: wrap;
  justify-content: center;
}
.page-num {
  padding: 0.5rem 0.75rem;
  border: 2px solid transparent;
  background: transparent;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;
  min-width: 36px;
  text-align: center;
}
.page-num:hover:not(.active):not(.dots) {
  background: #f7fafc;
  border-color: #e2e8f0;
}
.page-num.active {
  background: #cc0000;
  color: white;
  border-color: #cc0000;
  box-shadow: 0 2px 8px rgba(204, 0, 0, 0.3);
}
.page-num.dots {
  cursor: default;
  color: #a0aec0;
  background: transparent;
}
.page-num.dots:hover {
  background: transparent;
  border-color: transparent;
}

/* Responsive */
@media (max-width: 1024px) {
  .communication-card { flex-direction: column; }
  .card-image-wrapper {
    flex: 0 0 200px;
    width: 100%;
  }
  .card-video {
    width: 100%;
    height: 200px;
  }
  .card-image {
    width: 100%;
    height: 200px;
  }
}

@media (max-width: 768px) {
  .official-communications { padding: 1rem; }
  .header-title { font-size: 1.8rem; }
  .header-subtitle { font-size: 0.95rem; }
  .filters-container { flex-direction: column; }
  .filters-group { flex-direction: column; }
  .filter-select { width: 100%; }
  .card-image-wrapper { flex: 0 0 180px; }
  .card-video { height: 180px; }
  .card-image { height: 180px; }
  .card-title { font-size: 1.1rem; }
  .pagination { padding: 0.75rem; }
  .pagination-controls { gap: 0.3rem; }
  .page-btn {
    padding: 0.35rem 0.5rem;
    min-width: 32px;
    font-size: 0.85rem;
  }
  .page-num {
    padding: 0.35rem 0.5rem;
    min-width: 32px;
    font-size: 0.85rem;
  }
}

@media (max-width: 480px) {
  .header-title { font-size: 1.5rem; }
  .card-image-wrapper { flex: 0 0 150px; }
  .card-video { height: 150px; }
  .card-image { height: 150px; }
  .card-title { font-size: 1rem; }
  .card-meta { flex-direction: column; gap: 0.5rem; }
  .pagination-info .info-text { font-size: 0.8rem; }
}
</style>
