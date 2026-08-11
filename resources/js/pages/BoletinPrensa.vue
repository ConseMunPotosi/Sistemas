<template>
  <div class="press-release-board">
    <!-- Header -->
    <div class="press-header">
      <h2 class="press-title">📰 Boletines de Prensa</h2>
      <div class="press-actions">
        <button v-if="isAdmin" class="btn-primary" @click="openCreateModal">
          <span>+ Nuevo Boletín</span>
        </button>
      </div>
    </div>

    <!-- Filtros -->
    <div class="press-filters">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Buscar boletines..."
        class="search-input"
      />
      <select v-model="selectedCategory" class="filter-select">
        <option value="">Todas las categorías</option>
        <option v-for="category in categories" :key="category" :value="category">
          {{ category }}
        </option>
      </select>
      <select v-model="sortOrder" class="filter-select">
        <option value="desc">Más recientes</option>
        <option value="asc">Más antiguos</option>
      </select>
    </div>

    <!-- Lista de Boletines -->
    <div v-if="filteredReleases.length > 0" class="press-list">
      <div
        v-for="release in paginatedReleases"
        :key="release.id"
        class="press-release-item"
        :class="{ featured: release.featured }"
      >
        <div class="release-header">
          <div class="release-title-section">
            <h3 class="release-title">{{ release.title }}</h3>
            <span v-if="release.featured" class="featured-badge">⭐ Destacado</span>
          </div>
          <div v-if="isAdmin" class="release-actions">
            <button class="action-btn edit-btn" @click="openEditModal(release)">
              ✏️
            </button>
            <button class="action-btn delete-btn" @click="confirmDelete(release.id)">
              🗑️
            </button>
            <button class="action-btn featured-btn" @click="toggleFeatured(release.id)">
              {{ release.featured ? '⭐' : '☆' }}
            </button>
          </div>
        </div>

        <div class="release-meta">
          <span class="meta-item">
            <span class="meta-icon">📅</span>
            {{ formatDate(release.publishDate) }}
          </span>
          <span class="meta-item">
            <span class="meta-icon">🏷️</span>
            {{ release.category }}
          </span>
          <span class="meta-item">
            <span class="meta-icon">👤</span>
            {{ release.author }}
          </span>
        </div>

        <p class="release-summary">{{ release.summary }}</p>

        <button
          class="read-more-btn"
          @click="toggleExpand(release.id)"
        >
          {{ expandedReleases[release.id] ? 'Leer menos' : 'Leer más' }}
        </button>

        <div v-if="expandedReleases[release.id]" class="release-content">
          <p>{{ release.content }}</p>
          <div v-if="release.mediaUrl" class="release-media">
            <img v-if="isImage(release.mediaUrl)" :src="release.mediaUrl" alt="Media" />
            <a v-else :href="release.mediaUrl" target="_blank" class="media-link">
              📎 Ver recurso adjunto
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Estado Vacío -->
    <div v-else class="empty-state">
      <p>No hay boletines disponibles</p>
    </div>

    <!-- Paginación -->
    <div v-if="totalPages > 1" class="pagination">
      <button
        class="page-btn"
        :disabled="currentPage === 1"
        @click="currentPage--"
      >
        Anterior
      </button>
      <span class="page-info">Página {{ currentPage }} de {{ totalPages }}</span>
      <button
        class="page-btn"
        :disabled="currentPage === totalPages"
        @click="currentPage++"
      >
        Siguiente
      </button>
    </div>

    <!-- Modal de Creación/Edición -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>{{ editingRelease.id ? 'Editar Boletín' : 'Nuevo Boletín' }}</h2>
          <button class="close-btn" @click="closeModal">✕</button>
        </div>

        <form @submit.prevent="saveRelease" class="modal-form">
          <div class="form-group">
            <label for="modal-title">Título *</label>
            <input
              id="modal-title"
              v-model="editingRelease.title"
              type="text"
              required
              placeholder="Título del boletín"
            />
          </div>

          <div class="form-group">
            <label for="modal-summary">Resumen *</label>
            <textarea
              id="modal-summary"
              v-model="editingRelease.summary"
              rows="2"
              required
              placeholder="Breve resumen del boletín"
            />
          </div>

          <div class="form-group">
            <label for="modal-content">Contenido completo *</label>
            <textarea
              id="modal-content"
              v-model="editingRelease.content"
              rows="5"
              required
              placeholder="Contenido detallado del boletín"
            />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="modal-category">Categoría *</label>
              <select id="modal-category" v-model="editingRelease.category" required>
                <option value="">Selecciona una categoría</option>
                <option v-for="cat in categories" :key="cat" :value="cat">
                  {{ cat }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label for="modal-publishDate">Fecha de publicación *</label>
              <input
                id="modal-publishDate"
                v-model="editingRelease.publishDate"
                type="date"
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label for="modal-author">Autor</label>
            <input
              id="modal-author"
              v-model="editingRelease.author"
              type="text"
              placeholder="Nombre del autor"
            />
          </div>

          <div class="form-group">
            <label for="modal-mediaUrl">URL de recurso multimedia</label>
            <input
              id="modal-mediaUrl"
              v-model="editingRelease.mediaUrl"
              type="url"
              placeholder="https://ejemplo.com/imagen.jpg"
            />
          </div>

          <div class="form-group checkbox">
            <label>
              <input v-model="editingRelease.featured" type="checkbox" />
              Destacar este boletín
            </label>
          </div>

          <div class="form-actions">
            <button type="button" class="btn-secondary" @click="closeModal">
              Cancelar
            </button>
            <button type="submit" class="btn-primary">
              {{ editingRelease.id ? 'Actualizar' : 'Publicar' }}
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
        <p class="confirm-message">¿Estás seguro de eliminar este boletín?</p>
        <div class="confirm-actions">
          <button class="btn-cancel" @click="showDeleteConfirm = false">
            Cancelar
          </button>
          <button class="btn-confirm" @click="deleteRelease">
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
  initialReleases: {
    type: Array,
    default: () => []
  }
})

// State
const releases = ref(props.initialReleases)
const searchQuery = ref('')
const selectedCategory = ref('')
const sortOrder = ref('desc')
const currentPage = ref(1)
const itemsPerPage = 5
const showModal = ref(false)
const showDeleteConfirm = ref(false)
const deleteTargetId = ref(null)
const expandedReleases = ref({})

// Estado para el formulario
const editingRelease = ref({
  id: null,
  title: '',
  summary: '',
  content: '',
  category: '',
  publishDate: new Date().toISOString().split('T')[0],
  author: 'Departamento de Prensa',
  mediaUrl: '',
  featured: false
})

// Computed
const categories = computed(() => {
  return ['Empresa', 'Producto', 'Evento', 'Financiero', 'Premios', 'Otros']
})

const filteredReleases = computed(() => {
  let filtered = [...releases.value]

  // Filtro por búsqueda
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(release =>
      release.title.toLowerCase().includes(query) ||
      release.summary.toLowerCase().includes(query) ||
      release.content.toLowerCase().includes(query)
    )
  }

  // Filtro por categoría
  if (selectedCategory.value) {
    filtered = filtered.filter(release =>
      release.category === selectedCategory.value
    )
  }

  // Ordenamiento
  filtered.sort((a, b) => {
    const dateA = new Date(a.publishDate)
    const dateB = new Date(b.publishDate)
    return sortOrder.value === 'desc' ? dateB - dateA : dateA - dateB
  })

  return filtered
})

const totalPages = computed(() => {
  return Math.ceil(filteredReleases.value.length / itemsPerPage)
})

const paginatedReleases = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredReleases.value.slice(start, end)
})

// Methods
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const isImage = (url) => {
  return /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(url)
}

const toggleExpand = (id) => {
  expandedReleases.value[id] = !expandedReleases.value[id]
}

const loadReleases = async () => {
  try {
    // Simular carga de API
    const response = await fetch('/api/press-releases')
    const data = await response.json()
    releases.value = data
  } catch (error) {
    console.error('Error loading releases:', error)
    // Datos de ejemplo si falla la API
    releases.value = [
      {
        id: '1',
        title: 'Lanzamiento del Nuevo Producto XYZ',
        summary: 'Presentamos nuestra innovadora solución que revolucionará el mercado',
        content: 'Después de meses de investigación y desarrollo, nos complace anunciar el lanzamiento de nuestro nuevo producto que transformará la industria. Este producto representa un hito importante en nuestra misión de proporcionar soluciones de vanguardia.',
        category: 'Producto',
        publishDate: '2024-12-15',
        author: 'Departamento de Marketing',
        featured: true,
        mediaUrl: ''
      },
      {
        id: '2',
        title: 'Resultados Financieros del Cuarto Trimestre',
        summary: 'La compañía reporta un crecimiento del 25% en ingresos',
        content: 'En el cuarto trimestre del año, la compañía ha logrado resultados excepcionales con un crecimiento del 25% en ingresos comparado con el mismo período del año anterior. Este crecimiento se debe principalmente a la expansión en nuevos mercados.',
        category: 'Financiero',
        publishDate: '2024-12-10',
        author: 'Departamento Financiero',
        featured: false,
        mediaUrl: ''
      }
    ]
  }
}

const openCreateModal = () => {
  editingRelease.value = {
    id: null,
    title: '',
    summary: '',
    content: '',
    category: '',
    publishDate: new Date().toISOString().split('T')[0],
    author: 'Departamento de Prensa',
    mediaUrl: '',
    featured: false
  }
  showModal.value = true
}

const openEditModal = (release) => {
  editingRelease.value = { ...release }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  // No resetear editingRelease para mantener los datos si se cierra accidentalmente
}

const saveRelease = async () => {
  // Validación
  if (!editingRelease.value.title || !editingRelease.value.content) {
    alert('Por favor completa todos los campos requeridos')
    return
  }

  try {
    if (editingRelease.value.id) {
      // Actualizar
      const index = releases.value.findIndex(r => r.id === editingRelease.value.id)
      if (index !== -1) {
        releases.value[index] = { ...editingRelease.value }
      }
      emit('release-updated', editingRelease.value)
    } else {
      // Crear nuevo
      const newRelease = {
        ...editingRelease.value,
        id: `release-${Date.now()}`,
        createdAt: new Date().toISOString()
      }
      releases.value.unshift(newRelease)
      emit('release-created', newRelease)
    }
    closeModal()
  } catch (error) {
    console.error('Error saving release:', error)
    alert('Error al guardar el boletín')
  }
}

const confirmDelete = (id) => {
  deleteTargetId.value = id
  showDeleteConfirm.value = true
}

const deleteRelease = async () => {
  try {
    releases.value = releases.value.filter(r => r.id !== deleteTargetId.value)
    emit('release-deleted', deleteTargetId.value)
    showDeleteConfirm.value = false
    deleteTargetId.value = null
  } catch (error) {
    console.error('Error deleting release:', error)
    alert('Error al eliminar el boletín')
  }
}

const toggleFeatured = (releaseId) => {
  const release = releases.value.find(r => r.id === releaseId)
  if (release) {
    release.featured = !release.featured
    emit('release-featured-toggled', release)
  }
}

// Emits
const emit = defineEmits([
  'release-created',
  'release-updated',
  'release-deleted',
  'release-featured-toggled'
])

// Lifecycle
onMounted(() => {
  // Cargar datos si no se proporcionaron inicialmente
  if (props.initialReleases.length === 0) {
    loadReleases()
  }
})
</script>

<style scoped>
/* Estilo principal del componente - FONDO COMPLETO */
.press-release-board {
  margin: 0 auto;
  padding: 2rem;
  min-height: 100vh;
  /* Fondo con imagen y gradiente combinados */
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Si quieres usar solo imagen de fondo sin gradiente, usa esto en su lugar */
/*
.press-release-board {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  min-height: 100vh;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
}
*/

/* Si quieres usar solo un color de fondo */
/*
.press-release-board {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
*/

/* Si quieres usar un patrón o gradiente con imagen */
/*
.press-release-board {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  min-height: 100vh;
  background-image:
    repeating-linear-gradient(45deg, rgba(255,255,255,0.05) 0px, rgba(255,255,255,0.05) 20px, transparent 20px, transparent 40px),
    url('/images/fondo.png');
  background-size: auto, cover;
  background-position: center;
}
*/

/* Header */
.press-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  backdrop-filter: blur(10px);
  padding: 1.5rem 2rem;
  border-radius: 1rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.press-title {
  font-size: 2rem;
  font-weight: 700;
  color: #1a202c;
  margin: 0;
}

.press-actions {
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

/* Filtros */
.press-filters {
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
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.filter-select {
  min-width: 150px;
}

/* Items de boletines */
.press-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.press-release-item {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  border-left: 4px solid #e2e8f0;
  transition: all 0.3s ease;
}

.press-release-item:hover {
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
  background: rgba(255, 255, 255, 0.95);
}

.press-release-item.featured {
  border-left-color: #f6ad55;
  background: linear-gradient(135deg, rgba(255, 250, 240, 0.95), rgba(255, 255, 255, 0.95));
  backdrop-filter: blur(10px);
}

.release-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1rem;
}

.release-title-section {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.release-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #2d3748;
  margin: 0;
}

.featured-badge {
  background: #f6ad55;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.release-actions {
  display: flex;
  gap: 0.5rem;
}

.action-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.25rem 0.5rem;
  border-radius: 0.375rem;
  transition: all 0.2s ease;
  font-size: 1.1rem;
}

.action-btn:hover {
  background: #f7fafc;
  transform: scale(1.1);
}

.release-meta {
  display: flex;
  gap: 1.5rem;
  margin-bottom: 1rem;
  flex-wrap: wrap;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  color: #718096;
  font-size: 0.875rem;
}

.meta-icon {
  font-size: 1rem;
}

.release-summary {
  color: #4a5568;
  line-height: 1.6;
  margin: 0 0 1rem 0;
}

.read-more-btn {
  background: none;
  border: none;
  color: #667eea;
  font-weight: 600;
  cursor: pointer;
  padding: 0;
  font-size: 0.95rem;
}

.read-more-btn:hover {
  text-decoration: underline;
}

.release-content {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 2px solid #f7fafc;
}

.release-content p {
  color: #2d3748;
  line-height: 1.8;
}

.release-media {
  margin-top: 1rem;
}

.release-media img {
  max-width: 100%;
  border-radius: 0.5rem;
  max-height: 400px;
  object-fit: cover;
}

.media-link {
  display: inline-block;
  padding: 0.5rem 1rem;
  background: #f7fafc;
  border-radius: 0.5rem;
  color: #667eea;
  text-decoration: none;
  font-weight: 500;
}

.media-link:hover {
  background: #edf2f7;
}

/* Estado vacío */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
  color: #718096;
}

/* Paginación */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin-top: 2rem;
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(10px);
  padding: 0.75rem;
  border-radius: 1rem;
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

.page-info {
  font-weight: 500;
  color: #4a5568;
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
  max-width: 600px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  padding: 2rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.3s ease;
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

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group.checkbox {
  flex-direction: row;
  align-items: center;
  gap: 0.5rem;
}

.form-group.checkbox label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-weight: normal;
}

.form-group.checkbox input[type="checkbox"] {
  width: 1.2rem;
  height: 1.2rem;
  cursor: pointer;
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
  margin: 1rem 0 1.5rem 0;
  line-height: 1.6;
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
  .press-release-board {
    padding: 1rem;
  }

  .press-header {
    flex-direction: column;
    gap: 1rem;
    align-items: stretch;
    padding: 1rem;
  }

  .press-filters {
    flex-direction: column;
    padding: 0.75rem;
  }

  .search-input,
  .filter-select {
    width: 100%;
  }

  .release-header {
    flex-direction: column;
    gap: 0.75rem;
  }

  .release-actions {
    width: 100%;
    justify-content: flex-end;
  }

  .release-meta {
    flex-direction: column;
    gap: 0.5rem;
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
}
</style>
