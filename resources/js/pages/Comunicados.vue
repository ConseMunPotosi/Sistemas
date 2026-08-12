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

    <!-- Lista de Comunicados -->
    <section class="communications-list">
      <div v-if="filteredCommunications.length > 0" class="list-container">
        <div
          v-for="communication in paginatedCommunications"
          :key="communication.id"
          class="communication-card"
          :class="{
            'is-urgent': communication.type === 'urgent'
          }"
        >
          <!-- Imagen del comunicado -->
          <div class="card-image-wrapper">
            <img
              :src="communication.image || '/images/default-comunicado.jpg'"
              :alt="communication.title"
              class="card-image"
              loading="lazy"
            />
          </div>

          <div class="card-content">
            <div class="card-header">
              <div class="card-title-group">
                <h3 class="card-title">{{ communication.title }}</h3>
              </div>
            </div>

            <div class="card-meta">
              <span class="meta-item">
                <span class="meta-icon">📅</span>
                {{ formatDate(communication.publishDate) }}
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
          {{ searchQuery ? 'No se encontraron resultados para tu búsqueda' : 'No hay comunicados disponibles' }}
        </p>
        <button class="btn-primary" @click="resetFilters">
          Limpiar filtros
        </button>
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
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

// Props
const props = defineProps({
  initialCommunications: {
    type: Array,
    default: () => []
  }
})

// State
const communications = ref([])
const searchQuery = ref('')
const sortOrder = ref('desc')
const currentPage = ref(1)
const itemsPerPage = 5
const expandedCommunications = ref({})

// Computed
const filteredCommunications = computed(() => {
  let filtered = [...communications.value]

  // Búsqueda
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(comm =>
      comm.title.toLowerCase().includes(query) ||
      comm.summary.toLowerCase().includes(query) ||
      comm.content.toLowerCase().includes(query)
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
  const date = new Date(dateString)
  return date.toLocaleString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
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

// Datos de ejemplo - 8 comunicados oficiales con imágenes
const loadCommunications = () => {
  communications.value = [
    {
      id: '1',
      title: 'Concejo Municipal Aprueba Presupuesto 2025 para Obras de Infraestructura',
      summary: 'En sesión ordinaria, el Concejo Municipal aprobó el presupuesto anual para la gestión 2025, destinando más de Bs. 250 millones para proyectos de infraestructura y desarrollo urbano.',
      content: 'En una sesión histórica, el Concejo Municipal de Potosí aprobó por mayoría absoluta el presupuesto general para la gestión 2025. El monto total asciende a Bs. 452.5 millones, de los cuales el 60% será destinado a proyectos de infraestructura vial, saneamiento básico y equipamiento urbano. Los concejales destacaron la importancia de esta inversión para el desarrollo sostenible del municipio, priorizando las zonas periféricas que históricamente han tenido menos acceso a servicios básicos.',
      publishDate: '2025-01-20T09:00:00',
      image: 'https://picsum.photos/seed/presupuesto/600/400',
      attachments: [
        { name: 'Presupuesto_2025.pdf', url: '#', size: '2.8 MB' }
      ],
      links: [
        { label: 'Detalle del presupuesto', url: '#' }
      ]
    },
    {
      id: '2',
      title: 'Lanzamiento del Programa de Emprendimiento Juvenil "Potosí Emprende 2025"',
      summary: 'El Concejo Municipal en alianza con la Cámara de Comercio lanza el programa "Potosí Emprende 2025" para apoyar a jóvenes emprendedores.',
      content: 'El programa "Potosí Emprende 2025" busca impulsar el talento emprendedor de los jóvenes potosinos de 18 a 35 años. Los participantes recibirán capacitación en gestión empresarial, marketing digital y finanzas. Además, los 20 mejores proyectos recibirán un capital semilla de hasta Bs. 15.000 y acompañamiento técnico durante los primeros 6 meses de operación.',
      publishDate: '2025-01-18T10:30:00',
      image: 'https://picsum.photos/seed/emprendimiento/600/400',
      attachments: [],
      links: [
        { label: 'Bases del concurso', url: '#' }
      ]
    },
    {
      id: '3',
      title: 'Concejo Municipal Declara Patrimonio Cultural la Festividad del Tinku',
      summary: 'El pleno del Concejo Municipal aprobó por unanimidad declarar Patrimonio Cultural Inmaterial del municipio la tradicional Festividad del Tinku.',
      content: 'La Festividad del Tinku, una de las manifestaciones culturales más importantes de la región, ha sido reconocida oficialmente como Patrimonio Cultural Inmaterial del Municipio de Potosí. Esta declaración permitirá destinar recursos municipales para la preservación y promoción de esta tradición ancestral que combina danza, música y rituales andinos.',
      publishDate: '2025-01-15T14:00:00',
      image: 'https://picsum.photos/seed/tinku/600/400',
      attachments: [
        { name: 'Declaratoria_Tinku.pdf', url: '#', size: '2.2 MB' }
      ],
      links: []
    },
    {
      id: '4',
      title: 'Plan de Movilidad Urbana para el Centro Histórico de Potosí',
      summary: 'El Concejo Municipal presenta el nuevo Plan de Movilidad Urbana que transformará el centro histórico con calles peatonales y ciclovías.',
      content: 'El Plan de Movilidad Urbana para el Centro Histórico contempla la peatonalización de las principales calles del casco antiguo, la implementación de ciclovías y la mejora del transporte público. El proyecto, que se ejecutará en tres fases durante 2025 y 2026, incluye la instalación de señalética turística, mobiliario urbano y áreas de descanso.',
      publishDate: '2025-01-12T11:00:00',
      image: 'https://picsum.photos/seed/movilidad/600/400',
      attachments: [
        { name: 'Plan_Movilidad.pdf', url: '#', size: '3.2 MB' }
      ],
      links: []
    },
    {
      id: '5',
      title: 'Resultados de la Encuesta de Satisfacción Ciudadana 2024',
      summary: 'El Concejo Municipal presenta los resultados de la Encuesta de Satisfacción Ciudadana 2024, que muestra un 78% de aprobación en la gestión municipal.',
      content: 'La Encuesta de Satisfacción Ciudadana 2024, realizada a más de 5.000 ciudadanos de los diferentes distritos, reveló que el 78% de los encuestados aprueba la gestión municipal. Los aspectos mejor valorados fueron la transparencia en la gestión de recursos (85%), la ejecución de obras públicas (82%) y la atención a las demandas vecinales (76%).',
      publishDate: '2025-01-10T08:00:00',
      image: 'https://picsum.photos/seed/encuesta/600/400',
      attachments: [
        { name: 'Encuesta_2024.pdf', url: '#', size: '1.5 MB' }
      ],
      links: []
    },
    {
      id: '6',
      title: 'Concejo Municipal Conforma Comisión de Seguimiento al Proyecto del Tren Metropolitano',
      summary: 'El Concejo Municipal ha conformado una comisión especial de seguimiento al proyecto del Tren Metropolitano, que conectará Potosí con las ciudades vecinas.',
      content: 'La comisión especial estará integrada por 5 concejales y tendrá como función principal dar seguimiento al avance del proyecto del Tren Metropolitano, una iniciativa que busca conectar Potosí con las ciudades de Sucre, Oruro y Cochabamba. El proyecto contará con financiamiento del Banco Interamericano de Desarrollo (BID).',
      publishDate: '2025-01-08T15:00:00',
      image: 'https://picsum.photos/seed/tren/600/400',
      attachments: [],
      links: [
        { label: 'Proyecto Tren Metropolitano', url: '#' }
      ]
    },
    {
      id: '7',
      title: 'Campaña de Reforestación Urbana "Potosí Verde 2025"',
      summary: 'El Concejo Municipal lanza la campaña de reforestación urbana "Potosí Verde 2025" con la meta de plantar 10.000 árboles en toda la ciudad.',
      content: 'La campaña "Potosí Verde 2025" tiene como objetivo plantar 10.000 árboles nativos en diferentes zonas de la ciudad, con especial énfasis en áreas periurbanas y márgenes de ríos. La iniciativa cuenta con el apoyo de la Gobernación y organizaciones ambientales, y se desarrollará durante los meses de febrero a abril.',
      publishDate: '2025-01-06T09:30:00',
      image: 'https://picsum.photos/seed/reforestacion/600/400',
      attachments: [
        { name: 'Plan_Reforestacion.pdf', url: '#', size: '1.6 MB' }
      ],
      links: []
    },
    {
      id: '8',
      title: 'Acuerdo de Cooperación con la Universidad Autónoma Tomás Frías',
      summary: 'El Concejo Municipal y la Universidad Autónoma Tomás Frías firman un acuerdo de cooperación para el desarrollo de proyectos de investigación y extensión universitaria.',
      content: 'El acuerdo marco de cooperación entre el Concejo Municipal y la Universidad Autónoma Tomás Frías establece bases para la colaboración en áreas como investigación aplicada, pasantías estudiantiles, y proyectos de desarrollo comunitario. La alianza busca fortalecer el vínculo entre el gobierno municipal y la academia.',
      publishDate: '2025-01-03T10:00:00',
      image: 'https://picsum.photos/seed/universidad/600/400',
      attachments: [
        { name: 'Acuerdo_UATF.pdf', url: '#', size: '2.1 MB' }
      ],
      links: []
    }
  ]
}

// Watch para reiniciar página al cambiar filtros
watch([searchQuery, sortOrder], () => {
  currentPage.value = 1
})

// Lifecycle
onMounted(() => {
  if (props.initialCommunications && props.initialCommunications.length > 0) {
    communications.value = props.initialCommunications
  } else {
    loadCommunications()
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

.header-left {
  text-align: center;
}

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

.header-title .icon {
  font-size: 2rem;
}

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
.communications-list {
  margin-top: 2rem;
}

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

.card-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.communication-card:hover .card-image {
  transform: scale(1.05);
}

.urgent-badge {
  position: absolute;
  top: 1rem;
  left: 1rem;
  background: #ef4444;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 700;
  box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

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

.meta-icon {
  font-size: 1rem;
}

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

.attachments,
.related-links {
  margin-top: 1.5rem;
}

.attachments h4,
.related-links h4 {
  color: #1e293b;
  font-size: 1rem;
  margin-bottom: 0.75rem;
}

.attachment-list,
.links-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.attachment-list li,
.links-list li {
  padding: 0.5rem 0;
  border-bottom: 1px solid #f1f5f9;
}

.attachment-list li:last-child,
.links-list li:last-child {
  border-bottom: none;
}

.attachment-link,
.link-item {
  color: #3b82f6;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
}

.attachment-link:hover,
.link-item:hover {
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
  .communication-card {
    flex-direction: column;
  }

  .card-image-wrapper {
    flex: 0 0 200px;
    width: 100%;
  }

  .card-image {
    width: 100%;
    height: 200px;
  }
}

@media (max-width: 768px) {
  .official-communications {
    padding: 1rem;
  }

  .header-title {
    font-size: 1.8rem;
  }

  .header-subtitle {
    font-size: 0.95rem;
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

  .card-image-wrapper {
    flex: 0 0 180px;
  }

  .card-image {
    height: 180px;
  }

  .card-title {
    font-size: 1.1rem;
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
  .header-title {
    font-size: 1.5rem;
  }

  .card-image-wrapper {
    flex: 0 0 150px;
  }

  .card-image {
    height: 150px;
  }

  .card-title {
    font-size: 1rem;
  }

  .card-meta {
    flex-direction: column;
    gap: 0.5rem;
  }

  .pagination-info .info-text {
    font-size: 0.8rem;
  }
}
</style>
