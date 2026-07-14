<template>
  <div class="principal">
    <div class="concejales-header">
      <h2 class="concejales-titulo">Miembros del Honorable Concejo Municipal de Potosí</h2>
      <p class="concejales-subtitulo">Elegidos por voto popular directo, los 11 concejales del Honorable Concejo Municipal de Potosí ejercerán sus funciones por un período de cinco años, comprendido entre las gestiones 2026 y 2031.</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6" v-for="concejal in concejales" :key="concejal.id">
        <div class="card h-100 border-0 shadow-sm">
          <div class="row g-0 h-100">
            <div class="col-md-4">
              <img :src="concejal.imagen" :alt="concejal.nombre"
               class="img-fluid h-100" style="object-fit: cover; min-height: 200px;">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title fw-bold">{{ concejal.nombre }}</h5>
                <h6 class="text-danger mb-2">{{ concejal.cargo }}</h6>
                <p class="card-text text-muted small">{{ concejal.descripcion }}</p>
                <b><p>Responsable de distritos:</p></b>
                <p class="card-text text-muted small">{{ concejal.distritos }}</p>
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
  </div>
</template>

<script>
export default {
    name: 'Concejales',
    data() {
        return {
            concejales: [
                {
                    id: 1,
                    nombre: 'Abog. Germán Antonio Vidaurre Villanueva',
                    cargo: 'Concejal - Presidente',
                    imagen: '/images/concejales/Antonio_Vidaurre.png',
                    descripcion: 'Experiencia profesional en gestión municipal, 25 años de experiencia en medios de comunicación, 23 años los desempeñó en Radio Fides Potosí (Bolivia); Voz reconocida en el ámbito informativo local.',
                    distritos: 'No tiene asignado',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
                {
                    id: 2,
                    nombre: 'Claudio German Clemente Vedia',
                    cargo: 'Concejal - Vicepresidente',
                    imagen: '/images/concejales/Claudio_Clemente.png',
                    descripcion: 'A detallar',
                    distritos: 'No tiene asignado',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
                {
                    id: 3,
                    nombre: 'Maria del Carmen Michel Araujo',
                    cargo: 'Concejal - Secretaria',
                    imagen: '/images/concejales/Carmen_Michel.png',
                    descripcion: 'A detallar',
                    distritos: 'No tiene asignado',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
                {
                    id: 4,
                    nombre: 'Ing. Manuel Alejandro Calizaya Limachi',
                    cargo: 'Concejal',
                    imagen: '/images/concejales/Manuel_Calizaya.png',
                    descripcion: 'A detallar',
                    distritos: '9 - 12',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
                {
                    id: 5,
                    nombre: 'Ariel Jiménez Gómez',
                    cargo: 'Concejal',
                    imagen: '/images/concejales/Ariel_Jimenez.png',
                    descripcion: 'A detallar',
                    distritos: '1 - 17 - 19',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
                {
                    id: 6,
                    nombre: 'Ing. Clementina Aroni Mamani',
                    cargo: 'Concejal',
                    imagen: '/images/concejales/Clementina_Aroni.png',
                    descripcion: 'A detallar',
                    distritos: '5 - 16',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
                {
                    id: 7,
                    nombre: 'Ing. Guido Armando Cruz Mora',
                    cargo: 'Concejal',
                    imagen: '/images/concejales/Guido_Cruz.png',
                    descripcion: 'A detallar',
                    distritos: '8 - 20 - 21',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
                {
                    id: 8,
                    nombre: 'Jacqueline Lourdes Gutierrez Carrasco',
                    cargo: 'Concejal',
                    imagen: '/images/concejales/Jacqueline_Gutierrez.png',
                    descripcion: 'A detallar',
                    distritos: '6 - 7 - 11',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
                {
                    id: 9,
                    nombre: 'Christie Mónica Chacón Duran',
                    cargo: 'Concejal',
                    imagen: '/images/concejales/Monica_Chacon.png',
                    descripcion: 'A detallar',
                    distritos: '3 - 10',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
                {
                    id: 10,
                    nombre: 'Eddy Fernandez Fuertes',
                    cargo: 'Concejal',
                    imagen: '/images/concejales/Eddy_Fernandez.png',
                    descripcion: 'A detallar',
                    distritos: '13 - 14 -18',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
                {
                    id: 11,
                    nombre: 'A designar',
                    cargo: 'Concejal',
                    imagen: '/images/concejales/A_designar.png',
                    descripcion: 'A detallar',
                    distritos: 'No asignado',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
            ]
        };
    }
};
</script>

<style scoped>
/* ========== CONTENEDOR PRINCIPAL ========== */
.principal {
  margin: 0 auto;
  padding: 1rem;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
}
.concejales-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 1rem;
  color: red;
}
.concejales-titulo {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  color: #cc0000;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
}
.concejales-subtitulo {
  font-size: 1.2rem;
  margin: 0;
  color: #555;
}
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12) !important;
}

.card .col-md-4 {
    padding: 0;
}

a {
    transition: transform 0.3s ease;
    display: inline-block;
}

a:hover {
    transform: scale(1.2);
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
  font-size: 1rem;
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
</style>
