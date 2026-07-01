<template>
    <div class="inicio-page">
        <!-- Carrusel de imágenes -->
        <div id="carouselConcejo" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button v-for="(slide, index) in carruselImagenes" :key="index"
                        type="button" data-bs-target="#carouselConcejo"
                        :data-bs-slide-to="index"
                        :class="{ active: index === 0 }"
                        :aria-label="'Slide ' + (index + 1)"></button>
            </div>
            <div class="carousel-inner">
                <div v-for="(slide, index) in carruselImagenes" :key="index"
                     class="carousel-item" :class="{ active: index === 0 }">
                    <img :src="slide.imagen" class="d-block w-100" :alt="slide.titulo"
                         style="height: 550px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-4 rounded">
                        <h3 class="fw-bold">{{ slide.titulo }}</h3>
                        <p>{{ slide.descripcion }}</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselConcejo"
                    data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselConcejo"
                    data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>

        <!-- Información del Concejo -->
        <div class="container py-5">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-4 fw-bold text-primary">
                        <i class="fas fa-university me-3"></i>Concejo Municipal de Potosí
                    </h2>
                    <p class="lead text-muted">
                        Representando los intereses de todos los potosinos
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-handshake text-primary" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">Misión</h5>
                            <p class="card-text text-muted">
                                Velar por el desarrollo integral de Potosí a través de la legislación
                                y fiscalización de políticas públicas.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-eye text-success" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">Visión</h5>
                            <p class="card-text text-muted">
                                Ser un Concejo Municipal transparente, participativo y eficiente,
                                referente en gestión legislativa municipal.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-gem text-warning" style="font-size: 3rem;"></i>
                            <h5 class="card-title mt-3">Valores</h5>
                            <ul class="list-unstyled text-muted">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Transparencia</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Participación</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Ética</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Compromiso</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="row mt-5 g-3">
                <div class="col-md-3 col-6">
                    <div class="bg-primary text-white p-3 rounded text-center">
                        <h3 class="display-6">11</h3>
                        <p class="mb-0">Concejales</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bg-success text-white p-3 rounded text-center">
                        <h3 class="display-6">10</h3>
                        <p class="mb-0">Comisiones</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bg-info text-white p-3 rounded text-center">
                        <h3 class="display-6">150+</h3>
                        <p class="mb-0">Leyes Aprobadas</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bg-warning text-white p-3 rounded text-center">
                        <h3 class="display-6">200+</h3>
                        <p class="mb-0">Sesiones Realizadas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Inicio',
    data() {
        return {
            carruselImagenes: [
                {
                    imagen: '/images/carousel/concejo1.jpg',
                    titulo: 'Bienvenidos al Concejo Municipal de Potosí',
                    descripcion: 'Trabajando por el desarrollo y bienestar de todos los potosinos'
                },
                {
                    imagen: '/images/carousel/concejo2.jpg',
                    titulo: 'Sesiones Plenarias',
                    descripcion: 'Participa activamente en las sesiones del Concejo Municipal'
                },
                {
                    imagen: '/images/carousel/concejo3.jpg',
                    titulo: 'Compromiso con la Ciudadanía',
                    descripcion: 'Escuchamos y atendemos las necesidades de nuestra comunidad'
                }
            ]
        };
    }
};
</script>

<style scoped>
.carousel-item {
    height: 550px;
}

.carousel-caption {
    bottom: 100px;
    background: rgba(0, 0, 0, 0.6);
    padding: 30px;
    border-radius: 12px;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15) !important;
}
</style>
