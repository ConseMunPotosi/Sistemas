<template>
  <div class="page-container">
    <!-- Contenido izquierdo (superpuesto) -->
    <div class="left-content">
      <img
        src="../../../public/images/concejo.png"
        alt="Concejo"
        class="desktop-image"
      />
      <img
        src="../../../public/images/concejo-mobile.png"
        alt="Concejo Mobile"
        class="mobile-image"
      />
    </div>

    <!-- Contenido derecho (detrás) -->
    <div class="right-content">
      <div class="top">
        <!-- Carrusel -->
        <div class="carousel-wrapper">
          <div class="carousel-container">
            <div
              class="carousel-track"
              :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
            >
              <div
                v-for="(slide, index) in slides"
                :key="index"
                class="carousel-slide"
              >
                <!-- Bloque Izquierdo: Imagen -->
                <div class="slide-image-wrapper">
                  <img :src="slide.image" :alt="slide.title" class="slide-img" />
                </div>

                <!-- Bloque Derecho: Texto (Fondo blanco) -->
                <div class="slide-content">
                  <div class="text-container">
                    <h2 class="slide-title">{{ slide.title }}</h2>
                    <p class="slide-description">{{ slide.description }}</p>
                    <p class="slide-date">📅 {{ slide.date }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- ===== CONTROLES UNIFICADOS ===== -->
            <div class="carousel-controls-group">

              <!-- Botones -->
              <div class="carousel-buttons">
                <button class="carousel-btn" @click="prevSlide">
                  <svg viewBox="0 0 24 24" width="20" height="20">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" fill="white"/>
                  </svg>
                </button>
                <button class="carousel-btn" @click="nextSlide">
                  <svg viewBox="0 0 24 24" width="20" height="20">
                    <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z" fill="white"/>
                  </svg>
                </button>
              </div>

              <!-- Indicadores (Puntos) -->
              <div class="carousel-dots">
                <span
                  v-for="(slide, index) in slides"
                  :key="index"
                  class="dot"
                  :class="{ active: currentSlide === index }"
                  @click="goToSlide(index)"
                ></span>
              </div>

            </div>
            <!-- ===== FIN CONTROLES UNIFICADOS ===== -->

          </div>
        </div>
      </div>
      <div class="bottom">
        <div class="bottom-content">
          <div class="imagenLogo">
            <img src="../../../public/images/Logo_negativo.png" alt="Logo Concejo Municipal" />
          </div>
          <div class="texto">
            <h1 class="titulo">Concejo Municipal de Potosí</h1>
            <p class="descripcion">
                El Concejo Municipal de Potosí constituye el Órgano Legislativo,
                Deliberativo y Fiscalizador del Gobierno Autónomo Municipal de
                Potosí. Está conformado por once (11) concejalas y concejales,
                elegidos mediante sufragio universal, directo y secreto, para
                un periodo de gestión de cinco años. Actualmente, la institución
                se encuentra en el ejercicio del periodo constitucional 2026–2031.
            </p>
            <div class="hero-botones">
                <button class="btn-primario" @click="abrirModal">
                Ver más
                <i class="bi bi-arrow-right"></i>
                </button>
            </div>
          </div>
        </div>
        <div class="informacion">
          <div class="inf_titulo">Información Importante</div>
          <div class="bienvenida-stats">
          <div class="stat">
            <i class="bi bi-people-fill"></i>
            <div class="stat-info">
              <span class="stat-number">11</span>
              <span class="stat-label">Concejales</span>
            </div>
          </div>
          <div class="stat">
            <i class="bi bi-diagram-3"></i>
            <div class="stat-info">
              <span class="stat-number">10</span>
              <span class="stat-label">Comisiones</span>
            </div>
          </div>
          <div class="stat">
            <i class="bi bi-geo-alt"></i>
            <div class="stat-info">
              <span class="stat-number">21</span>
              <span class="stat-label">Distritos</span>
            </div>
          </div>
          <div class="stat">
            <i class="bi bi-flag"></i>
            <div class="stat-info">
              <span class="stat-number">233</span>
              <span class="stat-label">Juntas Vecinales</span>
            </div>
          </div>
          <div class="stat">
            <i class="bi bi-person-standing"></i>
            <div class="stat-info">
              <span class="stat-number">218.702</span>
              <span class="stat-label">Ciudadanos</span>
              <span class="stat-fuente">Fuente: I.N.E.</span>
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div v-if="modalVisible" class="modal-overlay" @click.self="cerrarModal">
      <div class="modal-container modal-pdf">
        <button class="modal-close" @click="cerrarModal">
         <i class="bi bi-x-lg"></i>
        </button>
        <div class="modal-content">
          <h2 class="modal-titulo">
           Concejo Municipal de Potosí - Ley Municipal 067/2015
          </h2>
          <div class="modal-cuerpo pdf-container">
            <iframe src="/pdf/LeyMunicipal_067-2015.pdf" type="application/pdf"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import 'bootstrap-icons/font/bootstrap-icons.css'
import { useNoticiasStore } from '@/stores/noticias.js'
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'

export default {
  name: 'RightAlignedCarousel',
  setup() {
    const store = useNoticiasStore()
    const currentSlide = ref(0)
    const modalVisible = ref(false)
    const autoplayInterval = ref(null)

    const slides = computed(() => {
      const todas = store.noticias || []
      // Cambia el 1 por el ID real de tu categoría "Noticias"
      const noticiasFiltradas = todas.filter(n => n.id_categoria === 1)

      const ordenadas = [...noticiasFiltradas].sort((a, b) =>
        new Date(b.fecha_creacion) - new Date(a.fecha_creacion)
      )

      const ultimas5 = ordenadas.slice(0, 5)

      return ultimas5.map(noticia => ({
        title: noticia.titulo,
        description: noticia.resumen || '',
        date: formatDate(noticia.fecha_creacion),
        image: getMainImage(noticia)
      }))
    })

    const formatDate = (dateString) => {
      if (!dateString) return 'Fecha no disponible'
      try {
        const date = new Date(dateString)
        if (isNaN(date.getTime())) return 'Fecha inválida'
        const day = String(date.getDate()).padStart(2, '0')
        const month = String(date.getMonth() + 1).padStart(2, '0')
        const year = date.getFullYear()
        return `${day}/${month}/${year}`
      } catch {
        return dateString
      }
    }

    const getMainImage = (noticia) => {
      if (noticia.archivos && noticia.archivos.length > 0) {
        const img = noticia.archivos.find(a => a.tipo_mime?.startsWith('image/'))
        if (img) return getFileUrl(img.ruta_archivo)
      }
      return 'https://picsum.photos/seed/default/1200/800'
    }

    const getFileUrl = (ruta) => {
      if (!ruta) return '#'
      return `/${ruta}`
    }

    const nextSlide = () => {
      if (slides.value.length > 0) {
        currentSlide.value = (currentSlide.value + 1) % slides.value.length
      }
    }

    const prevSlide = () => {
      if (slides.value.length > 0) {
        currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length
      }
    }

    const goToSlide = (index) => {
      currentSlide.value = index
    }

    const startAutoplay = () => {
      if (autoplayInterval.value) clearInterval(autoplayInterval.value)
      autoplayInterval.value = setInterval(() => {
        if (slides.value.length > 0) {
          nextSlide()
        }
      }, 4000)
    }

    const stopAutoplay = () => {
      if (autoplayInterval.value) {
        clearInterval(autoplayInterval.value)
        autoplayInterval.value = null
      }
    }

    const abrirModal = () => {
      modalVisible.value = true
      document.body.style.overflow = 'hidden'
    }

    const cerrarModal = () => {
      modalVisible.value = false
      document.body.style.overflow = ''
    }

    onMounted(async () => {
      await store.fetchNoticias()
      startAutoplay()
    })

    onBeforeUnmount(() => {
      stopAutoplay()
    })

    return {
      store,
      currentSlide,
      modalVisible,
      slides,
      nextSlide,
      prevSlide,
      goToSlide,
      abrirModal,
      cerrarModal
    }
  }
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.page-container {
  position: relative;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  background-image: url('/images/fondo.png');
}

/* ===== CONTENIDO IZQUIERDO ===== */
.left-content {
  position: absolute;
  top: 0;
  left: 0;
  width: 50%;
  height: 100%;
  z-index: 2;
  overflow: hidden;
}

.left-content img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* Imagen mobile oculta por defecto */
.mobile-image {
  display: none;
}

/* Imagen desktop visible por defecto */
.desktop-image {
  display: block;
}

/* ===== CONTENIDO DERECHO ===== */
.right-content {
  position: absolute;
  top: 0;
  left: 40%;
  width: 60%;
  height: 100%;
  z-index: 1;
  display: flex;
  flex-direction: column;
}

.top {
  height: 30%;
  position: relative;
  overflow: hidden;
}

.bottom {
  height: 70%;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 0.5rem;
}

.bottom-content {
  text-align: center;
  max-width: 90%;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}

.imagenLogo {
  max-width: 300px;
  width: 100%;
  animation: entrarArriba 2s ease-out forwards;
  display: flex;
  justify-content: center;
  margin-bottom: 1rem;
}

.imagenLogo img {
  width: 100%;
  height: auto;
}

/* Texto */
.texto {
  max-width: 90%;
  animation: entrarDerecha 1.8s ease-out forwards;
  margin-left: 10%;
}

.titulo {
  font-size: 2rem;
  font-weight: 800;
  color: #cc0000;
  margin-bottom: 1rem;
  line-height: 1.2;
}

.descripcion {
  font-size: 1rem;
  color: #2c3e50;
  margin-bottom: 0.5rem;
  line-height: 1.6;
  background: rgba(255, 255, 255, 0.8);
  padding: 0.5rem;
  border-radius: 8px;
  text-align: justify;
}

.hero-botones {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  margin-left: 2%;
}

.btn-primario {
  background: #cc0000;
  color: white;
  padding: 0.8rem 2rem;
  border: none;
  border-radius: 50px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.btn-primario:hover {
  background: #990000;
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(204, 0, 0, 0.3);
}

.btn-primario:hover i {
  transform: translateX(5px);
}

.btn-primario i {
  transition: transform 0.3s ease;
}

/* ===== CARRUSEL ===== */
.carousel-wrapper {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
}

.carousel-container {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
}

.carousel-track {
  display: flex;
  width: 100%;
  height: 100%;
  transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  will-change: transform;
}

.carousel-slide {
  flex: 0 0 100%;
  height: 100%;
  position: relative;
  display: flex;
  flex-direction: row;
  background: white;
}

/* 📸 Bloque Izquierdo: Imagen (30%) */
.slide-image-wrapper {
  width: 30%;
  height: 100%;
  overflow: hidden;
}

.slide-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* 📝 Bloque Derecho: Texto (70%) */
.slide-content {
  width: 70%;
  height: 100%;
  display: flex;
  align-items: flex-start; /* Corregido: flex-start en lugar de left */
  justify-content: center;
  padding: 2rem;
  background: white;
}

.text-container {
  max-width: 100%;
  text-align: left;
}

.slide-title {
  font-size: 2.2rem;
  font-weight: 800;
  color: #1a1a2e;
  margin-bottom: 1rem;
  line-height: 1.2;
}

.slide-description {
  font-size: 1.1rem;
  color: #4a5568;
  margin-bottom: 1.5rem;
  line-height: 1.6;
  text-align: justify;
}

.slide-date {
  font-size: 1rem;
  font-weight: 500;
  color: #cc0000;
}

/* ===== CONTROLES UNIFICADOS (DERECHA, ABAJO) ===== */
.carousel-controls-group {
  position: absolute;
  bottom: 20px;
  right: 20px;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 20px;
  z-index: 10;
}

.carousel-buttons {
  display: flex;
  gap: 8px;
}

.carousel-btn {
  background: rgba(0, 0, 0, 0.5);
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  backdrop-filter: blur(4px);
}

.carousel-btn:hover {
  background: rgba(0, 0, 0, 0.7);
  transform: scale(1.1);
  border-color: white;
}

.carousel-btn svg {
  width: 20px;
  height: 20px;
}

.carousel-dots {
  display: flex;
  gap: 8px;
  align-items: center;
}

.dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.2);
  cursor: pointer;
  transition: all 0.3s ease;
}

.dot:hover {
  background: rgba(0, 0, 0, 0.4);
  transform: scale(1.2);
}

.dot.active {
  background: #cc0000;
  transform: scale(1.25);
  box-shadow: 0 0 12px rgba(204, 0, 0, 0.3);
}

/* ========== MODAL ========== */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3);
  backdrop-filter: blur(5px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease;
}

.modal-container {
  position: relative;
  max-width: 800px;
  width: 90%;
  max-height: 65vh;
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  border-radius: 20px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
  animation: entrarAbajo 0.4s ease;
  overflow: hidden;
}

.modal-close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  width: 35px;
  height: 35px;
  border-radius: 50%;
  background: rgba(204, 0, 0, 0.1);
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  z-index: 10;
}

.modal-close i {
  font-size: 1.2rem;
  color: #cc0000;
}

.modal-close:hover {
  background: #cc0000;
  transform: rotate(90deg);
}

.modal-close:hover i {
  color: white;
}

.modal-content {
  padding: 2rem;
}

.modal-titulo {
  font-size: 1.8rem;
  font-weight: 800;
  color: #cc0000;
  margin-bottom: 1.5rem;
  text-align: center;
  border-bottom: 2px solid #cc0000;
  padding-bottom: 0.8rem;
}

/* ========== PDF ========== */
.modal-container.modal-pdf {
  width: 95%;
  max-width: 1000px;
  height: 100vh;
  display: flex;
  flex-direction: column;
}

.modal-container.modal-pdf .modal-content {
  height: 100%;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.pdf-container {
  flex: 1;
  padding: 0 !important;
  margin-top: 15px;
  overflow: hidden;
}

.pdf-container iframe {
  width: 100%;
  height: 100%;
  border: none;
  border-radius: 0 0 20px 20px;
}

.informacion{
  max-width: 90%;
  animation: entrarDerecha 1.8s ease-out forwards;
  margin-left: 10%;
  margin-top: 2rem;
}

.inf_titulo{
  font-size: 1.2rem;
  font-weight: 600;
  color: #cc0000;
  margin-bottom: 1rem;
  line-height: 1.2;
}

/* ========== DATOS ========== */
.bienvenida-stats {
  align-items: center;
  margin-top: 2%;
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
  animation: entrarAbajo 2s ease-out forwards;
}

.stat {
  display: flex;
  align-items: center;
  gap: 1rem;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  padding: 1rem 1.5rem;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.stat:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  background: white;
}

.stat i {
  font-size: 2rem;
  color: #cc0000;
}

.stat-info {
  display: flex;
  flex-direction: column;
}

.stat-number {
  font-size: 1.5rem;
  font-weight: 800;
  color: #2c3e50;
}

.stat-label {
  font-size: 0.85rem;
  color: #666;
}
.stat-fuente{
  font-size: 0.75rem;
  color: #c00000;
  font-style: italic;
}

/* ============================================================ */
/* ===== RESPONSIVE: TABLET (1024px) ===== */
/* ============================================================ */
@media (max-width: 1024px) {
  .right-content {
    left: 45%;
    width: 55%;
  }

  .slide-title {
    font-size: 1.8rem;
  }
  .slide-description {
    font-size: 1rem;
  }
  .carousel-btn {
    width: 40px;
    height: 40px;
  }
  .carousel-btn svg {
    width: 20px;
    height: 20px;
  }
}

/* ============================================================ */
/* ===== RESPONSIVE: MOBILE (768px) ===== */
/* ============================================================ */
@media (max-width: 768px) {
  .page-container {
    height: auto;
    min-height: 100vh;
    overflow-y: auto;
  }

  .left-content {
    position: relative;
    width: 100%;
    height: 40vh;
    min-height: 300px;
  }

  .desktop-image { display: none !important; }
  .mobile-image { display: block !important; }

  .right-content {
    position: relative;
    left: 0;
    width: 100%;
    height: auto;
    min-height: 60vh;
  }

  .top {
    height: 40vh;
    min-height: 250px;
  }

  .bottom {
    height: auto;
    min-height: 250px;
    padding: 2rem 1.5rem;
  }

  .slide-image-wrapper { width: 30%; }
  .slide-content { width: 70%; padding: 1rem; }

  .slide-title { font-size: 1.2rem; }
  .slide-description { font-size: 0.9rem; }
  .slide-date { font-size: 0.8rem; }

  .carousel-btn {
    width: 36px;
    height: 36px;
  }
  .carousel-btn svg { width: 18px; height: 18px; }
}

/* ============================================================ */
/* ===== RESPONSIVE: MOBILE PEQUEÑO (480px) ===== */
/* ============================================================ */
@media (max-width: 480px) {
  .left-content { height: 30vh; min-height: 200px; }
  .top { height: 35vh; min-height: 200px; }

  .slide-image-wrapper { width: 30%; }
  .slide-content { width: 70%; padding: 0.5rem; }

  .slide-title { font-size: 1rem; }
  .slide-description { font-size: 0.8rem; }
  .slide-date { font-size: 0.7rem; }

  .carousel-btn { width: 30px; height: 30px; }
  .carousel-btn svg { width: 16px; height: 16px; }
}

/* ============================================================ */
/* ===== RESPONSIVE: LANDSCAPE ===== */
/* ============================================================ */
@media (max-height: 600px) and (orientation: landscape) {
  .left-content { width: 40%; }
  .right-content { left: 40%; width: 60%; }

  .slide-title { font-size: 1.2rem; }
  .slide-description { font-size: 0.9rem; }
}
</style>
