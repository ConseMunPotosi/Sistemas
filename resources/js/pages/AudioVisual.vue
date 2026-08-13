<template>
  <div class="audiovisual-material">
    <!-- Header con título centrado y subtítulo -->
    <div class="av-header">
      <div class="header-content">
        <h2 class="av-title">🎬 Material Audiovisual</h2>
        <p class="av-subtitle">
          Espacio dedicado a la difusión de jingles y spots institucionales del Concejo Municipal de Potosí
        </p>
      </div>
    </div>

    <!-- Pestañas de Selección -->
    <div class="av-tabs">
      <button
        class="tab-btn"
        :class="{ active: activeTab === 'jingles' }"
        @click="activeTab = 'jingles'"
      >
        <span class="tab-icon">🎵</span>
        Jingles
        <span class="tab-count">{{ jingles.length }}</span>
      </button>
      <button
        class="tab-btn"
        :class="{ active: activeTab === 'spots' }"
        @click="activeTab = 'spots'"
      >
        <span class="tab-icon">📺</span>
        Spots
        <span class="tab-count">{{ spots.length }}</span>
      </button>
      <button
        class="tab-btn"
        :class="{ active: activeTab === 'todos' }"
        @click="activeTab = 'todos'"
      >
        <span class="tab-icon">📋</span>
        Todos
        <span class="tab-count">{{ totalItems }}</span>
      </button>
    </div>

    <!-- Filtros -->
    <div class="av-filters">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Buscar por título, descripción o tags..."
        class="search-input"
      />
      <select v-model="sortOrder" class="filter-select">
        <option value="desc">Más recientes</option>
        <option value="asc">Más antiguos</option>
      </select>
      <select v-model="filterStatus" class="filter-select">
        <option value="todos">Todos los estados</option>
        <option value="activo">Activos</option>
        <option value="inactivo">Inactivos</option>
      </select>
    </div>

    <!-- Vista de Grid -->
    <div v-if="filteredItems.length > 0" class="av-grid">
      <div
        v-for="item in paginatedItems"
        :key="item.id"
        class="av-card"
        :class="{
          'is-jingle': item.type === 'jingle',
          'is-spot': item.type === 'spot',
          'inactive': item.status === 'inactivo'
        }"
      >
        <!-- Card Header -->
        <div class="card-header">
          <div class="card-type-badge">
            <span v-if="item.type === 'jingle'">🎵 Jingle</span>
            <span v-else>📺 Spot</span>
          </div>
        </div>

        <!-- Contenido Multimedia -->
        <div class="card-media">
          <div v-if="item.type === 'jingle'" class="audio-player">
            <div class="waveform-placeholder">
              <div class="waveform-bars">
                <span v-for="i in 30" :key="i" class="bar" :style="{ height: getRandomHeight() }"></span>
              </div>
            </div>
            <audio
              v-if="item.audioUrl"
              controls
              class="audio-controls"
              :src="item.audioUrl"
            >
              Tu navegador no soporta el elemento de audio.
            </audio>
            <div v-else class="no-media">
              <span>🔊 Sin audio disponible</span>
            </div>
          </div>

          <div v-else class="video-player">
            <video
              v-if="item.videoUrl"
              controls
              class="video-controls"
              :src="item.videoUrl"
              poster="https://via.placeholder.com/400x225/667eea/ffffff?text=Spot"
            >
              Tu navegador no soporta el elemento de video.
            </video>
            <div v-else class="no-media">
              <span>📹 Sin video disponible</span>
            </div>
          </div>
        </div>

        <!-- Información -->
        <div class="card-info">
          <h3 class="card-title">{{ item.title }}</h3>
          <p class="card-description">{{ item.description }}</p>

          <div class="card-meta">
            <span class="meta-item">
              <span class="meta-icon">📅</span>
              {{ formatDate(item.createdAt) }}
            </span>
            <span class="meta-item">
              Duración:<span class="meta-icon">⏱️</span>
              {{ item.duration || '00:00' }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Estado Vacío -->
    <div v-else class="empty-state">
      <div class="empty-icon">📭</div>
      <h3>No hay materiales disponibles</h3>
      <p>No se encontraron {{ activeTab === 'todos' ? 'materiales' : activeTab }} que coincidan con tu búsqueda.</p>
      <button class="btn-primary" @click="resetFilters">
        Limpiar filtros
      </button>
    </div>

    <!-- Paginación -->
    <div v-if="totalPages > 1" class="pagination">
      <div class="pagination-info">
        <span class="info-text">
          Mostrando {{ (currentPage - 1) * itemsPerPage + 1 }} -
          {{ Math.min(currentPage * itemsPerPage, filteredItems.length) }}
          de {{ filteredItems.length }} materiales
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

// Props
const props = defineProps({
  initialItems: {
    type: Array,
    default: () => []
  }
})

// State
const items = ref([])
const activeTab = ref('todos')
const searchQuery = ref('')
const sortOrder = ref('desc')
const filterStatus = ref('todos')
const currentPage = ref(1)
const itemsPerPage = 8

// Computed
const jingles = computed(() => {
  return items.value.filter(item => item.type === 'jingle')
})

const spots = computed(() => {
  return items.value.filter(item => item.type === 'spot')
})

const totalItems = computed(() => {
  return items.value.length
})

const filteredItems = computed(() => {
  let filtered = [...items.value]

  // Filtrar por tipo
  if (activeTab.value === 'jingles') {
    filtered = filtered.filter(item => item.type === 'jingle')
  } else if (activeTab.value === 'spots') {
    filtered = filtered.filter(item => item.type === 'spot')
  }

  // Filtrar por búsqueda
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(item =>
      item.title.toLowerCase().includes(query) ||
      item.description.toLowerCase().includes(query) ||
      (item.tags && item.tags.some(tag => tag.toLowerCase().includes(query)))
    )
  }

  // Filtrar por estado
  if (filterStatus.value !== 'todos') {
    filtered = filtered.filter(item =>
      item.status === filterStatus.value
    )
  }

  // Ordenamiento
  filtered.sort((a, b) => {
    const dateA = new Date(a.createdAt)
    const dateB = new Date(b.createdAt)
    return sortOrder.value === 'desc' ? dateB - dateA : dateA - dateB
  })

  return filtered
})

const totalPages = computed(() => {
  return Math.ceil(filteredItems.value.length / itemsPerPage)
})

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredItems.value.slice(start, end)
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
const getRandomHeight = () => {
  return `${Math.random() * 30 + 10}px`
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
    const container = document.querySelector('.av-grid')
    if (container) {
      container.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }
}

const resetFilters = () => {
  searchQuery.value = ''
  sortOrder.value = 'desc'
  filterStatus.value = 'todos'
  activeTab.value = 'todos'
  currentPage.value = 1
}

// Datos de ejemplo
const loadItems = () => {
  items.value = [
    {
      id: '1',
      type: 'jingle',
      title: 'Jingle Corporativo 2024',
      description: 'Melodía institucional para todas las campañas de la empresa',
      duration: '00:30',
      tags: ['corporativo', 'melodia', '2024'],
      audioUrl: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
      videoUrl: '',
      status: 'activo',
      createdAt: '2024-12-10T10:00:00Z'
    },
    {
      id: '2',
      type: 'spot',
      title: 'Spot Publicitario Navidad',
      description: 'Campaña navideña para televisión y redes sociales',
      duration: '00:45',
      tags: ['navidad', 'publicidad', 'tv'],
      audioUrl: '',
      videoUrl: 'https://www.w3schools.com/html/mov_bbb.mp4',
      status: 'activo',
      createdAt: '2024-12-05T15:30:00Z'
    },
    {
      id: '3',
      type: 'jingle',
      title: 'Jingle Promocional Verano',
      description: 'Música para promociones de temporada veraniega',
      duration: '00:20',
      tags: ['verano', 'promocion', 'alegre'],
      audioUrl: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3',
      videoUrl: '',
      status: 'activo',
      createdAt: '2024-11-20T09:15:00Z'
    },
    {
      id: '4',
      type: 'spot',
      title: 'Spot Lanzamiento Producto XYZ',
      description: 'Video promocional para el lanzamiento del nuevo producto',
      duration: '01:00',
      tags: ['lanzamiento', 'producto', 'innovacion'],
      audioUrl: '',
      videoUrl: 'https://www.w3schools.com/html/mov_bbb.mp4',
      status: 'inactivo',
      createdAt: '2024-11-10T14:20:00Z'
    },
    {
      id: '5',
      type: 'jingle',
      title: 'Jingle de Fin de Año',
      description: 'Música festiva para las celebraciones de fin de año',
      duration: '00:25',
      tags: ['navidad', 'fin de año', 'festivo'],
      audioUrl: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3',
      videoUrl: '',
      status: 'activo',
      createdAt: '2024-12-20T08:00:00Z'
    },
    {
      id: '6',
      type: 'spot',
      title: 'Spot de Seguridad Vial',
      description: 'Campaña de concientización sobre seguridad vial',
      duration: '00:30',
      tags: ['seguridad', 'vial', 'campana'],
      audioUrl: '',
      videoUrl: 'https://www.w3schools.com/html/mov_bbb.mp4',
      status: 'activo',
      createdAt: '2024-12-15T11:00:00Z'
    }
  ]
}

// Watch para reiniciar página al cambiar filtros
watch([searchQuery, activeTab, filterStatus, sortOrder], () => {
  currentPage.value = 1
})

// Lifecycle
onMounted(() => {
  if (props.initialItems && props.initialItems.length > 0) {
    items.value = props.initialItems
  } else {
    loadItems()
  }
})
</script>

<style scoped>
/* Estilos principales */
.audiovisual-material {
  margin: 0 auto;
  padding: 2rem;
  min-height: 100vh;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
}

/* Header con título centrado */
.av-header {
  margin-bottom: 2.5rem;
  text-align: center;
}

.header-content {
  max-width: 90%;
  margin: 0 auto;
}

.av-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: #cc0000;
  margin: 0 0 0.5rem 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
}

.av-subtitle {
  font-size: 1.1rem;
  color: #1a202c;
  max-width: 90%;
  margin: 0 auto;
  text-align: justify;
  line-height: 1.6;
}

/* Tabs */
.av-tabs {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 2rem;
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(10px);
  padding: 0.5rem;
  border-radius: 1rem;
}

.tab-btn {
  flex: 1;
  padding: 0.75rem 1.5rem;
  border: none;
  background: transparent;
  border-radius: 0.75rem;
  font-weight: 600;
  color: #4a5568;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.tab-btn:hover {
  background: rgba(204, 0, 0, 0.05);
}

.tab-btn.active {
  background: white;
  color: #cc0000;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.tab-icon {
  font-size: 1.2rem;
}

.tab-count {
  background: #e2e8f0;
  padding: 0.1rem 0.6rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.tab-btn.active .tab-count {
  background: #cc0000;
  color: white;
}

/* Filtros */
.av-filters {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(10px);
  padding: 1rem;
  border-radius: 1rem;
}

.search-input,
.filter-select {
  padding: 0.75rem 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 0.5rem;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: white;
}

.search-input {
  flex: 1;
  min-width: 200px;
}

.search-input:focus,
.filter-select:focus {
  outline: none;
  border-color: #cc0000;
  box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
}

.filter-select {
  min-width: 150px;
}

/* Grid */
.av-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

/* Card */
.av-card {
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  border: 2px solid transparent;
  border-color: #a0aec0;
}

.av-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.av-card.inactive {
  opacity: 0.6;
}

/* Card Header */
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 1.25rem;
  background: rgba(247, 250, 252, 0.5);
  border-bottom: 1px solid #e2e8f0;
}

.card-type-badge {
  font-weight: 600;
  font-size: 0.85rem;
}

.is-jingle .card-type-badge {
  color: #48bb78;
}

.is-spot .card-type-badge {
  color: #4299e1;
}

/* Card Media */
.card-media {
  padding: 1rem;
  background: #1a202c;
  min-height: 150px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.waveform-placeholder {
  width: 100%;
  padding: 0.5rem;
}

.waveform-bars {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 2px;
  height: 40px;
}

.bar {
  width: 4px;
  background: linear-gradient(to top, #bb4848, #cc0000);
  border-radius: 2px;
  animation: wave 1s ease-in-out infinite;
}

.bar:nth-child(odd) {
  animation-delay: 0.2s;
}

@keyframes wave {
  0%, 100% {
    transform: scaleY(1);
  }
  50% {
    transform: scaleY(0.5);
  }
}

.audio-controls,
.video-controls {
  width: 100%;
  border-radius: 0.5rem;
}

.video-controls {
  max-height: 200px;
}

.no-media {
  color: #a0aec0;
  font-size: 0.9rem;
  text-align: center;
}

/* Card Info */
.card-info {
  padding: 1.25rem;
}

.card-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1a202c;
  margin: 0 0 0.5rem 0;
  line-height: 1.3;
}

.card-description {
  color: #4a5568;
  font-size: 0.95rem;
  line-height: 1.6;
  margin: 0 0 1rem 0;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.card-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-top: 0.75rem;
}

.tag {
  background: #edf2f7;
  color: #4a5568;
  padding: 0.15rem 0.6rem;
  border-radius: 9999px;
  font-size: 0.7rem;
  font-weight: 500;
}

.card-meta {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  align-items: center;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  color: #718096;
  font-size: 0.8rem;
}

.meta-item.activo .status-dot {
  background: #48bb78;
}

.meta-item.inactivo .status-dot {
  background: #fc8181;
}

.status-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  display: inline-block;
}

/* Estado Vacío */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.empty-state h3 {
  color: #2d3748;
  margin: 0 0 0.5rem 0;
}

.empty-state p {
  color: #718096;
  margin: 0 0 1.5rem 0;
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
@media (max-width: 768px) {
  .audiovisual-material {
    padding: 1rem;
  }

  .av-title {
    font-size: 1.8rem;
  }

  .av-subtitle {
    font-size: 0.95rem;
    max-width: 100%;
  }

  .av-tabs {
    flex-direction: column;
  }

  .av-filters {
    flex-direction: column;
    padding: 0.75rem;
  }

  .search-input,
  .filter-select {
    width: 100%;
  }

  .av-grid {
    grid-template-columns: 1fr;
  }

  .pagination {
    padding: 0.75rem;
  }

  .pagination-controls {
    gap: 0.3rem;
  }

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
  .av-title {
    font-size: 1.5rem;
  }

  .av-subtitle {
    font-size: 0.85rem;
  }

  .card-title {
    font-size: 1rem;
  }

  .pagination-info .info-text {
    font-size: 0.8rem;
  }
}
</style>
