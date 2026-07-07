<template>
  <div class="page-container">
    <!-- Imágenes decorativas -->
    <img src="../../../public/images/home/img1.png" class="img img1" />
    <img src="../../../public/images/home/img2.png" class="img img2" />
    <img src="../../../public/images/home/img3.png" class="img img3" />
    <img src="../../../public/images/home/img4.png" class="img img4" />

    <!-- Logo -->
    <div class="imagenLogo">
      <img src="../../../public/images/Logo_negativo.png" class="logo" />
    </div>

    <!-- Contenido principal -->
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
      </div><br/>
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
  background-image: url('../../../public/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  overflow: hidden;
}

/* ========== IMÁGENES DECORATIVAS ========== */
.img {
  position: absolute;
  max-width: 100%;
  height: auto;
}

.img1 {
  width: 88vh;
  left: 0;
  top: 0;
  z-index: 1;
  animation: entrarIzquierda 1.5s ease-out forwards;
}

.img2 {
  width: 18vw;
  left: 34vw;
  z-index: 3;
  animation: entrarDerecha 1.8s ease-out forwards;
}

.img3 {
  width: 18vw;
  left: 26vw;
  z-index: 2;
  animation: entrarArriba 2s ease-out forwards;
}

.img4 {
  width: 8vw;
  left: 39vw;
  top: 63vh;
  z-index: 1;
  animation: entrarAbajo 2s ease-out forwards;
}

/* ========== LOGO ========== */
.imagenLogo {
  max-width: 700px;
  margin-left: 70%;
  margin-top: 5%;
  text-align: center;
  animation: entrarArriba 2s ease-out forwards;
}

/* ========== TEXTO PRINCIPAL ========== */
.texto {
  max-width: 700px;
  margin-top: 1%;
  margin-left: 60%;
  text-align: justify;
  animation: entrarDerecha 1.8s ease-out forwards;
}

.titulo {
  font-weight: 800;
  color: #cc0000;
  margin-bottom: 1.5rem;
  line-height: 1.2;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
  animation: fadeInLeft 0.8s ease;
}

.descripcion {
  font-size: 1.2rem;
  color: #000000;
  margin-bottom: 2rem;
  line-height: 1.6;
  animation: fadeInLeft 0.8s ease 0.2s both;
}

.hero-botones {
  display: flex;
  gap: 1rem;
  justify-content: flex-start;
  flex-wrap: wrap;
  animation: fadeInLeft 0.8s ease 0.4s both;
}

.btn-primario {
  background: #cc0000;
  color: white;
  padding: 1rem 2rem;
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

/* ========== ESTADÍSTICAS ========== */
.bienvenida-stats {
  align-items: center;
  margin-top: 2%;
  margin-left: 60%;
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
  animation: slideInUp 0.4s ease;
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

@keyframes fadeInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
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

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ========== RESPONSIVE ========== */
@media (max-width: 1024px) {
  .img1 {
    width: 60vw;
  }
  .img2 {
    width: 35vw;
    left: 30vw;
  }
  .img3 {
    width: 25vw;
    left: 25vw;
  }
  .img4 {
    width: 20vw;
    left: 45vw;
    top: 10vw;
  }
}

@media (max-width: 768px) {
  .texto {
    max-width: 90%;
    margin-left: 5%;
    margin-top: 2%;
  }

  .imagenLogo {
    max-width: 90%;
    margin-left: 5%;
    margin-top: 2%;
  }

  .titulo {
    font-size: 1.8rem;
  }

  .descripcion {
    font-size: 1rem;
  }

  .bienvenida-stats {
    margin-left: 5%;
    gap: 1rem;
  }

  .stat {
    padding: 0.8rem 1rem;
  }

  .stat-number {
    font-size: 1.2rem;
  }

  .modal-titulo {
    font-size: 1.4rem;
  }

  .modal-content {
    padding: 1.5rem;
  }
}

@media (max-width: 480px) {
  .page-container {
    min-height: 120vw;
  }

  .img1 {
    width: 90vw;
    left: 5vw;
    top: 0;
  }

  .img2 {
    width: 70vw;
    left: 15vw;
    top: 20vh;
  }

  .img3 {
    width: 60vw;
    left: 20vw;
    top: 45vh;
  }

  .img4 {
    width: 50vw;
    left: 25vw;
    top: 30vw;
  }

  .texto {
    margin-left: 5%;
    margin-top: 5%;
  }

  .imagenLogo {
    margin-left: 5%;
    margin-top: 5%;
  }

  .titulo {
    font-size: 1.4rem;
  }

  .descripcion {
    font-size: 0.85rem;
  }

  .btn-primario {
    padding: 0.7rem 1.5rem;
    font-size: 0.85rem;
  }

  .bienvenida-stats {
    flex-direction: column;
    align-items: flex-start;
  }

  .stat {
    width: 100%;
  }

  .modal-titulo {
    font-size: 1.2rem;
  }

  .modal-content {
    padding: 1rem;
  }
}
</style>