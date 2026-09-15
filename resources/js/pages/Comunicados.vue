<template>
  <div class="official-communications">

    <!-- =====================================================
         ENCABEZADO
    ====================================================== -->
    <header class="communications-header">

      <div class="header-icon">
        <i class="fas fa-bullhorn"></i>
      </div>

      <h1 class="header-title">
        Comunicados Oficiales
      </h1>

      <p class="header-subtitle">
        Información oficial y anuncios del Concejo Municipal de Potosí.
      </p>

    </header>


    <!-- =====================================================
         FILTROS
    ====================================================== -->
    <section
      v-if="!store.loading && filteredCommunications.length > 0"
      class="filters-section"
    >

      <div class="filters-container">

        <div class="search-wrapper">

          <span class="search-icon">
            <i class="fas fa-search"></i>
          </span>

          <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar comunicados..."
            class="search-input"
          />

        </div>


        <div class="filters-group">

          <select
            v-model="sortOrder"
            class="filter-select"
          >

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
            {{ filteredCommunications.length }}
          </strong>

          <span>
            {{
              filteredCommunications.length === 1
                ? 'comunicado'
                : 'comunicados'
            }}
          </span>

        </div>

      </div>

    </section>


    <!-- =====================================================
         CONTENIDO
    ====================================================== -->
    <div class="content-container">


      <!-- CARGANDO -->
      <div
        v-if="store.loading"
        class="loading-state"
      >

        <div class="spinner"></div>

        <p>
          Cargando comunicados...
        </p>

      </div>


      <!-- ERROR -->
      <div
        v-else-if="store.error"
        class="error-state"
      >

        <div class="error-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>

        <h3>
          No se pudieron cargar los comunicados
        </h3>

        <p>
          {{ store.error }}
        </p>

        <button
          class="btn-primary"
          @click="cargarNuevamente"
        >
          <i class="fas fa-sync-alt me-2"></i>
          Intentar nuevamente
        </button>

      </div>


      <!-- LISTA -->
      <section
        v-else-if="filteredCommunications.length > 0"
        class="communications-list"
      >

        <div class="list-container">

          <article
            v-for="communication in paginatedCommunications"
            :key="communication.id_noticia"
            class="communication-card"
          >

            <!-- =================================================
                 MEDIA
            ================================================== -->
            <div class="card-image-wrapper">

              <!-- VIDEO -->
              <video
                v-if="getMainMedia(communication).type === 'video'"
                :src="getMainMedia(communication).url"
                controls
                preload="metadata"
                class="card-video"
              ></video>


              <!-- IMAGEN -->
              <img
                v-else
                :src="getMainMedia(communication).url"
                :alt="communication.titulo"
                class="card-image"
                loading="lazy"
                @error="handleImageError"
              />

            </div>


            <!-- =================================================
                 INFORMACIÓN
            ================================================== -->
            <div class="card-content">

              <div class="card-header">

                <div class="card-title-group">

                  <span class="category-badge">
                    Comunicado Oficial
                  </span>

                  <h3 class="card-title">
                    {{ communication.titulo || 'Sin título' }}
                  </h3>

                </div>

              </div>


              <!-- FECHA -->
              <div class="card-meta">

                <span class="meta-item">

                  <span class="meta-icon">
                    <i class="fas fa-calendar-alt"></i>
                  </span>

                  {{ formatDate(
                    communication.fecha_publicacion ||
                    communication.fecha_creacion
                  ) }}

                </span>

              </div>


              <!-- RESUMEN -->
              <p class="card-summary">

                {{
                  communication.resumen ||
                  'Sin resumen disponible.'
                }}

              </p>


              <!-- ACCIONES -->
              <div class="card-footer">

                <button
                  class="read-more-btn"
                  @click="toggleExpand(
                    communication.id_noticia
                  )"
                >

                  {{
                    expandedCommunications[
                      communication.id_noticia
                    ]
                      ? 'Ver menos'
                      : 'Leer más'
                  }}

                  <span class="btn-arrow">
                    →
                  </span>

                </button>


                <!-- PDF -->
                <template v-if="getPdfFile(communication)">

                  <button
                    class="btn-pdf-outline"
                    @click="openPdf(
                      getPdfFile(communication).ruta_archivo
                    )"
                  >

                    <i class="fas fa-eye me-1"></i>

                    Ver PDF

                  </button>


                  <a
                    class="btn-pdf"
                    :href="getFileUrl(
                      getPdfFile(communication).ruta_archivo
                    )"
                    :download="getPdfFile(
                      communication
                    ).nombre_archivo"
                    target="_blank"
                    rel="noopener noreferrer"
                  >

                    <i class="fas fa-download me-1"></i>

                    Descargar

                  </a>

                </template>

              </div>


              <!-- =================================================
                   CONTENIDO EXPANDIDO
              ================================================== -->
              <div
                v-if="
                  expandedCommunications[
                    communication.id_noticia
                  ]
                "
                class="card-expanded"
              >

                <div class="expanded-content">

                  <p
                    class="contenido-texto"
                    v-html="formatContent(
                      communication.contenido
                    )"
                  ></p>


                  <!-- ARCHIVOS -->
                  <div
                    v-if="communication.archivos?.length"
                    class="attachments"
                  >

                    <h4>
                      <i class="fas fa-paperclip me-2"></i>
                      Archivos adjuntos
                    </h4>


                    <ul class="attachment-list">

                      <li
                        v-for="archivo in communication.archivos"
                        :key="archivo.id_archivo"
                      >

                        <a
                          :href="getFileUrl(
                            archivo.ruta_archivo
                          )"
                          target="_blank"
                          rel="noopener noreferrer"
                          class="attachment-link"
                        >

                          <i
                            :class="getAttachmentIcon(
                              archivo
                            )"
                            class="me-2"
                          ></i>

                          {{ archivo.nombre_archivo }}

                        </a>


                        <span
                          v-if="archivo.peso_bytes"
                          class="file-size"
                        >
                          {{
                            formatFileSize(
                              archivo.peso_bytes
                            )
                          }}
                        </span>

                      </li>

                    </ul>

                  </div>

                </div>

              </div>

            </div>

          </article>

        </div>

      </section>


      <!-- =====================================================
           SIN RESULTADOS
      ====================================================== -->
      <div
        v-else
        class="empty-state"
      >

        <div class="empty-icon">

          <i
            :class="
              searchQuery
                ? 'fas fa-search'
                : 'fas fa-bullhorn'
            "
          ></i>

        </div>

        <h3 class="empty-title">

          {{
            searchQuery
              ? 'No se encontraron resultados'
              : 'No hay comunicados'
          }}

        </h3>

        <p class="empty-description">

          {{
            searchQuery
              ? 'No existen comunicados que coincidan con tu búsqueda.'
              : 'Actualmente no hay comunicados oficiales disponibles.'
          }}

        </p>


        <button
          v-if="searchQuery"
          class="btn-primary"
          @click="resetFilters"
        >
          Limpiar búsqueda
        </button>

      </div>

    </div>


    <!-- =====================================================
         PAGINACIÓN
    ====================================================== -->
    <div
      v-if="totalPages > 1"
      class="pagination"
    >

      <div class="pagination-info">

        <span class="info-text">

          Mostrando

          {{
            (currentPage - 1) *
            itemsPerPage + 1
          }}

          -

          {{
            Math.min(
              currentPage * itemsPerPage,
              filteredCommunications.length
            )
          }}

          de

          {{ filteredCommunications.length }}

          comunicados

        </span>

      </div>


      <div class="pagination-controls">

        <!-- Primera -->
        <button
          class="page-btn"
          :disabled="currentPage === 1"
          @click="goToPage(1)"
          title="Primera página"
        >
          «
        </button>


        <!-- Anterior -->
        <button
          class="page-btn"
          :disabled="currentPage === 1"
          @click="goToPage(currentPage - 1)"
          title="Página anterior"
        >
          ‹
        </button>


        <!-- Números -->
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
            @click="
              page !== '...' &&
              goToPage(page)
            "
          >

            {{ page }}

          </button>

        </div>


        <!-- Siguiente -->
        <button
          class="page-btn"
          :disabled="
            currentPage === totalPages
          "
          @click="goToPage(currentPage + 1)"
          title="Página siguiente"
        >
          ›
        </button>


        <!-- Última -->
        <button
          class="page-btn"
          :disabled="
            currentPage === totalPages
          "
          @click="goToPage(totalPages)"
          title="Última página"
        >
          »
        </button>

      </div>

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

import { useNoticiasStore }
  from '@/stores/noticias.js';


/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

const props = defineProps({

  initialCommunications: {
    type: Array,
    default: () => []
  }

});


/*
|--------------------------------------------------------------------------
| Store
|--------------------------------------------------------------------------
*/

const store =
  useNoticiasStore();


/*
|--------------------------------------------------------------------------
| Categoría Comunicados
|--------------------------------------------------------------------------
*/

const CATEGORIA_COMUNICADOS_ID = 3;


/*
|--------------------------------------------------------------------------
| Estado
|--------------------------------------------------------------------------
*/

const searchQuery = ref('');

const sortOrder = ref('desc');

const currentPage = ref(1);

const itemsPerPage = 5;

const expandedCommunications =
  ref({});


/*
|--------------------------------------------------------------------------
| Filtrar comunicados
|--------------------------------------------------------------------------
*/

const filteredCommunications =
  computed(() => {

    let filtered =
      store.noticias
        ? [...store.noticias]
        : [];


    /*
     * Categoría = Comunicados
     */
    filtered =
      filtered.filter(
        (noticia) =>
          Number(
            noticia.id_categoria
          ) ===
          CATEGORIA_COMUNICADOS_ID
      );


    /*
     * Solo activos
     */
    filtered =
      filtered.filter(
        (noticia) =>
          noticia.estado !== false &&
          noticia.estado !== 0
      );


    /*
     * Solo publicados en web
     */
    filtered =
      filtered.filter(
        (noticia) =>
          noticia.publicado_web !== false &&
          noticia.publicado_web !== 0
      );


    /*
     * Buscar
     */
    const query =
      searchQuery.value
        .trim()
        .toLowerCase();


    if (query) {

      filtered =
        filtered.filter(
          (comunicado) => {

            const titulo =
              String(
                comunicado.titulo || ''
              )
                .toLowerCase();

            const resumen =
              String(
                comunicado.resumen || ''
              )
                .toLowerCase();

            const contenido =
              String(
                comunicado.contenido || ''
              )
                .toLowerCase();


            return (
              titulo.includes(query) ||
              resumen.includes(query) ||
              contenido.includes(query)
            );

          }
        );

    }


    /*
     * Orden
     */
    filtered.sort(
      (a, b) => {

        const dateA =
          new Date(
            a.fecha_publicacion ||
            a.fecha_creacion
          );

        const dateB =
          new Date(
            b.fecha_publicacion ||
            b.fecha_creacion
          );


        return sortOrder.value === 'desc'
          ? dateB - dateA
          : dateA - dateB;

      }
    );


    return filtered;

  });


/*
|--------------------------------------------------------------------------
| Paginación
|--------------------------------------------------------------------------
*/

const totalPages =
  computed(() => {

    return Math.ceil(
      filteredCommunications.value.length /
      itemsPerPage
    );

  });


const paginatedCommunications =
  computed(() => {

    const start =
      (currentPage.value - 1) *
      itemsPerPage;

    const end =
      start + itemsPerPage;


    return filteredCommunications.value.slice(
      start,
      end
    );

  });


const pageNumbers =
  computed(() => {

    const pages = [];

    const total =
      totalPages.value;

    const current =
      currentPage.value;


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

      } else if (
        current >= total - 2
      ) {

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


/*
|--------------------------------------------------------------------------
| Fecha
|--------------------------------------------------------------------------
*/

const formatDate = (dateString) => {

  if (!dateString) {

    return 'Fecha no disponible';

  }


  try {

    const date =
      new Date(dateString);


    if (
      Number.isNaN(
        date.getTime()
      )
    ) {

      return 'Fecha inválida';

    }


    const day =
      String(
        date.getDate()
      ).padStart(2, '0');

    const month =
      String(
        date.getMonth() + 1
      ).padStart(2, '0');

    const year =
      date.getFullYear();


    return `${day}/${month}/${year}`;

  } catch {

    return 'Fecha inválida';

  }

};


/*
|--------------------------------------------------------------------------
| Tamaño archivo
|--------------------------------------------------------------------------
*/

const formatFileSize = (bytes) => {

  if (!bytes) {

    return '0 B';

  }


  const sizes =
    [
      'B',
      'KB',
      'MB',
      'GB'
    ];


  const i =
    Math.floor(
      Math.log(bytes) /
      Math.log(1024)
    );


  return (
    parseFloat(
      (
        bytes /
        Math.pow(
          1024,
          i
        )
      ).toFixed(2)
    ) +
    ' ' +
    sizes[i]
  );

};


/*
|--------------------------------------------------------------------------
| URL archivo
|--------------------------------------------------------------------------
*/

const getFileUrl = (ruta) => {

  if (!ruta) {

    return '#';

  }


  return ruta.startsWith('/')
    ? ruta
    : `/${ruta}`;

};


/*
|--------------------------------------------------------------------------
| Obtener PDF principal
|--------------------------------------------------------------------------
*/

const getPdfFile = (comunicado) => {

  if (
    !comunicado?.archivos ||
    comunicado.archivos.length === 0
  ) {

    return null;

  }


  return (
    comunicado.archivos.find(
      (archivo) =>
        archivo.extension?.toLowerCase() === 'pdf' ||
        archivo.tipo_mime === 'application/pdf'
    ) ||
    null
  );

};


/*
|--------------------------------------------------------------------------
| Obtener media principal
|--------------------------------------------------------------------------
*/

const getMainMedia = (comunicado) => {

  if (
    !comunicado?.archivos ||
    comunicado.archivos.length === 0
  ) {

    return {
      type: 'image',
      url: '/images/default-comunicado.jpg'
    };

  }


  /*
   * Primero video
   */
  const video =
    comunicado.archivos.find(
      (archivo) =>
        archivo.tipo_mime?.startsWith(
          'video/'
        )
    );


  if (video) {

    return {

      type: 'video',

      url:
        getFileUrl(
          video.ruta_archivo
        )

    };

  }


  /*
   * Luego imagen
   */
  const imagen =
    comunicado.archivos.find(
      (archivo) =>
        archivo.tipo_mime?.startsWith(
          'image/'
        )
    );


  if (imagen) {

    return {

      type: 'image',

      url:
        getFileUrl(
          imagen.ruta_archivo
        )

    };

  }


  /*
   * Si solamente hay PDF
   */
  return {

    type: 'image',

    url: '/images/default-comunicado.jpg'

  };

};


/*
|--------------------------------------------------------------------------
| Icono archivo
|--------------------------------------------------------------------------
*/

const getAttachmentIcon = (archivo) => {

  const mime =
    archivo?.tipo_mime || '';

  const extension =
    archivo?.extension
      ?.toLowerCase() || '';


  if (
    mime.startsWith('video/') ||
    [
      'mp4',
      'avi',
      'mov',
      'mkv',
      'webm'
    ].includes(extension)
  ) {

    return 'fas fa-video';

  }


  if (
    mime.startsWith('image/') ||
    [
      'jpg',
      'jpeg',
      'png',
      'gif',
      'webp'
    ].includes(extension)
  ) {

    return 'fas fa-image';

  }


  if (
    mime.includes('pdf') ||
    extension === 'pdf'
  ) {

    return 'fas fa-file-pdf';

  }


  return 'fas fa-file';

};


/*
|--------------------------------------------------------------------------
| Abrir PDF
|--------------------------------------------------------------------------
*/

const openPdf = (ruta) => {

  const url =
    getFileUrl(ruta);


  if (url && url !== '#') {

    window.open(
      url,
      '_blank',
      'noopener,noreferrer'
    );

  }

};


/*
|--------------------------------------------------------------------------
| Contenido
|--------------------------------------------------------------------------
*/

const formatContent = (contenido) => {

  if (!contenido) {

    return 'Sin contenido disponible.';

  }


  return String(contenido)
    .replace(
      /&/g,
      '&amp;'
    )
    .replace(
      /</g,
      '&lt;'
    )
    .replace(
      />/g,
      '&gt;'
    )
    .replace(
      /"/g,
      '&quot;'
    )
    .replace(
      /'/g,
      '&#039;'
    )
    .replace(
      /\n/g,
      '<br>'
    );

};


/*
|--------------------------------------------------------------------------
| Expandir
|--------------------------------------------------------------------------
*/

const toggleExpand = (id) => {

  expandedCommunications.value[id] =
    !expandedCommunications.value[id];

};


/*
|--------------------------------------------------------------------------
| Paginación
|--------------------------------------------------------------------------
*/

const goToPage = (page) => {

  if (
    page >= 1 &&
    page <= totalPages.value
  ) {

    currentPage.value =
      page;


    const container =
      document.querySelector(
        '.communications-list'
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
| Limpiar filtros
|--------------------------------------------------------------------------
*/

const resetFilters = () => {

  searchQuery.value = '';

  sortOrder.value = 'desc';

  currentPage.value = 1;

};


/*
|--------------------------------------------------------------------------
| Recargar
|--------------------------------------------------------------------------
*/

const cargarNuevamente = async () => {

  try {

    await store.fetchNoticias();

  } catch (error) {

    console.error(
      'Error:',
      error
    );

  }

};


/*
|--------------------------------------------------------------------------
| Error imagen
|--------------------------------------------------------------------------
*/

const handleImageError = (event) => {

  event.target.src =
    '/images/default-comunicado.jpg';

};


/*
|--------------------------------------------------------------------------
| Reiniciar página
|--------------------------------------------------------------------------
*/

watch(
  [
    searchQuery,
    sortOrder
  ],
  () => {

    currentPage.value = 1;

  }
);


/*
|--------------------------------------------------------------------------
| Cargar datos
|--------------------------------------------------------------------------
*/

onMounted(
  async () => {

    if (
      props.initialCommunications &&
      props.initialCommunications.length > 0
    ) {

      store.noticias =
        props.initialCommunications;

    } else {

      await store.fetchNoticias();

    }

  }
);

</script>


<style scoped>

.official-communications {
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
   HEADER
========================================================= */

.communications-header {
  max-width: 1200px;

  margin:
    0 auto
    2rem;

  text-align: center;
}


.header-icon {
  width: 62px;
  height: 62px;

  margin:
    0 auto
    1rem;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: #cc0000;

  color: #fff;

  font-size: 1.5rem;

  box-shadow:
    0 8px 20px
    rgba(204, 0, 0, 0.18);
}


.header-title {
  margin: 0;

  color: #cc0000;

  font-size: 2.4rem;

  font-weight: 800;
}


.header-subtitle {
  margin:
    0.7rem auto 0;

  color: #555;

  font-size: 1rem;
}


/* =========================================================
   FILTROS
========================================================= */

.filters-section {
  max-width: 1200px;

  margin:
    0 auto
    2rem;

  padding: 1rem 1.2rem;

  background:
    rgba(
      255,
      255,
      255,
      0.94
    );

  border-radius: 12px;

  box-shadow:
    0 4px 18px
    rgba(0, 0, 0, 0.08);
}


.filters-container {
  display: flex;

  align-items: center;

  gap: 1rem;

  flex-wrap: wrap;
}


.search-wrapper {
  position: relative;

  flex: 1;

  min-width: 240px;
}


.search-icon {
  position: absolute;

  left: 15px;

  top: 50%;

  transform:
    translateY(-50%);

  color: #999;
}


.search-input {
  width: 100%;

  padding:
    0.75rem
    1rem
    0.75rem
    2.7rem;

  border:
    1px solid #ddd;

  border-radius: 8px;

  outline: none;
}


.search-input:focus {
  border-color: #cc0000;

  box-shadow:
    0 0 0 3px
    rgba(204, 0, 0, 0.08);
}


.filter-select {
  min-width: 170px;

  padding: 0.75rem 1rem;

  border:
    1px solid #ddd;

  border-radius: 8px;

  background: #fff;

  cursor: pointer;
}


.results-count {
  min-width: 140px;

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
   LISTA
========================================================= */

.communications-list {
  max-width: 1200px;

  margin: 0 auto;
}


.list-container {
  display: flex;

  flex-direction: column;

  gap: 1.4rem;
}


/* =========================================================
   TARJETA
========================================================= */

.communication-card {
  display: flex;

  min-height: 240px;

  overflow: hidden;

  background:
    rgba(
      255,
      255,
      255,
      0.96
    );

  border:
    1px solid
    #e5e7eb;

  border-left:
    4px solid
    #e5e7eb;

  border-radius: 14px;

  box-shadow:
    0 4px 15px
    rgba(0, 0, 0, 0.07);

  transition:
    transform 0.25s ease,
    box-shadow 0.25s ease,
    border-color 0.25s ease;
}


.communication-card:hover {
  transform:
    translateY(-3px);

  box-shadow:
    0 10px 26px
    rgba(0, 0, 0, 0.11);

  border-left-color:
    #cc0000;
}


/* =========================================================
   MEDIA
========================================================= */

.card-image-wrapper {
  flex:
    0 0 300px;

  min-height: 240px;

  overflow: hidden;

  background: #111;
}


.card-image,
.card-video {
  display: block;

  width: 100%;

  height: 100%;

  min-height: 240px;

  object-fit: cover;
}


.card-video {
  background: #000;

  object-fit: contain;
}


/* =========================================================
   CONTENIDO
========================================================= */

.card-content {
  flex: 1;

  display: flex;

  flex-direction: column;

  padding: 1.4rem;
}


.card-header {
  margin-bottom: 0.8rem;
}


.card-title-group {
  display: flex;

  flex-direction: column;

  align-items: flex-start;

  gap: 0.6rem;
}


.category-badge {
  padding:
    0.3rem
    0.7rem;

  border-radius: 20px;

  background:
    #fbeaea;

  color:
    #cc0000;

  font-size:
    0.75rem;

  font-weight:
    700;
}


.card-title {
  margin: 0;

  color: #1e293b;

  font-size: 1.3rem;

  font-weight: 800;

  line-height: 1.4;
}


/* =========================================================
   META
========================================================= */

.card-meta {
  margin-bottom:
    0.8rem;
}


.meta-item {
  display: inline-flex;

  align-items: center;

  gap: 0.5rem;

  color: #64748b;

  font-size: 0.88rem;
}


.meta-icon {
  color:
    #cc0000;
}


/* =========================================================
   RESUMEN
========================================================= */

.card-summary {
  margin: 0;

  color: #475569;

  line-height: 1.65;

  display: -webkit-box;

  -webkit-line-clamp: 4;

  -webkit-box-orient: vertical;

  overflow: hidden;
}


/* =========================================================
   FOOTER
========================================================= */

.card-footer {
  display: flex;

  align-items: center;

  gap: 0.65rem;

  flex-wrap: wrap;

  margin-top: auto;

  padding-top: 1rem;
}


.read-more-btn {
  padding: 0;

  border: none;

  background: none;

  color: #cc0000;

  font-weight: 700;

  cursor: pointer;
}


.read-more-btn:hover {
  text-decoration:
    underline;
}


.btn-arrow {
  margin-left:
    0.2rem;
}


.btn-pdf-outline,
.btn-pdf {
  display: inline-flex;

  align-items: center;

  gap: 0.2rem;

  padding:
    0.45rem
    0.75rem;

  border-radius: 6px;

  font-size: 0.82rem;

  text-decoration: none;

  cursor: pointer;
}


.btn-pdf-outline {
  border:
    1px solid
    #cc0000;

  background:
    white;

  color:
    #cc0000;
}


.btn-pdf-outline:hover {
  background:
    #fbeaea;
}


.btn-pdf {
  border:
    1px solid
    #cc0000;

  background:
    #cc0000;

  color:
    white;
}


.btn-pdf:hover {
  background:
    #a30000;

  color:
    white;
}


/* =========================================================
   EXPANDIDO
========================================================= */

.card-expanded {
  margin-top:
    1rem;

  padding-top:
    1rem;

  border-top:
    1px solid
    #eee;

  animation:
    slideDown
    0.25s ease;
}


@keyframes slideDown {

  from {
    opacity: 0;

    transform:
      translateY(-8px);
  }

  to {
    opacity: 1;

    transform:
      translateY(0);
  }

}


.contenido-texto {
  margin: 0;

  color: #334155;

  line-height: 1.8;
}


/* =========================================================
   ADJUNTOS
========================================================= */

.attachments {
  margin-top:
    1.4rem;

  padding-top:
    1rem;

  border-top:
    1px solid
    #eee;
}


.attachments h4 {
  margin:
    0 0
    0.7rem;

  color:
    #333;

  font-size:
    0.95rem;
}


.attachment-list {
  margin: 0;

  padding: 0;

  list-style: none;
}


.attachment-list li {
  padding:
    0.45rem
    0;

  border-bottom:
    1px solid
    #f1f5f9;
}


.attachment-list li:last-child {
  border-bottom: none;
}


.attachment-link {
  color:
    #cc0000;

  font-size:
    0.88rem;

  font-weight:
    600;

  text-decoration:
    none;
}


.attachment-link:hover {
  text-decoration:
    underline;
}


.file-size {
  margin-left:
    0.4rem;

  color:
    #94a3b8;

  font-size:
    0.78rem;
}


/* =========================================================
   ESTADOS
========================================================= */

.loading-state,
.error-state,
.empty-state {
  max-width:
    850px;

  margin:
    2rem auto;

  padding:
    4rem 2rem;

  text-align:
    center;

  background:
    rgba(
      255,
      255,
      255,
      0.92
    );

  border-radius:
    14px;

  box-shadow:
    0 4px 15px
    rgba(
      0,
      0,
      0,
      0.06
    );
}


.spinner {
  width:
    42px;

  height:
    42px;

  margin:
    0 auto 1rem;

  border:
    4px solid
    #eee;

  border-top-color:
    #cc0000;

  border-radius:
    50%;

  animation:
    spin
    1s linear infinite;
}


@keyframes spin {

  to {
    transform:
      rotate(360deg);
  }

}


.error-icon,
.empty-icon {
  margin-bottom:
    1rem;

  color:
    #aaa;

  font-size:
    3.5rem;
}


.error-state h3,
.empty-title {
  margin-bottom:
    0.5rem;

  color:
    #444;
}


.error-state p,
.empty-description {
  margin-bottom:
    1.3rem;

  color:
    #777;
}


.btn-primary {
  display:
    inline-flex;

  align-items:
    center;

  padding:
    0.7rem
    1.2rem;

  border:
    none;

  border-radius:
    7px;

  background:
    #cc0000;

  color:
    white;

  font-weight:
    600;

  cursor:
    pointer;
}


.btn-primary:hover {
  background:
    #a30000;
}


/* =========================================================
   PAGINACIÓN
========================================================= */

.pagination {
  max-width:
    1200px;

  margin:
    2rem auto 0;

  padding:
    1rem;

  background:
    rgba(
      255,
      255,
      255,
      0.8
    );

  border-radius:
    12px;

  display:
    flex;

  flex-direction:
    column;

  align-items:
    center;

  gap:
    0.8rem;
}


.info-text {
  color:
    #666;

  font-size:
    0.88rem;
}


.pagination-controls {
  display:
    flex;

  align-items:
    center;

  gap:
    0.3rem;

  flex-wrap:
    wrap;

  justify-content:
    center;
}


.page-numbers {
  display:
    flex;

  gap:
    0.2rem;
}


.page-btn,
.page-num {
  min-width:
    36px;

  height:
    36px;

  border:
    1px solid
    #ddd;

  border-radius:
    6px;

  background:
    white;

  cursor:
    pointer;

  font-weight:
    600;
}


.page-btn:hover:not(:disabled),
.page-num:hover:not(:disabled) {
  border-color:
    #cc0000;

  color:
    #cc0000;
}


.page-num.active {
  border-color:
    #cc0000;

  background:
    #cc0000;

  color:
    white;
}


.page-btn:disabled,
.page-num:disabled {
  opacity:
    0.4;

  cursor:
    not-allowed;
}


.page-num.dots {
  cursor:
    default;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

  .communication-card {
    flex-direction:
      column;
  }


  .card-image-wrapper {
    flex:
      0 0 220px;

    width:
      100%;

    min-height:
      220px;
  }


  .card-image,
  .card-video {
    min-height:
      220px;
  }

}


@media (max-width: 768px) {

  .official-communications {
    padding:
      1rem;
  }


  .header-title {
    font-size:
      1.9rem;
  }


  .filters-container {
    flex-direction:
      column;

    align-items:
      stretch;
  }


  .search-wrapper,
  .filter-select,
  .results-count {
    width:
      100%;
  }


  .results-count {
    justify-content:
      flex-start;
  }


  .card-image-wrapper {
    min-height:
      180px;
  }


  .card-image,
  .card-video {
    min-height:
      180px;
  }


  .card-title {
    font-size:
      1.1rem;
  }

}

</style>
