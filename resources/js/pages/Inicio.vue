<template>
  <div class="card-container">
    <div class="responsive-grid">
      <!-- Imagen -->
      <div class="image-wrapper">
        <img src="../../../public/images/concejo.png" class="card-image" />
      </div>

      <!-- Contenido -->
      <div class="info-wrapper">
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
        <!-- Estadísticas -->
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
              <span class="stat-number">218.336</span>
              <span class="stat-label">Ciudadanos</span>
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
  </div>
</template>

<script setup>
import { ref } from 'vue'
import 'bootstrap-icons/font/bootstrap-icons.css'

const modalVisible = ref(false)

const abrirModal = () => {
  modalVisible.value = true
  document.body.style.overflow = 'hidden'
}

const cerrarModal = () => {
  modalVisible.value = false
  document.body.style.overflow = ''
}
</script>

<style scoped>
/* ========== CONTENEDOR PRINCIPAL ========== */
.card-container {
  width: 100%;
  margin: 0 auto;
   overflow-y: auto;
}

/* ========== GRID RESPONSIVO ========== */
.responsive-grid {
  display: grid;
  grid-template-columns: 1fr; /* 1 columna en móviles */
  background: #ffffff;
  overflow: hidden;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
}

/* ========== IMAGEN ========== */
.image-wrapper {
  width: 100%;
  height: 250px;
  overflow: hidden;
}

.card-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  animation: entrarIzquierda 1.5s ease-out forwards;
}

/* ========== CONTENIDO ========== */
.info-wrapper {
  padding: 2rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

/* Logo */
.imagenLogo {
  max-width: 300px;
  margin-bottom: 1.5rem;
  animation: entrarArriba 2s ease-out forwards;
}

.imagenLogo img {
  width: 100%;
  margin-left: 30%;
  height: auto;
  display: block;
}

/* Texto */
.texto {
  max-width: 100%;
  animation: entrarDerecha 1.8s ease-out forwards;
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
  margin-bottom: 1.5rem;
  line-height: 1.6;
  background: rgba(255, 255, 255, 0.8);
  padding: 1rem;
  border-radius: 8px;
}

.hero-botones {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
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

/* ========== TABLETA (2 columnas) ========== */
@media (min-width: 768px) {
  .responsive-grid {
    grid-template-columns: 1fr 1fr;
  }

  .image-wrapper {
    height: 100%;
    min-height: 400px;
  }

  .info-wrapper {
    padding: 2.5rem;
  }

  .imagenLogo {
    max-width: 250px;
  }

  .titulo {
    font-size: 2.5rem;
  }

  .descripcion {
    font-size: 1.1rem;
    padding: 1.5rem;
  }
}

/* ========== ESCRITORIO GRANDE ========== */
@media (min-width: 1024px) {
  .info-wrapper {
    padding: 3rem;
  }

  .imagenLogo {
    max-width: 300px;
  }

  .titulo {
    font-size: 3rem;
  }

  .descripcion {
    font-size: 1.2rem;
  }
}

/* ========== MÓVILES PEQUEÑOS ========== */
@media (max-width: 480px) {
  .card-container {
    padding: 0.5rem;
  }

  .info-wrapper {
    padding: 1.5rem;
  }

  .imagenLogo {
    max-width: 150px;
    margin-left: 0;
    margin: 0 auto 1rem;
  }

  .titulo {
    font-size: 1.5rem;
    text-align: center;
  }

  .descripcion {
    font-size: 0.9rem;
    padding: 0.8rem;
    text-align: justify;
  }

  .hero-botones {
    justify-content: center;
  }

  .btn-primario {
    width: 100%;
    justify-content: center;
    padding: 0.7rem 1.5rem;
  }
}
/* ========== ANIMACIONES ========== */
@keyframes entrarIzquierda {
  from {
    transform: translateX(-200px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}
@keyframes entrarArriba {
  from {
    transform: translateY(-200px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}
@keyframes entrarDerecha {
  from {
    transform: translateX(200px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}
@keyframes entrarAbajo {
  from {
    transform: translateY(200px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
