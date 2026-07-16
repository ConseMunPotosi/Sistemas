<template>
    <div class="principal">
        <div class="sesion-header">
            <h2 class="sesion-titulo"><i class="fas fa-video me-3"></i>Sesiones del Concejo</h2>
            <p class="sesion-subtitulo">Las grabaciones de las sesiones del Concejo Municipal de Potosí, se realizan de manera sistemática y permanente, permitiendo documentar íntegramente el desarrollo de cada sesión ordinaria y extraordinaria, asegurando que la ciudadanía pueda conocer en detalle las discusiones, los argumentos de los concejales, las votaciones y las resoluciones adoptadas sobre los asuntos de interés público.</p>
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
                    titulo: 'Sesión Ordinaria 023',
                    fecha: '14/07/2026',
                    duracion: '2h 15min',
                    videoUrl: 'https://www.youtube.com/embed/VIDEO_ID1'
                },
                {
                    id: 2,
                    titulo: 'Sesión Extraordinaria 022',
                    fecha: '09/07/2026',
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
.principal {
  margin: 0 auto;
  padding: 1rem;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
}
.sesion-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 1rem;
  color: red;
}
.sesion-titulo {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  color: #cc0000;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
}
.sesion-subtitulo {
  font-size: 1.2rem;
  margin: 0;
  color: #555;
  text-align: justify;
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
</style>
