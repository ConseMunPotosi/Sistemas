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
        <div class="header-actions">
          <button
            v-if="isAdmin"
            class="btn-create"
            @click="openCreateModal"
          >
            <span class="btn-icon">➕</span>
            Nuevo Comunicado
          </button>
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
          <select v-model="selectedType" class="filter-select">
            <option value="">Todos los tipos</option>
            <option value="urgent">🚨 Urgente</option>
            <option value="important">⭐ Importante</option>
            <option value="general">📋 General</option>
            <option value="event">🎯 Evento</option>
            <option value="update">🔄 Actualización</option>
          </select>

          <select v-model="selectedStatus" class="filter-select">
            <option value="">Todos los estados</option>
            <option value="published">✅ Publicado</option>
            <option value="draft">📝 Borrador</option>
            <option value="archived">📦 Archivado</option>
          </select>

          <select v-model="sortOrder" class="filter-select">
            <option value="desc">Más recientes</option>
            <option value="asc">Más antiguos</option>
          </select>
        </div>
      </div>

      <!-- Etiquetas rápidas -->
      <div class="quick-tags">
        <span class="tags-label">Filtros rápidos:</span>
        <button
          v-for="tag in quickTags"
          :key="tag"
          class="tag-btn"
          :class="{ active: activeTag === tag }"
          @click="toggleTag(tag)"
        >
          {{ tag }}
        </button>
        <button
          v-if="activeTag"
          class="tag-btn clear-tag"
          @click="clearTags"
        >
          ✕ Limpiar
        </button>
      </div>
    </section>

    <!-- Estadísticas -->
    <section v-if="communications.length > 0" class="stats-section">
      <div class="stats-grid">
        <div class="stat-card">
          <span class="stat-number">{{ communications.length }}</span>
          <span class="stat-label">Total</span>
        </div>
        <div class="stat-card">
          <span class="stat-number">{{ urgentCount }}</span>
          <span class="stat-label">Urgentes</span>
        </div>
        <div class="stat-card">
          <span class="stat-number">{{ publishedCount }}</span>
          <span class="stat-label">Publicados</span>
        </div>
        <div class="stat-card">
          <span class="stat-number">{{ recentCount }}</span>
          <span class="stat-label">Últimos 7 días</span>
        </div>
      </div>
    </section>

    <!-- Lista de Comunicados -->
    <section class="communications-list">
      <div v-if="filteredCommunications.length > 0" class="list-container">
        <div
          v-for="communication in paginatedCommunications"
          :key="communication.id"
          class="communication-card"
          :class="[
            communication.type,
            {
              'is-urgent': communication.type === 'urgent',
              'is-draft': communication.status === 'draft',
              'is-archived': communication.status === 'archived'
            }
          ]"
        >
          <!-- Badge de estado -->
          <div class="card-badge" :class="communication.type">
            {{ getTypeLabel(communication.type) }}
          </div>

          <!-- Contenido -->
          <div class="card-content">
            <div class="card-header">
              <div class="card-title-group">
                <h3 class="card-title">{{ communication.title }}</h3>
                <span v-if="communication.status === 'draft'" class="status-badge draft">
                  📝 Borrador
                </span>
                <span v-if="communication.status === 'archived'" class="status-badge archived">
                  📦 Archivado
                </span>
                <span v-if="communication.important" class="status-badge important">
                  ⭐ Importante
                </span>
              </div>

              <div v-if="isAdmin" class="card-actions">
                <button class="action-btn edit" @click="openEditModal(communication)" title="Editar">
                  ✏️
                </button>
                <button class="action-btn duplicate" @click="duplicateCommunication(communication)" title="Duplicar">
                  📋
                </button>
                <button
                  class="action-btn toggle-status"
                  @click="toggleStatus(communication.id)"
                  :title="communication.status === 'published' ? 'Archivar' : 'Publicar'"
                >
                  {{ communication.status === 'published' ? '📦' : '✅' }}
                </button>
                <button class="action-btn delete" @click="confirmDelete(communication.id)" title="Eliminar">
                  🗑️
                </button>
              </div>
            </div>

            <div class="card-meta">
              <span class="meta-item">
                <span class="meta-icon">📅</span>
                {{ formatDate(communication.publishDate) }}
              </span>
              <span class="meta-item">
                <span class="meta-icon">👤</span>
                {{ communication.author }}
              </span>
              <span class="meta-item">
                <span class="meta-icon">🏷️</span>
                {{ communication.department || 'General' }}
              </span>
              <span v-if="communication.expiryDate" class="meta-item">
                <span class="meta-icon">⏳</span>
                Expira: {{ formatDate(communication.expiryDate) }}
              </span>
            </div>

            <p class="card-summary">{{ communication.summary }}</p>

            <div class="card-footer">
              <button
                class="read-more-btn"
                @click="toggleExpand(communication.id)"
              >
                {{ expandedCommunications[communication.id] ? '📖 Ver menos' : '📖 Leer más' }}
              </button>

              <div v-if="communication.tags && communication.tags.length > 0" class="card-tags">
                <span
                  v-for="tag in communication.tags"
                  :key="tag"
                  class="tag"
                  @click="filterByTag(tag)"
                >
                  #{{ tag }}
                </span>
              </div>
            </div>

            <!-- Contenido expandido -->
            <div v-if="expandedCommunications[communication.id]" class="card-expanded">
              <div class="expanded-content">
                <p>{{ communication.content }}</p>

                <div v-if="communication.attachments && communication.attachments.length > 0" class="attachments">
                  <h4>📎 Archivos adjuntos</h4>
                  <ul class="attachment-list">
                    <li v-for="(file, index) in communication.attachments" :key="index">
                      <a :href="file.url" target="_blank" class="attachment-link">
                        {{ file.name }}
                      </a>
                      <span class="file-size">{{ file.size }}</span>
                    </li>
                  </ul>
                </div>

                <div v-if="communication.links && communication.links.length > 0" class="related-links">
                  <h4>🔗 Enlaces relacionados</h4>
                  <ul class="links-list">
                    <li v-for="(link, index) in communication.links" :key="index">
                      <a :href="link.url" target="_blank" class="link-item">
                        {{ link.label }}
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Estado vacío -->
      <div v-else class="empty-state">
        <div class="empty-icon">📭</div>
        <h3 class="empty-title">No hay comunicados</h3>
        <p class="empty-description">
          {{ searchQuery ? 'No se encontraron resultados para tu búsqueda' : 'Comienza creando tu primer comunicado' }}
        </p>
        <button v-if="!searchQuery && isAdmin" class="btn-create-empty" @click="openCreateModal">
          Crear Comunicado
        </button>
      </div>

      <!-- Paginación -->
      <div v-if="totalPages > 1" class="pagination">
        <button
          class="page-btn"
          :disabled="currentPage === 1"
          @click="currentPage--"
        >
          ◀ Anterior
        </button>

        <div class="page-numbers">
          <button
            v-for="page in visiblePages"
            :key="page"
            class="page-number"
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
          Siguiente ▶
        </button>
      </div>
    </section>

    <!-- Modal de Creación/Edición -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content modal-large">
        <div class="modal-header">
          <h2>{{ editingCommunication.id ? '✏️ Editar Comunicado' : '📝 Nuevo Comunicado' }}</h2>
          <button class="close-btn" @click="closeModal">✕</button>
        </div>

        <form @submit.prevent="saveCommunication" class="modal-form">
          <!-- Título -->
          <div class="form-group">
            <label for="comm-title">Título *</label>
            <input
              id="comm-title"
              v-model="editingCommunication.title"
              type="text"
              required
              placeholder="Título del comunicado"
              maxlength="150"
            />
            <span class="char-count">{{ editingCommunication.title.length }}/150</span>
          </div>

          <!-- Resumen -->
          <div class="form-group">
            <label for="comm-summary">Resumen *</label>
            <textarea
              id="comm-summary"
              v-model="editingCommunication.summary"
              rows="2"
              required
              placeholder="Breve resumen del comunicado"
              maxlength="300"
            />
            <span class="char-count">{{ editingCommunication.summary.length }}/300</span>
          </div>

          <!-- Contenido -->
          <div class="form-group">
            <label for="comm-content">Contenido completo *</label>
            <textarea
              id="comm-content"
              v-model="editingCommunication.content"
              rows="6"
              required
              placeholder="Contenido detallado del comunicado"
            />
          </div>

          <!-- Configuración -->
          <div class="form-row">
            <div class="form-group">
              <label for="comm-type">Tipo *</label>
              <select id="comm-type" v-model="editingCommunication.type" required>
                <option value="">Selecciona un tipo</option>
                <option value="urgent">🚨 Urgente</option>
                <option value="important">⭐ Importante</option>
                <option value="general">📋 General</option>
                <option value="event">🎯 Evento</option>
                <option value="update">🔄 Actualización</option>
              </select>
            </div>

            <div class="form-group">
              <label for="comm-status">Estado</label>
              <select id="comm-status" v-model="editingCommunication.status">
                <option value="draft">📝 Borrador</option>
                <option value="published">✅ Publicado</option>
                <option value="archived">📦 Archivado</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="comm-publishDate">Fecha de publicación *</label>
              <input
                id="comm-publishDate"
                v-model="editingCommunication.publishDate"
                type="datetime-local"
                required
              />
            </div>

            <div class="form-group">
              <label for="comm-expiryDate">Fecha de expiración</label>
              <input
                id="comm-expiryDate"
                v-model="editingCommunication.expiryDate"
                type="datetime-local"
              />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="comm-author">Autor *</label>
              <input
                id="comm-author"
                v-model="editingCommunication.author"
                type="text"
                required
                placeholder="Nombre del autor"
              />
            </div>

            <div class="form-group">
              <label for="comm-department">Departamento</label>
              <input
                id="comm-department"
                v-model="editingCommunication.department"
                type="text"
                placeholder="Departamento responsable"
              />
            </div>
          </div>

          <!-- Etiquetas -->
          <div class="form-group">
            <label>Etiquetas</label>
            <div class="tags-input">
              <input
                v-model="tagInput"
                type="text"
                placeholder="Escribe una etiqueta y presiona Enter"
                @keydown.enter.prevent="addTag"
              />
              <button type="button" class="add-tag-btn" @click="addTag">➕</button>
            </div>
            <div class="tags-display">
              <span
                v-for="(tag, index) in editingCommunication.tags"
                :key="index"
                class="tag-item"
              >
                #{{ tag }}
                <button type="button" class="remove-tag" @click="removeTag(index)">✕</button>
              </span>
            </div>
          </div>

          <!-- Importante -->
          <div class="form-group checkbox">
            <label>
              <input v-model="editingCommunication.important" type="checkbox" />
              ⭐ Marcar como importante
            </label>
          </div>

          <!-- Acciones del formulario -->
          <div class="form-actions">
            <button type="button" class="btn-secondary" @click="closeModal">
              Cancelar
            </button>
            <button type="submit" class="btn-primary">
              {{ editingCommunication.id ? 'Actualizar' : 'Publicar' }}
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
        <p class="confirm-message">
          ¿Estás seguro de eliminar este comunicado? Esta acción no se puede deshacer.
        </p>
        <div class="confirm-actions">
          <button class="btn-cancel" @click="showDeleteConfirm = false">
            Cancelar
          </button>
          <button class="btn-confirm" @click="deleteCommunication">
            Eliminar
          </button>
        </div>
      </div>
    </div>

    <!-- Notificación Toast -->
    <div v-if="toast.show" class="toast" :class="toast.type">
      <span class="toast-icon">{{ toast.icon }}</span>
      <span class="toast-message">{{ toast.message }}</span>
      <button class="toast-close" @click="hideToast">✕</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

// Props
const props = defineProps({
  isAdmin: {
    type: Boolean,
    default: false
  },
  initialCommunications: {
    type: Array,
    default: () => []
  }
})

// State
const communications = ref(props.initialCommunications)
const searchQuery = ref('')
const selectedType = ref('')
const selectedStatus = ref('')
const sortOrder = ref('desc')
const currentPage = ref(1)
const itemsPerPage = 5
const showModal = ref(false)
const showDeleteConfirm = ref(false)
const deleteTargetId = ref(null)
const expandedCommunications = ref({})
const activeTag = ref('')
const tagInput = ref('')
const toast = ref({
  show: false,
  message: '',
  type: 'success',
  icon: '✅'
})

// Estado para el formulario
const editingCommunication = ref({
  id: null,
  title: '',
  summary: '',
  content: '',
  type: '',
  status: 'draft',
  publishDate: new Date().toISOString().slice(0, 16),
  expiryDate: '',
  author: '',
  department: '',
  tags: [],
  important: false,
  attachments: [],
  links: []
})

// Computed
const quickTags = ['Urgente', 'Importante', 'Evento', 'Actualización']

const urgentCount = computed(() => {
  return communications.value.filter(c => c.type === 'urgent').length
})

const publishedCount = computed(() => {
  return communications.value.filter(c => c.status === 'published').length
})

const recentCount = computed(() => {
  const sevenDaysAgo = new Date()
  sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7)
  return communications.value.filter(c => {
    const date = new Date(c.publishDate)
    return date >= sevenDaysAgo
  }).length
})

const filteredCommunications = computed(() => {
  let filtered = [...communications.value]

  // Búsqueda
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(comm =>
      comm.title.toLowerCase().includes(query) ||
      comm.summary.toLowerCase().includes(query) ||
      comm.content.toLowerCase().includes(query) ||
      comm.tags.some(tag => tag.toLowerCase().includes(query))
    )
  }

  // Filtro por tipo
  if (selectedType.value) {
    filtered = filtered.filter(comm => comm.type === selectedType.value)
  }

  // Filtro por estado
  if (selectedStatus.value) {
    filtered = filtered.filter(comm => comm.status === selectedStatus.value)
  }

  // Filtro por etiqueta
  if (activeTag.value) {
    const tagLower = activeTag.value.toLowerCase()
    filtered = filtered.filter(comm =>
      comm.tags.some(tag => tag.toLowerCase() === tagLower)
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
  return Math.ceil(filteredCommunications.value.length / itemsPerPage)
})

const paginatedCommunications = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredCommunications.value.slice(start, end)
})

const visiblePages = computed(() => {
  const pages = []
  const total = totalPages.value
  const current = currentPage.value

  if (total <= 7) {
    for (let i = 1; i <= total; i++) pages.push(i)
  } else {
    pages.push(1)
    if (current > 3) pages.push('...')
    for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) {
      pages.push(i)
    }
    if (current < total - 2) pages.push('...')
    pages.push(total)
  }
  return pages
})

// Methods
const formatDate = (dateString) => {
  if (!dateString) return 'Fecha no disponible'
  const date = new Date(dateString)
  return date.toLocaleString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getTypeLabel = (type) => {
  const labels = {
    urgent: '🚨 URGENTE',
    important: '⭐ IMPORTANTE',
    general: '📋 GENERAL',
    event: '🎯 EVENTO',
    update: '🔄 ACTUALIZACIÓN'
  }
  return labels[type] || type
}

const toggleExpand = (id) => {
  expandedCommunications.value[id] = !expandedCommunications.value[id]
}

const toggleTag = (tag) => {
  activeTag.value = activeTag.value === tag ? '' : tag
  currentPage.value = 1
}

const clearTags = () => {
  activeTag.value = ''
  currentPage.value = 1
}

const filterByTag = (tag) => {
  activeTag.value = tag
  currentPage.value = 1
}

const addTag = () => {
  const tag = tagInput.value.trim()
  if (tag && !editingCommunication.value.tags.includes(tag)) {
    editingCommunication.value.tags.push(tag)
    tagInput.value = ''
  }
}

const removeTag = (index) => {
  editingCommunication.value.tags.splice(index, 1)
}

const openCreateModal = () => {
  editingCommunication.value = {
    id: null,
    title: '',
    summary: '',
    content: '',
    type: '',
    status: 'draft',
    publishDate: new Date().toISOString().slice(0, 16),
    expiryDate: '',
    author: 'Departamento de Comunicación',
    department: '',
    tags: [],
    important: false,
    attachments: [],
    links: []
  }
  showModal.value = true
}

const openEditModal = (communication) => {
  editingCommunication.value = {
    ...communication,
    publishDate: communication.publishDate.slice(0, 16)
  }
  if (communication.expiryDate) {
    editingCommunication.value.expiryDate = communication.expiryDate.slice(0, 16)
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const saveCommunication = async () => {
  // Validación
  if (!editingCommunication.value.title || !editingCommunication.value.content) {
    showToast('error', '❌', 'Por favor completa todos los campos requeridos')
    return
  }

  try {
    if (editingCommunication.value.id) {
      // Actualizar
      const index = communications.value.findIndex(c => c.id === editingCommunication.value.id)
      if (index !== -1) {
        communications.value[index] = { ...editingCommunication.value }
      }
      emit('communication-updated', editingCommunication.value)
      showToast('success', '✅', 'Comunicado actualizado exitosamente')
    } else {
      // Crear nuevo
      const newCommunication = {
        ...editingCommunication.value,
        id: `comm-${Date.now()}`,
        createdAt: new Date().toISOString()
      }
      communications.value.unshift(newCommunication)
      emit('communication-created', newCommunication)
      showToast('success', '✅', 'Comunicado creado exitosamente')
    }
    closeModal()
  } catch (error) {
    console.error('Error saving communication:', error)
    showToast('error', '❌', 'Error al guardar el comunicado')
  }
}

const confirmDelete = (id) => {
  deleteTargetId.value = id
  showDeleteConfirm.value = true
}

const deleteCommunication = async () => {
  try {
    communications.value = communications.value.filter(c => c.id !== deleteTargetId.value)
    emit('communication-deleted', deleteTargetId.value)
    showDeleteConfirm.value = false
    deleteTargetId.value = null
    showToast('success', '🗑️', 'Comunicado eliminado exitosamente')
  } catch (error) {
    console.error('Error deleting communication:', error)
    showToast('error', '❌', 'Error al eliminar el comunicado')
  }
}

const toggleStatus = (communicationId) => {
  const comm = communications.value.find(c => c.id === communicationId)
  if (comm) {
    if (comm.status === 'published') {
      comm.status = 'archived'
      showToast('info', '📦', 'Comunicado archivado')
    } else if (comm.status === 'archived') {
      comm.status = 'published'
      showToast('success', '✅', 'Comunicado publicado nuevamente')
    } else {
      comm.status = 'published'
      showToast('success', '✅', 'Comunicado publicado')
    }
    emit('communication-status-changed', comm)
  }
}

const duplicateCommunication = (communication) => {
  const newComm = {
    ...communication,
    id: `comm-${Date.now()}`,
    title: `${communication.title} (Copia)`,
    status: 'draft',
    publishDate: new Date().toISOString().slice(0, 16),
    createdAt: new Date().toISOString()
  }
  communications.value.unshift(newComm)
  emit('communication-created', newComm)
  showToast('success', '📋', 'Comunicado duplicado exitosamente')
}

const showToast = (type, icon, message) => {
  toast.value = {
    show: true,
    type,
    icon,
    message
  }
  setTimeout(() => {
    hideToast()
  }, 5000)
}

const hideToast = () => {
  toast.value.show = false
}

// Cargar datos de ejemplo
const loadCommunications = async () => {
  try {
    const response = await fetch('/api/communications')
    const data = await response.json()
    communications.value = data
  } catch (error) {
    console.log('Cargando datos de ejemplo...')
    communications.value = [
      {
        id: '1',
        title: 'Nuevo Sistema de Gestión Implementado',
        summary: 'La organización adopta nueva plataforma tecnológica para optimizar procesos',
        content: 'Nos complace anunciar la implementación del nuevo Sistema de Gestión Integrado que permitirá optimizar los procesos internos y mejorar la eficiencia operativa. Este sistema ha sido desarrollado con las últimas tecnologías y está diseñado para adaptarse a las necesidades de todos los departamentos.',
        type: 'important',
        status: 'published',
        publishDate: '2024-12-15T09:00:00',
        expiryDate: '2025-01-15T23:59:59',
        author: 'Dirección General',
        department: 'Tecnología',
        tags: ['Tecnología', 'Actualización', 'Importante'],
        important: true,
        attachments: [
          { name: 'Manual_Usuario.pdf', url: '#', size: '2.5 MB' }
        ],
        links: [
          { label: 'Sistema de Gestión', url: '#' }
        ]
      },
      {
        id: '2',
        title: 'Reunión Extraordinaria de Emergencia',
        summary: 'Convocatoria urgente para tratar temas críticos de la organización',
        content: 'Debido a la situación actual, se convoca a todos los directivos a una reunión extraordinaria que se llevará a cabo el día de mañana. La asistencia es obligatoria y se tratarán temas de máxima importancia para el futuro de la organización.',
        type: 'urgent',
        status: 'published',
        publishDate: '2024-12-14T10:30:00',
        expiryDate: '2024-12-16T23:59:59',
        author: 'Comité Ejecutivo',
        department: 'Dirección',
        tags: ['Urgente', 'Reunión'],
        important: true,
        attachments: [],
        links: [
          { label: 'Orden del día', url: '#' }
        ]
      },
      {
        id: '3',
        title: 'Resultados del Proyecto Anual 2024',
        summary: 'Presentación de los logros alcanzados durante el año fiscal',
        content: 'Se han alcanzado todos los objetivos propuestos para el año fiscal 2024, superando las expectativas en un 15%. Los detalles completos serán presentados en la reunión de balance anual.',
        type: 'general',
        status: 'published',
        publishDate: '2024-12-13T14:00:00',
        expiryDate: '',
        author: 'Gerencia de Proyectos',
        department: 'Operaciones',
        tags: ['Resultados', 'Proyectos'],
        important: false,
        attachments: [],
        links: []
      }
    ]
  }
}

// Emits
const emit = defineEmits([
  'communication-created',
  'communication-updated',
  'communication-deleted',
  'communication-status-changed'
])

// Watch para reiniciar página al cambiar filtros
watch([searchQuery, selectedType, selectedStatus, activeTag], () => {
  currentPage.value = 1
})

// Lifecycle
onMounted(() => {
  if (props.initialCommunications.length === 0) {
    loadCommunications()
  }
})
</script>

<style scoped>
/* Estilos Globales */
.official-communications {
  margin: 0 auto;
  padding: 2rem;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background: #f8fafc;
  min-height: 100vh;
  background-image: url('/images/fondo.png');
}

/* Header */
.communications-header {
  background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
  border-radius: 1.5rem;
  padding: 2.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.header-left {
  flex: 1;
}

.header-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: white;
  margin: 0 0 0.5rem 0;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.header-title .icon {
  font-size: 2.8rem;
}

.header-subtitle {
  color: #94a3b8;
  font-size: 1.1rem;
  margin: 0;
}

.btn-create {
  background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
  color: white;
  border: none;
  padding: 0.9rem 2rem;
  border-radius: 0.75rem;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.btn-create:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
}

.btn-create .btn-icon {
  font-size: 1.2rem;
}

/* Filtros */
.filters-section {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.filters-container {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  margin-bottom: 1rem;
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
  background: #f8fafc;
}

.search-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  background: white;
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
  background: #f8fafc;
  cursor: pointer;
  transition: all 0.3s ease;
  min-width: 140px;
}

.filter-select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.quick-tags {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
  padding-top: 0.75rem;
  border-top: 2px solid #f1f5f9;
}

.tags-label {
  font-weight: 600;
  color: #64748b;
  font-size: 0.9rem;
}

.tag-btn {
  padding: 0.4rem 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 9999px;
  background: white;
  cursor: pointer;
  font-size: 0.85rem;
  font-weight: 500;
  color: #475569;
  transition: all 0.3s ease;
}

.tag-btn:hover {
  background: #f1f5f9;
  border-color: #94a3b8;
}

.tag-btn.active {
  background: #3b82f6;
  color: white;
  border-color: #3b82f6;
}

.tag-btn.clear-tag {
  background: #fee2e2;
  border-color: #fca5a5;
  color: #dc2626;
}

.tag-btn.clear-tag:hover {
  background: #fecaca;
}

/* Estadísticas */
.stats-section {
  margin-bottom: 2rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
}

.stat-card {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  text-align: center;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.stat-number {
  display: block;
  font-size: 2rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.875rem;
  color: #64748b;
  font-weight: 500;
}

/* Lista de Comunicados */
.communications-list {
  margin-top: 2rem;
}

.list-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.communication-card {
  background: white;
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  position: relative;
}

.communication-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.communication-card.is-urgent {
  border-left: 6px solid #ef4444;
}

.communication-card.is-draft {
  opacity: 0.7;
  border-left: 6px solid #94a3b8;
}

.communication-card.is-archived {
  opacity: 0.5;
  border-left: 6px solid #64748b;
}

.card-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  padding: 0.25rem 1rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 700;
  color: white;
  z-index: 1;
}

.card-badge.urgent {
  background: #ef4444;
}

.card-badge.important {
  background: #f59e0b;
}

.card-badge.general {
  background: #3b82f6;
}

.card-badge.event {
  background: #8b5cf6;
}

.card-badge.update {
  background: #10b981;
}

.card-content {
  padding: 1.5rem;
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
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.status-badge {
  padding: 0.2rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.status-badge.draft {
  background: #f1f5f9;
  color: #475569;
}

.status-badge.archived {
  background: #e2e8f0;
  color: #64748b;
}

.status-badge.important {
  background: #fef3c7;
  color: #d97706;
}

.card-actions {
  display: flex;
  gap: 0.5rem;
}

.action-btn {
  background: none;
  border: none;
  padding: 0.3rem 0.6rem;
  border-radius: 0.5rem;
  font-size: 1.1rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.action-btn:hover {
  background: #f1f5f9;
  transform: scale(1.1);
}

.action-btn.delete:hover {
  background: #fee2e2;
}

.action-btn.edit:hover {
  background: #dbeafe;
}

.action-btn.toggle-status:hover {
  background: #d1fae5;
}

.action-btn.duplicate:hover {
  background: #fef3c7;
}

.card-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  margin-bottom: 1rem;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #64748b;
  font-size: 0.875rem;
}

.meta-icon {
  font-size: 1rem;
}

.card-summary {
  color: #475569;
  line-height: 1.6;
  margin: 0 0 1rem 0;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.read-more-btn {
  background: none;
  border: none;
  color: #3b82f6;
  font-weight: 600;
  cursor: pointer;
  padding: 0;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.read-more-btn:hover {
  color: #2563eb;
  text-decoration: underline;
}

.card-tags {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.tag {
  padding: 0.2rem 0.75rem;
  background: #f1f5f9;
  border-radius: 9999px;
  font-size: 0.8rem;
  color: #475569;
  cursor: pointer;
  transition: all 0.3s ease;
}

.tag:hover {
  background: #e2e8f0;
  transform: scale(1.05);
}

.card-expanded {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 2px solid #f1f5f9;
  animation: slideDown 0.3s ease;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.expanded-content p {
  color: #334155;
  line-height: 1.8;
  margin-bottom: 1rem;
}

.attachments, .related-links {
  margin-top: 1.5rem;
}

.attachments h4, .related-links h4 {
  color: #1e293b;
  font-size: 1rem;
  margin-bottom: 0.75rem;
}

.attachment-list, .links-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.attachment-list li, .links-list li {
  padding: 0.5rem 0;
  border-bottom: 1px solid #f1f5f9;
}

.attachment-list li:last-child, .links-list li:last-child {
  border-bottom: none;
}

.attachment-link, .link-item {
  color: #3b82f6;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
}

.attachment-link:hover, .link-item:hover {
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
  background: white;
  border-radius: 1rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.empty-title {
  font-size: 1.5rem;
  color: #1e293b;
  margin: 0 0 0.5rem 0;
}

.empty-description {
  color: #64748b;
  margin-bottom: 1.5rem;
}

.btn-create-empty {
  background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
  color: white;
  border: none;
  padding: 0.75rem 2rem;
  border-radius: 0.75rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-create-empty:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

/* Paginación */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin-top: 2rem;
  padding: 1rem 0;
}

.page-btn {
  padding: 0.5rem 1rem;
  border: 2px solid #e2e8f0;
  background: white;
  border-radius: 0.75rem;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;
  color: #475569;
}

.page-btn:hover:not(:disabled) {
  background: #3b82f6;
  color: white;
  border-color: #3b82f6;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-numbers {
  display: flex;
  gap: 0.5rem;
}

.page-number {
  padding: 0.5rem 0.75rem;
  border: 2px solid transparent;
  border-radius: 0.5rem;
  background: none;
  cursor: pointer;
  font-weight: 500;
  color: #475569;
  transition: all 0.3s ease;
  min-width: 2.5rem;
}

.page-number:hover {
  background: #f1f5f9;
}

.page-number.active {
  background: #3b82f6;
  color: white;
  border-color: #3b82f6;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(6px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 1rem;
}

.modal-content {
  background: white;
  border-radius: 1.5rem;
  max-width: 800px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  padding: 2.5rem;
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
  margin-bottom: 2rem;
}

.modal-header h2 {
  margin: 0;
  color: #1e293b;
  font-size: 1.75rem;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.8rem;
  cursor: pointer;
  color: #94a3b8;
  transition: all 0.3s ease;
  padding: 0.25rem 0.5rem;
  border-radius: 0.5rem;
}

.close-btn:hover {
  color: #1e293b;
  background: #f1f5f9;
  transform: rotate(90deg);
}

.modal-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  position: relative;
}

.form-group label {
  font-weight: 600;
  color: #1e293b;
  font-size: 0.9rem;
}

.form-group input,
.form-group textarea,
.form-group select {
  padding: 0.75rem 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 0.75rem;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  font-family: inherit;
  background: #f8fafc;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  background: white;
}

.form-group textarea {
  resize: vertical;
}

.char-count {
  position: absolute;
  bottom: 0.5rem;
  right: 1rem;
  font-size: 0.75rem;
  color: #94a3b8;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.tags-input {
  display: flex;
  gap: 0.5rem;
}

.tags-input input {
  flex: 1;
}

.add-tag-btn {
  padding: 0.75rem 1.25rem;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 0.75rem;
  cursor: pointer;
  font-size: 1.2rem;
  transition: all 0.3s ease;
}

.add-tag-btn:hover {
  background: #2563eb;
}

.tags-display {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.tag-item {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.25rem 0.75rem;
  background: #dbeafe;
  color: #1e40af;
  border-radius: 9999px;
  font-size: 0.85rem;
  font-weight: 500;
}

.remove-tag {
  background: none;
  border: none;
  cursor: pointer;
  color: #1e40af;
  padding: 0 0.25rem;
  font-size: 0.8rem;
}

.remove-tag:hover {
  color: #dc2626;
}

.form-group.checkbox {
  flex-direction: row;
  align-items: center;
  gap: 0.75rem;
}

.form-group.checkbox label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-weight: 500;
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
  padding-top: 1.5rem;
  border-top: 2px solid #f1f5f9;
}

.btn-secondary {
  padding: 0.75rem 1.5rem;
  background: #f1f5f9;
  border: 2px solid #e2e8f0;
  border-radius: 0.75rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  color: #475569;
}

.btn-secondary:hover {
  background: #e2e8f0;
}

.btn-primary {
  padding: 0.75rem 2rem;
  background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
  color: white;
  border: none;
  border-radius: 0.75rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
}

/* Confirmación */
.confirm-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(6px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 2000;
}

.confirm-dialog {
  background: white;
  border-radius: 1.5rem;
  padding: 2.5rem;
  max-width: 450px;
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
  font-size: 4rem;
  margin-bottom: 0.5rem;
}

.confirm-title {
  color: #1e293b;
  margin: 0.5rem 0;
  font-size: 1.5rem;
}

.confirm-message {
  color: #475569;
  margin: 1rem 0 2rem 0;
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
  border-radius: 0.75rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-cancel {
  background: #f1f5f9;
  color: #475569;
}

.btn-cancel:hover {
  background: #e2e8f0;
}

.btn-confirm {
  background: #ef4444;
  color: white;
}

.btn-confirm:hover {
  background: #dc2626;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

/* Toast */
.toast {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  padding: 1rem 1.5rem;
  border-radius: 1rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  z-index: 3000;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  animation: slideIn 0.3s ease;
  max-width: 400px;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.toast.success {
  background: #dcfce7;
  color: #166534;
  border-left: 4px solid #22c55e;
}

.toast.error {
  background: #fee2e2;
  color: #991b1b;
  border-left: 4px solid #ef4444;
}

.toast.info {
  background: #dbeafe;
  color: #1e40af;
  border-left: 4px solid #3b82f6;
}

.toast-icon {
  font-size: 1.5rem;
}

.toast-message {
  font-weight: 500;
}

.toast-close {
  background: none;
  border: none;
  cursor: pointer;
  color: inherit;
  font-size: 1.2rem;
  padding: 0 0.25rem;
}

/* Responsive */
@media (max-width: 768px) {
  .official-communications {
    padding: 1rem;
  }

  .communications-header {
    padding: 1.5rem;
  }

  .header-title {
    font-size: 1.8rem;
  }

  .filters-container {
    flex-direction: column;
  }

  .filters-group {
    flex-direction: column;
  }

  .filter-select {
    width: 100%;
  }

  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .modal-content {
    padding: 1.5rem;
    margin: 0.5rem;
  }

  .card-header {
    flex-direction: column;
  }

  .card-actions {
    width: 100%;
    justify-content: flex-end;
  }

  .page-numbers {
    display: none;
  }

  .toast {
    bottom: 1rem;
    right: 1rem;
    left: 1rem;
    max-width: none;
  }
}

@media (max-width: 480px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }

  .card-meta {
    flex-direction: column;
    gap: 0.5rem;
  }

  .card-footer {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
