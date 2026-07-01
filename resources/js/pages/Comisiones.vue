<template>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-primary">
                <i class="fas fa-layer-group me-3"></i>Comisiones del Concejo
            </h2>
            <p class="lead text-muted">Las 10 comisiones que trabajan por el desarrollo de Potosí</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4" v-for="comision in comisiones" :key="comision.id">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i :class="comision.icono" class="text-primary" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-3 fw-bold">{{ comision.nombre }}</h5>
                        <p class="card-text text-muted">{{ comision.descripcion }}</p>
                        <span class="badge bg-primary">{{ comision.integrantes }} miembros</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Comisiones',
    data() {
        return {
            comisiones: [
                {
                    id: 1,
                    nombre: 'Comisión de Desarrollo Urbano',
                    descripcion: 'Planificación y control del crecimiento urbano de la ciudad. Vela por el ordenamiento territorial.',
                    icono: 'fas fa-city',
                    integrantes: 5
                },
                {
                    id: 2,
                    nombre: 'Comisión de Educación y Cultura',
                    descripcion: 'Fomenta la educación, cultura y patrimonio histórico de Potosí.',
                    icono: 'fas fa-graduation-cap',
                    integrantes: 4
                },
                {
                    id: 3,
                    nombre: 'Comisión de Salud y Medio Ambiente',
                    descripcion: 'Promueve políticas de salud pública y protección del medio ambiente.',
                    icono: 'fas fa-heart',
                    integrantes: 4
                },
                {
                    id: 4,
                    nombre: 'Comisión de Desarrollo Económico',
                    descripcion: 'Impulsa el desarrollo económico y el empleo en la ciudad.',
                    icono: 'fas fa-chart-line',
                    integrantes: 4
                },
                {
                    id: 5,
                    nombre: 'Comisión de Infraestructura',
                    descripcion: 'Supervisa proyectos de infraestructura y obras públicas.',
                    icono: 'fas fa-road',
                    integrantes: 5
                },
                {
                    id: 6,
                    nombre: 'Comisión de Derechos Humanos',
                    descripcion: 'Vela por el respeto de los derechos humanos en la ciudad.',
                    icono: 'fas fa-hand-holding-heart',
                    integrantes: 3
                },
                {
                    id: 7,
                    nombre: 'Comisión de Juventud y Deportes',
                    descripcion: 'Promueve la participación juvenil y el desarrollo deportivo.',
                    icono: 'fas fa-running',
                    integrantes: 3
                },
                {
                    id: 8,
                    nombre: 'Comisión de Transparencia',
                    descripcion: 'Garantiza la transparencia y rendición de cuentas.',
                    icono: 'fas fa-shield-alt',
                    integrantes: 3
                },
                {
                    id: 9,
                    nombre: 'Comisión de Desarrollo Social',
                    descripcion: 'Atiende las necesidades de los grupos más vulnerables.',
                    icono: 'fas fa-users',
                    integrantes: 4
                },
                {
                    id: 10,
                    nombre: 'Comisión de Relaciones Internacionales',
                    descripcion: 'Fomenta hermanamientos y cooperación internacional.',
                    icono: 'fas fa-globe-americas',
                    integrantes: 3
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
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
}

.card i {
    transition: transform 0.3s ease;
}

.card:hover i {
    transform: scale(1.1);
}
</style>
