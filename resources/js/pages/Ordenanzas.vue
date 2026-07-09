<template>
  <div class="container">
    <div class="ordenanzas-header">
      <h2 class="ordenanzas-titulo">Ordenazas Municipales</h2>
      <p class="ordenanzas-subtitulo">
        Normas jurídicas de carácter general y obligatorio que emanan del Concejo Municipal, en ejercicio de su potestad normativa y dentro del marco de la autonomía municipal reconocida por la Ley de Municipalidades . Estas disposiciones legales constituyen el instrumento principal a través del cual el Gobierno Municipal regula, autoriza y fiscaliza las diferentes materias de su competencia, con la finalidad de satisfacer las necesidades colectivas y garantizar el bienestar social y material de los habitantes del municipio.
      </p>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="input-group mb-4">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text" class="form-control" v-model="busqueda"
                 placeholder="Buscar Resoluciones por título, número o año...">
        </div>

        <div class="table-container">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr class="fila-encabezado"> <!-- Clase para el encabezado rojo -->
                  <th>#</th>
                  <th>Número</th>
                  <th>Estado</th>
                  <th>Fecha Promulgación</th>
                  <th>Título</th>
                  <th>Resumen</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="ley in leyesFiltradas" :key="ley.id">
                  <td>{{ ley.id }}</td>
                  <td>{{ ley.numero }}</td>
                  <td>{{ ley.estado }}</td>
                  <td>{{ ley.fecha }}</td>
                  <td class="texto-justificado">{{ ley.titulo }}</td>
                  <td class="texto-justificado">{{ ley.resumen }}</td>
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
                    numero: 'R-001/2024',
                    estado: 'Vigente',
                    fecha: '15/01/2024',
                    titulo: 'Ley de Promoción del Desarrollo Económico Local',
                    resumen: 'Ley que promueve el desarrollo económico local',
                },
                {
                    id: 2,
                    numero: 'R-002/2024',
                    estado: 'Vigente',
                    fecha: '28/02/2024',
                    titulo: 'Ley de Protección del Patrimonio Cultural',
                    resumen: 'Ley que protege el patrimonio cultural del municipio',
                },
                {
                    id: 3,
                    numero: 'R-003/2024',
                    estado: 'En revisión',
                    fecha: '10/03/2024',
                    titulo: 'Ley de Ordenamiento Territorial',
                    resumen: 'Ley que regula el ordenamiento territorial urbano',
                },
                {
                    id: 4,
                    numero: 'R-004/2024',
                    estado: 'Vigente',
                    fecha: '22/04/2024',
                    titulo: 'Ley de Gestión de Riesgos',
                    resumen: 'Ley para la gestión de riesgos y desastres',
                },
                {
                    id: 5,
                    numero: 'R-005/2024',
                    estado: 'Derogada',
                    fecha: '05/05/2024',
                    titulo: 'Ley de Fomento al Turismo',
                    resumen: 'Ley que fomenta el turismo sostenible',
                }
            ]
        };
    },
    computed: {
        leyesFiltradas() {
            if (!this.busqueda) return this.leyes;
            return this.leyes.filter(ley =>
                ley.titulo.toLowerCase().includes(this.busqueda.toLowerCase()) ||
                ley.numero.toLowerCase().includes(this.busqueda.toLowerCase()) ||
                ley.estado.toLowerCase().includes(this.busqueda.toLowerCase())
            );
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
.ordenanzas-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 1.5rem;
}

.ordenanzas-titulo {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  letter-spacing: 1px;
  color: #cc0000;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
}

/* ========== SUBTÍTULO ========== */
.ordenanzas-subtitulo {
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

/* ========== CONTENEDOR DE LA TABLA ========== */
.table-container {
  max-width: 95%;
  margin: 0 auto;
  padding: 0 1rem;
}

/* ========== TABLA ========== */
.table-responsive {
  background-color: rgba(255, 255, 255, 0.92);
  border-radius: 12px;
  padding: 0.5rem;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
  overflow-x: auto;
}

.table {
  margin-bottom: 0;
  background-color: transparent;
}

/* ===== ENCABEZADO DE LA TABLA EN ROJO ===== */
.fila-encabezado {
  background-color: #cc0000 !important; /* Rojo */
  color: white !important; /* Texto blanco para contraste */
}

.fila-encabezado th {
  background-color: #cc0000 !important; /* Rojo */
  color: white !important; /* Texto blanco */
  font-weight: 700;
  text-align: center; /* Centrar texto */
  padding: 14px 15px;
  border-bottom: 2px solid #990000;
  font-size: 0.95rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Cambiar color al hacer hover sobre el encabezado */
.fila-encabezado:hover th {
  background-color: #b30000 !important; /* Rojo más oscuro al hover */
}

/* ===== CONTENIDO DE LA TABLA CENTRADO ===== */
.table tbody td {
  padding: 12px 15px;
  vertical-align: middle;
  text-align: center; /* 🔴 Centra TODO el contenido de las celdas */
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.table tbody tr:hover {
  background-color: rgba(204, 0, 0, 0.05);
}

.texto-justificado {
  text-align: justify;
  text-justify: inter-word;
  word-break: break-word;
  max-width: 300px;
}

/* ===== ESTILO PARA EL ESTADO (Badges) ===== */
.table tbody td:nth-child(3) {
  font-weight: 600;
}

/* Estado: Vigente → Verde */
.table tbody tr td:nth-child(3):contains('Vigente') {
  color: #198754;
}

/* Estado: En revisión → Naranja */
.table tbody tr td:nth-child(3):contains('En revisión') {
  color: #fd7e14;
}

/* Estado: Derogada → Rojo */
.table tbody tr td:nth-child(3):contains('Derogada') {
  color: #dc3545;
}

/* ========== BARRA DE BÚSQUEDA ========== */
.input-group {
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.input-group-text {
  background-color: white;
  border-right: none;
}

.input-group .form-control {
  border-left: none;
}

.input-group .form-control:focus {
  border-color: #cc0000;
  box-shadow: 0 0 0 0.25rem rgba(204, 0, 0, 0.25);
}

/* ========== BOTONES ========== */
.btn-outline-primary {
  border-color: #cc0000;
  color: #cc0000;
}

.btn-outline-primary:hover {
  background-color: #cc0000;
  color: white;
  border-color: #cc0000;
}

.btn-outline-success {
  border-color: #198754;
  color: #198754;
}

.btn-outline-success:hover {
  background-color: #198754;
  color: white;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
  .container {
    padding: 1rem;
  }

  .Leyes-titulo {
    font-size: 1.8rem;
  }

  .Leyes-subtitulo {
    font-size: 0.95rem;
    padding: 0 0.5rem;
  }

  .table-container {
    padding: 0 0.5rem;
  }

  .table-responsive {
    padding: 0.25rem;
  }

  .table thead th,
  .table tbody td {
    padding: 8px 10px;
    font-size: 0.8rem;
  }

  .fila-encabezado th {
    font-size: 0.75rem;
    padding: 10px 6px;
  }
}
</style>
