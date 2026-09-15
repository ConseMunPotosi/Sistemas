<template>
  <div class="principal">
    <div class="concejales-header">
      <h2 class="concejales-titulo">
        Miembros del Honorable Concejo Municipal de Potosí
      </h2>

      <p class="concejales-subtitulo">
        Elegidos por voto popular directo, los concejales del Honorable Concejo
        Municipal de Potosí ejercerán sus funciones por un período de cinco
        años, comprendido entre las gestiones 2026 y 2031.
      </p>
    </div>

    <!-- Cargando -->
    <div v-if="cargando" class="text-center py-5">
      <div class="spinner-border text-danger" role="status"></div>
      <p class="mt-3 text-muted">
        Cargando información de los concejales...
      </p>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="alert alert-danger text-center">
      {{ error }}
    </div>

    <!-- Sin concejales -->
    <div v-else-if="concejales.length === 0" class="text-center py-5">
      <p class="text-muted">
        No existen concejales activos registrados.
      </p>
    </div>

    <!-- Concejales -->
    <div v-else class="row g-4">
      <div
        class="col-lg-4 col-md-6"
        v-for="concejal in concejales"
        :key="concejal.id_concejal"
      >
        <div class="card h-100 border-0 shadow-sm">
          <div class="row g-0 h-100">

            <!-- Fotografía -->
            <div class="col-md-4">
              <img
                :src="getImagenUrl(concejal.imagen)"
                :alt="concejal.nombre"
                class="img-fluid h-100"
                style="object-fit: cover; min-height: 200px;"
                @error="imagenError"
              >
            </div>

            <!-- Información -->
            <div class="col-md-8">
              <div class="card-body">

                <h5 class="card-title fw-bold">
                  {{ concejal.nombre }}
                </h5>

                <h6 class="text-danger mb-2">
                  {{ concejal.cargo || 'Concejal' }}
                </h6>

                <p
                  v-if="concejal.descripcion"
                  class="card-text text-muted small"
                >
                  {{ concejal.descripcion }}
                </p>

                <p v-else class="card-text text-muted small">
                  Información del concejal.
                </p>

                <b>
                  <p>Responsable de distritos:</p>
                </b>

                <p class="card-text text-muted small">
                  {{ concejal.distritos || 'No asignado' }}
                </p>

                <!-- Redes sociales institucionales -->
                <div class="social-icons-group">

                  <!-- YouTube -->
                  <button
                    class="social-icon youtube"
                    aria-label="Youtube"
                    @click="irARedSocial(
                      'https://www.youtube.com/@ConcejoMunicipalPotosi',
                      'YouTube'
                    )"
                  >
                    <i class="bi bi-youtube"></i>
                  </button>

                  <!-- Facebook -->
                  <button
                    class="social-icon facebook"
                    aria-label="Facebook"
                    @click="irARedSocial(
                      'https://www.facebook.com/profile.php?id=100068918032041',
                      'Facebook'
                    )"
                  >
                    <i class="bi bi-facebook"></i>
                  </button>

                  <!-- Instagram -->
                  <button
                    class="social-icon instagram"
                    aria-label="Instagram"
                    @click="irARedSocial(
                      'https://www.instagram.com/concejomunicipalpotosi',
                      'Instagram'
                    )"
                  >
                    <i class="bi bi-instagram"></i>
                  </button>

                  <!-- Twitter/X -->
                  <button
                    class="social-icon twitter-x"
                    aria-label="Twitter"
                    @click="irARedSocial(
                      'https://twitter.com/ConcejoPotosi',
                      'Twitter'
                    )"
                  >
                    <i class="bi bi-twitter-x"></i>
                  </button>

                  <!-- WhatsApp -->
                  <button
                    class="social-icon whatsapp"
                    aria-label="WhatsApp"
                    @click="irARedSocial(
                      'https://wa.me/59170000000',
                      'WhatsApp'
                    )"
                  >
                    <i class="bi bi-whatsapp"></i>
                  </button>

                  <!-- TikTok -->
                  <button
                    class="social-icon tiktok"
                    aria-label="Tiktok"
                    @click="irARedSocial(
                      'https://www.tiktok.com/@concejomunicipaldepotosi',
                      'TikTok'
                    )"
                  >
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
import axios from 'axios';

export default {
  name: 'Concejales',

  data() {
    return {
      concejales: [],
      cargando: false,
      error: ''
    };
  },

  mounted() {
    this.cargarConcejales();
  },

  methods: {

    async cargarConcejales() {
      this.cargando = true;
      this.error = '';

      try {
        const response = await axios.get('/api/public/concejales');

        if (response.data.success) {
          this.concejales = response.data.data || [];
        } else {
          this.concejales = [];
          this.error = 'No se pudo cargar la información de los concejales.';
        }

      } catch (error) {
        console.error(
          'Error al cargar concejales públicos:',
          error
        );

        this.concejales = [];

        this.error =
          'No se pudo conectar con el servidor para cargar los concejales.';
      } finally {
        this.cargando = false;
      }
    },

    getImagenUrl(imagen) {
      if (!imagen) {
        return '/images/concejales/A_designar.png';
      }

      if (
        imagen.startsWith('http://') ||
        imagen.startsWith('https://')
      ) {
        return imagen;
      }

      return imagen.startsWith('/')
        ? imagen
        : `/${imagen}`;
    },

    imagenError(event) {
      event.target.src =
        '/images/concejales/A_designar.png';
    },

    irARedSocial(url, redSocial) {
      window.open(
        url,
        '_blank',
        'noopener,noreferrer'
      );
    }
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
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;

  border-radius: 12px;
  overflow: hidden;
}

.card:hover {
  transform: translateY(-5px);

  box-shadow:
    0 10px 30px rgba(0, 0, 0, 0.12) !important;
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

.social-icon.facebook:hover i {
  color: #1877f2;
}

.social-icon.youtube:hover i {
  color: #ff0000;
}

.social-icon.instagram:hover i {
  color: #e4405f;
}

.social-icon.twitter-x:hover i {
  color: #1da1f2;
}

.social-icon.whatsapp:hover i {
  color: #25d366;
}

.social-icon.tiktok:hover i {
  color: #00f2ea;
}
</style>
