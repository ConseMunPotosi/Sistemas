<template>
  <div class="press-release-board">

    <!-- =====================================================
         ENCABEZADO
    ====================================================== -->
    <div class="press-header">
      <div>
        <div class="press-icon">
          <i class="fas fa-newspaper"></i>
        </div>

        <h2 class="press-title">
          Boletines de Prensa
        </h2>

        <p class="press-subtitle">
          Información oficial y comunicados de interés del Concejo Municipal
          de Potosí.
        </p>
      </div>
    </div>


    <!-- =====================================================
         FILTROS
    ====================================================== -->
    <div
      v-if="!loading && releases.length > 0"
      class="press-filters"
    >

      <div class="search-box">

        <i class="fas fa-search"></i>

        <input
          v-model="searchQuery"
          type="text"
          placeholder="Buscar boletín..."
        >

      </div>


      <div class="filter-box">

        <select v-model="sortOrder">

          <option value="desc">
            Más recientes
          </option>

          <option value="asc">
            Más antiguos
          </option>

        </select>

      </div>


      <div class="results-count">

        <strong>
          {{ filteredReleases.length }}
        </strong>

        <span>
          {{ filteredReleases.length === 1
            ? 'boletín'
            : 'boletines'
          }}
        </span>

      </div>

    </div>


    <!-- =====================================================
         CARGANDO
    ====================================================== -->
    <div
      v-if="loading"
      class="state-container"
    >

      <div class="spinner-border text-danger"></div>

      <p>
        Cargando boletines...
      </p>

    </div>


    <!-- =====================================================
         ERROR
    ====================================================== -->
    <div
      v-else-if="error"
      class="alert alert-danger"
    >

      <i class="fas fa-exclamation-triangle me-2"></i>

      {{ error }}

    </div>


    <!-- =====================================================
         SIN BOLETINES
    ====================================================== -->
    <div
      v-else-if="filteredReleases.length === 0"
      class="empty-state"
    >

      <div class="empty-icon">
        <i class="fas fa-newspaper"></i>
      </div>

      <h3>
        No se encontraron boletines
      </h3>

      <p>
        Actualmente no existen boletines de prensa disponibles.
      </p>

      <button
        v-if="searchQuery"
        class="btn btn-outline-danger"
        @click="searchQuery = ''"
      >
        Limpiar búsqueda
      </button>

    </div>


    <!-- =====================================================
         LISTADO
    ====================================================== -->
    <div
      v-else
      class="press-list"
    >

      <article
        v-for="release in paginatedReleases"
        :key="release.id"
        class="press-release-item"
      >

        <div class="release-main">


          <!-- =================================================
               PDF
          ================================================== -->
          <div class="release-thumbnail">

            <div
              v-if="release.pdfUrl"
              class="pdf-thumbnail"
              @click="openPdf(release.pdfUrl)"
            >

              <div class="pdf-icon-wrapper">

                <svg
                  class="pdf-icon"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                >

                  <path
                    d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"
                  />

                  <path
                    d="M14 2v6h6"
                  />

                  <path
                    d="M12 18v-4"
                  />

                  <path
                    d="M12 10v.01"
                  />

                </svg>

                <span class="pdf-badge">
                  PDF
                </span>

              </div>


              <div class="pdf-info">

                <span class="pdf-name">
                  {{ getPdfName(release.pdfUrl) }}
                </span>

                <span class="pdf-size">
                  {{ getPdfSize(release.pdfSize) }}
                </span>

              </div>


              <button
                class="pdf-download-btn"
                @click.stop="downloadPdf(
                  release.pdfUrl,
                  release.title
                )"
                title="Descargar PDF"
              >

                <i class="fas fa-download"></i>

              </button>

            </div>


            <div
              v-else
              class="no-pdf"
            >

              <span class="no-pdf-icon">
                <i class="fas fa-file-alt"></i>
              </span>

              <span class="no-pdf-text">
                Sin PDF
              </span>

            </div>

          </div>


          <!-- =================================================
               INFORMACIÓN
          ================================================== -->
          <div class="release-info">

            <div class="release-header">

              <div class="release-title-section">

                <span class="category-badge">
                  Boletín de Prensa
                </span>

                <h3 class="release-title">
                  {{ release.title }}
                </h3>

              </div>

            </div>


            <!-- FECHA -->
            <div class="release-meta">

              <span class="meta-item">

                <i class="fas fa-calendar-alt"></i>

                {{ formatDate(release.publishDate) }}

              </span>

            </div>


            <!-- RESUMEN -->
            <p class="release-summary">
              {{ release.summary }}
            </p>


            <!-- LEER MÁS -->
            <button
              class="read-more-btn"
              @click="toggleExpand(release.id)"
            >

              {{
                expandedReleases[release.id]
                  ? 'Leer menos'
                  : 'Leer más'
              }}

              <span class="btn-arrow">
                →
              </span>

            </button>


            <!-- CONTENIDO -->
            <div
              v-if="expandedReleases[release.id]"
              class="release-content"
            >

              <p>
                {{ release.content }}
              </p>

            </div>


            <!-- ACCIONES -->
            <div class="release-actions">

              <button
                v-if="release.pdfUrl"
                class="btn btn-outline-danger btn-sm"
                @click="openPdf(release.pdfUrl)"
              >

                <i class="fas fa-eye me-2"></i>

                Ver PDF

              </button>


              <button
                v-if="release.pdfUrl"
                class="btn btn-danger btn-sm"
                @click="downloadPdf(
                  release.pdfUrl,
                  release.title
                )"
              >

                <i class="fas fa-download me-2"></i>

                Descargar

              </button>

            </div>

          </div>

        </div>

      </article>

    </div>


    <!-- =====================================================
         PAGINACIÓN
    ====================================================== -->
    <div
      v-if="totalPages > 1"
      class="pagination"
    >

      <button
        class="page-btn"
        :disabled="currentPage === 1"
        @click="goToPage(1)"
      >
        «
      </button>


      <button
        class="page-btn"
        :disabled="currentPage === 1"
        @click="goToPage(currentPage - 1)"
      >
        ‹
      </button>


      <button
        v-for="page in pageNumbers"
        :key="page"
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


      <button
        class="page-btn"
        :disabled="currentPage === totalPages"
        @click="goToPage(currentPage + 1)"
      >
        ›
      </button>


      <button
        class="page-btn"
        :disabled="currentPage === totalPages"
        @click="goToPage(totalPages)"
      >
        »
      </button>

    </div>

  </div>
</template>


<script setup>

import {
  ref,
  computed,
  onMounted,
  watch
} from 'vue';

import axios from 'axios';


/*
|--------------------------------------------------------------------------
| Estado
|--------------------------------------------------------------------------
*/

const releases = ref([]);

const loading = ref(false);

const error = ref('');

const searchQuery = ref('');

const sortOrder = ref('desc');

const currentPage = ref(1);

const itemsPerPage = 5;

const expandedReleases = ref({});


/*
|--------------------------------------------------------------------------
| Cargar noticias desde API
|--------------------------------------------------------------------------
*/

const loadReleases = async () => {

  loading.value = true;

  error.value = '';

  try {

    const response = await axios.get(
      '/api/noticias'
    );


    const noticias = response.data.data || [];


    /*
     * Categoría de Boletines de Prensa:
     * id_categoria = 2
     */
    releases.value = noticias

      .filter((noticia) => {

        const categoriaId =
          noticia.categoria?.id_categoria;

        const publicadoWeb =
          noticia.publicado_web !== false;

        const estado =
          noticia.estado !== false;


        return (
          Number(categoriaId) === 2 &&
          publicadoWeb &&
          estado
        );

      })


      .map((noticia) => {

        /*
         * Buscar el primer archivo disponible.
         */
        const archivo =
          noticia.archivos?.find(
            (item) =>
              item.estado === undefined ||
              item.estado === true
          ) ||
          noticia.archivos?.[0] ||
          null;


        return {

          id: noticia.id_noticia,

          title:
            noticia.titulo ||
            'Sin título',

          summary:
            noticia.resumen ||
            'Sin resumen disponible.',

          content:
            noticia.contenido ||
            'Sin contenido disponible.',

          publishDate:
            noticia.fecha_publicacion ||
            noticia.fecha_creacion,

          pdfUrl:
            archivo?.ruta_archivo
              ? getFileUrl(archivo.ruta_archivo)
              : null,

          pdfSize:
            archivo?.peso_bytes ||
            null

        };

      });


    currentPage.value = 1;

  } catch (err) {

    console.error(
      'Error al cargar boletines:',
      err
    );

    error.value =
      'No se pudieron cargar los boletines de prensa.';

  } finally {

    loading.value = false;

  }

};


/*
|--------------------------------------------------------------------------
| Filtro y orden
|--------------------------------------------------------------------------
*/

const filteredReleases = computed(() => {

  let filtered = [
    ...releases.value
  ];


  if (searchQuery.value.trim()) {

    const query =
      searchQuery.value
        .trim()
        .toLowerCase();


    filtered = filtered.filter(
      (release) =>

        release.title
          .toLowerCase()
          .includes(query)

        ||

        release.summary
          .toLowerCase()
          .includes(query)

        ||

        release.content
          .toLowerCase()
          .includes(query)
    );

  }


  filtered.sort((a, b) => {

    const dateA =
      new Date(a.publishDate);

    const dateB =
      new Date(b.publishDate);


    return sortOrder.value === 'desc'
      ? dateB - dateA
      : dateA - dateB;

  });


  return filtered;

});


/*
|--------------------------------------------------------------------------
| Paginación
|--------------------------------------------------------------------------
*/

const totalPages = computed(() => {

  return Math.ceil(
    filteredReleases.value.length /
    itemsPerPage
  );

});


const paginatedReleases = computed(() => {

  const start =
    (currentPage.value - 1) *
    itemsPerPage;

  const end =
    start + itemsPerPage;


  return filteredReleases.value.slice(
    start,
    end
  );

});


const pageNumbers = computed(() => {

  const pages = [];

  const total = totalPages.value;

  const current = currentPage.value;


  if (total <= 5) {

    for (
      let i = 1;
      i <= total;
      i++
    ) {

      pages.push(i);

    }

  } else {

    if (current <= 3) {

      pages.push(
        1,
        2,
        3,
        '...',
        total
      );

    } else if (current >= total - 2) {

      pages.push(
        1,
        '...',
        total - 2,
        total - 1,
        total
      );

    } else {

      pages.push(
        1,
        '...',
        current - 1,
        current,
        current + 1,
        '...',
        total
      );

    }

  }


  return pages;

});


const goToPage = (page) => {

  if (
    page >= 1 &&
    page <= totalPages.value
  ) {

    currentPage.value = page;

    const container =
      document.querySelector(
        '.press-list'
      );


    if (container) {

      container.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });

    }

  }

};


/*
|--------------------------------------------------------------------------
| Reiniciar página cuando cambia búsqueda
|--------------------------------------------------------------------------
*/

watch(
  searchQuery,
  () => {

    currentPage.value = 1;

  }
);


/*
|--------------------------------------------------------------------------
| Expandir contenido
|--------------------------------------------------------------------------
*/

const toggleExpand = (id) => {

  expandedReleases.value[id] =
    !expandedReleases.value[id];

};


/*
|--------------------------------------------------------------------------
| Formato de fecha
|--------------------------------------------------------------------------
*/

const formatDate = (dateString) => {

  if (!dateString) {

    return 'Fecha no disponible';

  }


  const date =
    new Date(dateString);


  if (Number.isNaN(date.getTime())) {

    return dateString;

  }


  return date.toLocaleDateString(
    'es-ES',
    {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    }
  );

};


/*
|--------------------------------------------------------------------------
| Nombre del PDF
|--------------------------------------------------------------------------
*/

const getPdfName = (url) => {

  if (!url) {
    return 'Documento';
  }


  const parts =
    url.split('/');


  const filename =
    parts[parts.length - 1];


  return filename.length > 28
    ? filename.substring(0, 28) + '...'
    : filename;

};


/*
|--------------------------------------------------------------------------
| Tamaño del PDF
|--------------------------------------------------------------------------
*/

const getPdfSize = (size) => {

  if (!size) {
    return 'Tamaño desconocido';
  }


  const bytes =
    parseInt(size);


  if (Number.isNaN(bytes)) {

    return 'Tamaño desconocido';

  }


  if (bytes < 1024) {

    return `${bytes} B`;

  }


  if (bytes < 1024 * 1024) {

    return `${(
      bytes / 1024
    ).toFixed(1)} KB`;

  }


  if (
    bytes <
    1024 * 1024 * 1024
  ) {

    return `${(
      bytes /
      (1024 * 1024)
    ).toFixed(1)} MB`;

  }


  return `${(
    bytes /
    (1024 * 1024 * 1024)
  ).toFixed(1)} GB`;

};


/*
|--------------------------------------------------------------------------
| URL de archivo
|--------------------------------------------------------------------------
*/

const getFileUrl = (ruta) => {

  if (!ruta) {
    return null;
  }


  return ruta.startsWith('/')
    ? ruta
    : `/${ruta}`;

};


/*
|--------------------------------------------------------------------------
| Abrir PDF
|--------------------------------------------------------------------------
*/

const openPdf = (url) => {

  if (url) {

    window.open(
      url,
      '_blank',
      'noopener,noreferrer'
    );

  }

};


/*
|--------------------------------------------------------------------------
| Descargar PDF
|--------------------------------------------------------------------------
*/

const downloadPdf = (
  url,
  title
) => {

  if (!url) {
    return;
  }


  const link =
    document.createElement('a');


  link.href = url;

  link.download =
    `${title || 'boletin'}.pdf`;

  link.target = '_blank';

  document.body.appendChild(link);

  link.click();

  document.body.removeChild(link);

};


/*
|--------------------------------------------------------------------------
| Inicialización
|--------------------------------------------------------------------------
*/

onMounted(() => {

  loadReleases();

});

</script>


<style scoped>

.press-release-board {
  width: 100%;

  min-height: 100vh;

  padding: 2rem;

  background-image:
    url('/images/fondo.png');

  background-size: cover;

  background-position: center;

  background-attachment: fixed;

  background-repeat: no-repeat;
}


/* =========================================================
   ENCABEZADO
========================================================= */

.press-header {
  max-width: 1200px;

  margin: 0 auto 2rem;

  text-align: center;
}


.press-icon {
  width: 60px;

  height: 60px;

  margin: 0 auto 1rem;

  display: flex;

  align-items: center;

  justify-content: center;

  border-radius: 50%;

  background: #cc0000;

  color: white;

  font-size: 1.5rem;

  box-shadow:
    0 8px 20px rgba(204, 0, 0, 0.2);
}


.press-title {
  margin: 0;

  color: #cc0000;

  font-size: 2.3rem;

  font-weight: 800;
}


.press-subtitle {
  max-width: 850px;

  margin: 0.75rem auto 0;

  color: #666;

  line-height: 1.6;

  font-size: 1rem;
}


/* =========================================================
   FILTROS
========================================================= */

.press-filters {
  max-width: 1200px;

  margin: 0 auto 1.5rem;

  padding: 1rem;

  display: flex;

  align-items: center;

  gap: 1rem;

  flex-wrap: wrap;

  background: rgba(
    255,
    255,
    255,
    0.95
  );

  border-radius: 12px;

  box-shadow:
    0 4px 15px rgba(0, 0, 0, 0.08);
}


.search-box {
  flex: 1;

  min-width: 250px;

  position: relative;
}


.search-box i {
  position: absolute;

  left: 15px;

  top: 50%;

  transform: translateY(-50%);

  color: #999;
}


.search-box input {
  width: 100%;

  padding: 0.7rem 1rem 0.7rem 2.7rem;

  border: 1px solid #ddd;

  border-radius: 8px;

  outline: none;
}


.search-box input:focus {
  border-color: #cc0000;

  box-shadow:
    0 0 0 3px rgba(
      204,
      0,
      0,
      0.08
    );
}


.filter-box select {
  min-width: 180px;

  padding: 0.7rem 1rem;

  border: 1px solid #ddd;

  border-radius: 8px;

  background: white;
}


.results-count {
  min-width: 130px;

  display: flex;

  justify-content: center;

  align-items: center;

  gap: 0.4rem;

  color: #666;
}


.results-count strong {
  color: #cc0000;

  font-size: 1.2rem;
}


/* =========================================================
   LISTADO
========================================================= */

.press-list {
  max-width: 1200px;

  margin: 0 auto;

  display: flex;

  flex-direction: column;

  gap: 1.5rem;
}


.press-release-item {
  background: white;

  border-radius: 14px;

  padding: 1.25rem;

  box-shadow:
    0 4px 15px rgba(
      0,
      0,
      0,
      0.08
    );

  border-left: 4px solid #e2e8f0;

  transition:
    transform 0.25s ease,
    box-shadow 0.25s ease,
    border-color 0.25s ease;
}


.press-release-item:hover {
  transform: translateY(-3px);

  box-shadow:
    0 10px 24px rgba(
      0,
      0,
      0,
      0.12
    );

  border-left-color: #cc0000;
}


.release-main {
  display: flex;

  gap: 1.5rem;

  align-items: stretch;
}


/* =========================================================
   PDF
========================================================= */

.release-thumbnail {
  flex: 0 0 190px;

  min-height: 190px;
}


.pdf-thumbnail,
.no-pdf {
  width: 100%;

  min-height: 190px;

  border-radius: 10px;
}


.pdf-thumbnail {
  position: relative;

  display: flex;

  flex-direction: column;

  align-items: center;

  justify-content: center;

  padding: 1rem;

  background:
    linear-gradient(
      135deg,
      #f7f7f7,
      #e9e9e9
    );

  border: 2px solid #e2e8f0;

  cursor: pointer;
}


.pdf-thumbnail:hover {
  border-color: #cc0000;
}


.pdf-icon-wrapper {
  position: relative;

  margin-bottom: 0.6rem;
}


.pdf-icon {
  width: 64px;

  height: 64px;

  color: #cc0000;
}


.pdf-badge {
  position: absolute;

  top: -5px;

  right: -25px;

  padding: 0.15rem 0.5rem;

  border-radius: 4px;

  background: #cc0000;

  color: white;

  font-size: 0.65rem;

  font-weight: 700;
}


.pdf-info {
  width: 100%;

  text-align: center;
}


.pdf-name {
  display: block;

  color: #333;

  font-size: 0.75rem;

  font-weight: 600;

  word-break: break-all;
}


.pdf-size {
  color: #888;

  font-size: 0.7rem;
}


.pdf-download-btn {
  position: absolute;

  right: 8px;

  bottom: 8px;

  width: 34px;

  height: 34px;

  display: flex;

  align-items: center;

  justify-content: center;

  border: none;

  border-radius: 50%;

  background: #cc0000;

  color: white;

  cursor: pointer;
}


.no-pdf {
  display: flex;

  flex-direction: column;

  align-items: center;

  justify-content: center;

  gap: 0.5rem;

  background: #f8f8f8;

  border: 2px dashed #ddd;

  color: #999;
}


.no-pdf-icon {
  font-size: 2.5rem;
}


/* =========================================================
   INFORMACIÓN
========================================================= */

.release-info {
  flex: 1;

  display: flex;

  flex-direction: column;
}


.release-title-section {
  display: flex;

  flex-direction: column;

  gap: 0.6rem;
}


.category-badge {
  align-self: flex-start;

  padding: 0.3rem 0.7rem;

  border-radius: 20px;

  background: #fbeaea;

  color: #cc0000;

  font-size: 0.75rem;

  font-weight: 700;
}


.release-title {
  margin: 0;

  color: #2d3748;

  font-size: 1.25rem;

  line-height: 1.4;

  font-weight: 700;
}


.release-meta {
  margin:
    0.8rem
    0
    0.5rem;
}


.meta-item {
  display: inline-flex;

  align-items: center;

  gap: 0.45rem;

  color: #777;

  font-size: 0.88rem;
}


.meta-item i {
  color: #cc0000;
}


.release-summary {
  margin: 0 0 0.7rem;

  color: #4a5568;

  line-height: 1.65;

  font-size: 0.95rem;
}


.read-more-btn {
  align-self: flex-start;

  padding: 0;

  border: none;

  background: none;

  color: #cc0000;

  font-weight: 600;

  cursor: pointer;
}


.btn-arrow {
  margin-left: 0.2rem;
}


.release-content {
  margin-top: 0.8rem;

  padding-top: 0.8rem;

  border-top: 1px solid #eee;
}


.release-content p {
  margin: 0;

  color: #444;

  line-height: 1.75;

  white-space: pre-line;
}


.release-actions {
  display: flex;

  gap: 0.5rem;

  margin-top: auto;

  padding-top: 1rem;
}


/* =========================================================
   ESTADOS
========================================================= */

.state-container {
  max-width: 1200px;

  margin: 0 auto;

  padding: 5rem 1rem;

  text-align: center;

  color: #777;
}


.state-container p {
  margin-top: 1rem;
}


.empty-state {
  max-width: 700px;

  margin: 2rem auto;

  padding: 4rem 1rem;

  text-align: center;

  background: rgba(
    255,
    255,
    255,
    0.9
  );

  border-radius: 14px;
}


.empty-icon {
  margin-bottom: 1rem;

  color: #bbb;

  font-size: 3.5rem;
}


.empty-state h3 {
  color: #555;

  margin-bottom: 0.5rem;
}


.empty-state p {
  color: #888;

  margin-bottom: 1.5rem;
}


/* =========================================================
   PAGINACIÓN
========================================================= */

.pagination {
  max-width: 1200px;

  margin: 2rem auto 0;

  display: flex;

  justify-content: center;

  align-items: center;

  gap: 0.35rem;
}


.page-btn,
.page-num {
  min-width: 38px;

  height: 38px;

  border-radius: 7px;

  border: 1px solid #ddd;

  background: white;

  cursor: pointer;

  font-weight: 600;
}


.page-btn:hover:not(:disabled),
.page-num:hover:not(:disabled) {
  border-color: #cc0000;

  color: #cc0000;
}


.page-num.active {
  border-color: #cc0000;

  background: #cc0000;

  color: white;
}


.page-btn:disabled,
.page-num:disabled {
  opacity: 0.45;

  cursor: not-allowed;
}


.page-num.dots {
  cursor: default;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

  .press-release-board {
    padding: 1rem;
  }


  .press-title {
    font-size: 1.8rem;
  }


  .press-filters {
    flex-direction: column;

    align-items: stretch;
  }


  .search-box,
  .filter-box,
  .results-count {
    width: 100%;

    min-width: 0;
  }


  .results-count {
    justify-content: flex-start;
  }


  .release-main {
    flex-direction: column;
  }


  .release-thumbnail {
    flex: none;

    width: 100%;
  }


  .pdf-thumbnail,
  .no-pdf {
    min-height: 160px;
  }

}

</style>
