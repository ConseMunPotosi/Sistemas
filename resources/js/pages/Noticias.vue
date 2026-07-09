<template>
    <div class="container py-5">
        <div class="noticias-header">
            <h2 class="noticias-titulo">
            Noticias
            </h2>
            <p class="noticias-subtitulo">El Concejo Municipal de Potosí ya cuenta con un espacio digital dinámico y confiable, cuyo objetivo central es mantener a la ciudadanía informada sobre la actividad legislativa y de fiscalización que se desarrolla en el municipio. </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4" v-for="noticia in noticias" :key="noticia.id">
                <div class="card h-100 border-0 shadow-sm">
                    <img :src="noticia.imagen" class="card-img-top" :alt="noticia.titulo"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary">{{ noticia.categoria }}</span>
                            <small class="text-muted">{{ noticia.fecha }}</small>
                        </div>
                        <h5 class="card-title fw-bold">{{ noticia.titulo }}</h5>
                        <p class="card-text text-muted">{{ noticia.resumen }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <button class="btn btn-outline-primary btn-sm" @click="verNoticia(noticia.id)">
                            Leer más <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Noticias',
    data() {
        return {
            noticias: [
                {
                    id: 1,
                    titulo: 'Concejo Municipal aprueba presupuesto 2025',
                    resumen: 'El Concejo Municipal aprobó el presupuesto para la gestión 2025 destinado a obras de infraestructura...',
                    fecha: '20/06/2026',
                    categoria: 'Presupuesto',
                    imagen: '/images/noticias/noticia1.jpg'
                },
                // Agregar más noticias...
            ]
        };
    },
    methods: {
        verNoticia(id) {
            // Redirigir a la página de detalle de noticia
            this.$router.push(`/noticia/${id}`);
        }
    }
};
</script>

<style scoped>
/* ========== CONTENEDOR PRINCIPAL ========== */
.container {
  margin: 0 auto;
  padding: 1rem;
  min-height: 100vh;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  width: 100%;
  max-width: 100%;
  display: block;
}

/* ========== HEADER ========== */
.noticias-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 1.5rem;
}

.noticias-titulo {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  letter-spacing: 1px;
  color: #cc0000;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
}

/* ========== SUBTÍTULO ========== */
.noticias-subtitulo {
  font-size: 1.1rem;
  color: #000000 !important;
  margin: 0;
  max-width: 90%;
  margin-left: auto;
  margin-right: auto;
  text-shadow: 1px 1px 4px rgba(255, 255, 255, 0.8);
  text-align: justify;
  text-justify: inter-word;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
}

.card-img-top {
    transition: transform 0.5s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}
</style>
