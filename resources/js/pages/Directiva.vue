<template>
  <div class="directiva-container">
    <div class="directiva-header">
      <h2 class="directiva-titulo">Directiva del Concejo Municipal</h2>
      <p class="directiva-subtitulo">Periodo Constitucional 2026-2027</p>
    </div>

    <!-- Tarjetas de la Directiva -->
    <div class="directiva-grid">
      <!-- Presidente (Arriba - Destacado) -->
      <div class="directiva-card presidente" v-if="directiva.presidente">
        <div class="card-image presidente-image">
          <img :src="directiva.presidente.foto" :alt="directiva.presidente.nombre" />
          <span class="cargo-badge presidente-badge">Presidente</span>
        </div>
        <div class="card-content">
          <h3 class="nombre">{{ directiva.presidente.nombre }}</h3>
          <p class="cargo">{{ directiva.presidente.cargo }}</p>
          <p class="descripcion">{{ directiva.presidente.descripcion }}</p>
          <div class="card-footer">
            <div class="social-icons-group">
              <!-- YouTube -->
              <button
                class="social-icon youtube"
                aria-label="Youtube"
                @click="irARedSocial('https://www.youtube.com/@ConcejoMunicipalPotosi', 'YouTube')">
                <i class="bi bi-youtube"></i>
              </button>

              <!-- Facebook -->
              <button
                class="social-icon facebook"
                aria-label="Facebook"
                @click="irARedSocial('https://www.facebook.com/profile.php?id=100068918032041', 'Facebook')">
                <i class="bi bi-facebook"></i>
              </button>

              <!-- Instagram -->
              <button
                class="social-icon instagram"
                aria-label="Instagram"
                @click="irARedSocial('https://www.instagram.com/concejomunicipalpotosi', 'Instagram')">
                <i class="bi bi-instagram"></i>
              </button>

              <!-- Twitter/X -->
              <button
                class="social-icon twitter-x"
                aria-label="Twitter"
                @click="irARedSocial('https://twitter.com/ConcejoPotosi', 'Twitter')">
                <i class="bi bi-twitter-x"></i>
              </button>

                <!-- WhatsApp -->
              <button
                class="social-icon whatsapp"
                aria-label="WhatsApp"
                @click="irARedSocial('https://wa.me/59170000000', 'WhatsApp')">
                <i class="bi bi-whatsapp"></i>
              </button>

              <!-- TikTok -->
              <button
                class="social-icon tiktok"
                aria-label="Tiktok"
                @click="irARedSocial('https://www.tiktok.com/@concejomunicipaldepotosi', 'TikTok')">
                <i class="bi bi-tiktok"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Fila inferior: Vicepresidente y Secretario -->
      <div class="directiva-secundarios">
        <!-- Vicepresidente -->
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
              <div class="social-icons-group">
                <!-- YouTube -->
                <button
                    class="social-icon youtube"
                    aria-label="Youtube"
                    @click="irARedSocial('https://www.youtube.com/@ConcejoMunicipalPotosi', 'YouTube')">
                    <i class="bi bi-youtube"></i>
                </button>

                <!-- Facebook -->
                <button
                    class="social-icon facebook"
                    aria-label="Facebook"
                    @click="irARedSocial('https://www.facebook.com/profile.php?id=100068918032041', 'Facebook')">
                    <i class="bi bi-facebook"></i>
                </button>

                <!-- Instagram -->
                <button
                    class="social-icon instagram"
                    aria-label="Instagram"
                    @click="irARedSocial('https://www.instagram.com/concejomunicipalpotosi', 'Instagram')">
                    <i class="bi bi-instagram"></i>
                </button>

                <!-- Twitter/X -->
                <button
                    class="social-icon twitter-x"
                    aria-label="Twitter"
                    @click="irARedSocial('https://twitter.com/ConcejoPotosi', 'Twitter')">
                    <i class="bi bi-twitter-x"></i>
                </button>

                    <!-- WhatsApp -->
                <button
                    class="social-icon whatsapp"
                    aria-label="WhatsApp"
                    @click="irARedSocial('https://wa.me/59170000000', 'WhatsApp')">
                    <i class="bi bi-whatsapp"></i>
                </button>

                <!-- TikTok -->
                <button
                    class="social-icon tiktok"
                    aria-label="Tiktok"
                    @click="irARedSocial('https://www.tiktok.com/@concejomunicipaldepotosi', 'TikTok')">
                    <i class="bi bi-tiktok"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Concejal Secretario -->
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
              <div class="social-icons-group">
                <!-- YouTube -->
                <button
                    class="social-icon youtube"
                    aria-label="Youtube"
                    @click="irARedSocial('https://www.youtube.com/@ConcejoMunicipalPotosi', 'YouTube')">
                    <i class="bi bi-youtube"></i>
                </button>

                <!-- Facebook -->
                <button
                    class="social-icon facebook"
                    aria-label="Facebook"
                    @click="irARedSocial('https://www.facebook.com/profile.php?id=100068918032041', 'Facebook')">
                    <i class="bi bi-facebook"></i>
                </button>

                <!-- Instagram -->
                <button
                    class="social-icon instagram"
                    aria-label="Instagram"
                    @click="irARedSocial('https://www.instagram.com/concejomunicipalpotosi', 'Instagram')">
                    <i class="bi bi-instagram"></i>
                </button>

                <!-- Twitter/X -->
                <button
                    class="social-icon twitter-x"
                    aria-label="Twitter"
                    @click="irARedSocial('https://twitter.com/ConcejoPotosi', 'Twitter')">
                    <i class="bi bi-twitter-x"></i>
                </button>

                    <!-- WhatsApp -->
                <button
                    class="social-icon whatsapp"
                    aria-label="WhatsApp"
                    @click="irARedSocial('https://wa.me/59170000000', 'WhatsApp')">
                    <i class="bi bi-whatsapp"></i>
                </button>

                <!-- TikTok -->
                <button
                    class="social-icon tiktok"
                    aria-label="Tiktok"
                    @click="irARedSocial('https://www.tiktok.com/@concejomunicipaldepotosi', 'TikTok')">
                    <i class="bi bi-tiktok"></i>
                </button>
              </div>
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

const directiva = ref({
  presidente: {
    nombre: 'Abg. German Antonio Vidaurre Villanueva',
    cargo: 'Presidente del Concejo Municipal',
    foto: '/images/Concejales/Antonio_Vidaurre.png',
    descripcion: 'Experiencia profesional en gestión municipal, 25 años de experiencia en medios de comunicación, 23 años los desempeñó en Radio Fides Potosí (Bolivia); Voz reconocida en el ámbito informativo local.',
    facebook: 'https://www.facebook.com/profile.php?id=100064507628933',
    twitter: 'https://twitter.com/AntonioVidaurre',
    instagram: 'https://www.instagram.com/antoniovidaurre',
    youtube: 'https://www.youtube.com/@AntonioVidaurre',
    tiktok: 'https://www.tiktok.com/@antoniovidaurre',
    web: 'https://concejomunicipalpotosi.gob.bo'
  },
  vicepresidente: {
    nombre: 'Claudio German Clemente Vedia',
    cargo: 'Vicepresidente del Concejo Municipal',
    foto: '/images/concejales/Claudio_Clemente.png',
    descripcion: 'Profesional con amplia experiencia en gestión pública y desarrollo municipal. Comprometido con el progreso de Potosí y la participación ciudadana.',
    facebook: 'https://www.facebook.com/claudioclemente',
    twitter: 'https://twitter.com/ClaudioClemente',
    instagram: 'https://www.instagram.com/claudioclemente',
    youtube: 'https://www.youtube.com/@ClaudioClemente',
    web: ''
  },
  secretario: {
    nombre: 'María del Carmen Michel Araujo',
    cargo: 'Concejal Secretaria',
    foto: '/images/Concejales/Carmen_Michel.png',
    descripcion: 'Destacada lideresa social y defensora de los derechos de la mujer y la niñez. Con amplia trayectoria en organizaciones sociales y trabajo comunitario.',
    facebook: 'https://www.facebook.com/carmenmichel',
    twitter: 'https://twitter.com/CarmenMichel',
    instagram: 'https://www.instagram.com/carmenmichel',
    youtube: 'https://www.youtube.com/@CarmenMichel',
    tiktok: 'https://www.tiktok.com/@carmenmichel',
    web: ''
  }
})
</script>

<style scoped>
.directiva-container {
  margin: 0 auto;
  padding: 1rem;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  min-height: 100vh;
}

.directiva-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 1rem;
}

.directiva-titulo {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  color: #cc0000;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
}

.directiva-subtitulo {
  font-size: 1.2rem;
  color: #333;
  margin: 0;
  font-weight: 500;
}

.directiva-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  max-width: 1200px;
  margin: 0 auto;
}

.directiva-secundarios {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}

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

/* ========== PRESIDENTE DESTACADO ========== */
.presidente {
  position: relative;
  border: 2px solid #cc0000;
  box-shadow: 0 8px 30px rgba(204, 0, 0, 0.2);
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
  box-shadow: 0 12px 40px rgba(204, 0, 0, 0.3);
  transform: translateY(-8px) scale(1.01);
}

@keyframes pulse {
  0%, 100% { transform: scale(1) rotate(-10deg); }
  50% { transform: scale(1.2) rotate(10deg); }
}

/* ========== IMAGEN - MISMO TAMAÑO PARA TODOS ========== */
.card-image {
  position: relative;
  width: 100%;
  padding-top: 100%;
  overflow: hidden;
  background-color: #f0f0f0;
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

/* Imagen del presidente - Mismo tamaño que las demás */
.presidente-image {
  padding-top: 100%; /* Mismo tamaño que las demás tarjetas */
}

.presidente-image img {
  object-fit: cover; /* Mismo que las demás */
}

.directiva-card:hover .card-image img {
  transform: scale(1.05);
}

/* ========== BADGE ========== */
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

/* ========== CONTENIDO ========== */
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

.card-footer {
  display: flex;
  justify-content: center;
  align-items: center;
  padding-top: 0.8rem;
  border-top: 1px solid #f0f0f0;
}

.social-icons-group {
  margin-top: 0.5rem;
  display: flex;
  gap: 0.8rem;
}

.social-icon {
  background: transparent;
  cursor: pointer;
  border: none;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  text-decoration: none;
}

.social-icon i {
  color: #A8A8B7;
  font-size: 1.5rem;
  transition: all 0.3s ease;
}

.social-icon:hover i {
  transform: scale(1.2);
}

.social-icon.facebook:hover i {color: #1877f2; }
.social-icon.youtube:hover i {color: #ff0000;}
.social-icon.instagram:hover i {color: #e4405f;}
.social-icon.twitter-x:hover i {color: #1da1f2;}
.social-icon.whatsapp:hover i {color: #25d366;}
.social-icon.tiktok:hover i {color: #00f2ea;}

/* ========== RESPONSIVE ========== */

/* Móviles: Una columna */
@media (max-width: 767px) {
  .directiva-grid {
    grid-template-columns: 1fr;
  }

  .directiva-secundarios {
    grid-template-columns: 1fr;
  }
}

/* Tablets: 2 columnas para secundarios */
@media (min-width: 768px) and (max-width: 1023px) {
  .directiva-grid {
    grid-template-columns: 1fr;
  }

  .directiva-secundarios {
    grid-template-columns: 1fr 1fr;
  }
}

/* Desktop: Presidente arriba, secundarios abajo en 2 columnas */
@media (min-width: 1024px) {
  .directiva-grid {
    grid-template-columns: 1fr;
    max-width: 1000px;
  }

  .directiva-secundarios {
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }

  .presidente {
    max-width: 50%;
    margin: 0 auto;
    width: 100%;
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

  .enlace-social {
    width: 30px;
    height: 30px;
    font-size: 0.8rem;
  }
}

/* ========== ANIMACIONES ========== */
.directiva-card {
  animation: fadeInUp 0.6s ease forwards;
  opacity: 0;
}

.presidente { animation-delay: 0.2s; }
.vicepresidente { animation-delay: 0.4s; }
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
</style>
