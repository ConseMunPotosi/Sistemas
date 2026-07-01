<template>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-primary">
                <i class="fas fa-user-tie me-3"></i>Directiva del Concejo
            </h2>
            <p class="lead text-muted">Equipo directivo que lidera el Concejo Municipal de Potosí</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6" v-for="miembro in directiva" :key="miembro.id">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="position-relative">
                        <img :src="miembro.imagen" class="card-img-top" :alt="miembro.nombre"
                             style="height: 300px; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 end-0 bg-gradient-to-top p-3">
                            <span class="badge bg-primary fs-6 px-3 py-2">{{ miembro.cargo }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ miembro.nombre }}</h5>
                        <p class="card-text text-muted">{{ miembro.descripcion }}</p>
                        <div class="mt-3">
                            <a :href="miembro.facebook" class="btn btn-outline-primary btn-sm me-2" v-if="miembro.facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a :href="miembro.twitter" class="btn btn-outline-info btn-sm me-2" v-if="miembro.twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a :href="miembro.linkedin" class="btn btn-outline-primary btn-sm" v-if="miembro.linkedin">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Gestión {{ miembro.gestion }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Directiva',
    data() {
        return {
            directiva: [
                {
                    id: 1,
                    nombre: 'Lic. Juan Carlos Pérez',
                    cargo: 'Presidente del Concejo',
                    imagen: '/images/directiva/presidente.jpg',
                    descripcion: 'Responsable de dirigir las sesiones plenarias y representar al Concejo Municipal. Cuenta con más de 15 años de experiencia en gestión municipal.',
                    facebook: '#',
                    twitter: '#',
                    linkedin: '#',
                    gestion: '2024-2026'
                },
                {
                    id: 2,
                    nombre: 'Dra. María Fernanda López',
                    cargo: 'Vicepresidenta',
                    imagen: '/images/directiva/vicepresidente.jpg',
                    descripcion: 'Coordina las comisiones y apoya al Presidente en sus funciones. Especialista en derecho administrativo y políticas públicas.',
                    facebook: '#',
                    twitter: '#',
                    linkedin: '#',
                    gestion: '2024-2026'
                },
                {
                    id: 3,
                    nombre: 'Ing. Carlos Alberto Flores',
                    cargo: 'Secretario del Concejo',
                    imagen: '/images/directiva/secretario.jpg',
                    descripcion: 'Encargado de la gestión documental y actas de las sesiones. Ingeniero con amplia experiencia en administración pública.',
                    facebook: '#',
                    twitter: '#',
                    linkedin: '#',
                    gestion: '2024-2026'
                }
            ]
        };
    }
};
</script>

<style scoped>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.bg-gradient-to-top {
    background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
}

.card-img-top {
    height: 300px;
    object-fit: cover;
}

.btn-outline-primary:hover,
.btn-outline-info:hover {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}
</style>
