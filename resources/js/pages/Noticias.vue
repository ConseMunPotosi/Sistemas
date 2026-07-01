<template>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-primary">
                <i class="fas fa-newspaper me-3"></i>Noticias
            </h2>
            <p class="lead text-muted">Últimas noticias del Concejo Municipal de Potosí</p>
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
