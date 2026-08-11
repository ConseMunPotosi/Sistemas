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

    <!-- ========== CONTENIDO PRINCIPAL ========== -->
    <div v-if="filteredItems.length > 0" class="noticias-grid">
      <!-- ===== NOTICIAS ===== -->
      <div
        v-for="item in paginatedItems"
        :key="item.id"
        class="noticia-card"
        :class="{
          'tipo-noticia': item.tipo === 'noticia',
          'tipo-boletin': item.tipo === 'boletin',
          'tipo-audiovisual': item.tipo === 'audiovisual',
          'destacado': item.destacado
        }"
      >
        <!-- Badge de tipo -->
        <div class="card-tipo-badge">
          <span v-if="item.tipo === 'noticia'">📰 Noticia</span>
          <span v-if="item.destacado" class="destacado-badge">⭐ Destacado</span>
        </div>

        <!-- Imagen o Multimedia -->
        <div class="card-media">
          <!-- Noticias y Boletines -->
          <div v-if="item.tipo !== 'audiovisual'" class="card-img-wrapper">
            <img
              :src="item.imagen || '/images/default-noticia.jpg'"
              class="card-img"
              :alt="item.titulo"
              loading="lazy"
            />
            <span v-if="item.imagenes && item.imagenes.length > 1" class="multi-badge">
              📸 {{ item.imagenes.length }}
            </span>
            <span v-if="item.tipo === 'boletin' && item.featured" class="featured-badge">
              ⭐ Destacado
            </span>
          </div>

          <!-- Audiovisual -->
          <div v-else class="card-media-wrapper">
            <div v-if="item.subtipo === 'jingle'" class="audio-player">
              <div class="waveform-bars">
                <span v-for="i in 30" :key="i" class="bar" :style="{ height: getRandomHeight() }"></span>
              </div>
              <audio
                v-if="item.audioUrl"
                controls
                class="audio-controls"
                :src="item.audioUrl"
              >
                Tu navegador no soporta audio.
              </audio>
              <div v-else class="no-media">🔊 Sin audio disponible</div>
            </div>
            <div v-else class="video-player">
              <video
                v-if="item.videoUrl"
                controls
                class="video-controls"
                :src="item.videoUrl"
                poster="https://via.placeholder.com/400x225/667eea/ffffff?text=Spot"
              >
                Tu navegador no soporta video.
              </video>
              <div v-else class="no-media">📹 Sin video disponible</div>
            </div>
          </div>
        </div>

        <!-- Información -->
        <div class="card-body">
          <div class="card-meta">
            <span class="meta-categoria">{{ item.categoria }}</span>
            <span class="meta-fecha">
              <span class="meta-icon">📅</span>
              {{ formatDate(item.fecha) }}
            </span>
          </div>

          <h3 class="card-title">{{ item.titulo }}</h3>
          <p class="card-resumen">{{ item.resumen || item.summary }}</p>
        </div>

        <!-- Footer -->
        <div class="card-footer">
          <button
            class="btn-leer-mas"
            @click="abrirModal(item)"
          >
            {{ item.tipo === 'audiovisual' ? 'Ver detalle' : 'Leer más' }}
            <span class="btn-arrow">→</span>
          </button>
          <div v-if="item.tipo === 'audiovisual'" class="card-tipo-icon">
            {{ item.subtipo === 'jingle' ? '🎵' : '📺' }}
          </div>
        </div>
      </div>
    </div>

    <!-- ========== ESTADO VACÍO ========== -->
    <div v-else class="empty-state">
      <div class="empty-icon">📭</div>
      <h3>No hay contenido disponible</h3>
      <p>No se encontraron resultados para tu búsqueda.</p>
      <button class="btn-primary" @click="resetFilters">
        Limpiar filtros
      </button>
    </div>

    <!-- ========== PAGINACIÓN ========== -->
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

    <!-- ========== MODAL DETALLADO ========== -->
    <div
      class="modal-overlay"
      v-if="modalVisible"
      @click.self="cerrarModal"
    >
      <div class="modal-contenedor">
        <!-- Header -->
        <div class="modal-header-custom" :class="'header-' + (itemSeleccionado.tipo || 'noticia')">
          <h5 class="modal-titulo">
            <span class="modal-tipo-icon">
              {{ itemSeleccionado.tipo === 'noticia' ? '📰':'' }}
            </span>
            {{ itemSeleccionado.titulo }}
          </h5>
          <button type="button" class="btn-close-custom" @click="cerrarModal">
            ✕
          </button>
        </div>

        <div class="modal-body-custom">
          <!-- Galería de imágenes (Noticias y Boletines) -->
          <div v-if="itemSeleccionado.tipo !== 'audiovisual' && itemSeleccionado.imagenes && itemSeleccionado.imagenes.length" class="galeria-container">
            <div class="modal-img-wrapper">
              <img
                :src="imagenActual"
                class="img-fluid"
                :alt="itemSeleccionado.titulo"
              />
              <div class="contador-imagenes" v-if="itemSeleccionado.imagenes.length > 1">
                {{ indiceActual + 1 }} / {{ itemSeleccionado.imagenes.length }}
              </div>
              <button
                class="btn-nav btn-nav-izquierda"
                v-if="itemSeleccionado.imagenes.length > 1"
                @click="cambiarImagenNavegacion(-1)"
              >
                ‹
              </button>
              <button
                class="btn-nav btn-nav-derecha"
                v-if="itemSeleccionado.imagenes.length > 1"
                @click="cambiarImagenNavegacion(1)"
              >
                ›
              </button>
            </div>
            <div class="miniaturas-container" v-if="itemSeleccionado.imagenes.length > 1">
              <div
                v-for="(img, index) in itemSeleccionado.imagenes"
                :key="index"
                class="miniatura-item"
                :class="{ activa: imagenActual === img }"
                @click="cambiarImagen(img)"
              >
                <img :src="img" :alt="`Imagen ${index + 1}`" />
              </div>
            </div>
          </div>

          <!-- Reproductor Audiovisual -->
          <div v-if="itemSeleccionado.tipo === 'audiovisual'" class="modal-media">
            <div v-if="itemSeleccionado.subtipo === 'jingle'" class="modal-audio">
              <div class="modal-waveform">
                <span v-for="i in 40" :key="i" class="bar" :style="{ height: getRandomHeight() }"></span>
              </div>
              <audio
                v-if="itemSeleccionado.audioUrl"
                controls
                class="modal-audio-controls"
                :src="itemSeleccionado.audioUrl"
              >
                Tu navegador no soporta audio.
              </audio>
              <div v-else class="no-media">🔊 Sin audio disponible</div>
            </div>
            <div v-else class="modal-video">
              <video
                v-if="itemSeleccionado.videoUrl"
                controls
                class="modal-video-controls"
                :src="itemSeleccionado.videoUrl"
                poster="https://via.placeholder.com/800x450/667eea/ffffff?text=Spot"
              >
                Tu navegador no soporta video.
              </video>
              <div v-else class="no-media">📹 Sin video disponible</div>
            </div>
          </div>

          <!-- Metadatos -->
          <div class="modal-meta">
            <span class="meta-badge" style="background-color: #cc0000;">
              <span class="meta-icon">🏷️</span> {{ itemSeleccionado.categoria }}
            </span>
            <span class="meta-badge" style="color:black">
              <span class="meta-icon">📅</span> {{ formatDate(itemSeleccionado.fecha) }}
            </span>
          </div>

          <!-- Contenido -->
          <div class="modal-contenido">
            <p class="contenido-texto">
              {{ itemSeleccionado.contenido || itemSeleccionado.content || 'No hay contenido disponible.' }}
            </p>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer-custom">
          <button type="button" class="btn btn-secondary" @click="cerrarModal">
            ✕ Cerrar
          </button>
          <button type="button" class="btn btn-compartir" @click="compartirItem">
            <span class="btn-icon">📤</span> Compartir
          </button>
          <button v-if="isAdmin" type="button" class="btn btn-editar" @click="editarItem">
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
  name: 'NoticiasBoletines',
  props: {
    isAdmin: {
      type: Boolean,
      default: false
    },
    initialData: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props, { emit }) {
    // ===== STATE =====
    const activeTab = ref('noticias')
    const searchQuery = ref('')
    const selectedCategory = ref('')
    const sortOrder = ref('desc')
    const currentPage = ref(1)
    const itemsPerPage = 6
    const modalVisible = ref(false)
    const imagenActual = ref('')
    const indiceActual = ref(0)
    const itemSeleccionado = ref({
      id: null,
      tipo: 'noticia',
      subtipo: null,
      titulo: '',
      resumen: '',
      summary: '',
      contenido: '',
      content: '',
      fecha: '',
      categoria: '',
      imagen: '',
      imagenes: [],
      tags: [],
      destacado: false,
      featured: false,
      autor: '',
      audioUrl: '',
      videoUrl: '',
      views: 0,
      likes: 0,
      duration: '00:00',
      status: 'activo'
    })

    // ===== DATOS =====
    const noticias = ref([
      {
        id: 1,
        tipo: 'noticia',
        titulo: 'REUNIÓN DE COORDINACIÓN SOBRE LA FESTIVIDAD DE CHUTILLOS',
        resumen: 'Con el objetivo de optimizar la planificación, promoción y desarrollo de la Festividad de Chutillos...',
        contenido: 'Con el objetivo de optimizar la planificación, promoción y desarrollo de la Festividad de Chutillos, la concejal municipal Lic. Jacqueline Lourdes Gutiérrez Carrasco, presidenta de la Comisión de Turismo, Cultura y Preservación de Áreas Históricas junto al personal del presidente de la comisión Jurídica y Desarrollo Institucional que preside el Ing. Guido Armando Cruz Mora, participaron en la reunión estratégica de socialización y coordinación interinstitucional organizada junto al Órgano Ejecutivo Municipal y sus distintas secretarías. El encuentro contó con la participación activa de representantes de la Federación de Empresarios Privados de Potosí FEPP, la Cámara de Mujeres Empresarias de Bolivia CAMEBOL filial Potosí, la Cámara Hotelera, así como de diversas agencias y operadoras de turismo. El propósito central de la reunión, fue unificar esfuerzos sectoriales para garantizar una organización eficiente y de alto impacto para esta festividad, la cual ostenta el título de Patrimonio Cultural Inmaterial de la Humanidad, declarada por la UNESCO.',
        fecha: '2026-07-10',
        categoria: 'Turismo y Cultura',
        imagen: '/images/noticias/chutillos.jpg',
        imagenes: [
          '/images/noticias/chutillos.jpg',
          '/images/noticias/chutillos2.jpg',
          '/images/noticias/chutillos3.jpg'
        ],
        tags: ['cultura', 'turismo', 'chutillos'],
        destacado: true,
        autor: 'Departamento de Prensa'
      },
      {
        id: 2,
        tipo: 'noticia',
        titulo: 'CONCEJAL ASIGNADO AL DISTRITO 20 REALIZA GESTIONES',
        resumen: 'Con el objetivo de gestionar la atención de las demandas del distrito 20...',
        contenido: 'Con el objetivo de gestionar la atención de las demandas del distrito 20, el concejal asignado a este importante sector de la ciudad, Ing. Guido Armando Cruz Mora, sostuvo reunión con la participación del Secretario General y el responsable de salud del municipio, como representantes vecinales. En esta importante reunión, se conoció y evaluó el tema de predios destinados a la construcción de la nueva infraestructura de la Unidad Educativa Evo Morales.',
        fecha: '2026-07-10',
        categoria: 'Gestión',
        imagen: '/images/noticias/gestionD-20.jpg',
        imagenes: [
          '/images/noticias/gestionD-20-1.jpg',
          '/images/noticias/gestionD-20-2.jpg'
        ],
        tags: ['gestión', 'distrito 20', 'educación'],
        destacado: false,
        autor: 'Departamento de Comunicación'
      }
    ])

    const boletines = ref([
      {
        id: 1,
        tipo: 'boletin',
        titulo: 'Lanzamiento del Nuevo Producto XYZ',
        summary: 'Presentamos nuestra innovadora solución que revolucionará el mercado',
        content: 'Después de meses de investigación y desarrollo, nos complace anunciar el lanzamiento de nuestro nuevo producto que transformará la industria.',
        fecha: '2026-06-15',
        categoria: 'Producto',
        imagen: '/images/boletines/producto.jpg',
        imagenes: [],
        tags: ['producto', 'innovación', 'lanzamiento'],
        featured: true,
        autor: 'Departamento de Marketing'
      },
      {
        id: 2,
        tipo: 'boletin',
        titulo: 'Resultados Financieros del Cuarto Trimestre',
        summary: 'La compañía reporta un crecimiento del 25% en ingresos',
        content: 'En el cuarto trimestre del año, la compañía ha logrado resultados excepcionales con un crecimiento del 25% en ingresos.',
        fecha: '2026-06-10',
        categoria: 'Financiero',
        imagen: '/images/boletines/financiero.jpg',
        imagenes: [],
        tags: ['finanzas', 'crecimiento', 'resultados'],
        featured: false,
        autor: 'Departamento Financiero'
      }
    ])

    const itemsAudiovisual = ref([
      {
        id: 1,
        tipo: 'audiovisual',
        subtipo: 'jingle',
        titulo: 'Jingle Corporativo 2024',
        description: 'Melodía institucional para todas las campañas',
        categoria: 'Institucional',
        duration: '00:30',
        tags: ['corporativo', 'melodia'],
        audioUrl: 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
        videoUrl: '',
        status: 'activo',
        views: 1250,
        likes: 89,
        fecha: '2026-06-05'
      },
      {
        id: 2,
        tipo: 'audiovisual',
        subtipo: 'spot',
        titulo: 'Spot Publicitario Navidad',
        description: 'Campaña navideña para televisión',
        categoria: 'Comercial',
        duration: '00:45',
        tags: ['navidad', 'publicidad'],
        audioUrl: '',
        videoUrl: 'https://www.w3schools.com/html/mov_bbb.mp4',
        status: 'activo',
        views: 3400,
        likes: 215,
        fecha: '2026-06-03'
      }
    ])

    // ===== COMPUTED =====
    const allCategories = computed(() => {
      const cats = new Set()
      noticias.value.forEach(n => cats.add(n.categoria))
      boletines.value.forEach(b => cats.add(b.categoria))
      itemsAudiovisual.value.forEach(a => cats.add(a.categoria))
      return Array.from(cats)
    })

    const allItems = computed(() => {
      const items = []

      noticias.value.forEach(n => {
        items.push({
          ...n,
          tipo: 'noticia',
          resumen: n.resumen || n.summary,
          contenido: n.contenido || n.content
        })
      })

      boletines.value.forEach(b => {
        items.push({
          ...b,
          tipo: 'boletin',
          resumen: b.summary || b.resumen,
          contenido: b.content || b.contenido
        })
      })

      itemsAudiovisual.value.forEach(a => {
        items.push({
          ...a,
          tipo: 'audiovisual',
          resumen: a.description || a.resumen,
          contenido: a.description || a.contenido
        })
      })

      return items
    })

    const filteredItems = computed(() => {
      let filtered = [...allItems.value]

      // Filtrar por tab
      if (activeTab.value === 'noticias') {
        filtered = filtered.filter(item => item.tipo === 'noticia')
      } else if (activeTab.value === 'boletines') {
        filtered = filtered.filter(item => item.tipo === 'boletin')
      } else if (activeTab.value === 'audiovisual') {
        filtered = filtered.filter(item => item.tipo === 'audiovisual')
      }

      // Búsqueda
      if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(item =>
          item.titulo.toLowerCase().includes(query) ||
          (item.resumen && item.resumen.toLowerCase().includes(query)) ||
          (item.contenido && item.contenido.toLowerCase().includes(query)) ||
          (item.tags && item.tags.some(t => t.toLowerCase().includes(query)))
        )
      }

      // Categoría
      if (selectedCategory.value) {
        filtered = filtered.filter(item => item.categoria === selectedCategory.value)
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

    // ===== METHODS =====
    const getRandomHeight = () => {
      return `${Math.random() * 30 + 10}px`
    }

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

    const abrirModal = (item) => {
      itemSeleccionado.value = { ...item }

      // Configurar galería
      if (item.imagenes && item.imagenes.length > 0) {
        imagenActual.value = item.imagenes[0]
        indiceActual.value = 0
      } else if (item.imagen) {
        imagenActual.value = item.imagen
        indiceActual.value = 0
        if (!item.imagenes) {
          itemSeleccionado.value.imagenes = [item.imagen]
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
      indiceActual.value = itemSeleccionado.value.imagenes.indexOf(img)
    }

    const cambiarImagenNavegacion = (direccion) => {
      if (!itemSeleccionado.value.imagenes || itemSeleccionado.value.imagenes.length <= 1) return
      const total = itemSeleccionado.value.imagenes.length
      let nuevoIndice = indiceActual.value + direccion
      if (nuevoIndice < 0) nuevoIndice = total - 1
      if (nuevoIndice >= total) nuevoIndice = 0
      indiceActual.value = nuevoIndice
      imagenActual.value = itemSeleccionado.value.imagenes[nuevoIndice]
    }

    const compartirItem = () => {
      const texto = `📰 ${itemSeleccionado.value.titulo}\n\n${itemSeleccionado.value.resumen || itemSeleccionado.value.summary || ''}\n\nLeer más en: ${window.location.href}`

      if (navigator.share) {
        navigator.share({
          title: itemSeleccionado.value.titulo,
          text: itemSeleccionado.value.resumen || '',
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

    const editarItem = () => {
      emit('edit-item', itemSeleccionado.value)
      cerrarModal()
    }

    const resetFilters = () => {
      searchQuery.value = ''
      selectedCategory.value = ''
      sortOrder.value = 'desc'
      activeTab.value = 'noticias'
      currentPage.value = 1
    }

    // ===== LIFECYCLE =====
    onMounted(() => {
      if (props.initialData) {
        if (props.initialData.noticias) noticias.value = props.initialData.noticias
        if (props.initialData.boletines) boletines.value = props.initialData.boletines
        if (props.initialData.audiovisual) itemsAudiovisual.value = props.initialData.audiovisual
      }
    })

    return {
      activeTab,
      searchQuery,
      selectedCategory,
      sortOrder,
      currentPage,
      modalVisible,
      imagenActual,
      indiceActual,
      itemSeleccionado,
      noticias,
      boletines,
      itemsAudiovisual,
      allCategories,
      filteredItems,
      totalPages,
      paginatedItems,
      pageNumbers,
      getRandomHeight,
      formatDate,
      abrirModal,
      cerrarModal,
      cambiarImagen,
      cambiarImagenNavegacion,
      compartirItem,
      editarItem,
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
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
}

/* ========== HEADER ========== */
.noticias-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 1.5rem;
}

.noticias-titulo {
  font-size: 2.5rem;
  margin: 0.5rem 0 0.5rem 0;
  color: #cc0000;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  font-weight: 800;
  letter-spacing: 1px;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
}

.titulo-icon {
  font-size: 2.8rem;
}

.noticias-subtitulo {
  font-size: 1.1rem;
  color: #1a202c;
  max-width: 96%;
  margin: 0 auto;
  text-align: justify;
  line-height: 1.6;
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
  border: 2px solid transparent;
  display: flex;
  flex-direction: column;
}

.noticia-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.noticia-card.tipo-noticia {
  border-color: #cc0000;
}

.noticia-card.destacado {
  background: linear-gradient(135deg, rgba(255, 250, 240, 0.95), rgba(255, 255, 255, 0.95));
  border-width: 3px;
}

/* ========== BADGE TIPO ========== */
.card-tipo-badge {
  padding: 0.5rem 1rem;
  background: #f7fafc;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.85rem;
  font-weight: 600;
}

.tipo-noticia .card-tipo-badge {
  color: #cc0000;
  background: rgba(204, 0, 0, 0.05);
}

.tipo-boletin .card-tipo-badge {
  color: #28a745;
  background: rgba(40, 167, 69, 0.05);
}

.tipo-audiovisual .card-tipo-badge {
  color: #667eea;
  background: rgba(102, 126, 234, 0.05);
}

.destacado-badge {
  background: #f6ad55;
  color: white;
  padding: 0.2rem 0.6rem;
  border-radius: 9999px;
  font-size: 0.7rem;
  font-weight: 600;
}

/* ========== MEDIA ========== */
.card-media {
  width: 100%;
  overflow: hidden;
}

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

.featured-badge {
  position: absolute;
  top: 10px;
  left: 10px;
  background: #f6ad55;
  color: white;
  padding: 0.25rem 0.6rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

/* ========== AUDIO/VIDEO ========== */
.card-media-wrapper {
  padding: 1rem;
  background: #1a202c;
}

.audio-player {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.waveform-bars {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 3px;
  height: 50px;
  width: 100%;
}

.waveform-bars .bar {
  width: 5px;
  background: linear-gradient(to top, #48bb78, #38a169);
  border-radius: 3px;
  animation: wave 1s ease-in-out infinite;
}

.waveform-bars .bar:nth-child(odd) {
  animation-delay: 0.2s;
}

@keyframes wave {
  0%, 100% { transform: scaleY(1); }
  50% { transform: scaleY(0.5); }
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
  padding: 1rem;
  text-align: center;
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
  justify-content: space-between;
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

.card-tipo-icon {
  font-size: 1.5rem;
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
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
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
  background: #cc0000;
  color: white;
  border-color: #cc0000;
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
  background: #cc0000;
  color: white;
  border-color: #cc0000;
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
  color: white;
  flex-shrink: 0;
}

.header-noticia {
  background: #cc0000;
}

.header-boletin {
  background: #28a745;
}

.header-audiovisual {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.modal-titulo {
  margin: 0;
  font-size: 1.15rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding-right: 1rem;
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

/* ========== MODAL MEDIA ========== */
.modal-media {
  margin-bottom: 1.5rem;
}

.modal-waveform {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 3px;
  height: 60px;
  background: #1a202c;
  border-radius: 0.75rem;
  padding: 1rem;
  margin-bottom: 0.75rem;
}

.modal-waveform .bar {
  width: 5px;
  background: linear-gradient(to top, #48bb78, #38a169);
  border-radius: 3px;
  animation: wave 1s ease-in-out infinite;
}

.modal-waveform .bar:nth-child(odd) {
  animation-delay: 0.2s;
}

.modal-audio-controls,
.modal-video-controls {
  width: 100%;
  border-radius: 0.5rem;
}

.modal-video-controls {
  border-radius: 0.75rem;
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
  margin-top: 1rem;
}

.contenido-titulo {
  color: #2d3748;
  margin-bottom: 0.75rem;
  font-size: 1rem;
}

.contenido-texto {
  color: #4a5568;
  line-height: 1.8;
  text-align: justify;
  font-size: 1.05rem;
  margin: 0;
}

/* ========== MODAL STATS ========== */
.modal-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
  gap: 1rem;
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid #e2e8f0;
}

.stat-card {
  text-align: center;
}

.stat-number {
  display: block;
  font-size: 1.5rem;
  font-weight: 700;
  color: #2d3748;
}

.stat-label {
  font-size: 0.85rem;
  color: #718096;
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
    flex-direction: column;
  }

  .noticias-subtitulo {
    max-width: 100%;
    font-size: 0.95rem;
  }

  .noticias-tabs {
    flex-direction: column;
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

  .modal-stats {
    grid-template-columns: repeat(3, 1fr);
  }
}
</style>
