<template>
  <div class="page-container">
    <img src="../../../public/images/home/img1.png" class="img img1" />
    <img src="../../../public/images/home/img2.png" class="img img2" />
    <img src="../../../public/images/home/img3.png" class="img img3" />
    <img src="../../../public/images/home/img4.png" class="img img4" />
    <div class="imagenLogo">
        <img src="../../../public/images/Logo_negativo.png" class="logo" />
    </div>
    <div class="texto">
        <h1 class="titulo align:center">Concejo Municipal de Potosí</h1>
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
    <div class="bienvenida-stats">
      <div class="stat">
        <i class="bi bi-geo-alt-fill"></i>
        <div class="stat-info">
          <span class="stat-number">4.067 m.s.n.m.</span>
          <span class="stat-label">Altitud</span>
        </div>
      </div>
      <div class="stat">
        <i class="bi bi-person-standing"></i>
        <div class="stat-info">
          <span class="stat-number">1</span>
          <span class="stat-label">Alcalde</span>
        </div>
      </div>
      <div class="stat">
        <i class="bi bi-people-fill"></i>
        <div class="stat-info">
          <span class="stat-number">11</span>
          <span class="stat-label">Concejales</span>
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
    <!-- Modal -->
    <div v-if="modalVisible" class="modal-overlay" @click.self="cerrarModal">
      <div class="modal-container">
        <button class="modal-close" @click="cerrarModal">
          <i class="bi bi-x-lg"></i>
        </button>

        <div class="modal-content">
          <h2 class="modal-titulo">
            <i class="bi bi-info-circle-fill"></i>
            Concejo Municipal de Potosí
          </h2>

          <div class="modal-cuerpo">
            <p class="modal-texto">
              El Concejo Municipal de Potosí es el órgano legislativo, deliberativo y fiscalizador
              del Gobierno Autónomo Municipal de Potosí. Está conformado por concejales electos
              democráticamente que representan a los diferentes distritos de la ciudad.
            </p>

            <p class="modal-texto">
              <strong>Funciones principales:</strong>
            </p>
            <ul class="modal-lista">
              <li><i class="bi bi-check-circle-fill"></i> Legislar sobre asuntos municipales</li>
              <li><i class="bi bi-check-circle-fill"></i> Fiscalizar la gestión del Ejecutivo Municipal</li>
              <li><i class="bi bi-check-circle-fill"></i> Aprobar el presupuesto municipal</li>
              <li><i class="bi bi-check-circle-fill"></i> Controlar el uso de recursos públicos</li>
              <li><i class="bi bi-check-circle-fill"></i> Representar a la ciudadanía</li>
            </ul>

            <p class="modal-texto">
              Trabajamos día a día para construir una ciudad más justa, transparente y desarrollada,
              escuchando las necesidades de todos los ciudadanos y velando por el bienestar común.
            </p>

            <div class="modal-info">
              <i class="bi bi-clock-history"></i>
              <span>Atención: Lunes a Viernes de 8:30 a 12:00 y de 14:30 a 16:00</span>
            </div>
          </div>

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
            <i class="bi bi-info-circle-fill"></i>
            Concejo Municipal de Potosí
          </h2>

          <div class="modal-cuerpo">
            <p class="modal-texto">
              El Concejo Municipal de Potosí es el órgano legislativo, deliberativo y fiscalizador
              del Gobierno Autónomo Municipal de Potosí. Está conformado por concejales electos
              democráticamente que representan a los diferentes distritos de la ciudad.
            </p>

            <p class="modal-texto">
              <strong>Funciones principales:</strong>
            </p>
            <ul class="modal-lista">
              <li><i class="bi bi-check-circle-fill"></i> Legislar sobre asuntos municipales</li>
              <li><i class="bi bi-check-circle-fill"></i> Fiscalizar la gestión del Ejecutivo Municipal</li>
              <li><i class="bi bi-check-circle-fill"></i> Aprobar el presupuesto municipal</li>
              <li><i class="bi bi-check-circle-fill"></i> Controlar el uso de recursos públicos</li>
              <li><i class="bi bi-check-circle-fill"></i> Representar a la ciudadanía</li>
            </ul>

            <p class="modal-texto">
              Trabajamos día a día para construir una ciudad más justa, transparente y desarrollada,
              escuchando las necesidades de todos los ciudadanos y velando por el bienestar común.
            </p>

            <div class="modal-info">
              <i class="bi bi-clock-history"></i>
              <span>Atención: Lunes a Viernes de 8:30 a 12:00 y de 14:30 a 16:00</span>
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
  document.body.style.overflow = 'hidden' // Evitar scroll del body
}

const cerrarModal = () => {
  modalVisible.value = false
  document.body.style.overflow = '' // Restaurar scroll
}

const explorar = () => {
  abrirModal()
}
</script>

<style scoped>
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

/* BASE */
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
.imagenLogo {
  max-width: 700px;
  margin-left: 70%;
  margin-top: 5%;
  text-align: center;
  animation: entrarArriba 2s ease-out forwards;
}

/* BIENVENIDA */
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
  text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
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

.btn-primario i {
  transition: transform 0.3s ease;
}

.btn-primario:hover i {
  transform: translateX(5px);
}

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
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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

/* RESPONSIVE TABLET */
@media (max-width: 1024px) {
  .img1 { width: 60vw; }
  .img2 { width: 35vw; left: 30vw; }
  .img3 { width: 25vw; left: 25vw; }
  .img4 { width: 20vw; left: 45vw; top: 10vw;}
}

/* RESPONSIVE MÓVIL */
@media (max-width: 600px) {
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
}

/* ANIMACIONES */
@keyframes entrarIzquierda {
  from { transform: translateX(-200px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

@keyframes entrarDerecha {
  from { transform: translateX(200px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

@keyframes entrarArriba {
  from { transform: translateY(-200px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

@keyframes entrarAbajo {
  from { transform: translateY(200px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
/* ========== MODAL ========== */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(5px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease;
}

.modal-container {
  position: relative;
  max-width: 600px;
  width: 90%;
  max-height: 85vh;
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  border-radius: 20px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
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
  display: flex;
  align-items: center;
  gap: 0.8rem;
  border-bottom: 2px solid #cc0000;
  padding-bottom: 0.8rem;
}

.modal-titulo i {
  font-size: 1.8rem;
}

.modal-cuerpo {
  max-height: 55vh;
  overflow-y: auto;
  padding-right: 0.5rem;
}

.modal-cuerpo::-webkit-scrollbar {
  width: 6px;
}

.modal-cuerpo::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.modal-cuerpo::-webkit-scrollbar-thumb {
  background: #cc0000;
  border-radius: 10px;
}

.modal-texto {
  font-size: 1rem;
  color: #333;
  line-height: 1.6;
  margin-bottom: 1rem;
}

.modal-lista {
  list-style: none;
  padding: 0;
  margin: 1rem 0;
}

.modal-lista li {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  padding: 0.6rem 0;
  color: #333;
  font-size: 0.95rem;
}

.modal-lista li i {
  color: #cc0000;
  font-size: 1rem;
}

.modal-info {
  background: #f0f0f0;
  padding: 1rem;
  border-radius: 12px;
  display: flex;
  align-items: center;
  gap: 0.8rem;
  margin: 1rem 0;
  color: #333;
}

.modal-info i {
  font-size: 1.2rem;
  color: #cc0000;
}

.modal-boton {
  width: 100%;
  background: linear-gradient(135deg, #cc0000, #990000);
  color: white;
  border: none;
  padding: 0.8rem;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  margin-top: 1.5rem;
}

.modal-boton:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3);
}

/* Animaciones */
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
@media (max-width: 768px) {
  .contenido {
    padding: 2rem 1.5rem;
  }

  .titulo {
    font-size: 1.8rem;
  }

  .descripcion {
    font-size: 1rem;
  }

  .bienvenida-stats {
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

  .modal-texto {
    font-size: 0.9rem;
  }

  .modal-lista li {
    font-size: 0.85rem;
  }
}

@media (max-width: 480px) {
  .contenido {
    padding: 1.5rem 1rem;
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
