<template>
  <div class="container py-5">
    <div class="noticias-header">
      <h2 class="noticias-titulo">Noticias</h2>
      <p class="noticias-subtitulo">Este espacio propio del Concejo Municipal de Potosí es digital dinámico y confiable, cuyo objetivo central es mantener a la ciudadanía informada sobre la actividad legislativa y de fiscalización que se desarrolla en el municipio.</p>
    </div>

    <div class="row g-4">
      <div class="col-12 col-lg-6" v-for="noticia in noticiasOrdenadas" :key="noticia.id_noticia">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-img-wrapper">
            <img :src="noticia.imagen" class="card-img-top" :alt="noticia.titulo">
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

    <!-- ========== MODAL PERSONALIZADO ========== -->
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
          <!-- Imagen de la noticia -->
          <div class="modal-img-wrapper">
            <img
              :src="noticiaSeleccionada.imagen"
              class="img-fluid"
              :alt="noticiaSeleccionada.titulo"
            >
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
      noticiaSeleccionada: {
        id_noticia: null,
        titulo: '',
        resumen: '',
        contenido: '',
        fecha: '',
        categoria: '',
        imagen: ''
      },
      noticias: [
        {
          id_noticia: 1,
          titulo: 'REUNIÓN DE COORDINACIÓN SOBRE LA FESTIVIDAD DE CHUTILLOS',
          resumen: 'Con el objetivo de optimizar la planificación, promoción y desarrollo de la Festividad de Chutillos, la concejal municipal Lic. Jacqueline Lourdes Gutiérrez Carrasco, presidenta de ...',
          contenido: 'Con el objetivo de optimizar la planificación, promoción y desarrollo de la Festividad de Chutillos, la concejal municipal Lic. Jacqueline Lourdes Gutiérrez Carrasco, presidenta de la Comisión de Turismo, Cultura y Preservación de Áreas Históricas junto al personal del presidente de la comisión Jurídica y Desarrollo Institucional que preside el Ing. Guido Armando Cruz Mora, participaron en la reunión estratégica de socialización y coordinación interinstitucional organizada junto al Órgano Ejecutivo Municipal y sus distintas secretarías. El encuentro contó con la participación activa de representantes de la Federación de Empresarios Privados de Potosí FEPP, la Cámara de Mujeres Empresarias de Bolivia CAMEBOL filial Potosí, la Cámara Hotelera, así como de diversas agencias y operadoras de turismo. El propósito central de la reunión, fue unificar esfuerzos sectoriales para garantizar una organización eficiente y de alto impacto para esta festividad, la cual ostenta el título de Patrimonio Cultural Inmaterial de la Humanidad, declarada por la UNESCO. Durante su intervención, la Lic. Jaqueline Lourdes Gutiérrez Carrasco, destacó que la articulación entre el turismo, la cultura y la economía regional es clave para consolidar un trabajo responsable y ordenado. Asimismo, enfatizó el compromiso de la comisión para realzar una de las expresiones culturales más representativas del municipio potosino, Chutillos.',
          fecha: '10/07/2026',
          categoria: 'Turismo y Cultura',
          imagen: '/images/noticias/chutillos.jpg'
        },
        {
          id_noticia: 2,
          titulo: 'Nuevo reglamento de tránsito en debate',
          resumen: 'Los concejales iniciaron el debate del nuevo reglamento de tránsito que busca mejorar la movilidad urbana...',
          contenido: 'En la sesión de este martes, los concejales dieron inicio al debate del nuevo reglamento de tránsito que busca actualizar las normas de movilidad urbana en la ciudad. El proyecto incluye medidas para reducir la congestión vehicular, promover el uso de bicicletas y mejorar la seguridad vial. Se espera que el reglamento sea aprobado en las próximas semanas luego de una serie de audiencias públicas con la participación de la ciudadanía.',
          fecha: '18/06/2026',
          categoria: 'Legislación',
          imagen: '/images/noticias/noticia2.jpg'
        },
        {
          id_noticia: 3,
          titulo: 'Proyecto de modernización administrativa',
          resumen: 'Se presentó el proyecto de modernización de los procesos administrativos del municipio...',
          contenido: 'El Concejo Municipal recibió el proyecto de modernización administrativa que propone la implementación de un sistema digital para agilizar los trámites municipales. Este proyecto contempla la digitalización de todos los procesos, la creación de una plataforma en línea para la atención al ciudadano y la capacitación del personal municipal en nuevas tecnologías. Se estima que la implementación completa tomará aproximadamente 6 meses.',
          fecha: '15/06/2026',
          categoria: 'Modernización',
          imagen: '/images/noticias/noticia3.jpg'
        },
        {
          id_noticia: 4,
          titulo: 'Audiencia pública por obras de alcantarillado',
          resumen: 'El Concejo Municipal convoca a audiencia pública para tratar las obras de alcantarillado en zonas periurbanas...',
          contenido: 'El Concejo Municipal convoca a todos los ciudadanos a participar en la audiencia pública que se realizará para tratar el proyecto de obras de alcantarillado en las zonas periurbanas de la ciudad. El proyecto beneficiará a más de 5,000 familias que actualmente no cuentan con este servicio básico. Durante la audiencia se presentarán los planos, el cronograma de ejecución y se responderán las preguntas de los vecinos.',
          fecha: '10/06/2026',
          categoria: 'Obras Públicas',
          imagen: '/images/noticias/noticia4.jpg'
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
      this.modalVisible = true;
      // Evitar scroll en el body cuando el modal está abierto
      document.body.style.overflow = 'hidden';
    },
    cerrarModal() {
      this.modalVisible = false;
      // Restaurar scroll
      document.body.style.overflow = '';
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
    // Asegurar que se restaure el scroll al desmontar
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

/* ========== SUBTÍTULO ========== */
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

/* ========== WRAPPER DE IMAGEN ========== */
.card-img-wrapper {
  width: 50%;
  height: 250px;
  overflow: hidden;
  background-color: #f0f0f0;
  position: relative;
   margin: 0 auto;
}

.card-img-wrapper .card-img-top {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  transition: transform 0.5s ease;
}

.card:hover .card-img-wrapper .card-img-top {
  transform: scale(1.05);
}

/* ========== TARJETAS ========== */
.card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 12px;
  overflow: hidden;
  height: 100%;
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
}

/* ========== BADGE ========== */
.badge {
  background-color: #cc0000;
  color: white;
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
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

/* ========== MODAL PERSONALIZADO ========== */
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
  padding: 1.5rem;
  background-color: #cc0000;
  color: white;
  flex-shrink: 0;
}

.modal-titulo {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding-right: 1rem;
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

/* ========== IMAGEN DEL MODAL ========== */
.modal-img-wrapper {
  width: 100%;
  max-height: 350px;
  overflow: hidden;
  border-radius: 8px;
  background-color: #f0f0f0;
}

.modal-img-wrapper img {
  width: 100%;
  height: 100%;
  max-height: 350px;
  object-fit: contain;
  object-position: center;
  background-color: #f0f0f0;
}

/* ========== BADGE CUSTOM ========== */
.badge-custom {
  display: inline-block;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  color: white;
  font-size: 0.9rem;
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
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
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
    max-height: 200px;
  }

  .modal-img-wrapper img {
    max-height: 200px;
  }

  .modal-footer-custom {
    flex-direction: column;
  }

  .modal-footer-custom .btn {
    width: 100%;
  }
}

/* ========== PREVENIR SCROLL ========== */
body.modal-open {
  overflow: hidden;
}
</style>
