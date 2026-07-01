<template>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-primary">
                <i class="fas fa-users me-3"></i>Concejales de Potosí
            </h2>
            <p class="lead text-muted">Los 11 concejales que representan a nuestra ciudad</p>
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
                                <h6 class="text-primary mb-2">{{ concejal.cargo }}</h6>
                                <p class="card-text text-muted small">{{ concejal.descripcion }}</p>
                                <div class="mt-2">
                                    <a :href="concejal.facebook" class="text-primary me-2" v-if="concejal.facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a :href="concejal.twitter" class="text-info me-2" v-if="concejal.twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a :href="concejal.instagram" class="text-danger me-2" v-if="concejal.instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a :href="concejal.linkedin" class="text-primary" v-if="concejal.linkedin">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
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
                    nombre: 'Dra. Ana María Rodríguez',
                    cargo: 'Concejal - Oficialía Mayor',
                    imagen: '/images/concejales/concejal1.jpg',
                    descripcion: 'Abogada especializada en derechos humanos y políticas sociales. Promueve la participación ciudadana.',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#',
                    linkedin: '#'
                },
                // Agregar los 10 concejales restantes
                // ...
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
</style>
