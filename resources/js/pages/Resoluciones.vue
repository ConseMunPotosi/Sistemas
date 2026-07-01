<template>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-primary">
                <i class="fas fa-gavel me-3"></i>LResoluciones Promulgadas
            </h2>
            <p class="lead text-muted">Normas legales aprobadas por el Concejo Municipal</p>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="input-group mb-4">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" v-model="busqueda"
                           placeholder="Buscar leyes por título, número o año...">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Número</th>
                                <th>Título</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ley in leyesFiltradas" :key="ley.id">
                                <td>{{ ley.id }}</td>
                                <td><span class="badge bg-primary">{{ ley.numero }}</span></td>
                                <td>{{ ley.titulo }}</td>
                                <td>{{ ley.fecha }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Leyes',
    data() {
        return {
            busqueda: '',
            leyes: [
                {
                    id: 1,
                    numero: 'L-001/2024',
                    titulo: 'Ley de Promoción del Desarrollo Económico Local',
                    fecha: '15/01/2024',
                    tipo: 'Ley'
                },
                {
                    id: 2,
                    numero: 'L-002/2024',
                    titulo: 'Ley de Protección del Patrimonio Cultural',
                    fecha: '28/02/2024',
                    tipo: 'Ley'
                },
                // Agregar más leyes...
            ]
        };
    },
    computed: {
        leyesFiltradas() {
            if (!this.busqueda) return this.leyes;
            return this.leyes.filter(ley =>
                ley.titulo.toLowerCase().includes(this.busqueda.toLowerCase()) ||
                ley.numero.toLowerCase().includes(this.busqueda.toLowerCase())
            );
        }
    }
};
</script>
