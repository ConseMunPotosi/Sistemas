<template>
  <div class="container py-5">
    <div class="noticias-header">
      <h2 class="noticias-titulo">Noticias</h2>
      <p class="noticias-subtitulo">Este espacio propio del Concejo Municipal de Potosí es digital dinámico y confiable, cuyo objetivo central es mantener a la ciudadanía informada sobre la actividad legislativa y de fiscalización que se desarrolla en el municipio.</p>
    </div>

    <div class="row g-4">
      <div class="col-12 col-lg-6" v-for="noticia in noticiasOrdenadas" :key="noticia.id_noticia">
        <div class="card h-100 border-0 shadow-sm">
          <!-- Contenedor de imagen optimizado -->
          <div class="card-img-wrapper">
            <img
              :src="noticia.imagen"
              class="card-img-top"
              :alt="noticia.titulo"
              loading="lazy"
            >
            <!-- Badge de imágenes múltiples -->
            <span class="imagenes-badge" v-if="noticia.imagenes && noticia.imagenes.length > 1">
              <i class="fas fa-images me-1"></i>{{ noticia.imagenes.length }}
            </span>
          </div>

          <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
              <span class="badge">{{ noticia.categoria }}</span>
              <small class="text-muted">{{ noticia.fecha }}</small>
            </div>
            <h5 class="card-title fw-bold">{{ noticia.titulo }}</h5>
            <p class="card-text text-muted">{{ noticia.resumen }}</p>
          </div>
          <div class="card-footer bg-transparent border-0">
            <button class="btn btn-danger btn-sm" @click="abrirModal(noticia)">
              Leer más <i class="fas fa-arrow-right ms-1"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ========== MODAL CON GALERÍA DE IMÁGENES ========== -->
    <div
      class="modal-overlay"
      v-if="modalVisible"
      @click.self="cerrarModal"
    >
      <div class="modal-contenedor">
        <div class="modal-header-custom">
          <h5 class="modal-titulo">
            <i class="fas fa-newspaper me-2"></i>{{ noticiaSeleccionada.titulo }}
          </h5>
          <button type="button" class="btn-close-custom" @click="cerrarModal">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="modal-body-custom">
          <!-- ===== GALERÍA DE IMÁGENES ===== -->
          <div class="galeria-container">
            <!-- Imagen principal -->
            <div class="modal-img-wrapper">
              <img
                :src="imagenActual"
                class="img-fluid"
                :alt="noticiaSeleccionada.titulo"
              >

              <!-- Contador de imágenes -->
              <div class="contador-imagenes" v-if="noticiaSeleccionada.imagenes && noticiaSeleccionada.imagenes.length > 1">
                <span>{{ indiceActual + 1 }} / {{ noticiaSeleccionada.imagenes.length }}</span>
              </div>

              <!-- Botones de navegación -->
              <button
                class="btn-nav btn-nav-izquierda"
                v-if="noticiaSeleccionada.imagenes && noticiaSeleccionada.imagenes.length > 1"
                @click="cambiarImagenNavegacion(-1)"
              >
                <i class="fas fa-chevron-left"></i>
              </button>
              <button
                class="btn-nav btn-nav-derecha"
                v-if="noticiaSeleccionada.imagenes && noticiaSeleccionada.imagenes.length > 1"
                @click="cambiarImagenNavegacion(1)"
              >
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>

            <!-- Miniaturas -->
            <div class="miniaturas-container" v-if="noticiaSeleccionada.imagenes && noticiaSeleccionada.imagenes.length > 1">
              <div
                v-for="(img, index) in noticiaSeleccionada.imagenes"
                :key="index"
                class="miniatura-item"
                :class="{ activa: imagenActual === img }"
                @click="cambiarImagen(img)"
              >
                <img
                  :src="img"
                  :alt="`Imagen ${index + 1}`"
                >
              </div>
            </div>
          </div>

          <!-- Metadatos -->
          <div class="d-flex flex-wrap gap-3 mb-3 mt-3">
            <span class="badge-custom" style="background-color: #cc0000;">
              <i class="fas fa-tag me-1"></i>{{ noticiaSeleccionada.categoria }}
            </span>
            <span class="badge-custom bg-secondary">
              <i class="fas fa-calendar-alt me-1"></i>{{ noticiaSeleccionada.fecha }}
            </span>
          </div>

          <!-- Contenido completo -->
          <div class="contenido-noticia">
            <h6 class="fw-bold mb-3" style="color: #cc0000;">Descripción completa</h6>
            <p class="text-justify" style="line-height: 1.8; font-size: 1.05rem;">
              {{ noticiaSeleccionada.contenido }}
            </p>
          </div>
        </div>

        <div class="modal-footer-custom">
          <button type="button" class="btn btn-secondary" @click="cerrarModal">
            <i class="fas fa-times me-1"></i>Cerrar
          </button>
          <button type="button" class="btn btn-danger" @click="compartirNoticia">
            <i class="fas fa-share-alt me-1"></i>Compartir
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Noticias',
  data() {
    return {
      modalVisible: false,
      imagenActual: '',
      indiceActual: 0,
      noticiaSeleccionada: {
        id_noticia: null,
        titulo: '',
        resumen: '',
        contenido: '',
        fecha: '',
        categoria: '',
        imagen: '',
        imagenes: []
      },
      noticias: [
        {
          id_noticia: 1,
          titulo: 'REUNIÓN DE COORDINACIÓN SOBRE LA FESTIVIDAD DE CHUTILLOS',
          resumen: 'Con el objetivo de optimizar la planificación, promoción y desarrollo de la Festividad de Chutillos, la concejal municipal Lic. Jacqueline Lourdes Gutiérrez Carrasco, presidenta de ...',
          contenido: 'Con el objetivo de optimizar la planificación, promoción y desarrollo de la Festividad de Chutillos, la concejal municipal Lic. Jacqueline Lourdes Gutiérrez Carrasco, presidenta de la Comisión de Turismo, Cultura y Preservación de Áreas Históricas junto al personal del presidente de la comisión Jurídica y Desarrollo Institucional que preside el Ing. Guido Armando Cruz Mora, participaron en la reunión estratégica de socialización y coordinación interinstitucional organizada junto al Órgano Ejecutivo Municipal y sus distintas secretarías. El encuentro contó con la participación activa de representantes de la Federación de Empresarios Privados de Potosí FEPP, la Cámara de Mujeres Empresarias de Bolivia CAMEBOL filial Potosí, la Cámara Hotelera, así como de diversas agencias y operadoras de turismo. El propósito central de la reunión, fue unificar esfuerzos sectoriales para garantizar una organización eficiente y de alto impacto para esta festividad, la cual ostenta el título de Patrimonio Cultural Inmaterial de la Humanidad, declarada por la UNESCO. Durante su intervención, la Lic. Jaqueline Lourdes Gutiérrez Carrasco, destacó que la articulación entre el turismo, la cultura y la economía regional es clave para consolidar un trabajo responsable y ordenado. Asimismo, enfatizó el compromiso de la comisión para realzar una de las expresiones culturales más representativas del municipio potosino, Chutillos.',
          fecha: '10/07/2026',
          categoria: 'Turismo y Cultura',
          imagen: '/images/noticias/chutillos.jpg',
          imagenes: [
            '/images/noticias/chutillos.jpg',
            '/images/noticias/chutillos2.jpg',
            '/images/noticias/chutillos3.jpg',
            '/images/noticias/chutillos4.jpg'
          ]
        },
        {
          id_noticia: 2,
          titulo: 'CONCEJAL ASIGNADO AL DISTRITO 20 REALIZA GESTIONES CON EL EJECUTIVO Y EL CONTROL SOCIAL',
          resumen: 'Con el objetivo de gestionar la atención de las demandas del distrito 20, el concejal asignado a este importante sector de la ciudad, Ing. Guido Armando Cruz Mora, sostuvo...',
          contenido: 'Con el objetivo de gestionar la atención de las demandas del distrito 20, el concejal asignado a este importante sector de la ciudad, Ing. Guido Armando Cruz Mora, sostuvo reunión con la participación del Secretario General y el responsable de salud del municipio, como representantes vecinales. En esta importante reunión, se conoció y evaluó el tema de predios destinados a la construcción de la nueva infraestructura de la Unidad Educativa Evo Morales, ubicada en la zona de Rollo Kucho, asimismo la necesidad de proyectar la edificación del nuevo Centro de Salud Ambulatorio en Cantumarca. Temas que serán atendidos en el marco de las competencias.',
          fecha: '10/07/2026',
          categoria: 'Gestión',
          imagen: '/images/noticias/gestionD-20.jpg',
          imagenes: [
            '/images/noticias/gestionD-20-1.jpg',
            '/images/noticias/gestionD-20-2.jpg',
            '/images/noticias/gestionD-20-3.jpg'
          ]
        }
      ]
    };
  },
  computed: {
    noticiasOrdenadas() {
      return [...this.noticias].sort((a, b) => {
        const fechaA = this.convertirFecha(a.fecha);
        const fechaB = this.convertirFecha(b.fecha);
        return fechaB - fechaA;
      });
    }
  },
  methods: {
    abrirModal(noticia) {
      this.noticiaSeleccionada = { ...noticia };
      if (noticia.imagenes && noticia.imagenes.length > 0) {
        this.imagenActual = noticia.imagenes[0];
        this.indiceActual = 0;
      } else {
        this.imagenActual = noticia.imagen;
        this.indiceActual = 0;
        if (!noticia.imagenes) {
          this.noticiaSeleccionada.imagenes = [noticia.imagen];
        }
      }
      this.modalVisible = true;
      document.body.style.overflow = 'hidden';
    },
    cerrarModal() {
      this.modalVisible = false;
      document.body.style.overflow = '';
    },
    cambiarImagen(img) {
      this.imagenActual = img;
      this.indiceActual = this.noticiaSeleccionada.imagenes.indexOf(img);
    },
    cambiarImagenNavegacion(direccion) {
      if (!this.noticiaSeleccionada.imagenes || this.noticiaSeleccionada.imagenes.length <= 1) return;
      const total = this.noticiaSeleccionada.imagenes.length;
      let nuevoIndice = this.indiceActual + direccion;
      if (nuevoIndice < 0) nuevoIndice = total - 1;
      if (nuevoIndice >= total) nuevoIndice = 0;
      this.indiceActual = nuevoIndice;
      this.imagenActual = this.noticiaSeleccionada.imagenes[nuevoIndice];
    },
    compartirNoticia() {
      const url = window.location.href;
      const texto = `📰 ${this.noticiaSeleccionada.titulo}\n\n${this.noticiaSeleccionada.resumen}\n\nLeer más en: ${url}`;
      if (navigator.share) {
        navigator.share({
          title: this.noticiaSeleccionada.titulo,
          text: this.noticiaSeleccionada.resumen,
          url: window.location.href
        }).catch(err => {
          console.log('Error al compartir:', err);
        });
      } else {
        navigator.clipboard.writeText(texto).then(() => {
          alert('¡Enlace copiado al portapapeles! Compártelo con tus amigos.');
        }).catch(() => {
          alert('Comparte esta noticia: ' + texto);
        });
      }
    },
    convertirFecha(fechaStr) {
      const partes = fechaStr.split('/');
      return new Date(`${partes[1]}/${partes[0]}/${partes[2]}`);
    }
  },
  beforeUnmount() {
    document.body.style.overflow = '';
  }
};
</script>

<style scoped>
/* ========== CONTENEDOR PRINCIPAL ========== */
.container {
  margin: 0 auto;
  padding: 1rem;
  min-height: 100vh;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  width: 100%;
  max-width: 100%;
  display: block;
}

/* ========== HEADER ========== */
.noticias-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 1.5rem;
}

.noticias-titulo {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  letter-spacing: 1px;
  color: #cc0000;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
}

.noticias-subtitulo {
  font-size: 1.1rem;
  color: #000000 !important;
  margin: 0;
  max-width: 90%;
  margin-left: auto;
  margin-right: auto;
  text-shadow: 1px 1px 4px rgba(255, 255, 255, 0.8);
  text-align: justify;
  text-justify: inter-word;
}

/* ========== WRAPPER DE IMAGEN - IMAGEN COMPLETA ========== */
.card-img-wrapper {
  width: 100%;
  height: 220px; /* Altura fija para uniformidad */
  overflow: hidden;
  background-color: #f5f5f5;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card-img-wrapper .card-img-top {
  width: 100%;
  height: 100%;
  object-fit: contain; /* Muestra la imagen completa */
  object-position: center;
  background-color: #f5f5f5;
  transition: transform 0.5s ease;
  padding: 4px; /* Pequeño padding para no tocar bordes */
}

.card:hover .card-img-wrapper .card-img-top {
  transform: scale(1.02);
}

/* ========== BADGE DE MÚLTIPLES IMÁGENES ========== */
.imagenes-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(204, 0, 0, 0.9);
  color: white;
  padding: 0.25rem 0.6rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  z-index: 2;
  display: flex;
  align-items: center;
  gap: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* ========== TARJETAS ========== */
.card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 12px;
  overflow: hidden;
  height: 100%;
  background: white;
}

.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
}

.card-body {
  padding: 1.25rem;
}

.card-title {
  font-size: 1.1rem;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  min-height: 3rem;
}

.card-text {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  font-size: 0.95rem;
}

.card-footer {
  padding: 0.75rem 1.25rem;
}

/* ========== BADGE ========== */
.badge {
  background-color: #cc0000;
  color: white;
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  font-weight: 500;
}

/* ========== BOTÓN ROJO ========== */
.btn-danger {
  background-color: #cc0000;
  border-color: #cc0000;
  color: white;
  transition: all 0.3s ease;
  padding: 0.4rem 1.2rem;
  border-radius: 25px;
  font-weight: 500;
}

.btn-danger:hover {
  background-color: #a80000;
  border-color: #a80000;
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(204, 0, 0, 0.3);
}

.btn-danger:active {
  background-color: #8a0000;
  border-color: #8a0000;
  transform: scale(0.95);
}

.btn-secondary {
  background-color: #6c757d;
  border-color: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background-color: #5a6268;
  border-color: #545b62;
}

/* ========== MODAL ========== */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(5px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  animation: fadeIn 0.3s ease;
  padding: 1rem;
}

.modal-contenedor {
  background: white;
  border-radius: 16px;
  max-width: 800px;
  width: 100%;
  max-height: 90vh;
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
  background-color: #cc0000;
  color: white;
  flex-shrink: 0;
}

.modal-titulo {
  margin: 0;
  font-size: 1.15rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding-right: 1rem;
  line-height: 1.3;
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
  display: flex;
  align-items: center;
  justify-content: center;
  line-height: 1;
  flex-shrink: 0;
}

.btn-close-custom:hover {
  background-color: rgba(255, 255, 255, 0.2);
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

.modal-body-custom::-webkit-scrollbar-thumb:hover {
  background: #a80000;
}

/* ========== GALERÍA DE IMÁGENES ========== */
.galeria-container {
  position: relative;
  width: 100%;
}

/* ========== IMAGEN PRINCIPAL ========== */
.modal-img-wrapper {
  width: 100%;
  max-height: 400px;
  overflow: hidden;
  border-radius: 8px;
  background-color: #f5f5f5;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-img-wrapper img {
  width: 100%;
  height: auto;
  max-height: 400px;
  object-fit: contain;
  object-position: center;
  background-color: #f5f5f5;
}

/* ========== CONTADOR DE IMÁGENES ========== */
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

/* ========== BOTONES DE NAVEGACIÓN ========== */
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
  font-size: 1.2rem;
}

.btn-nav:hover {
  background: rgba(0, 0, 0, 0.8);
  transform: translateY(-50%) scale(1.1);
}

.btn-nav-izquierda {
  left: 10px;
}

.btn-nav-derecha {
  right: 10px;
}

/* ========== MINIATURAS ========== */
.miniaturas-container {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.75rem;
  overflow-x: auto;
  padding: 0.25rem 0;
  flex-wrap: nowrap;
  scroll-behavior: smooth;
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
  background-color: #f5f5f5;
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
  object-position: center;
}

/* ========== BADGE CUSTOM ========== */
.badge-custom {
  display: inline-block;
  padding: 0.4rem 1rem;
  border-radius: 20px;
  color: white;
  font-size: 0.85rem;
  font-weight: 500;
}

.badge-custom.bg-secondary {
  background-color: #6c757d;
}

/* ========== CONTENIDO ========== */
.contenido-noticia {
  text-align: justify;
}

.text-justify {
  text-align: justify;
  text-justify: inter-word;
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
@media (max-width: 991px) {
  .col-lg-6 {
    flex: 0 0 100%;
    max-width: 100%;
  }
}

@media (max-width: 768px) {
  .noticias-titulo {
    font-size: 2rem;
  }

  .noticias-subtitulo {
    font-size: 0.95rem;
    max-width: 100%;
  }

  .card-img-wrapper {
    height: 180px !important;
  }

  .btn-danger {
    width: 100%;
    justify-content: center;
  }

  .modal-contenedor {
    max-height: 95vh;
    margin: 0.5rem;
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
  }

  .miniatura-item {
    flex: 0 0 60px;
    height: 45px;
  }

  .btn-nav {
    width: 30px;
    height: 30px;
    font-size: 0.9rem;
  }
}

@media (max-width: 576px) {
  .card-img-wrapper {
    height: 150px !important;
  }
}
</style>
