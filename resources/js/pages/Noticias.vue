<template>
  <div class="noticias-container">

    <!-- ENCABEZADO -->
    <div class="noticias-header">
      <h2 class="noticias-titulo">
        Noticias del<br />
        Concejo Municipal de Potosí
      </h2>

      <p class="noticias-subtitulo">
        Este espacio propio del Concejo Municipal de Potosí es digital dinámico y confiable,
        cuyo objetivo central es mantener a la ciudadanía informada sobre la actividad legislativa
        y de fiscalización que se desarrolla en el municipio.
      </p>
    </div>

    <!-- FILTROS -->
    <div class="noticias-filtros">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Buscar noticias..."
        class="search-input"
      />

      <select
        v-model="sortOrder"
        class="filter-select"
      >
        <option value="desc">Más recientes</option>
        <option value="asc">Más antiguos</option>
      </select>
    </div>

    <!-- CARGANDO -->
    <div
      v-if="store.loading"
      class="loading-state"
    >
      <div class="spinner"></div>
      <p>Cargando noticias...</p>
    </div>

    <!-- NOTICIAS -->
    <div
      v-else-if="filteredNoticias.length > 0"
      class="noticias-grid"
    >
      <div
        v-for="noticia in paginatedNoticias"
        :key="noticia.id_noticia"
        class="noticia-card"
      >

        <div class="card-tipo-badge">
          <span>📰 Noticia</span>

          <span
            v-if="noticia.destacado"
            class="destacado-badge"
          >
            ⭐ Destacado
          </span>
        </div>

        <!-- MULTIMEDIA PRINCIPAL -->
        <div class="card-img-wrapper">

          <video
            v-if="isVideo(noticia)"
            :src="getMainMedia(noticia)"
            autoplay
            muted
            loop
            controls
            playsinline
            class="card-video"
          ></video>

          <img
            v-else
            :src="getMainMedia(noticia)"
            class="card-img"
            :alt="noticia.titulo || 'Noticia'"
            loading="lazy"
          />

          <span
            v-if="noticia.archivos && noticia.archivos.length > 1"
            class="multi-badge"
          >
            📎 {{ noticia.archivos.length }} archivos
          </span>
        </div>

        <!-- CONTENIDO DE TARJETA -->
        <div class="card-body">

          <div class="card-meta">
            <span class="meta-fecha">
              <span class="meta-icon">📅</span>
              {{ formatDate(noticia.fecha_publicacion || noticia.fecha_creacion) }}
            </span>
          </div>

          <h3 class="card-title">
            {{ noticia.titulo }}
          </h3>

          <p class="card-resumen">
            {{ noticia.resumen || '-' }}
          </p>
        </div>

        <!-- ACCIÓN -->
        <div class="card-footer">
          <button
            type="button"
            class="btn-leer-mas"
            @click="abrirModal(noticia)"
          >
            Leer más
            <span class="btn-arrow">→</span>
          </button>
        </div>
      </div>
    </div>

    <!-- SIN RESULTADOS -->
    <div
      v-else
      class="empty-state"
    >
      <div class="empty-icon">📭</div>

      <h3>No hay noticias disponibles</h3>

      <p>
        No se encontraron resultados para tu búsqueda.
      </p>

      <button
        type="button"
        class="btn-primary"
        @click="resetFilters"
      >
        Limpiar filtros
      </button>
    </div>

    <!-- PAGINACIÓN -->
    <div
      v-if="totalPages > 1"
      class="pagination"
    >
      <div class="pagination-info">
        <span class="info-text">
          Mostrando
          {{ (currentPage - 1) * itemsPerPage + 1 }}
          -
          {{ Math.min(currentPage * itemsPerPage, filteredNoticias.length) }}
          de
          {{ filteredNoticias.length }}
          noticias
        </span>
      </div>

      <div class="pagination-controls">

        <button
          type="button"
          class="page-btn"
          :disabled="currentPage === 1"
          @click="goToPage(1)"
          title="Primera página"
        >
          ⟪
        </button>

        <button
          type="button"
          class="page-btn"
          :disabled="currentPage === 1"
          @click="goToPage(currentPage - 1)"
          title="Página anterior"
        >
          ←
        </button>

        <div class="page-numbers">
          <button
            v-for="page in pageNumbers"
            :key="page"
            type="button"
            class="page-num"
            :class="{
              active: page === currentPage,
              dots: page === '...'
            }"
            :disabled="page === '...'"
            @click="page !== '...' && goToPage(page)"
          >
            {{ page }}
          </button>
        </div>

        <button
          type="button"
          class="page-btn"
          :disabled="currentPage === totalPages"
          @click="goToPage(currentPage + 1)"
          title="Página siguiente"
        >
          →
        </button>

        <button
          type="button"
          class="page-btn"
          :disabled="currentPage === totalPages"
          @click="goToPage(totalPages)"
          title="Última página"
        >
          ⟫
        </button>

      </div>
    </div>

    <!-- MODAL -->
    <div
      v-if="modalVisible"
      class="modal-overlay"
      @click.self="cerrarModal"
    >
      <div class="modal-contenedor">

        <!-- CABECERA -->
        <div class="modal-header-custom">
          <h5 class="modal-titulo">
            <span class="modal-tipo-icon">📰</span>

            {{ noticiaSeleccionada.titulo }}
          </h5>

          <button
            type="button"
            class="btn-close-custom"
            @click="cerrarModal"
          >
            ✕
          </button>
        </div>

        <!-- CUERPO -->
        <div class="modal-body-custom">

          <!-- VIDEO -->
          <div
            v-if="archivoVideoPrincipal"
            class="galeria-container"
          >
            <div class="media-titulo">
              🎬 Video de la noticia
            </div>

            <div class="video-wrapper">
              <video
                :src="getFileUrl(archivoVideoPrincipal.ruta_archivo)"
                controls
                playsinline
                class="video-player"
              ></video>
            </div>
          </div>

          <!-- GALERÍA -->
          <div
            v-if="imagenesNoticia.length > 0"
            class="galeria-container"
          >
            <div class="media-titulo">
              🖼️ Galería de imágenes
            </div>

            <div class="modal-img-wrapper">

              <img
                :src="getFileUrl(imagenActual)"
                :alt="noticiaSeleccionada.titulo || 'Imagen de la noticia'"
                class="modal-imagen"
              />

              <div
                v-if="imagenesNoticia.length > 1"
                class="contador-imagenes"
              >
                {{ indiceActual + 1 }} / {{ imagenesNoticia.length }}
              </div>

              <button
                v-if="imagenesNoticia.length > 1"
                type="button"
                class="btn-nav btn-nav-izquierda"
                @click="cambiarImagenNavegacion(-1)"
              >
                ‹
              </button>

              <button
                v-if="imagenesNoticia.length > 1"
                type="button"
                class="btn-nav btn-nav-derecha"
                @click="cambiarImagenNavegacion(1)"
              >
                ›
              </button>

            </div>

            <!-- MINIATURAS -->
            <div
              v-if="imagenesNoticia.length > 1"
              class="miniaturas-container"
            >
              <div
                v-for="(imagen, index) in imagenesNoticia"
                :key="imagen.id_archivo || index"
                class="miniatura-item"
                :class="{
                  activa: imagenActual === imagen.ruta_archivo
                }"
                @click="cambiarImagen(imagen.ruta_archivo)"
              >
                <img
                  :src="getFileUrl(imagen.ruta_archivo)"
                  :alt="`Imagen ${index + 1}`"
                />
              </div>
            </div>
          </div>

          <!-- ARCHIVOS -->
          <div
            v-if="archivosNoVisuales.length > 0"
            class="archivos-noticia"
          >
            <div class="media-titulo">
              📎 Documentos y archivos adjuntos
            </div>

            <div
              v-for="archivo in archivosNoVisuales"
              :key="archivo.id_archivo || archivo.ruta_archivo"
              class="archivo-item"
            >

              <div class="archivo-info">

                <div class="archivo-icono">
                  {{ getFileIcon(archivo.extension, archivo.tipo_mime) }}
                </div>

                <div class="archivo-detalles">
                  <div class="archivo-nombre">
                    {{
                      archivo.nombre_archivo ||
                      getFileName(archivo.ruta_archivo)
                    }}
                  </div>

                  <div class="archivo-meta">
                    {{ getFileTypeLabel(archivo) }}

                    <span v-if="archivo.peso">
                      • {{ formatFileSize(archivo.peso) }}
                    </span>
                  </div>
                </div>

              </div>

              <div class="archivo-acciones">

                <button
                  v-if="isPdf(archivo)"
                  type="button"
                  class="btn btn-pdf"
                  @click="verArchivo(archivo)"
                >
                  <i class="fas fa-eye me-1"></i>
                  Ver PDF
                </button>

                <button
                  type="button"
                  class="btn btn-descargar"
                  @click="descargarArchivo(archivo)"
                >
                  <i class="fas fa-download me-1"></i>
                  Descargar
                </button>

              </div>
            </div>
          </div>

          <!-- FECHA -->
          <div class="modal-meta">
            <span class="meta-badge">
              <span class="meta-icon">📅</span>

              {{
                formatDate(
                  noticiaSeleccionada.fecha_publicacion ||
                  noticiaSeleccionada.fecha_creacion
                )
              }}
            </span>
          </div>

          <!-- CONTENIDO -->
          <div class="modal-contenido">
            <p class="contenido-texto">
              {{
                noticiaSeleccionada.contenido ||
                'No hay contenido disponible.'
              }}
            </p>
          </div>

        </div>

        <!-- PIE -->
        <div class="modal-footer-custom">

          <button
            type="button"
            class="btn btn-secondary"
            @click="cerrarModal"
          >
            ✕ Cerrar
          </button>

          <button
            type="button"
            class="btn btn-compartir"
            @click="compartirNoticia"
          >
            📤 Compartir
          </button>

        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useNoticiasStore } from '@/stores/noticias.js'

const store = useNoticiasStore()

const CATEGORIA_NOTICIAS_ID = 1

const searchQuery = ref('')
const sortOrder = ref('desc')

const currentPage = ref(1)
const itemsPerPage = 8

const modalVisible = ref(false)

const imagenActual = ref('')
const indiceActual = ref(0)

const noticiaSeleccionada = ref({
  id_noticia: null,
  titulo: '',
  resumen: '',
  contenido: '',
  fecha_creacion: '',
  fecha_publicacion: '',
  id_categoria: null,
  archivos: []
})

/* ==========================================
   NOTICIAS FILTRADAS
========================================== */

const filteredNoticias = computed(() => {
  let filtered = Array.isArray(store.noticias)
    ? [...store.noticias]
    : []

  filtered = filtered.filter(
    noticia =>
      Number(noticia.id_categoria) === CATEGORIA_NOTICIAS_ID
  )

  const query = searchQuery.value.trim().toLowerCase()

  if (query) {
    filtered = filtered.filter(noticia => {

      const titulo = noticia.titulo?.toLowerCase() || ''
      const resumen = noticia.resumen?.toLowerCase() || ''
      const contenido = noticia.contenido?.toLowerCase() || ''

      return (
        titulo.includes(query) ||
        resumen.includes(query) ||
        contenido.includes(query)
      )
    })
  }

  filtered.sort((a, b) => {

    const fechaA = new Date(
      a.fecha_publicacion ||
      a.fecha_creacion ||
      0
    )

    const fechaB = new Date(
      b.fecha_publicacion ||
      b.fecha_creacion ||
      0
    )

    return sortOrder.value === 'desc'
      ? fechaB - fechaA
      : fechaA - fechaB
  })

  return filtered
})

/* ==========================================
   PAGINACIÓN
========================================== */

const totalPages = computed(() => {
  return Math.ceil(
    filteredNoticias.value.length / itemsPerPage
  )
})

const paginatedNoticias = computed(() => {

  const start =
    (currentPage.value - 1) *
    itemsPerPage

  const end =
    start + itemsPerPage

  return filteredNoticias.value.slice(
    start,
    end
  )
})

const pageNumbers = computed(() => {

  const pages = []

  const total = totalPages.value
  const current = currentPage.value

  if (total <= 5) {

    for (let i = 1; i <= total; i++) {
      pages.push(i)
    }

    return pages
  }

  if (current <= 3) {
    pages.push(1, 2, 3, '...', total)
    return pages
  }

  if (current >= total - 2) {
    pages.push(
      1,
      '...',
      total - 2,
      total - 1,
      total
    )

    return pages
  }

  pages.push(
    1,
    '...',
    current - 1,
    current,
    current + 1,
    '...',
    total
  )

  return pages
})

/* ==========================================
   MULTIMEDIA
========================================== */

const imagenesNoticia = computed(() => {

  const archivos =
    noticiaSeleccionada.value?.archivos || []

  return archivos.filter(
    archivo =>
      archivo.tipo_mime?.toLowerCase().startsWith('image/')
  )
})

const archivoVideoPrincipal = computed(() => {

  const archivos =
    noticiaSeleccionada.value?.archivos || []

  return archivos.find(
    archivo =>
      archivo.tipo_mime?.toLowerCase().startsWith('video/')
  ) || null
})

const archivosNoVisuales = computed(() => {

  const archivos =
    noticiaSeleccionada.value?.archivos || []

  return archivos.filter(archivo => {

    const mime =
      archivo.tipo_mime?.toLowerCase() || ''

    return (
      !mime.startsWith('image/') &&
      !mime.startsWith('video/')
    )
  })
})

/* ==========================================
   FECHA
========================================== */

const formatDate = dateString => {

  if (!dateString) {
    return 'Fecha no disponible'
  }

  const date = new Date(dateString)

  if (Number.isNaN(date.getTime())) {
    return dateString
  }

  return date.toLocaleDateString(
    'es-ES',
    {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    }
  )
}

/* ==========================================
   URL
========================================== */

const getFileUrl = ruta => {

  if (!ruta) {
    return ''
  }

  if (
    ruta.startsWith('http://') ||
    ruta.startsWith('https://')
  ) {
    return ruta
  }

  return `/${ruta.replace(/^\/+/, '')}`
}

/* ==========================================
   VIDEO
========================================== */

const isVideo = noticia => {

  if (
    !noticia?.archivos ||
    !noticia.archivos.length
  ) {
    return false
  }

  return noticia.archivos.some(
    archivo =>
      archivo.tipo_mime?.toLowerCase().startsWith('video/')
  )
}

/* ==========================================
   MULTIMEDIA PRINCIPAL
========================================== */

const getMainMedia = noticia => {

  if (
    !noticia?.archivos ||
    !noticia.archivos.length
  ) {
    return '/images/default-noticia.jpg'
  }

  const video = noticia.archivos.find(
    archivo =>
      archivo.tipo_mime?.toLowerCase().startsWith('video/')
  )

  if (video) {
    return getFileUrl(video.ruta_archivo)
  }

  const imagen = noticia.archivos.find(
    archivo =>
      archivo.tipo_mime?.toLowerCase().startsWith('image/')
  )

  if (imagen) {
    return getFileUrl(imagen.ruta_archivo)
  }

  return '/images/default-noticia.jpg'
}

/* ==========================================
   ARCHIVOS
========================================== */

const getFileName = ruta => {

  if (!ruta) {
    return 'Archivo'
  }

  const partes = ruta.split('/')

  return partes[partes.length - 1]
}

const getFileIcon = (extension, mime) => {

  const ext =
    extension?.toLowerCase() || ''

  const tipo =
    mime?.toLowerCase() || ''

  if (
    ext === 'pdf' ||
    tipo.includes('pdf')
  ) {
    return '📕'
  }

  if (
    ext === 'doc' ||
    ext === 'docx' ||
    tipo.includes('word')
  ) {
    return '📘'
  }

  if (
    ext === 'xls' ||
    ext === 'xlsx' ||
    tipo.includes('excel') ||
    tipo.includes('spreadsheet')
  ) {
    return '📗'
  }

  if (ext === 'zip') {
    return '📦'
  }

  if (
    ext === 'txt' ||
    tipo.startsWith('text/')
  ) {
    return '📄'
  }

  return '📎'
}

const getFileTypeLabel = archivo => {

  const mime =
    archivo?.tipo_mime?.toLowerCase() || ''

  const extension =
    archivo?.extension?.toLowerCase() || ''

  if (
    mime.includes('pdf') ||
    extension === 'pdf'
  ) {
    return 'Documento PDF'
  }

  if (
    extension === 'doc' ||
    extension === 'docx' ||
    mime.includes('word')
  ) {
    return 'Documento Word'
  }

  if (
    extension === 'xls' ||
    extension === 'xlsx' ||
    mime.includes('excel') ||
    mime.includes('spreadsheet')
  ) {
    return 'Hoja de cálculo'
  }

  if (extension === 'zip') {
    return 'Archivo comprimido'
  }

  if (extension) {
    return extension.toUpperCase()
  }

  if (mime) {
    return mime
  }

  return 'Archivo'
}

const isPdf = archivo => {

  if (!archivo) {
    return false
  }

  const mime =
    archivo.tipo_mime?.toLowerCase() || ''

  const extension =
    archivo.extension?.toLowerCase() || ''

  return (
    mime.includes('application/pdf') ||
    mime.includes('pdf') ||
    extension === 'pdf'
  )
}

const formatFileSize = size => {

  if (
    size === null ||
    size === undefined ||
    size === ''
  ) {
    return 'Tamaño desconocido'
  }

  const bytes = Number(size)

  if (Number.isNaN(bytes)) {
    return 'Tamaño desconocido'
  }

  if (bytes < 1024) {
    return `${bytes} B`
  }

  if (bytes < 1024 * 1024) {
    return `${(bytes / 1024).toFixed(1)} KB`
  }

  if (bytes < 1024 * 1024 * 1024) {
    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`
  }

  return `${(
    bytes /
    (1024 * 1024 * 1024)
  ).toFixed(1)} GB`
}

const verArchivo = archivo => {

  if (!archivo?.ruta_archivo) {
    alert('No se encontró la ruta del archivo.')
    return
  }

  const url =
    getFileUrl(archivo.ruta_archivo)

  window.open(
    url,
    '_blank',
    'noopener,noreferrer'
  )
}

const descargarArchivo = archivo => {

  if (!archivo?.ruta_archivo) {
    alert('No se encontró la ruta del archivo.')
    return
  }

  const url =
    getFileUrl(archivo.ruta_archivo)

  const nombre =
    archivo.nombre_archivo ||
    getFileName(archivo.ruta_archivo) ||
    'archivo'

  const link =
    document.createElement('a')

  link.href = url
  link.download = nombre
  link.target = '_blank'
  link.rel = 'noopener noreferrer'

  document.body.appendChild(link)

  link.click()

  document.body.removeChild(link)
}

/* ==========================================
   NAVEGACIÓN
========================================== */

const goToPage = page => {

  if (
    page < 1 ||
    page > totalPages.value
  ) {
    return
  }

  currentPage.value = page

  const container =
    document.querySelector('.noticias-grid')

  if (container) {
    container.scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    })
  }
}

/* ==========================================
   MODAL
========================================== */

const abrirModal = noticia => {

  noticiaSeleccionada.value = {
    ...noticia,
    archivos: Array.isArray(noticia.archivos)
      ? [...noticia.archivos]
      : []
  }

  if (imagenesNoticia.value.length > 0) {

    imagenActual.value =
      imagenesNoticia.value[0].ruta_archivo

    indiceActual.value = 0

  } else {

    imagenActual.value = ''
    indiceActual.value = 0
  }

  modalVisible.value = true

  document.body.style.overflow = 'hidden'
}

const cerrarModal = () => {

  modalVisible.value = false

  imagenActual.value = ''
  indiceActual.value = 0

  document.body.style.overflow = ''
}

/* ==========================================
   GALERÍA
========================================== */

const cambiarImagen = ruta => {

  imagenActual.value = ruta

  const indice =
    imagenesNoticia.value.findIndex(
      imagen =>
        imagen.ruta_archivo === ruta
    )

  indiceActual.value =
    indice >= 0
      ? indice
      : 0
}

const cambiarImagenNavegacion = direccion => {

  const imagenes =
    imagenesNoticia.value

  if (imagenes.length <= 1) {
    return
  }

  let nuevoIndice =
    indiceActual.value + direccion

  if (nuevoIndice < 0) {
    nuevoIndice = imagenes.length - 1
  }

  if (nuevoIndice >= imagenes.length) {
    nuevoIndice = 0
  }

  indiceActual.value = nuevoIndice

  imagenActual.value =
    imagenes[nuevoIndice].ruta_archivo
}

/* ==========================================
   COMPARTIR
========================================== */

const compartirNoticia = async () => {

  const titulo =
    noticiaSeleccionada.value.titulo || ''

  const resumen =
    noticiaSeleccionada.value.resumen || ''

  const texto =
    `📰 ${titulo}\n\n${resumen}\n\nLeer más en: ${window.location.href}`

  if (navigator.share) {

    try {

      await navigator.share({
        title: titulo,
        text: resumen,
        url: window.location.href
      })

    } catch (error) {

      console.log(
        'Compartir cancelado:',
        error
      )
    }

    return
  }

  try {

    await navigator.clipboard.writeText(texto)

    alert(
      '¡Enlace copiado al portapapeles!'
    )

  } catch {

    alert(
      'Comparte esta noticia:\n\n' +
      texto
    )
  }
}

/* ==========================================
   FILTROS
========================================== */

const resetFilters = () => {

  searchQuery.value = ''
  sortOrder.value = 'desc'
  currentPage.value = 1
}

/* ==========================================
   CARGA
========================================== */

onMounted(async () => {
  await store.fetchNoticias()
})

onBeforeUnmount(() => {
  document.body.style.overflow = ''
})
</script>

<style scoped>
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

.noticias-header {
  text-align: center;
  margin-bottom: 2.5rem;
  padding: 2rem;
}

.noticias-titulo {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0 0 0.5rem;
  color: #cc0000;
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

.noticias-filtros {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  padding: 1rem;
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
}

.search-input,
.filter-select {
  padding: 0.75rem 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 0.5rem;
  font-size: 0.95rem;
  background: white;
}

.search-input {
  flex: 1;
  min-width: 200px;
}

.filter-select {
  min-width: 160px;
}

.search-input:focus,
.filter-select:focus {
  outline: none;
  border-color: #cc0000;
}

.noticias-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
}

.noticia-card {
  background: white;
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
  display: flex;
  flex-direction: column;
  transition: 0.3s ease;
}

.noticia-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
}

.card-tipo-badge {
  padding: 0.6rem 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #cc0000;
  font-weight: 600;
  font-size: 0.85rem;
  background: #f8f9fa;
}

.destacado-badge {
  color: #d4a000;
}

.card-img-wrapper {
  position: relative;
  width: 100%;
  height: 220px;
  overflow: hidden;
  background: #f5f5f5;
}

.card-img,
.card-video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.multi-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 0.35rem 0.65rem;
  border-radius: 20px;
  font-size: 0.75rem;
}

.card-body {
  padding: 1.25rem;
  flex: 1;
}

.card-meta {
  margin-bottom: 0.75rem;
}

.meta-fecha {
  color: #718096;
  font-size: 0.82rem;
}

.card-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1a202c;
  line-height: 1.4;
  margin-bottom: 0.6rem;
}

.card-resumen {
  color: #4a5568;
  font-size: 0.95rem;
  line-height: 1.6;
  margin: 0;
}

.card-footer {
  padding: 0.8rem 1.25rem;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
}

.btn-leer-mas {
  background: transparent;
  border: none;
  color: #cc0000;
  font-weight: 600;
  cursor: pointer;
}

.btn-arrow {
  margin-left: 0.4rem;
}

.empty-state,
.loading-state {
  text-align: center;
  padding: 4rem 2rem;
  background: rgba(255, 255, 255, 0.85);
  border-radius: 1rem;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.btn-primary {
  background: #cc0000;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  cursor: pointer;
}

.spinner {
  width: 40px;
  height: 40px;
  margin: 0 auto 1rem;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #cc0000;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

.pagination {
  margin-top: 2rem;
  padding: 1rem;
  text-align: center;
  background: rgba(255, 255, 255, 0.75);
  border-radius: 1rem;
}

.pagination-info {
  margin-bottom: 1rem;
}

.pagination-controls {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.page-btn,
.page-num {
  min-width: 36px;
  padding: 0.5rem 0.7rem;
  border: 1px solid #e2e8f0;
  background: white;
  border-radius: 0.5rem;
  cursor: pointer;
}

.page-num.active {
  background: #cc0000;
  color: white;
  border-color: #cc0000;
}

.page-num.dots {
  cursor: default;
}

/* MODAL */

.modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 9999;
  background: rgba(0, 0, 0, 0.65);
  backdrop-filter: blur(6px);
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1rem;
}

.modal-contenedor {
  width: 100%;
  max-width: 900px;
  max-height: 95vh;
  background: white;
  border-radius: 1.5rem;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.modal-header-custom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.25rem 1.5rem;
  background: #cc0000;
  color: white;
}

.modal-titulo {
  margin: 0;
  font-size: 1.15rem;
  font-weight: 700;
  line-height: 1.3;
}

.btn-close-custom {
  background: transparent;
  border: none;
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
}

.modal-body-custom {
  padding: 1.5rem;
  overflow-y: auto;
}

.galeria-container,
.archivos-noticia {
  margin-bottom: 1.5rem;
}

.media-titulo {
  margin-bottom: 0.75rem;
  font-weight: 700;
  color: #2d3748;
}

.modal-img-wrapper,
.video-wrapper {
  position: relative;
  width: 100%;
  border-radius: 0.75rem;
  overflow: hidden;
  background: #f5f5f5;
}

.modal-img-wrapper {
  max-height: 450px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-imagen {
  width: 100%;
  max-height: 450px;
  object-fit: contain;
}

.video-wrapper {
  background: #000;
}

.video-player {
  display: block;
  width: 100%;
  max-height: 450px;
  background: #000;
}

.contador-imagenes {
  position: absolute;
  top: 10px;
  right: 10px;
  padding: 0.3rem 0.75rem;
  border-radius: 20px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
}

.btn-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 42px;
  height: 42px;
  border: none;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.55);
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
}

.btn-nav-izquierda {
  left: 10px;
}

.btn-nav-derecha {
  right: 10px;
}

.miniaturas-container {
  display: flex;
  gap: 0.5rem;
  overflow-x: auto;
  margin-top: 0.75rem;
}

.miniatura-item {
  flex: 0 0 80px;
  height: 60px;
  border: 3px solid transparent;
  border-radius: 6px;
  overflow: hidden;
  cursor: pointer;
}

.miniatura-item.activa {
  border-color: #cc0000;
}

.miniatura-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.archivo-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  margin-bottom: 0.75rem;
  border: 1px solid #e2e8f0;
  background: #f8f9fa;
  border-radius: 0.75rem;
}

.archivo-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  min-width: 0;
}

.archivo-icono {
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 0 0 42px;
  border-radius: 0.5rem;
  background: white;
  font-size: 1.3rem;
}

.archivo-nombre {
  font-weight: 600;
  color: #2d3748;
  word-break: break-word;
}

.archivo-meta {
  margin-top: 0.2rem;
  font-size: 0.75rem;
  color: #718096;
}

.archivo-acciones {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.btn-pdf,
.btn-descargar {
  border-radius: 0.5rem;
  padding: 0.5rem 0.8rem;
  cursor: pointer;
}

.btn-pdf {
  background: white;
  color: #dc3545;
  border: 1px solid #dc3545;
}

.btn-descargar {
  background: white;
  color: #0d6efd;
  border: 1px solid #0d6efd;
}

.modal-meta {
  margin-bottom: 1rem;
}

.meta-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  padding: 0.35rem 0.8rem;
  border-radius: 20px;
  background: #6c757d;
  color: white;
  font-size: 0.8rem;
}

.contenido-texto {
  margin: 0;
  color: #4a5568;
  line-height: 1.8;
  text-align: justify;
  font-size: 1.05rem;
}

.modal-footer-custom {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  padding: 1rem 1.5rem;
  border-top: 1px solid #e9ecef;
}

.btn {
  border: none;
  border-radius: 0.5rem;
  padding: 0.55rem 1rem;
  font-weight: 600;
  cursor: pointer;
}

.btn-secondary {
  background: #f1f3f5;
  color: #495057;
}

.btn-compartir {
  background: #edf2f7;
  color: #495057;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
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
    font-size: 0.95rem;
  }

  .noticias-grid {
    grid-template-columns: 1fr;
  }

  .archivo-item {
    flex-direction: column;
    align-items: flex-start;
  }

  .archivo-acciones {
    width: 100%;
  }

  .archivo-acciones .btn {
    flex: 1;
  }

  .modal-footer-custom {
    flex-direction: column;
  }

  .modal-footer-custom .btn {
    width: 100%;
  }
}

@media (max-width: 480px) {

  .card-img-wrapper {
    height: 180px;
  }

  .noticias-titulo {
    font-size: 1.5rem;
  }
}
</style>
