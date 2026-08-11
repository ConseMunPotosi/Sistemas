<template>
  <div class="audiovisual-material">
    <!-- Header -->
    <div class="av-header">
      <h2 class="av-title">🎬 Material Audiovisual</h2>
      <div class="av-actions">
        <button v-if="isAdmin" class="btn-primary" @click="openCreateModal">
          <span>+ Nuevo Material</span>
        </button>
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
      <select v-model="selectedCategory" class="filter-select">
        <option value="">Todas las categorías</option>
        <option v-for="cat in categories" :key="cat" :value="cat">
          {{ cat }}
        </option>
      </select>
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
          <div v-if="isAdmin" class="card-actions">
            <button class="action-btn" @click="openEditModal(item)" title="Editar">
              ✏️
            </button>
            <button class="action-btn" @click="confirmDelete(item.id)" title="Eliminar">
              🗑️
            </button>
            <button class="action-btn" @click="toggleStatus(item.id)" title="Cambiar estado">
              {{ item.status === 'activo' ? '⏸️' : '▶️' }}
            </button>
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

          <div class="card-tags">
            <span v-for="tag in item.tags" :key="tag" class="tag">
              #{{ tag }}
            </span>
          </div>

          <div class="card-meta">
            <span class="meta-item">
              <span class="meta-icon">📅</span>
              {{ formatDate(item.createdAt) }}
            </span>
            <span class="meta-item">
              <span class="meta-icon">🏷️</span>
              {{ item.category }}
            </span>
            <span class="meta-item" :class="item.status">
              <span class="status-dot"></span>
              {{ item.status }}
            </span>
          </div>

          <div class="card-stats">
            <span class="stat-item">
              <span class="stat-icon">👁️</span>
              {{ item.views || 0 }} vistas
            </span>
            <span class="stat-item">
              <span class="stat-icon">❤️</span>
              {{ item.likes || 0 }} likes
            </span>
            <span class="stat-item">
              <span class="stat-icon">⏱️</span>
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
      <button v-if="isAdmin" class="btn-primary" @click="openCreateModal">
        Crear nuevo material
      </button>
    </div>

    <!-- Paginación -->
    <div v-if="totalPages > 1" class="pagination">
      <button
        class="page-btn"
        :disabled="currentPage === 1"
        @click="currentPage--"
      >
        ← Anterior
      </button>
      <div class="page-numbers">
        <button
          v-for="page in pageNumbers"
          :key="page"
          class="page-num"
          :class="{ active: page === currentPage }"
          @click="currentPage = page"
        >
          {{ page }}
        </button>
      </div>
      <button
        class="page-btn"
        :disabled="currentPage === totalPages"
        @click="currentPage++"
      >
        Siguiente →
      </button>
    </div>

    <!-- Modal de Creación/Edición -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content modal-large">
        <div class="modal-header">
          <h2>{{ editingItem.id ? 'Editar Material' : 'Nuevo Material' }}</h2>
          <button class="close-btn" @click="closeModal">✕</button>
        </div>

        <form @submit.prevent="saveItem" class="modal-form">
          <div class="form-row">
            <div class="form-group">
              <label>Tipo de Material *</label>
              <div class="type-selector">
                <button
                  type="button"
                  class="type-btn"
                  :class="{ active: editingItem.type === 'jingle' }"
                  @click="editingItem.type = 'jingle'"
                >
                  🎵 Jingle
                </button>
                <button
                  type="button"
                  class="type-btn"
                  :class="{ active: editingItem.type === 'spot' }"
                  @click="editingItem.type = 'spot'"
                >
                  📺 Spot
                </button>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="item-title">Título *</label>
            <input
              id="item-title"
              v-model="editingItem.title"
              type="text"
              required
              placeholder="Título del material"
            />
          </div>

          <div class="form-group">
            <label for="item-description">Descripción</label>
            <textarea
              id="item-description"
              v-model="editingItem.description"
              rows="3"
              placeholder="Descripción detallada del material"
            />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="item-category">Categoría *</label>
              <select id="item-category" v-model="editingItem.category" required>
                <option value="">Selecciona una categoría</option>
                <option v-for="cat in categories" :key="cat" :value="cat">
                  {{ cat }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label for="item-duration">Duración</label>
              <input
                id="item-duration"
                v-model="editingItem.duration"
                type="text"
                placeholder="00:30"
              />
            </div>
          </div>

          <div class="form-group">
            <label for="item-tags">Tags (separados por coma)</label>
            <input
              id="item-tags"
              v-model="tagsInput"
              type="text"
              placeholder="ejemplo: musica, promocion, verano"
            />
          </div>

          <div v-if="editingItem.type === 'jingle'" class="form-group">
            <label for="item-audio">URL del Audio</label>
            <input
              id="item-audio"
              v-model="editingItem.audioUrl"
              type="url"
              placeholder="https://ejemplo.com/audio.mp3"
            />
            <small class="form-hint">Formatos soportados: MP3, WAV, OGG</small>
          </div>

          <div v-else class="form-group">
            <label for="item-video">URL del Video</label>
            <input
              id="item-video"
              v-model="editingItem.videoUrl"
              type="url"
              placeholder="https://ejemplo.com/video.mp4"
            />
            <small class="form-hint">Formatos soportados: MP4, WebM, OGG</small>
          </div>

          <div class="form-group">
            <label for="item-status">Estado</label>
            <select id="item-status" v-model="editingItem.status">
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>

          <div class="form-actions">
            <button type="button" class="btn-secondary" @click="closeModal">
              Cancelar
            </button>
            <button type="submit" class="btn-primary">
              {{ editingItem.id ? 'Actualizar' : 'Crear' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal de Confirmación -->
    <div v-if="showDeleteConfirm" class="confirm-overlay" @click.self="showDeleteConfirm = false">
      <div class="confirm-dialog">
        <div class="confirm-icon">⚠️</div>
        <h3 class="confirm-title">Confirmar eliminación</h3>
        <p class="confirm-message">¿Estás seguro de eliminar este material audiovisual?</p>
        <p class="confirm-sub-message">Esta acción no se puede deshacer.</p>
        <div class="confirm-actions">
          <button class="btn-cancel" @click="showDeleteConfirm = false">
            Cancelar
          </button>
          <button class="btn-confirm" @click="deleteItem">
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// Props
const props = defineProps({
  isAdmin: {
    type: Boolean,
    default: false
  },
  initialItems: {
    type: Array,
    default: () => []
  }
})

// State
const items = ref(props.initialItems)
const activeTab = ref('todos')
const searchQuery = ref('')
const selectedCategory = ref('')
const sortOrder = ref('desc')
const filterStatus = ref('todos')
const currentPage = ref(1)
const itemsPerPage = 8
const showModal = ref(false)
const showDeleteConfirm = ref(false)
const deleteTargetId = ref(null)
const tagsInput = ref('')

// Estado para el formulario
const editingItem = ref({
  id: null,
  type: 'jingle',
  title: '',
  description: '',
  category: '',
  duration: '00:30',
  tags: [],
  audioUrl: '',
  videoUrl: '',
  status: 'activo',
  views: 0,
  likes: 0,
  createdAt: new Date().toISOString()
})

// Computed
const categories = computed(() => {
  return ['Comercial', 'Institucional', 'Promocional', 'Evento', 'Campaña', 'Otros']
})

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
      item.tags.some(tag => tag.toLowerCase().includes(query))
    )
  }

  // Filtrar por categoría
  if (selectedCategory.value) {
    filtered = filtered.filter(item =>
      item.category === selectedCategory.value
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

const loadItems = async () => {
  try {
    // Simular carga de API
    const response = await fetch('/api/audiovisual-items')
    const data = await response.json()
    items.value = data
  } catch (error) {
    console.error('Error loading items:', error)
    // Datos de ejemplo
    items.value = [
      {
        id: '1',
        type: 'jingle',
        title: 'Jingle Corporativo 2024',
        description: 'Melodía institucional para todas las campañas de la empresa',
        category: 'Institucional',
        duration: '00:30',
        tags: ['corporativo', 'melodia', '2024'],
        audioUrl: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
        videoUrl: '',
        status: 'activo',
        views: 1250,
        likes: 89,
        createdAt: '2024-12-10T10:00:00Z'
      },
      {
        id: '2',
        type: 'spot',
        title: 'Spot Publicitario Navidad',
        description: 'Campaña navideña para televisión y redes sociales',
        category: 'Comercial',
        duration: '00:45',
        tags: ['navidad', 'publicidad', 'tv'],
        audioUrl: '',
        videoUrl: 'https://www.w3schools.com/html/mov_bbb.mp4',
        status: 'activo',
        views: 3400,
        likes: 215,
        createdAt: '2024-12-05T15:30:00Z'
      },
      {
        id: '3',
        type: 'jingle',
        title: 'Jingle Promocional Verano',
        description: 'Música para promociones de temporada veraniega',
        category: 'Promocional',
        duration: '00:20',
        tags: ['verano', 'promocion', 'alegre'],
        audioUrl: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3',
        videoUrl: '',
        status: 'activo',
        views: 875,
        likes: 56,
        createdAt: '2024-11-20T09:15:00Z'
      },
      {
        id: '4',
        type: 'spot',
        title: 'Spot Lanzamiento Producto XYZ',
        description: 'Video promocional para el lanzamiento del nuevo producto',
        category: 'Campaña',
        duration: '01:00',
        tags: ['lanzamiento', 'producto', 'innovacion'],
        audioUrl: '',
        videoUrl: 'https://www.w3schools.com/html/mov_bbb.mp4',
        status: 'inactivo',
        views: 560,
        likes: 34,
        createdAt: '2024-11-10T14:20:00Z'
      }
    ]
  }
}

const openCreateModal = () => {
  editingItem.value = {
    id: null,
    type: 'jingle',
    title: '',
    description: '',
    category: '',
    duration: '00:30',
    tags: [],
    audioUrl: '',
    videoUrl: '',
    status: 'activo',
    views: 0,
    likes: 0,
    createdAt: new Date().toISOString()
  }
  tagsInput.value = ''
  showModal.value = true
}

const openEditModal = (item) => {
  editingItem.value = { ...item }
  tagsInput.value = item.tags.join(', ')
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const saveItem = async () => {
  // Validación
  if (!editingItem.value.title || !editingItem.value.category) {
    alert('Por favor completa todos los campos requeridos')
    return
  }

  // Procesar tags
  if (tagsInput.value) {
    editingItem.value.tags = tagsInput.value.split(',').map(tag => tag.trim()).filter(tag => tag)
  }

  try {
    if (editingItem.value.id) {
      // Actualizar
      const index = items.value.findIndex(item => item.id === editingItem.value.id)
      if (index !== -1) {
        items.value[index] = { ...editingItem.value }
      }
      emit('item-updated', editingItem.value)
    } else {
      // Crear nuevo
      const newItem = {
        ...editingItem.value,
        id: `item-${Date.now()}`,
        views: 0,
        likes: 0,
        createdAt: new Date().toISOString()
      }
      items.value.unshift(newItem)
      emit('item-created', newItem)
    }
    closeModal()
  } catch (error) {
    console.error('Error saving item:', error)
    alert('Error al guardar el material')
  }
}

const confirmDelete = (id) => {
  deleteTargetId.value = id
  showDeleteConfirm.value = true
}

const deleteItem = async () => {
  try {
    items.value = items.value.filter(item => item.id !== deleteTargetId.value)
    emit('item-deleted', deleteTargetId.value)
    showDeleteConfirm.value = false
    deleteTargetId.value = null
  } catch (error) {
    console.error('Error deleting item:', error)
    alert('Error al eliminar el material')
  }
}

const toggleStatus = (itemId) => {
  const item = items.value.find(i => i.id === itemId)
  if (item) {
    item.status = item.status === 'activo' ? 'inactivo' : 'activo'
    emit('item-status-toggled', item)
  }
}

// Emits
const emit = defineEmits([
  'item-created',
  'item-updated',
  'item-deleted',
  'item-status-toggled'
])

// Lifecycle
onMounted(() => {
  if (props.initialItems.length === 0) {
    loadItems()
  }
})
</script>

<style scoped>
/* Estilos principales */
.audiovisual-material {
  margin: 0 auto;
  padding: 2rem;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background-image: url('/images/fondo.png');
}

/* Header */
.av-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.av-title {
  font-size: 2rem;
  font-weight: 700;
  color: #1a202c;
  margin: 0;
}

.av-actions {
  display: flex;
  gap: 1rem;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 10px rgba(102, 126, 234, 0.4);
}

/* Tabs */
.av-tabs {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 2rem;
  background: #f7fafc;
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
  background: rgba(102, 126, 234, 0.1);
}

.tab-btn.active {
  background: white;
  color: #667eea;
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
  background: #667eea;
  color: white;
}

/* Filtros */
.av-filters {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
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
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
  background: white;
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.av-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.av-card.is-jingle {
  border-color: #48bb78;
}

.av-card.is-spot {
  border-color: #4299e1;
}

.av-card.inactive {
  opacity: 0.6;
}

/* Card Header */
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  background: #f7fafc;
  border-bottom: 1px solid #e2e8f0;
}

.card-type-badge {
  font-weight: 600;
  font-size: 0.9rem;
}

.is-jingle .card-type-badge {
  color: #48bb78;
}

.is-spot .card-type-badge {
  color: #4299e1;
}

.card-actions {
  display: flex;
  gap: 0.25rem;
}

.action-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.25rem 0.5rem;
  border-radius: 0.375rem;
  transition: all 0.2s ease;
  font-size: 1rem;
}

.action-btn:hover {
  background: rgba(0, 0, 0, 0.05);
  transform: scale(1.1);
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
  padding: 1rem;
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
  background: linear-gradient(to top, #48bb78, #38a169);
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
}

/* Card Info */
.card-info {
  padding: 1.5rem;
}

.card-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: #2d3748;
  margin: 0 0 0.5rem 0;
}

.card-description {
  color: #4a5568;
  font-size: 0.95rem;
  line-height: 1.5;
  margin: 0 0 1rem 0;
}

.card-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.tag {
  background: #edf2f7;
  color: #4a5568;
  padding: 0.2rem 0.6rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.card-meta {
  display: flex;
  gap: 1rem;
  margin-bottom: 0.75rem;
  flex-wrap: wrap;
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

.card-stats {
  display: flex;
  gap: 1.5rem;
  padding-top: 0.75rem;
  border-top: 1px solid #e2e8f0;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  color: #718096;
  font-size: 0.8rem;
}

.stat-icon {
  font-size: 0.9rem;
}

/* Estado Vacío */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: #f7fafc;
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

/* Paginación */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  margin-top: 2rem;
}

.page-btn {
  padding: 0.5rem 1rem;
  border: 2px solid #e2e8f0;
  background: white;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;
}

.page-btn:hover:not(:disabled) {
  background: #667eea;
  color: white;
  border-color: #667eea;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-numbers {
  display: flex;
  gap: 0.25rem;
}

.page-num {
  padding: 0.5rem 0.75rem;
  border: 2px solid transparent;
  background: transparent;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;
}

.page-num:hover {
  background: #f7fafc;
}

.page-num.active {
  background: #667eea;
  color: white;
  border-color: #667eea;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 1rem;
}

.modal-content {
  background: white;
  border-radius: 1.5rem;
  max-width: 700px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  padding: 2rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.3s ease;
}

.modal-large {
  max-width: 700px;
}

@keyframes slideUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.modal-header h2 {
  margin: 0;
  color: #1a202c;
  font-size: 1.5rem;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #718096;
  transition: all 0.3s ease;
}

.close-btn:hover {
  color: #1a202c;
  transform: rotate(90deg);
}

.modal-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.form-group label {
  font-weight: 600;
  color: #2d3748;
  font-size: 0.9rem;
}

.form-group input,
.form-group textarea,
.form-group select {
  padding: 0.75rem;
  border: 2px solid #e2e8f0;
  border-radius: 0.5rem;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  font-family: inherit;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group textarea {
  resize: vertical;
}

.form-hint {
  color: #a0aec0;
  font-size: 0.75rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.type-selector {
  display: flex;
  gap: 0.5rem;
}

.type-btn {
  flex: 1;
  padding: 0.75rem;
  border: 2px solid #e2e8f0;
  background: white;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 600;
}

.type-btn:hover {
  background: #f7fafc;
}

.type-btn.active {
  border-color: #667eea;
  background: #ebf0ff;
  color: #667eea;
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  margin-top: 1rem;
}

.btn-secondary {
  padding: 0.75rem 1.5rem;
  background: #f7fafc;
  border: 2px solid #e2e8f0;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  color: #4a5568;
}

.btn-secondary:hover {
  background: #edf2f7;
}

/* Confirmación */
.confirm-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 2000;
}

.confirm-dialog {
  background: white;
  border-radius: 1rem;
  padding: 2rem;
  max-width: 400px;
  width: 90%;
  text-align: center;
  animation: scaleUp 0.3s ease;
}

@keyframes scaleUp {
  from {
    transform: scale(0.9);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

.confirm-icon {
  font-size: 3rem;
  margin-bottom: 0.5rem;
}

.confirm-title {
  color: #1a202c;
  margin: 0.5rem 0;
  font-size: 1.25rem;
}

.confirm-message {
  color: #4a5568;
  margin: 0.5rem 0;
  line-height: 1.6;
}

.confirm-sub-message {
  color: #718096;
  font-size: 0.9rem;
  margin: 0 0 1.5rem 0;
}

.confirm-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
}

.btn-cancel,
.btn-confirm {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-cancel {
  background: #f7fafc;
  color: #4a5568;
}

.btn-cancel:hover {
  background: #edf2f7;
}

.btn-confirm {
  background: #fc8181;
  color: white;
}

.btn-confirm:hover {
  background: #f56565;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(252, 129, 129, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
  .av-header {
    flex-direction: column;
    gap: 1rem;
    align-items: stretch;
  }

  .av-tabs {
    flex-direction: column;
  }

  .av-filters {
    flex-direction: column;
  }

  .search-input,
  .filter-select {
    width: 100%;
  }

  .av-grid {
    grid-template-columns: 1fr;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .modal-content {
    padding: 1.5rem;
  }

  .form-actions {
    flex-direction: column-reverse;
  }

  .form-actions button {
    width: 100%;
  }

  .type-selector {
    flex-direction: column;
  }

  .pagination {
    flex-wrap: wrap;
  }

  .page-numbers {
    flex-wrap: wrap;
    justify-content: center;
  }
}
</style>
