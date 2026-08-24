<template>
  <div class="container">
    <div class="Resoluciones-header">
      <h2 class="Resoluciones-titulo">Resoluciones Municipales</h2>
      <p class="Resoluciones-subtitulo">
        Las resoluciones municipales son los actos administrativos mediante los cuales el Honorable Concejo Municipal de Potosí toma decisiones concretas para la gestión del municipio, autorizando acciones específicas o aprobando contratos y convenios que permiten la ejecución de proyectos y el funcionamiento de la administración local
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
                    numero: 'R-120/2025',
                    estado: 'Vigente',
                    fecha: '23/01/2025',
                    titulo: 'En el marco de la Ley N° 482 de Gobiernos Autónomos Municipales, aprobar la suscripción de CONTRATO DE ARRENDAMIENTO AMBIENTE DE INGRESO PRINCIPAL NUEVA TERMINAL DE BUSES POTOSİ (PARA TIENDA Y OTROS), con la Sra. Silvia Alejandra Fernández Mollo',
                    resumen: 'EI Órgano Ejecutivo Municipal quedara encargado del estricto cumplimiento del referido contrato de arrendamiento.',
                },
                {
                    numero: 'R-119/2025',
                    estado: 'Vigente',
                    fecha: '23/01/2025',
                    titulo: 'Autorizar a la Ejecutiva Municipal del Gobierno Autónomo Municipal de Potosí, la suscripción del contrato administrativo para la prestación del servicio de seguro N°002/2025 del proceso de contratación en la modalidad licitación publica',
                    resumen: 'La comisión de recepción, bajo su exclusiva responsabilidad, deberá verificar la CONTRATACIÓN DE SEGURO PARA TODO TIPO DE RIESGO DE DAÑOS A LA PROPIEDAD INMUEBLES Y ACTIVOS DEL G.A.M.P. 2025-2026...',
                },
                {
                    numero: 'R-118/2025',
                    estado: 'Vigente',
                    fecha: '23/01/2025',
                    titulo: 'AUTORIZAR a la Máxima Autoridad Ejecutiva, suscribir el CONTRATO ADMINISTRATIVO DE OBRA N° 061/2022',
                    resumen: 'Queda claramente establecido que en función a los principios de "buena fe" y "responsabilidad" que debe existir en la actuación de los servidores públicos el proceso de contratación para la...',
                },
                {
                    numero: 'R-117/2025',
                    estado: 'Vigente',
                    fecha: '23/01/2025',
                    titulo: 'Autorizar la suscripción del contrato administrativo de obra N° 063/2025',
                    resumen: 'Queda claramente establecido que en función a los principios de "buena fe" y "responsabilidad" que debe existir en la actuación de los servidores públicos el proceso de contratación para la...',
                },
                {
                    numero: 'R-113/2025',
                    estado: 'Derogada',
                    fecha: '17/12/2025',
                    titulo: 'En aplicación a la normativa vigente y conforme a determinación del Concejo Municipal, se autoriza el viaje de la Alcaldesa Municipal Interina LESLY JANETH FLORES MOLLO, a la ciudad de La Paz, para participar en la reunión que tendrá lugar el día jueves 18 de diciembre en Despacho del Ministerio de Hidrocarburos y Energías; a objeto de poder suscribir el convenio "luminarias para la Alcaldía de Potosí"',
                    resumen: 'Queda encargado del cumplimiento de la presente Resolución Municipal el Ejecutivo Municipal a través de las instancias respetivas del Gobierno Autónomo Municipal de Potosí en toda su estructura orgánica',
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
.Resoluciones-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 1.5rem;
}

.Resoluciones-titulo {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  letter-spacing: 1px;
  color: #cc0000;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
}

/* ========== SUBTÍTULO ========== */
.Resoluciones-subtitulo {
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
