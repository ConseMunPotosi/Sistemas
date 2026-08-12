<template>
  <div class="noticias-container">
    <!-- ========== HEADER ========== -->
    <div class="noticias-header">
      <h2 class="noticias-titulo">
        Noticias del<br>Concejo Municipal de Potosí
      </h2>
      <p class="noticias-subtitulo">
        Este espacio propio del Concejo Municipal de Potosí es digital dinámico y confiable,
        cuyo objetivo central es mantener a la ciudadanía informada sobre la actividad legislativa
        y de fiscalización que se desarrolla en el municipio.
      </p>
    </div>

    <!-- ========== FILTROS ========== -->
    <div class="noticias-filtros">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Buscar noticias..."
        class="search-input"
      />
      <select v-model="selectedCategory" class="filter-select">
        <option value="">Todas las categorías</option>
        <option v-for="cat in allCategories" :key="cat" :value="cat">
          {{ cat }}
        </option>
      </select>
      <select v-model="sortOrder" class="filter-select">
        <option value="desc">Más recientes</option>
        <option value="asc">Más antiguos</option>
      </select>
    </div>

    <!-- ========== CONTENIDO PRINCIPAL ========== -->
    <div v-if="filteredNoticias.length > 0" class="noticias-grid">
      <div
        v-for="noticia in paginatedNoticias"
        :key="noticia.id"
        class="noticia-card"
        :class="{ destacado: noticia.destacado }"
      >
        <!-- Badge de tipo -->
        <div class="card-tipo-badge">
          <span>📰 Noticia</span>
          <span v-if="noticia.destacado" class="destacado-badge">⭐ Destacado</span>
        </div>

        <!-- Imagen -->
        <div class="card-img-wrapper">
          <img
            :src="noticia.imagen || '/images/default-noticia.jpg'"
            class="card-img"
            :alt="noticia.titulo"
            loading="lazy"
          />
          <span v-if="noticia.imagenes && noticia.imagenes.length > 1" class="multi-badge">
            📸 {{ noticia.imagenes.length }}
          </span>
        </div>

        <!-- Información -->
        <div class="card-body">
          <div class="card-meta">
            <span class="meta-categoria">{{ noticia.categoria }}</span>
            <span class="meta-fecha">
              <span class="meta-icon">📅</span>
              {{ formatDate(noticia.fecha) }}
            </span>
          </div>

          <h3 class="card-title">{{ noticia.titulo }}</h3>
          <p class="card-resumen">{{ noticia.resumen }}</p>
        </div>

        <!-- Footer -->
        <div class="card-footer">
          <button class="btn-leer-mas" @click="abrirModal(noticia)">
            Leer más
            <span class="btn-arrow">→</span>
          </button>
        </div>
      </div>
    </div>

    <!-- ========== ESTADO VACÍO ========== -->
    <div v-else class="empty-state">
      <div class="empty-icon">📭</div>
      <h3>No hay noticias disponibles</h3>
      <p>No se encontraron resultados para tu búsqueda.</p>
      <button class="btn-primary" @click="resetFilters">
        Limpiar filtros
      </button>
    </div>

    <!-- ========== PAGINACIÓN ========== -->
    <div v-if="totalPages > 1" class="pagination">
      <div class="pagination-info">
        <span class="info-text">
          Mostrando {{ (currentPage - 1) * itemsPerPage + 1 }} -
          {{ Math.min(currentPage * itemsPerPage, filteredNoticias.length) }}
          de {{ filteredNoticias.length }} noticias
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

    <!-- ========== MODAL DETALLADO ========== -->
    <div
      class="modal-overlay"
      v-if="modalVisible"
      @click.self="cerrarModal"
    >
      <div class="modal-contenedor">
        <!-- Header -->
        <div class="modal-header-custom header-noticia">
          <h5 class="modal-titulo">
            <span class="modal-tipo-icon">📰</span>
            {{ noticiaSeleccionada.titulo }}
          </h5>
          <button type="button" class="btn-close-custom" @click="cerrarModal">
            ✕
          </button>
        </div>

        <div class="modal-body-custom">
          <!-- Galería de imágenes -->
          <div v-if="noticiaSeleccionada.imagenes && noticiaSeleccionada.imagenes.length" class="galeria-container">
            <div class="modal-img-wrapper">
              <img
                :src="imagenActual"
                class="img-fluid"
                :alt="noticiaSeleccionada.titulo"
              />
              <div class="contador-imagenes" v-if="noticiaSeleccionada.imagenes.length > 1">
                {{ indiceActual + 1 }} / {{ noticiaSeleccionada.imagenes.length }}
              </div>
              <button
                class="btn-nav btn-nav-izquierda"
                v-if="noticiaSeleccionada.imagenes.length > 1"
                @click="cambiarImagenNavegacion(-1)"
              >
                ‹
              </button>
              <button
                class="btn-nav btn-nav-derecha"
                v-if="noticiaSeleccionada.imagenes.length > 1"
                @click="cambiarImagenNavegacion(1)"
              >
                ›
              </button>
            </div>
            <div class="miniaturas-container" v-if="noticiaSeleccionada.imagenes.length > 1">
              <div
                v-for="(img, index) in noticiaSeleccionada.imagenes"
                :key="index"
                class="miniatura-item"
                :class="{ activa: imagenActual === img }"
                @click="cambiarImagen(img)"
              >
                <img :src="img" :alt="`Imagen ${index + 1}`" />
              </div>
            </div>
          </div>

          <!-- Metadatos -->
          <div class="modal-meta">
            <span class="meta-badge" style="background-color: #cc0000;">
              <span class="meta-icon">🏷️</span> {{ noticiaSeleccionada.categoria }}
            </span>
            <span class="meta-badge" style="background-color: #6c757d;">
              <span class="meta-icon">📅</span> {{ formatDate(noticiaSeleccionada.fecha) }}
            </span>
          </div>
          <!-- Contenido -->
          <div class="modal-contenido">
            <h6 class="contenido-titulo">📄 Descripción completa</h6>
            <p class="contenido-texto">
              {{ noticiaSeleccionada.contenido || 'No hay contenido disponible.' }}
            </p>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer-custom">
          <button type="button" class="btn btn-secondary" @click="cerrarModal">
            ✕ Cerrar
          </button>
          <button type="button" class="btn btn-compartir" @click="compartirNoticia">
            <span class="btn-icon">📤</span> Compartir
          </button>
          <button v-if="isAdmin" type="button" class="btn btn-editar" @click="editarNoticia">
            <span class="btn-icon">✏️</span> Editar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'

export default {
  name: 'Noticias',
  props: {
    isAdmin: {
      type: Boolean,
      default: false
    },
    initialNoticias: {
      type: Array,
      default: () => []
    }
  },
  setup(props, { emit }) {
    // ===== STATE =====
    const searchQuery = ref('')
    const selectedCategory = ref('')
    const sortOrder = ref('desc')
    const currentPage = ref(1)
    const itemsPerPage = ref(8)
    const modalVisible = ref(false)
    const imagenActual = ref('')
    const indiceActual = ref(0)
    const noticiaSeleccionada = ref({
      id: null,
      titulo: '',
      resumen: '',
      contenido: '',
      fecha: '',
      categoria: '',
      imagen: '',
      imagenes: [],
      tags: [],
      destacado: false,
      autor: ''
    })

    // ===== DATOS =====
    const noticias = ref([
      {
        id: 1,
        titulo: 'REUNIÓN DE COORDINACIÓN SOBRE LA FESTIVIDAD DE CHUTILLOS',
        resumen: 'Con el objetivo de optimizar la planificación, promoción y desarrollo de la Festividad de Chutillos, la concejal municipal Lic. Jacqueline Lourdes Gutiérrez Carrasco, presidenta de la Comisión de Turismo, Cultura y Preservación de Áreas Históricas junto al personal del presidente de la comisión Jurídica y Desarrollo Institucional...',
        contenido: 'Con el objetivo de optimizar la planificación, promoción y desarrollo de la Festividad de Chutillos, la concejal municipal Lic. Jacqueline Lourdes Gutiérrez Carrasco, presidenta de la Comisión de Turismo, Cultura y Preservación de Áreas Históricas junto al personal del presidente de la comisión Jurídica y Desarrollo Institucional que preside el Ing. Guido Armando Cruz Mora, participaron en la reunión estratégica de socialización y coordinación interinstitucional organizada junto al Órgano Ejecutivo Municipal y sus distintas secretarías. El encuentro contó con la participación activa de representantes de la Federación de Empresarios Privados de Potosí FEPP, la Cámara de Mujeres Empresarias de Bolivia CAMEBOL filial Potosí, la Cámara Hotelera, así como de diversas agencias y operadoras de turismo. El propósito central de la reunión, fue unificar esfuerzos sectoriales para garantizar una organización eficiente y de alto impacto para esta festividad, la cual ostenta el título de Patrimonio Cultural Inmaterial de la Humanidad, declarada por la UNESCO.',
        fecha: '2026-07-10',
        categoria: 'Turismo y Cultura',
        imagen: '/images/noticias/chutillos.jpg',
        imagenes: [
          '/images/noticias/chutillos.jpg',
          '/images/noticias/chutillos2.jpg',
          '/images/noticias/chutillos3.jpg'
        ],
        autor: 'Departamento de Prensa'
      },
      {
        id: 2,
        titulo: 'CONCEJAL ASIGNADO AL DISTRITO 20 REALIZA GESTIONES CON EL EJECUTIVO Y EL CONTROL SOCIAL',
        resumen: 'Con el objetivo de gestionar la atención de las demandas del distrito 20, el concejal asignado a este importante sector de la ciudad, Ing. Guido Armando Cruz Mora, sostuvo reunión con la participación del Secretario General y el responsable de salud del municipio...',
        contenido: 'Con el objetivo de gestionar la atención de las demandas del distrito 20, el concejal asignado a este importante sector de la ciudad, Ing. Guido Armando Cruz Mora, sostuvo reunión con la participación del Secretario General y el responsable de salud del municipio, como representantes vecinales. En esta importante reunión, se conoció y evaluó el tema de predios destinados a la construcción de la nueva infraestructura de la Unidad Educativa Evo Morales, ubicada en la zona de Rollo Kucho, asimismo la necesidad de proyectar la edificación del nuevo Centro de Salud Ambulatorio en Cantumarca. Temas que serán atendidos en el marco de las competencias.',
        fecha: '2026-07-10',
        categoria: 'Gestión',
        imagen: '/images/noticias/gestionD-20.jpg',
        imagenes: [
          '/images/noticias/gestionD-20-1.jpg',
          '/images/noticias/gestionD-20-2.jpg',
          '/images/noticias/gestionD-20-3.jpg'
        ],
        autor: 'Departamento de Comunicación'
      },
      {
        id: 3,
        titulo: 'APROBACIÓN DEL PRESUPUESTO 2027 PARA EL MUNICIPIO',
        resumen: 'En sesión ordinaria del Concejo Municipal, se aprobó el presupuesto general para la gestión 2027, priorizando obras de infraestructura y servicios básicos...',
        contenido: 'En sesión ordinaria del Concejo Municipal, se aprobó el presupuesto general para la gestión 2027, priorizando obras de infraestructura y servicios básicos para los diferentes distritos del municipio. El presupuesto asciende a Bs. 450 millones, destinados principalmente a proyectos de saneamiento básico, pavimentación y mejoramiento de espacios públicos. Los concejales destacaron la importancia de una gestión transparente y participativa.',
        fecha: '2026-07-05',
        categoria: 'Presupuesto',
        imagen: '/images/noticias/presupuesto.jpg',
        imagenes: [
          '/images/noticias/presupuesto.jpg'
        ],
        autor: 'Departamento Financiero'
      },
      {
        id: 4,
        titulo: 'CAMPAÑA DE VACUNACIÓN EN DISTRITOS PERIFÉRICOS',
        resumen: 'El Concejo Municipal en coordinación con el Servicio Departamental de Salud, lanza campaña de vacunación gratuita en los distritos periféricos de la ciudad...',
        contenido: 'El Concejo Municipal en coordinación con el Servicio Departamental de Salud, lanza campaña de vacunación gratuita en los distritos periféricos de la ciudad. La campaña busca inmunizar a más de 5,000 personas contra enfermedades prevenibles, con especial énfasis en niños y adultos mayores. Los puntos de vacunación estarán habilitados en los centros de salud de cada distrito.',
        fecha: '2026-07-03',
        categoria: 'Salud',
        imagen: '/images/noticias/vacunacion.jpg',
        imagenes: [
          '/images/noticias/vacunacion.jpg',
          '/images/noticias/vacunacion2.jpg'
        ],
        autor: 'Departamento de Salud'
      },
      {
        id: 5,
        titulo: 'CONVOCATORIA A CONCURSO DE FOTOGRAFÍA "POTOSÍ EN IMÁGENES"',
        resumen: 'La Comisión de Turismo y Cultura invita a todos los fotógrafos aficionados y profesionales a participar en el concurso "Potosí en Imágenes"...',
        contenido: 'La Comisión de Turismo y Cultura invita a todos los fotógrafos aficionados y profesionales a participar en el concurso "Potosí en Imágenes", que busca resaltar la riqueza cultural, histórica y paisajística de la ciudad. Los trabajos ganadores serán exhibidos en una muestra fotográfica y recibirán premios en efectivo. El plazo de inscripción vence el 15 de agosto.',
        fecha: '2026-07-01',
        categoria: 'Cultura',
        imagen: '/images/noticias/concurso.jpg',
        imagenes: [
          '/images/noticias/concurso.jpg'
        ],
        autor: 'Departamento de Cultura'
      },
      {
        id: 6,
        titulo: 'REUNIÓN DE COORDINACIÓN CON LA FEDERACIÓN DE JUNTAS VECINALES',
        resumen: 'Los concejales sostuvieron una reunión de coordinación con la Federación de Juntas Vecinales para atender las principales demandas de los barrios...',
        contenido: 'Los concejales sostuvieron una reunión de coordinación con la Federación de Juntas Vecinales para atender las principales demandas de los barrios. Se abordaron temas relacionados con alumbrado público, recolección de residuos y mantenimiento de áreas verdes. Se conformó una comisión de seguimiento para garantizar la ejecución de los proyectos priorizados.',
        fecha: '2026-06-28',
        categoria: 'Vecinal',
        imagen: '/images/noticias/vecinales.jpg',
        imagenes: [
          '/images/noticias/vecinales.jpg',
          '/images/noticias/vecinales2.jpg',
          '/images/noticias/vecinales3.jpg'
        ],
        autor: 'Departamento de Participación Ciudadana'
      }
    ])

    // Cargar noticias iniciales si se proporcionan
    if (props.initialNoticias && props.initialNoticias.length) {
      noticias.value = props.initialNoticias
    }

    // ===== COMPUTED =====
    const allCategories = computed(() => {
      const cats = new Set()
      noticias.value.forEach(n => cats.add(n.categoria))
      return Array.from(cats)
    })

    const filteredNoticias = computed(() => {
      let filtered = [...noticias.value]

      // Búsqueda
      if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(noticia =>
          noticia.titulo.toLowerCase().includes(query) ||
          noticia.resumen.toLowerCase().includes(query) ||
          noticia.contenido.toLowerCase().includes(query) ||
          (noticia.tags && noticia.tags.some(t => t.toLowerCase().includes(query)))
        )
      }

      // Categoría
      if (selectedCategory.value) {
        filtered = filtered.filter(noticia => noticia.categoria === selectedCategory.value)
      }

      // Ordenamiento
      filtered.sort((a, b) => {
        const dateA = new Date(a.fecha)
        const dateB = new Date(b.fecha)
        return sortOrder.value === 'desc' ? dateB - dateA : dateA - dateB
      })

      return filtered
    })

    const totalPages = computed(() => {
      return Math.ceil(filteredNoticias.value.length / itemsPerPage.value)
    })

    const paginatedNoticias = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage.value
      const end = start + itemsPerPage.value
      return filteredNoticias.value.slice(start, end)
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

    // ===== METHODS =====
    const formatDate = (dateString) => {
      if (!dateString) return 'Fecha no disponible'
      try {
        const date = new Date(dateString)
        return date.toLocaleDateString('es-ES', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        })
      } catch {
        return dateString
      }
    }

    const goToPage = (page) => {
      if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
        // Scroll al inicio de la lista
        const container = document.querySelector('.noticias-grid')
        if (container) {
          container.scrollIntoView({ behavior: 'smooth', block: 'start' })
        }
      }
    }

    const abrirModal = (noticia) => {
      noticiaSeleccionada.value = { ...noticia }

      // Configurar galería
      if (noticia.imagenes && noticia.imagenes.length > 0) {
        imagenActual.value = noticia.imagenes[0]
        indiceActual.value = 0
      } else if (noticia.imagen) {
        imagenActual.value = noticia.imagen
        indiceActual.value = 0
        if (!noticia.imagenes) {
          noticiaSeleccionada.value.imagenes = [noticia.imagen]
        }
      }

      modalVisible.value = true
      document.body.style.overflow = 'hidden'
    }

    const cerrarModal = () => {
      modalVisible.value = false
      document.body.style.overflow = ''
    }

    const cambiarImagen = (img) => {
      imagenActual.value = img
      indiceActual.value = noticiaSeleccionada.value.imagenes.indexOf(img)
    }

    const cambiarImagenNavegacion = (direccion) => {
      if (!noticiaSeleccionada.value.imagenes || noticiaSeleccionada.value.imagenes.length <= 1) return
      const total = noticiaSeleccionada.value.imagenes.length
      let nuevoIndice = indiceActual.value + direccion
      if (nuevoIndice < 0) nuevoIndice = total - 1
      if (nuevoIndice >= total) nuevoIndice = 0
      indiceActual.value = nuevoIndice
      imagenActual.value = noticiaSeleccionada.value.imagenes[nuevoIndice]
    }

    const compartirNoticia = () => {
      const texto = `📰 ${noticiaSeleccionada.value.titulo}\n\n${noticiaSeleccionada.value.resumen}\n\nLeer más en: ${window.location.href}`

      if (navigator.share) {
        navigator.share({
          title: noticiaSeleccionada.value.titulo,
          text: noticiaSeleccionada.value.resumen,
          url: window.location.href
        }).catch(err => console.log('Error al compartir:', err))
      } else {
        navigator.clipboard.writeText(texto).then(() => {
          alert('¡Enlace copiado al portapapeles!')
        }).catch(() => {
          alert('Comparte esta noticia: ' + texto)
        })
      }
    }

    const editarNoticia = () => {
      emit('edit-noticia', noticiaSeleccionada.value)
      cerrarModal()
    }

    const resetFilters = () => {
      searchQuery.value = ''
      selectedCategory.value = ''
      sortOrder.value = 'desc'
      currentPage.value = 1
    }

    // ===== LIFECYCLE =====
    onMounted(() => {
      // Si hay datos iniciales, cargarlos
      if (props.initialNoticias && props.initialNoticias.length) {
        noticias.value = props.initialNoticias
      }
    })

    return {
      searchQuery,
      selectedCategory,
      sortOrder,
      currentPage,
      itemsPerPage,
      modalVisible,
      imagenActual,
      indiceActual,
      noticiaSeleccionada,
      noticias,
      allCategories,
      filteredNoticias,
      totalPages,
      paginatedNoticias,
      pageNumbers,
      formatDate,
      goToPage,
      abrirModal,
      cerrarModal,
      cambiarImagen,
      cambiarImagenNavegacion,
      compartirNoticia,
      editarNoticia,
      resetFilters
    }
  }
}
</script>

<style scoped>
/* ========== CONTENEDOR PRINCIPAL ========== */
.noticias-container {
  margin: 0 auto;
  padding: 2rem;
  min-height: 100vh;
  background-image:url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
}

/* ========== HEADER ========== */
.noticias-header {
  text-align: center;
  margin-bottom: 2.5rem;
  padding: 2rem;
}

.noticias-titulo {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  color: #cc0000;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
  line-height: 1.2;
}

.noticias-subtitulo {
  font-size: 1.1rem;
  color: #1a202c;
  max-width: 95%;
  margin: 0 auto;
  text-align: justify;
  line-height: 1.6;
}

/* ========== FILTROS ========== */
.noticias-filtros {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(10px);
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

/* ========== GRID ========== */
.noticias-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

/* ========== TARJETAS ========== */
.noticia-card {
  background: white;
  backdrop-filter: blur(10px);
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

.noticia-card:hover {
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
  background: rgba(255, 255, 255, 0.96);
}

/* ========== BADGE ========== */
.card-tipo-badge {
  padding: 0.5rem 1rem;
  background: rgba(247, 250, 252, 0.5);
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.85rem;
  font-weight: 600;
  color: #cc0000;
}

/* ========== IMAGEN ========== */
.card-img-wrapper {
  position: relative;
  width: 100%;
  height: 220px;
  overflow: hidden;
  background: #f5f5f5;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.noticia-card:hover .card-img {
  transform: scale(1.05);
}

.multi-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 0.25rem 0.6rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 500;
}

/* ========== CARD BODY ========== */
.card-body {
  padding: 1.25rem;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.card-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.meta-categoria {
  background: #cc0000;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 500;
}

.meta-fecha {
  color: #718096;
  font-size: 0.8rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.card-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1a202c;
  margin: 0 0 0.5rem 0;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  min-height: 3rem;
}

.card-resumen {
  color: #4a5568;
  font-size: 0.95rem;
  line-height: 1.6;
  margin: 0 0 1rem 0;
  flex: 1;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.card-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
}

.tag {
  background: #edf2f7;
  color: #4a5568;
  padding: 0.15rem 0.6rem;
  border-radius: 9999px;
  font-size: 0.7rem;
  font-weight: 500;
}

/* ========== CARD FOOTER ========== */
.card-footer {
  padding: 0.75rem 1.25rem;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  background: rgba(247, 250, 252, 0.5);
}

.btn-leer-mas {
  background: none;
  border: none;
  color: #cc0000;
  font-weight: 600;
  cursor: pointer;
  padding: 0.25rem 0.5rem;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.95rem;
}

.btn-leer-mas:hover {
  color: #a80000;
  transform: translateX(4px);
}

.btn-arrow {
  transition: transform 0.3s ease;
}

.btn-leer-mas:hover .btn-arrow {
  transform: translateX(4px);
}

/* ========== ESTADO VACÍO ========== */
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
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* ========== PAGINACIÓN ========== */
.pagination {
  display: flex;
  flex-direction: column;
  gap: 1rem;
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

.pagination-options {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.per-page-select {
  padding: 0.4rem 0.75rem;
  border: 2px solid #e2e8f0;
  border-radius: 0.5rem;
  font-size: 0.85rem;
  background: white;
  cursor: pointer;
  transition: all 0.3s ease;
}

.per-page-select:focus {
  outline: none;
  border-color: #cc0000;
  box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
}

/* Responsive */
@media (max-width: 640px) {
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

  .pagination-options {
    width: 100%;
    justify-content: center;
  }
}

/* ========== MODAL ========== */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(8px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  padding: 1rem;
  animation: fadeIn 0.3s ease;
}

.modal-contenedor {
  background: white;
  border-radius: 1.5rem;
  max-width: 800px;
  width: 100%;
  max-height: 95vh;
  display: flex;
  flex-direction: column;
  animation: slideUp 0.3s ease;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  overflow: hidden;
}

/* ========== MODAL HEADER ========== */
.modal-header-custom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.25rem 1.5rem;
  background: #cc0000;
  color: white;
  flex-shrink: 0;
}

.modal-titulo {
  margin: 0;
  font-size: 1.15rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding-right: 1rem;
  line-height: 1.3;
}

.modal-tipo-icon {
  font-size: 1.5rem;
}

.btn-close-custom {
  background: none;
  border: none;
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  transition: all 0.3s ease;
  flex-shrink: 0;
  line-height: 1;
}

.btn-close-custom:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: rotate(90deg);
}

/* ========== MODAL BODY ========== */
.modal-body-custom {
  padding: 1.5rem;
  overflow-y: auto;
  flex: 1;
}

.modal-body-custom::-webkit-scrollbar {
  width: 6px;
}

.modal-body-custom::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.modal-body-custom::-webkit-scrollbar-thumb {
  background: #cc0000;
  border-radius: 10px;
}

/* ========== GALERÍA ========== */
.galeria-container {
  margin-bottom: 1.5rem;
}

.modal-img-wrapper {
  position: relative;
  width: 100%;
  max-height: 400px;
  overflow: hidden;
  border-radius: 0.75rem;
  background: #f5f5f5;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-img-wrapper img {
  width: 100%;
  height: auto;
  max-height: 400px;
  object-fit: contain;
}

.contador-imagenes {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 500;
  z-index: 5;
}

.btn-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 5;
  font-size: 1.5rem;
}

.btn-nav:hover {
  background: rgba(0, 0, 0, 0.8);
  transform: translateY(-50%) scale(1.1);
}

.btn-nav-izquierda { left: 10px; }
.btn-nav-derecha { right: 10px; }

.miniaturas-container {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.75rem;
  overflow-x: auto;
  padding: 0.25rem 0;
}

.miniaturas-container::-webkit-scrollbar {
  height: 4px;
}

.miniaturas-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.miniaturas-container::-webkit-scrollbar-thumb {
  background: #cc0000;
  border-radius: 10px;
}

.miniatura-item {
  flex: 0 0 80px;
  height: 60px;
  cursor: pointer;
  border-radius: 6px;
  overflow: hidden;
  border: 3px solid transparent;
  transition: all 0.3s ease;
  opacity: 0.6;
}

.miniatura-item:hover {
  opacity: 1;
  transform: scale(1.05);
}

.miniatura-item.activa {
  border-color: #cc0000;
  opacity: 1;
  box-shadow: 0 0 10px rgba(204, 0, 0, 0.3);
}

.miniatura-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* ========== MODAL META ========== */
.modal-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.meta-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  color: white;
  font-size: 0.8rem;
  font-weight: 500;
}

.meta-icon {
  font-size: 0.9rem;
}

.modal-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

/* ========== MODAL CONTENIDO ========== */
.modal-contenido {
  margin-top: 0.5rem;
}

.contenido-titulo {
  color: #2d3748;
  margin-bottom: 0.75rem;
  font-size: 1rem;
  font-weight: 700;
}

.contenido-texto {
  color: #4a5568;
  line-height: 1.8;
  text-align: justify;
  font-size: 1.05rem;
  margin: 0;
}

/* ========== MODAL FOOTER ========== */
.modal-footer-custom {
  padding: 1rem 1.5rem;
  border-top: 1px solid #e9ecef;
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  flex-shrink: 0;
  flex-wrap: wrap;
}

.btn {
  padding: 0.5rem 1.25rem;
  border: none;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-secondary {
  background: #f7fafc;
  color: #4a5568;
  border: 2px solid #e2e8f0;
}

.btn-secondary:hover {
  background: #edf2f7;
}

.btn-compartir {
  background: #edf2f7;
  color: #4a5568;
}

.btn-compartir:hover {
  background: #cc0000;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(204, 0, 0, 0.3);
}

.btn-editar {
  background: #667eea;
  color: white;
}

.btn-editar:hover {
  background: #5a67d8;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* ========== ANIMACIONES ========== */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from {
    transform: translateY(30px) scale(0.95);
    opacity: 0;
  }
  to {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
}

/* ========== RESPONSIVE ========== */
@media (max-width: 1024px) {
  .noticias-grid {
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  }
}

@media (max-width: 768px) {
  .noticias-container {
    padding: 1rem;
  }

  .noticias-titulo {
    font-size: 1.8rem;
  }

  .noticias-subtitulo {
    max-width: 100%;
    font-size: 0.95rem;
  }

  .noticias-filtros {
    flex-direction: column;
  }

  .search-input,
  .filter-select {
    width: 100%;
  }

  .noticias-grid {
    grid-template-columns: 1fr;
  }

  .modal-contenedor {
    max-height: 98vh;
  }

  .modal-header-custom {
    padding: 1rem;
  }

  .modal-titulo {
    font-size: 1rem;
  }

  .modal-body-custom {
    padding: 1rem;
  }

  .modal-img-wrapper {
    max-height: 250px;
  }

  .modal-img-wrapper img {
    max-height: 250px;
  }

  .modal-footer-custom {
    flex-direction: column;
  }

  .modal-footer-custom .btn {
    width: 100%;
    justify-content: center;
  }

  .miniatura-item {
    flex: 0 0 60px;
    height: 45px;
  }

  .btn-nav {
    width: 30px;
    height: 30px;
    font-size: 1rem;
  }

  .pagination {
    flex-wrap: wrap;
  }

  .page-numbers {
    flex-wrap: wrap;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .noticias-titulo {
    font-size: 1.5rem;
  }

  .card-img-wrapper {
    height: 180px;
  }
}
</style>
