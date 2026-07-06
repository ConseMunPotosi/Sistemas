<template>
  <div class="page-container">
    <img src="../../../public/images/home/img1.png" class="img img1" />
    <img src="../../../public/images/home/img2.png" class="img img2" />
    <img src="../../../public/images/home/img3.png" class="img img3" />
    <img src="../../../public/images/home/img4.png" class="img img4" />
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
</template>

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

/* BIENVENIDA */
.texto {
  max-width: 700px;
  margin-top: 10%;
  margin-left: 60%;
  text-align: justify;
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
</style>
