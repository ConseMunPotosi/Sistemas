<template>
  <div class="press-release-board">
    <!-- Header -->
    <div class="press-header">
      <h2 class="press-title">📰 Boletines de Prensa</h2>
    </div>

    <!-- Lista de Boletines -->
    <div v-if="filteredReleases.length > 0" class="press-list">
      <div
        v-for="release in paginatedReleases"
        :key="release.id"
        class="press-release-item"
        :class="{ featured: release.featured }"
      >
        <div class="release-main">
          <!-- Miniatura del PDF -->
          <div class="release-thumbnail">
            <div v-if="release.pdfUrl" class="pdf-thumbnail" @click="openPdf(release.pdfUrl)">
              <div class="pdf-icon-wrapper">
                <svg class="pdf-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" />
                  <path d="M14 2v6h6" />
                  <path d="M12 18v-4" />
                  <path d="M12 10v.01" />
                </svg>
                <span class="pdf-badge">PDF</span>
              </div>
              <div class="pdf-info">
                <span class="pdf-name">{{ getPdfName(release.pdfUrl) }}</span>
                <span class="pdf-size">{{ getPdfSize(release.pdfSize) }}</span>
              </div>
              <button class="pdf-download-btn" @click.stop="downloadPdf(release.pdfUrl, release.title)">
                <svg class="download-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                  <polyline points="7 10 12 15 17 10" />
                  <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
              </button>
            </div>
            <div v-else class="no-pdf">
              <span class="no-pdf-icon">📄</span>
              <span class="no-pdf-text">Sin PDF</span>
            </div>
          </div>

          <!-- Información del boletín -->
          <div class="release-info">
            <div class="release-header">
              <div class="release-title-section">
                <h3 class="release-title">{{ release.title }}</h3>
              </div>
            </div>

            <div class="release-meta">
              <span class="meta-item">
                <span class="meta-icon">📅</span>
                {{ formatDate(release.publishDate) }}
              </span>
            </div>

            <p class="release-summary">{{ release.summary }}</p>

            <button
              class="read-more-btn"
              @click="toggleExpand(release.id)"
            >
              {{ expandedReleases[release.id] ? 'Leer menos' : 'Leer más' }}
              <span class="btn-arrow">→</span>
            </button>

            <div v-if="expandedReleases[release.id]" class="release-content">
              <p>{{ release.content }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Paginación -->
    <div v-if="totalPages > 1" class="pagination">
      <div class="pagination-info">
        <span class="info-text">
          Mostrando {{ (currentPage - 1) * itemsPerPage + 1 }} -
          {{ Math.min(currentPage * itemsPerPage, filteredReleases.length) }}
          de {{ filteredReleases.length }} boletines
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
import { ref, computed, onMounted } from 'vue'

// Props
const props = defineProps({
  initialReleases: {
    type: Array,
    default: () => []
  }
})

// State
const releases = ref([])
const searchQuery = ref('')
const selectedCategory = ref('')
const sortOrder = ref('desc')
const currentPage = ref(1)
const itemsPerPage = 5
const expandedReleases = ref({})

// Computed
const categories = computed(() => {
  const cats = new Set()
  releases.value.forEach(r => cats.add(r.category))
  return Array.from(cats)
})

const filteredReleases = computed(() => {
  let filtered = [...releases.value]

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(release =>
      release.title.toLowerCase().includes(query) ||
      release.summary.toLowerCase().includes(query) ||
      release.content.toLowerCase().includes(query)
    )
  }

  if (selectedCategory.value) {
    filtered = filtered.filter(release =>
      release.category === selectedCategory.value
    )
  }

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
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const toggleExpand = (id) => {
  expandedReleases.value[id] = !expandedReleases.value[id]
}

const getPdfName = (url) => {
  if (!url) return 'Documento'
  const parts = url.split('/')
  const filename = parts[parts.length - 1]
  return filename.length > 20 ? filename.substring(0, 20) + '...' : filename
}

const getPdfSize = (size) => {
  if (!size) return 'Tamaño desconocido'
  const bytes = parseInt(size)
  if (bytes < 1024) return bytes + ' B'
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'
  if (bytes < 1024 * 1024 * 1024) return (bytes / (1024 * 1024)).toFixed(1) + ' MB'
  return (bytes / (1024 * 1024 * 1024)).toFixed(1) + ' GB'
}

const openPdf = (url) => {
  if (url) {
    window.open(url, '_blank')
  }
}

const downloadPdf = (url, title) => {
  if (url) {
    const link = document.createElement('a')
    link.href = url
    link.download = `${title || 'boletin'}.pdf`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  }
}

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
    const container = document.querySelector('.press-list')
    if (container) {
      container.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }
}

const resetFilters = () => {
  searchQuery.value = ''
  selectedCategory.value = ''
  sortOrder.value = 'desc'
  currentPage.value = 1
}

// Datos de ejemplo - 8 boletines de prensa
const loadReleases = () => {
  releases.value = [
    {
      id: '1',
      title: 'Concejo Municipal Aprueba Presupuesto 2025 para Obras de Infraestructura',
      summary: 'En sesión ordinaria, el Concejo Municipal aprobó el presupuesto anual para la gestión 2025, destinando más de Bs. 250 millones para proyectos de infraestructura y desarrollo urbano.',
      content: 'En una sesión histórica, el Concejo Municipal de Potosí aprobó por mayoría absoluta el presupuesto general para la gestión 2025. El monto total asciende a Bs. 452.5 millones, de los cuales el 60% será destinado a proyectos de infraestructura vial, saneamiento básico y equipamiento urbano. Los concejales destacaron la importancia de esta inversión para el desarrollo sostenible del municipio, priorizando las zonas periféricas que históricamente han tenido menos acceso a servicios básicos. El presupuesto también incluye partidas específicas para la mejora de espacios públicos, parques y plazas, así como para la implementación de un sistema de transporte público eficiente.',
      publishDate: '2025-01-20',
      pdfUrl: '/pdfs/presupuesto-2025.pdf',
      pdfSize: '2800000'
    },
    {
      id: '2',
      title: 'Lanzamiento del Programa de Emprendimiento Juvenil "Potosí Emprende 2025"',
      summary: 'El Concejo Municipal en alianza con la Cámara de Comercio lanza el programa "Potosí Emprende 2025" para apoyar a jóvenes emprendedores con financiamiento y asesoría técnica.',
      content: 'El programa "Potosí Emprende 2025" busca impulsar el talento emprendedor de los jóvenes potosinos de 18 a 35 años. Los participantes recibirán capacitación en gestión empresarial, marketing digital y finanzas. Además, los 20 mejores proyectos recibirán un capital semilla de hasta Bs. 15.000 y acompañamiento técnico durante los primeros 6 meses de operación. Las inscripciones estarán abiertas desde el 1 de febrero hasta el 15 de marzo de 2025. Los interesados podrán postular sus proyectos a través de la plataforma digital del Concejo Municipal.',
      publishDate: '2025-01-18',
      pdfUrl: '/pdfs/potosi-emprende-2025.pdf',
      pdfSize: '1800000'
    },
    {
      id: '3',
      title: 'Concejo Municipal Declara Patrimonio Cultural la Festividad del Tinku',
      summary: 'El pleno del Concejo Municipal aprobó por unanimidad declarar Patrimonio Cultural Inmaterial del municipio la tradicional Festividad del Tinku, que se celebra cada año en el mes de mayo.',
      content: 'La Festividad del Tinku, una de las manifestaciones culturales más importantes de la región, ha sido reconocida oficialmente como Patrimonio Cultural Inmaterial del Municipio de Potosí. Esta declaración permitirá destinar recursos municipales para la preservación y promoción de esta tradición ancestral que combina danza, música y rituales andinos. El concejal presidente de la Comisión de Cultura destacó que esta medida contribuirá a fortalecer la identidad cultural potosina y atraerá turismo durante la celebración. Se prevé la realización de talleres, exposiciones y eventos especiales para difundir el significado histórico y cultural del Tinku.',
      publishDate: '2025-01-15',
      pdfUrl: '/pdfs/tinku-patrimonio-cultural.pdf',
      pdfSize: '2200000'
    },
    {
      id: '4',
      title: 'Plan de Movilidad Urbana para el Centro Histórico de Potosí',
      summary: 'El Concejo Municipal presenta el nuevo Plan de Movilidad Urbana que transformará el centro histórico con calles peatonales y ciclovías.',
      content: 'El Plan de Movilidad Urbana para el Centro Histórico contempla la peatonalización de las principales calles del casco antiguo, la implementación de ciclovías y la mejora del transporte público. El proyecto, que se ejecutará en tres fases durante 2025 y 2026, incluye la instalación de señalética turística, mobiliario urbano y áreas de descanso. La inversión total asciende a Bs. 35 millones y se espera que beneficie a más de 50.000 personas que diariamente transitan por esta zona. El plan también considera la accesibilidad universal con rampas y señalización para personas con discapacidad.',
      publishDate: '2025-01-12',
      pdfUrl: '/pdfs/plan-movilidad-urbana.pdf',
      pdfSize: '3200000'
    },
    {
      id: '5',
      title: 'Resultados de la Encuesta de Satisfacción Ciudadana 2024',
      summary: 'El Concejo Municipal presenta los resultados de la Encuesta de Satisfacción Ciudadana 2024, que muestra un 78% de aprobación en la gestión municipal.',
      content: 'La Encuesta de Satisfacción Ciudadana 2024, realizada a más de 5.000 ciudadanos de los diferentes distritos, reveló que el 78% de los encuestados aprueba la gestión municipal. Los aspectos mejor valorados fueron la transparencia en la gestión de recursos (85%), la ejecución de obras públicas (82%) y la atención a las demandas vecinales (76%). Las áreas de oportunidad identificadas incluyen la mejora del alumbrado público, la recolección de residuos y el mantenimiento de áreas verdes. El Concejo Municipal se comprometió a implementar acciones correctivas en estos aspectos durante el primer semestre de 2025.',
      publishDate: '2025-01-10',
      pdfUrl: '/pdfs/encuesta-satisfaccion-2024.pdf',
      pdfSize: '1500000'
    },
    {
      id: '6',
      title: 'Concejo Municipal Conforma Comisión de Seguimiento al Proyecto del Tren Metropolitano',
      summary: 'El Concejo Municipal ha conformado una comisión especial de seguimiento al proyecto del Tren Metropolitano, que conectará Potosí con las ciudades vecinas.',
      content: 'La comisión especial estará integrada por 5 concejales y tendrá como función principal dar seguimiento al avance del proyecto del Tren Metropolitano, una iniciativa que busca conectar Potosí con las ciudades de Sucre, Oruro y Cochabamba. El proyecto, que contará con financiamiento del Banco Interamericano de Desarrollo (BID), prevé la construcción de 120 kilómetros de vía férrea y 5 estaciones intermedias. La comisión realizará reuniones mensuales con el Órgano Ejecutivo para evaluar los avances y garantizar la transparencia en la ejecución de este megaproyecto que transformará la región.',
      publishDate: '2025-01-08',
      pdfUrl: '/pdfs/tren-metropolitano-comision.pdf',
      pdfSize: '1900000'
    },
    {
      id: '7',
      title: 'Campaña de Reforestación Urbana "Potosí Verde 2025"',
      summary: 'El Concejo Municipal lanza la campaña de reforestación urbana "Potosí Verde 2025" con la meta de plantar 10.000 árboles en toda la ciudad.',
      content: 'La campaña "Potosí Verde 2025" tiene como objetivo plantar 10.000 árboles nativos en diferentes zonas de la ciudad, con especial énfasis en áreas periurbanas y márgenes de ríos. La iniciativa, que cuenta con el apoyo de la Gobernación y organizaciones ambientales, se desarrollará durante los meses de febrero a abril. Los vecinos podrán participar solicitando árboles para sus barrios a través de las juntas vecinales. La campaña también contempla talleres de educación ambiental en escuelas y colegios para sensibilizar a los niños y jóvenes sobre la importancia de conservar el medio ambiente.',
      publishDate: '2025-01-06',
      pdfUrl: '/pdfs/potosi-verde-2025.pdf',
      pdfSize: '1600000'
    },
    {
      id: '8',
      title: 'Acuerdo de Cooperación con la Universidad Autónoma Tomás Frías',
      summary: 'El Concejo Municipal y la Universidad Autónoma Tomás Frías firman un acuerdo de cooperación para el desarrollo de proyectos de investigación y extensión universitaria.',
      content: 'El acuerdo marco de cooperación entre el Concejo Municipal y la Universidad Autónoma Tomás Frías establece bases para la colaboración en áreas como investigación aplicada, pasantías estudiantiles, y proyectos de desarrollo comunitario. La alianza busca fortalecer el vínculo entre el gobierno municipal y la academia, generando espacios de diálogo y trabajo conjunto para abordar problemáticas urbanas desde un enfoque científico y técnico. En una primera fase, se implementarán proyectos piloto en los distritos 10 y 15, enfocados en temas de gestión de residuos sólidos y planificación urbana participativa.',
      publishDate: '2025-01-03',
      pdfUrl: '/pdfs/acuerdo-uatf-municipio.pdf',
      pdfSize: '2100000'
    }
  ]
}

// Lifecycle
onMounted(() => {
  if (props.initialReleases && props.initialReleases.length > 0) {
    releases.value = props.initialReleases
  } else {
    loadReleases()
  }
})
</script>

<style scoped>
/* Estilo principal del componente */
.press-release-board {
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
.press-header {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 2rem;
}

.press-title {
  font-weight: 800;
  color: #cc0000;
}

/* Items de boletines */
.press-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.press-release-item {
  background: white;
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
  background: rgba(255, 255, 255, 0.96);
  border-left-color: #cc0000;
}

/* Layout principal */
.release-main {
  display: flex;
  gap: 1.5rem;
  align-items: stretch;
}

/* Miniatura del PDF */
.release-thumbnail {
  flex: 0 0 180px;
  min-height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.pdf-thumbnail {
  width: 100%;
  height: 100%;
  min-height: 200px;
  background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
  border-radius: 0.75rem;
  border: 2px solid #e2e8f0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
}

.pdf-thumbnail:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
  border-color: #cc0000;
}

.pdf-icon-wrapper {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 0.5rem;
}

.pdf-icon {
  width: 64px;
  height: 64px;
  color: #cc0000;
}

.pdf-badge {
  position: absolute;
  top: -8px;
  right: -24px;
  background: #cc0000;
  color: white;
  font-size: 0.6rem;
  font-weight: 700;
  padding: 0.15rem 0.5rem;
  border-radius: 4px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.pdf-info {
  text-align: center;
  width: 100%;
}

.pdf-name {
  display: block;
  font-size: 0.75rem;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 0.2rem;
  word-break: break-all;
}

.pdf-size {
  font-size: 0.65rem;
  color: #718096;
}

.pdf-download-btn {
  position: absolute;
  bottom: 0.5rem;
  right: 0.5rem;
  background: rgba(204, 0, 0, 0.9);
  color: white;
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  opacity: 0;
}

.pdf-thumbnail:hover .pdf-download-btn {
  opacity: 1;
}

.pdf-download-btn:hover {
  background: #8B0000;
  transform: scale(1.1);
}

.download-icon {
  width: 16px;
  height: 16px;
  stroke: white;
}

.no-pdf {
  width: 100%;
  height: 100%;
  min-height: 200px;
  background: #f7fafc;
  border-radius: 0.75rem;
  border: 2px dashed #e2e8f0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.no-pdf-icon {
  font-size: 2.5rem;
}

.no-pdf-text {
  font-size: 0.8rem;
  color: #a0aec0;
}

/* Información del boletín */
.release-info {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.release-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0.75rem;
}

.release-title-section {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.release-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: #2d3748;
  margin: 0;
  line-height: 1.3;
}

.featured-badge {
  background: #f6ad55;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.release-meta {
  display: flex;
  gap: 1.5rem;
  margin-bottom: 0.75rem;
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
  margin: 0 0 0.75rem 0;
  font-size: 0.95rem;
}

.read-more-btn {
  background: none;
  border: none;
  color: #cc0000;
  font-weight: 600;
  cursor: pointer;
  padding: 0;
  font-size: 0.95rem;
  align-self: flex-start;
}

.read-more-btn:hover {
  text-decoration: underline;
}

.release-content {
  margin-top: 0.75rem;
  padding-top: 0.75rem;
  border-top: 2px solid #f7fafc;
}

.release-content p {
  color: #2d3748;
  line-height: 1.8;
  font-size: 0.95rem;
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

.empty-state h3 {
  color: #2d3748;
  margin-bottom: 0.5rem;
}

.empty-state p {
  color: #718096;
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
@media (max-width: 768px) {
  .press-release-board {
    padding: 1rem;
  }

  .press-header {
    flex-direction: column;
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

  .release-main {
    flex-direction: column;
    gap: 1rem;
  }

  .release-thumbnail {
    flex: 1 1 auto;
    min-height: 150px;
  }

  .pdf-thumbnail {
    min-height: 150px;
    max-height: 200px;
  }

  .no-pdf {
    min-height: 150px;
  }

  .release-header {
    flex-direction: column;
    gap: 0.75rem;
  }

  .release-meta {
    flex-direction: column;
    gap: 0.5rem;
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
  .press-title {
    font-size: 1.5rem;
  }

  .release-title {
    font-size: 1rem;
  }

  .release-thumbnail {
    min-height: 120px;
  }

  .pdf-thumbnail {
    min-height: 120px;
    padding: 0.75rem;
  }

  .pdf-icon {
    width: 40px;
    height: 40px;
  }

  .no-pdf {
    min-height: 120px;
  }

  .pagination-info .info-text {
    font-size: 0.8rem;
  }
}
</style>
