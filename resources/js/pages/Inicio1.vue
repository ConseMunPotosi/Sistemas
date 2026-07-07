<template>
  <div class="page-container">
    <!-- Imagen decorativa -->
    <div class="imagenes-decorativas">
      <img src="/images/home/imagen1.png" class="img img1" alt="Decoración" />
    </div>

    <!-- Contenido principal -->
    <div class="contenido-principal">
      <!-- Logo -->
      <div class="imagenLogo">
        <img src="/images/Logo_negativo.png" class="logo" alt="Logo Concejo Municipal" />
      </div>

      <!-- Texto -->
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
            <span class="stat-number">20</span>
            <span class="stat-label">Distritos</span>
          </div>
        </div>
        <div class="stat">
          <i class="bi bi-flag"></i>
          <div class="stat-info">
            <span class="stat-number">287</span>
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
      <div class="modal-container">
        <button class="modal-close" @click="cerrarModal">
          <i class="bi bi-x-lg"></i>
        </button>
        <div class="modal-content">
          <h2 class="modal-titulo">
            Concejo Municipal de Potosí - Ley Municipal 067/2015
          </h2>
          <div class="pdf-container">
            <iframe src="/pdf/LeyMunicipal_067-2015.pdf" type="application/pdf"></iframe>
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
.page-container {
  position: relative;
  width: 100%;
  min-height: 100vh;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  overflow: hidden;
  display: flex;
  align-items: center;
  padding: 2rem 4rem;
}

/* ========== CONTENIDO PRINCIPAL ========== */
.contenido-principal {
  position: relative;
  z-index: 10;
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
}

/* ========== IMAGEN DECORATIVA ========== */
.imagenes-decorativas {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 1;
  overflow: hidden;
}

.img1 {
  position: absolute;
  width: 35%;
  left: -5%;
  top: 0;
  animation: slideLeft 1.5s ease-out forwards;
  opacity: 0;
}

/* ========== LOGO ========== */
.imagenLogo {
  max-width: 280px;
  margin-bottom: 1rem;
  animation: fadeIn 0.8s ease forwards;
}

.logo {
  width: 100%;
  height: auto;
  display: block;
}

/* ========== TEXTO ========== */
.texto {
  max-width: 600px;
  animation: fadeIn 0.8s ease 0.3s both;
}

.titulo {
  font-size: 2.5rem;
  font-weight: 800;
  color: #cc0000;
  margin-bottom: 1.5rem;
  line-height: 1.2;
}

.descripcion {
  font-size: 1.05rem;
  color: #2c3e50;
  margin-bottom: 2rem;
  line-height: 1.8;
  background: rgba(255, 255, 255, 0.85);
  padding: 1.5rem;
  border-radius: 12px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.3);
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
  box-shadow: 0 4px 15px rgba(204, 0, 0, 0.3);
}

.btn-primario:hover {
  background: #990000;
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(204, 0, 0, 0.4);
}

.btn-primario:hover i {
  transform: translateX(5px);
}

.btn-primario i {
  transition: transform 0.3s ease;
}

/* ========== ESTADÍSTICAS ========== */
.bienvenida-stats {
  left: 50%;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 1rem;
  max-width: 700px;
  margin-top: 2rem;
}

.stat {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  padding: 0.8rem 1.2rem;
  border-radius: 12px;
  transition: all 0.3s ease;
  border: 1px solid rgba(255, 255, 255, 0.3);
  animation: fadeIn 0.6s ease forwards;
  opacity: 0;
}

.stat:nth-child(1) { animation-delay: 0.2s; }
.stat:nth-child(2) { animation-delay: 0.4s; }
.stat:nth-child(3) { animation-delay: 0.6s; }
.stat:nth-child(4) { animation-delay: 0.8s; }
.stat:nth-child(5) { animation-delay: 1.0s; }

.stat:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  background: white;
}

.stat i {
  font-size: 1.8rem;
  color: #cc0000;
  flex-shrink: 0;
}

.stat-info {
  display: flex;
  flex-direction: column;
}

.stat-number {
  font-size: 1.3rem;
  font-weight: 800;
  color: #2c3e50;
  line-height: 1.2;
}

.stat-label {
  font-size: 0.75rem;
  color: #666;
  font-weight: 500;
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
  align-items: center;
  justify-content: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease;
  padding: 1rem;
}

.modal-container {
  position: relative;
  width: 100%;
  max-width: 1000px;
  max-height: 90vh;
  background: white;
  border-radius: 20px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.4s ease;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.modal-close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  width: 40px;
  height: 40px;
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
  font-size: 1.3rem;
  color: #cc0000;
  transition: all 0.3s ease;
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
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.modal-titulo {
  font-size: 1.5rem;
  font-weight: 700;
  color: #cc0000;
  margin-bottom: 1rem;
  padding-bottom: 0.8rem;
  border-bottom: 2px solid #cc0000;
  flex-shrink: 0;
}

.pdf-container {
  flex: 1;
  min-height: 400px;
  overflow: hidden;
  border-radius: 8px;
  background: #f5f5f5;
}

.pdf-container iframe {
  width: 100%;
  height: 100%;
  min-height: 500px;
  border: none;
}

/* ========== ANIMACIONES ========== */
@keyframes slideLeft {
  from {
    transform: translateX(-100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 0.5;
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(50px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* ========== RESPONSIVE ========== */

/* Tablets y pantallas medianas */
@media (max-width: 1024px) {
  .page-container {
    padding: 2rem;
  }

  .img1 {
    width: 45%;
    left: -10%;
  }

  .texto {
    max-width: 500px;
  }

  .titulo {
    font-size: 2.2rem;
  }
}

/* Tablets pequeñas */
@media (max-width: 768px) {
  .page-container {
    padding: 1.5rem;
    min-height: auto;
  }

  .img1 {
    width: 60%;
    left: -20%;
    opacity: 0.3 !important;
  }

  .imagenLogo {
    max-width: 200px;
  }

  .texto {
    max-width: 100%;
  }

  .titulo {
    font-size: 2rem;
  }

  .descripcion {
    font-size: 1rem;
    padding: 1rem;
  }

  .bienvenida-stats {
    grid-template-columns: repeat(2, 1fr);
    max-width: 100%;
  }

  .stat {
    padding: 0.7rem 1rem;
  }

  .stat i {
    font-size: 1.5rem;
  }

  .stat-number {
    font-size: 1.1rem;
  }
}

/* Móviles - UNA SOLA COLUMNA */
@media (max-width: 480px) {
  .page-container {
    padding: 1rem;
  }

  .img1 {
    width: 80%;
    left: -30%;
    opacity: 0.2 !important;
  }

  .imagenLogo {
    max-width: 150px;
    margin: 0 auto 1rem;
  }

  .texto {
    text-align: center;
  }

  .titulo {
    font-size: 1.6rem;
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
    font-size: 0.9rem;
  }

  /* Estadísticas en UNA SOLA COLUMNA */
  .bienvenida-stats {
    grid-template-columns: 1fr;
    gap: 0.6rem;
    max-width: 100%;
  }

  .stat {
    width: 100%;
    padding: 0.7rem 1rem;
    justify-content: flex-start;
  }

  .stat i {
    font-size: 1.4rem;
  }

  .stat-number {
    font-size: 1.1rem;
  }

  .stat-label {
    font-size: 0.7rem;
  }

  /* Modal responsive */
  .modal-container {
    max-height: 95vh;
    border-radius: 12px;
  }

  .modal-content {
    padding: 1rem;
  }

  .modal-titulo {
    font-size: 1.1rem;
  }

  .pdf-container {
    min-height: 300px;
  }

  .pdf-container iframe {
    min-height: 350px;
  }

  .modal-close {
    width: 32px;
    height: 32px;
    top: 0.5rem;
    right: 0.5rem;
  }

  .modal-close i {
    font-size: 1rem;
  }
}

/* Móviles muy pequeños */
@media (max-width: 360px) {
  .titulo {
    font-size: 1.3rem;
  }

  .descripcion {
    font-size: 0.8rem;
    padding: 0.6rem;
  }

  .stat {
    padding: 0.5rem 0.8rem;
  }

  .stat i {
    font-size: 1.2rem;
  }

  .stat-number {
    font-size: 1rem;
  }
}
</style>
