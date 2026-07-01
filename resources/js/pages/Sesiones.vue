<template>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-primary">
                <i class="fas fa-video me-3"></i>Sesiones del Concejo
            </h2>
            <p class="lead text-muted">Filmaciones de las sesiones del Concejo Municipal</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6" v-for="sesion in sesiones" :key="sesion.id">
                <div class="card border-0 shadow-sm">
                    <div class="ratio ratio-16x9">
                        <iframe :src="sesion.videoUrl" :title="sesion.titulo"
                                frameborder="0" allowfullscreen></iframe>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ sesion.titulo }}</h5>
                        <p class="card-text text-muted">
                            <i class="fas fa-calendar-alt me-2"></i>{{ sesion.fecha }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-clock me-2"></i>{{ sesion.duracion }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Sesiones',
    data() {
        return {
            sesiones: [
                {
                    id: 1,
                    titulo: 'Sesión Ordinaria - 15 de junio 2026',
                    fecha: '15/06/2026',
                    duracion: '2h 15min',
                    videoUrl: 'https://www.youtube.com/embed/VIDEO_ID1'
                },
                {
                    id: 2,
                    titulo: 'Sesión Extraordinaria - Presupuesto 2025',
                    fecha: '10/06/2026',
                    duracion: '3h 30min',
                    videoUrl: 'https://www.youtube.com/embed/VIDEO_ID2'
                },
                // Agregar más sesiones...
            ]
        };
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
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12) !important;
}
</style>
