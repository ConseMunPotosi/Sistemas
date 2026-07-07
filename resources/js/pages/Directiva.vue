<template>
  <div class="directiva-container">
    <div class="directiva-header">
      <h2 class="directiva-titulo">Directiva del Concejo Municipal</h2>
      <p class="directiva-subtitulo">Periodo Constitucional 2026-2027</p>
    </div>

    <!-- Tarjetas de la Directiva -->
    <div class="directiva-grid">
      <!-- Vicepresidente (Izquierda) -->
      <div class="directiva-card vicepresidente" v-if="directiva.vicepresidente">
        <div class="card-image">
          <img :src="directiva.vicepresidente.foto" :alt="directiva.vicepresidente.nombre" />
          <span class="cargo-badge">Vicepresidente</span>
        </div>
        <div class="card-content">
          <h3 class="nombre">{{ directiva.vicepresidente.nombre }}</h3>
          <p class="cargo">{{ directiva.vicepresidente.cargo }}</p>
          <p class="descripcion">{{ directiva.vicepresidente.descripcion }}</p>
          <div class="card-footer">
            <div class="partido" v-if="directiva.vicepresidente.partido">
              <span class="partido-color" :style="{ background: directiva.vicepresidente.colorPartido }"></span>
              <span>{{ directiva.vicepresidente.partido }}</span>
            </div>
            <div class="enlaces">
              <a v-if="directiva.vicepresidente.facebook" :href="directiva.vicepresidente.facebook" target="_blank" class="enlace-social facebook">
                <i class="bi bi-facebook"></i>
              </a>
              <a v-if="directiva.vicepresidente.twitter" :href="directiva.vicepresidente.twitter" target="_blank" class="enlace-social twitter">
                <i class="bi bi-twitter-x"></i>
              </a>
              <a v-if="directiva.vicepresidente.instagram" :href="directiva.vicepresidente.instagram" target="_blank" class="enlace-social instagram">
                <i class="bi bi-instagram"></i>
              </a>
              <a v-if="directiva.vicepresidente.web" :href="directiva.vicepresidente.web" target="_blank" class="enlace-social web">
                <i class="bi bi-globe2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Presidente (Centro - Destacado) -->
      <div class="directiva-card presidente" v-if="directiva.presidente">
        <div class="card-image">
          <img :src="directiva.presidente.foto" :alt="directiva.presidente.nombre" />
          <span class="cargo-badge presidente-badge">Presidente</span>
          <span class="presidente-ribbon">★</span>
        </div>
        <div class="card-content">
          <h3 class="nombre">{{ directiva.presidente.nombre }}</h3>
          <p class="cargo">{{ directiva.presidente.cargo }}</p>
          <p class="descripcion">{{ directiva.presidente.descripcion }}</p>
          <div class="card-footer">
            <div class="partido" v-if="directiva.presidente.partido">
              <span class="partido-color" :style="{ background: directiva.presidente.colorPartido }"></span>
              <span>{{ directiva.presidente.partido }}</span>
            </div>
            <div class="enlaces">
              <a v-if="directiva.presidente.facebook" :href="directiva.presidente.facebook" target="_blank" class="enlace-social facebook">
                <i class="bi bi-facebook"></i>
              </a>
              <a v-if="directiva.presidente.twitter" :href="directiva.presidente.twitter" target="_blank" class="enlace-social twitter">
                <i class="bi bi-twitter-x"></i>
              </a>
              <a v-if="directiva.presidente.instagram" :href="directiva.presidente.instagram" target="_blank" class="enlace-social instagram">
                <i class="bi bi-instagram"></i>
              </a>
              <a v-if="directiva.presidente.web" :href="directiva.presidente.web" target="_blank" class="enlace-social web">
                <i class="bi bi-globe2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Concejal Secretario (Derecha) -->
      <div class="directiva-card secretario" v-if="directiva.secretario">
        <div class="card-image">
          <img :src="directiva.secretario.foto" :alt="directiva.secretario.nombre" />
          <span class="cargo-badge">Concejal Secretaria</span>
        </div>
        <div class="card-content">
          <h3 class="nombre">{{ directiva.secretario.nombre }}</h3>
          <p class="cargo">{{ directiva.secretario.cargo }}</p>
          <p class="descripcion">{{ directiva.secretario.descripcion }}</p>
          <div class="card-footer">
            <div class="partido" v-if="directiva.secretario.partido">
              <span class="partido-color" :style="{ background: directiva.secretario.colorPartido }"></span>
              <span>{{ directiva.secretario.partido }}</span>
            </div>
            <div class="enlaces">
              <a v-if="directiva.secretario.facebook" :href="directiva.secretario.facebook" target="_blank" class="enlace-social facebook">
                <i class="bi bi-facebook"></i>
              </a>
              <a v-if="directiva.secretario.twitter" :href="directiva.secretario.twitter" target="_blank" class="enlace-social twitter">
                <i class="bi bi-twitter-x"></i>
              </a>
              <a v-if="directiva.secretario.instagram" :href="directiva.secretario.instagram" target="_blank" class="enlace-social instagram">
                <i class="bi bi-instagram"></i>
              </a>
              <a v-if="directiva.secretario.web" :href="directiva.secretario.web" target="_blank" class="enlace-social web">
                <i class="bi bi-globe2"></i>
              </a>
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

// Datos de la Directiva
const directiva = ref({
  presidente: {
    nombre: 'Abg. German Antonio Vidaurre Villanueva',
    cargo: 'Presidente del Concejo Municipal',
    foto: '/images/Concejales/Antonio_Vidaurre.png',
    descripcion: 'Experiencia profesional en gestión municipal, 25 años de experiencia en medios de comunicación, 23 años los desempeñó en Radio Fides Potosí (Bolivia); Voz reconocida en el ámbito informativo local.',
    facebook: 'https://facebook.com/presidente',
    twitter: 'https://twitter.com/presidente',
    instagram: 'https://instagram.com/presidente',
    web: 'https://presidente.com'
  },
  vicepresidente: {
    nombre: 'Claudio German Clemente Vedia',
    cargo: 'Vicepresidente del Concejo Municipal',
    foto: '/images/concejales/Claudio_Clemente.png',
    descripcion: 'A detallar',
    facebook: 'https://facebook.com/vicepresidente',
    twitter: 'https://twitter.com/vicepresidente',
    instagram: 'https://instagram.com/vicepresidente'
  },
  secretario: {
    nombre: 'María del Carmen Michel Araujo',
    cargo: 'Concejal Secretaria',
    foto: '/images/Concejales/Carmen_Michel.png',
    descripcion: 'A detallar',
    facebook: 'https://facebook.com/secretario',
    twitter: 'https://twitter.com/secretario',
    instagram: 'https://instagram.com/secretario',
    web: 'https://secretario.com'
  }
})
</script>

<style scoped>
/* ========== CONTENEDOR PRINCIPAL ========== */
.directiva-container {
  margin: 0 auto;
  padding: 1rem;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
}

/* ========== HEADER ========== */
.directiva-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 1rem;
  color: red;
}

.directiva-titulo {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  letter-spacing: 1px;
}

.directiva-subtitulo {
  font-size: 1.5rem;
  margin: 0;
}

/* ========== GRID DE TARJETAS ========== */
.directiva-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
  align-items: stretch;
}

/* ========== TARJETA BASE ========== */
.directiva-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  border: 1px solid #f0f0f0;
}

.directiva-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

/* ========== TARJETA DEL PRESIDENTE (DESTACADA) ========== */
.presidente {
  position: relative;
  order: -1;
}

.presidente::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 6px;
  background: linear-gradient(90deg, #cc0000, #ff4444, #cc0000);
  background-size: 200% 100%;
  animation: gradiente 3s ease-in-out infinite;
}

@keyframes gradiente {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

.presidente:hover {
  border-color: #cc0000;
  box-shadow: 0 12px 40px rgba(204, 0, 0, 0.2);
}

.presidente-ribbon {
  position: absolute;
  top: 10px;
  left: 10px;
  font-size: 2rem;
  color: #ffd700;
  animation: pulse 2s ease-in-out infinite;
  text-shadow: 0 2px 8px rgba(255, 215, 0, 0.3);
  z-index: 5;
}

@keyframes pulse {
  0%, 100% { transform: scale(1) rotate(-10deg); }
  50% { transform: scale(1.2) rotate(10deg); }
}

/* ========== IMAGEN DE LA TARJETA ========== */
.card-image {
  position: relative;
  width: 100%;
  padding-top: 100%;
  overflow: hidden;
}

.card-image img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.directiva-card:hover .card-image img {
  transform: scale(1.05);
}

/* ========== BADGE DEL CARGO ========== */
.cargo-badge {
  position: absolute;
  bottom: 1rem;
  right: 1rem;
  background: rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(10px);
  color: white;
  padding: 0.4rem 1rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.presidente-badge {
  background: linear-gradient(135deg, #cc0000, #990000);
  font-size: 0.85rem;
  padding: 0.5rem 1.2rem;
  box-shadow: 0 4px 15px rgba(204, 0, 0, 0.4);
}

/* ========== CONTENIDO DE LA TARJETA ========== */
.card-content {
  padding: 1.5rem;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.nombre {
  font-size: 1.3rem;
  font-weight: 700;
  color: #1a1a2e;
  margin: 0 0 0.3rem 0;
}

.cargo {
  font-size: 0.9rem;
  color: #cc0000;
  font-weight: 600;
  margin: 0 0 0.8rem 0;
}

.descripcion {
  font-size: 0.95rem;
  color: #666;
  line-height: 1.6;
  margin: 0 0 1rem 0;
  flex: 1;
}

/* ========== FOOTER DE LA TARJETA ========== */
.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.8rem;
  padding-top: 0.8rem;
  border-top: 1px solid #f0f0f0;
}

.partido {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
  color: #555;
}

.partido-color {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  flex-shrink: 0;
}

/* ========== ENLACES SOCIALES ========== */
.enlaces {
  display: flex;
  gap: 0.5rem;
}

.enlace-social {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  color: white;
  text-decoration: none;
  font-size: 0.9rem;
}

.enlace-social:hover {
  transform: translateY(-3px) scale(1.1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.facebook { background: #1877f2; }
.twitter { background: #000000; }
.instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
.web { background: #4a6cf7; }

/* ========== RESPONSIVE ========== */

/* Tablets: 2 columnas */
@media (min-width: 768px) and (max-width: 1023px) {
  .directiva-grid {
    grid-template-columns: 1fr 1fr;
  }

  .presidente {
    grid-column: 1 / -1;
    order: 0;
  }
}

/* Desktop: 3 columnas */
@media (min-width: 1024px) {
  .directiva-grid {
    grid-template-columns: 1fr 1.2fr 1fr;
  }

  .presidente {
    order: 0;
    transform: scale(1.05);
    z-index: 2;
  }

  .presidente:hover {
    transform: scale(1.08) translateY(-8px);
  }
}

/* Móviles pequeños */
@media (max-width: 480px) {
  .directiva-container {
    padding: 1rem 0.5rem;
  }

  .directiva-titulo {
    font-size: 1.8rem;
  }

  .directiva-subtitulo {
    font-size: 1rem;
  }

  .card-content {
    padding: 1rem;
  }

  .nombre {
    font-size: 1.1rem;
  }

  .descripcion {
    font-size: 0.85rem;
  }

  .card-footer {
    flex-direction: column;
    align-items: flex-start;
  }

  .enlaces {
    width: 100%;
    justify-content: center;
  }
}

/* ========== ANIMACIONES DE ENTRADA ========== */
.directiva-card {
  animation: fadeInUp 0.6s ease forwards;
  opacity: 0;
}

.vicepresidente { animation-delay: 0.2s; }
.presidente { animation-delay: 0.4s; }
.secretario { animation-delay: 0.6s; }

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ========== ESTADO VACÍO ========== */
.directiva-grid:empty::after {
  content: 'No hay información de la directiva disponible';
  display: block;
  text-align: center;
  padding: 3rem;
  color: #999;
  grid-column: 1 / -1;
}
</style>
