<template>
  <div class="normas-page">
    <div class="normas-header">
      <h2 class="normas-titulo">{{ title }}</h2>
      <p class="normas-subtitulo">{{ subtitle }}</p>
    </div>

    <div class="search-box">
      <span class="search-icon"><i class="fas fa-search"></i></span>
      <input
        v-model="busqueda"
        type="search"
        class="form-control"
        :placeholder="`Buscar ${title.toLowerCase()} por título, número, gestión o descripción...`"
        @keyup.enter="cargarNormas"
      />
      <button v-if="busqueda" class="btn-clear" type="button" @click="limpiarBusqueda">
        <i class="fas fa-times"></i>
      </button>
      <select v-model="gestionSeleccionada" class="filtro-gestion">
      <option value="">Todas las gestiones</option>

      <option
        v-for="gestion in gestionesDisponibles"
        :key="gestion"
        :value="gestion"
      >
        {{ gestion }}
      </option>
     </select>
    </div>

    <div v-if="loading" class="state-box">
      <i class="fas fa-spinner fa-spin"></i>
      <span>Cargando documentos...</span>
    </div>

    <div v-else-if="error" class="state-box state-error">
      <i class="fas fa-triangle-exclamation"></i>
      <span>{{ error }}</span>
      <button class="btn-retry" type="button" @click="cargarNormas">Reintentar</button>
    </div>

    <div v-else class="table-container">
      <div class="results-bar">
        <span><strong>{{ normasFiltradas.length }}</strong> documento(s) encontrado(s)</span>
        <button class="btn-refresh" type="button" @click="cargarNormas" title="Actualizar">
          <i class="fas fa-rotate-right"></i>
        </button>
      </div>

      <div v-if="normasFiltradas.length" class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr class="fila-encabezado">
              <th>Número</th>
              <th>Gestión</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th>Título</th>
              <th>Documento</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="norma in normasFiltradas" :key="norma.id_norma">
              <td class="numero">{{ formatNumero(norma) }}</td>
              <td>{{ norma.gestion || '-' }}</td>
              <td>
                <span class="status-badge" :class="estadoClass(norma.estado?.nombre_estado)">
                  {{ norma.estado?.nombre_estado || 'Sin estado' }}
                </span>
              </td>
              <td>{{ formatDate(norma.fecha_publicacion) }}</td>
              <td class="texto-justificado">
                <strong>{{ norma.titulo || 'Sin título' }}</strong>
                <div v-if="norma.descripcion" class="descripcion">
                  {{ norma.descripcion }}
                </div>
              </td>
              <td class="acciones">
                <template v-if="archivoPrincipal(norma)">
                  <a
                    class="btn btn-sm btn-outline-primary"
                    :href="getFileUrl(archivoPrincipal(norma).ruta_archivo)"
                    target="_blank"
                    rel="noopener noreferrer"
                    title="Ver documento"
                  >
                    <i class="fas fa-eye"></i>
                  </a>
                  <a
                    class="btn btn-sm btn-outline-success"
                    :href="getFileUrl(archivoPrincipal(norma).ruta_archivo)"
                    :download="archivoPrincipal(norma).nombre_archivo"
                    title="Descargar documento"
                  >
                    <i class="fas fa-download"></i>
                  </a>
                </template>
                <span v-else class="sin-archivo">Sin PDF</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="empty-box">
        <i class="fas fa-folder-open"></i>
        <h3>No se encontraron documentos</h3>
        <p>Prueba con otro término de búsqueda.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';

const props = defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, required: true },
  tipo: { type: String, required: true },
});

const normas = ref([]);
const busqueda = ref('');
const loading = ref(false);
const error = ref('');
const gestionSeleccionada = ref('')

const cargarNormas = async () => {
  loading.value = true;
  error.value = '';

  try {
    const response = await axios.get('/api/public/gaceta/normas', {
      params: {
        tipo: props.tipo,
      },
    });

    normas.value = response.data?.data || [];
  } catch (e) {
    console.error('Error al cargar archivos:', e);
    error.value = 'No fue posible cargar los documentos. Verifica que el servidor esté funcionando.';
    normas.value = [];
  } finally {
    loading.value = false;
  }
};

const normasFiltradas = computed(() => {
  const q = busqueda.value.trim().toLowerCase();

  return normas.value.filter((norma) => {
    const texto = [
      norma.numero,
      norma.gestion,
      norma.titulo,
      norma.descripcion,
      norma.estado?.nombre_estado,
    ]
      .filter(Boolean)
      .join(' ')
      .toLowerCase();

    const coincideTexto = !q || texto.includes(q);

    const coincideGestion =
      !gestionSeleccionada.value ||
      String(norma.gestion) === String(gestionSeleccionada.value);

    return coincideTexto && coincideGestion;
  });
});

const gestionesDisponibles = computed(() => {
  return [...new Set(
    normas.value
      .map(norma => norma.gestion)
      .filter(Boolean)
  )].sort((a, b) => b - a);
});

const formatNumero = (norma) => {
  if (norma.numero === null || norma.numero === undefined || norma.numero === '') return '-';
  return `${norma.numero}/${norma.gestion || ''}`.replace(/\/$/, '');
};

const formatDate = (date) => {
  if (!date) return '-';
  const d = new Date(date);
  if (Number.isNaN(d.getTime())) return '-';
  return d.toLocaleDateString('es-BO', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    timeZone: 'UTC',
  });
};

const estadoClass = (estado) => {
  const value = (estado || '').toLowerCase();
  if (value.includes('derog')) return 'derogada';
  if (value.includes('modific')) return 'modificada';
  return 'vigente';
};

const archivoPrincipal = (norma) => {
  return norma.archivos?.find((archivo) => archivo.estado !== false) || norma.archivos?.[0] || null;
};

const getFileUrl = (ruta) => {
  if (!ruta) return '#';
  return ruta.startsWith('/') ? ruta : `/${ruta}`;
};

onMounted(cargarNormas);
</script>

<style scoped>
.normas-page {
  min-height: 100vh;
  padding: 1rem;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
}

.normas-header {
  max-width: 1100px;
  margin: 0 auto 2rem;
  padding: 1.5rem;
  text-align: center;
}

.normas-titulo {
  margin: 0 0 .75rem;
  color: #cc0000;
  font-size: 2.4rem;
  font-weight: 800;
  letter-spacing: .5px;
}

.normas-subtitulo {
  margin: 0 auto;
  max-width: 1000px;
  color: #111;
  font-size: 1.05rem;
  line-height: 1.65;
  text-align: justify;
}

.search-box {
  position: relative;
  display: flex;
  align-items: center;
  max-width: 760px;
  margin: 0 auto 1.5rem;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 5px 18px rgba(0, 0, 0, .12);
  overflow: hidden;
}

.search-icon {
  padding: 0 0 0 18px;
  color: #777;
}

.search-box .form-control {
  border: 0;
  box-shadow: none;
  padding: 14px 45px 14px 12px;
}

.search-box .form-control:focus {
  box-shadow: none;
}

.filtro-gestion {
  border: 0;
  border-left: 1px solid #ddd;
  background: #fff;
  padding: 14px 38px 14px 14px;
  min-width: 190px;
  color: #444;
  font-size: 0.95rem;
  cursor: pointer;
  outline: none;
}

.filtro-gestion:focus {
  box-shadow: none;
}

.btn-clear {
  position: absolute;
  right: 10px;
  border: 0;
  background: transparent;
  color: #777;
  cursor: pointer;
}

.table-container {
  max-width: 1200px;
  margin: 0 auto;
}

.results-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: .6rem;
  color: #555;
  font-size: .9rem;
}

.btn-refresh {
  border: 1px solid #ddd;
  border-radius: 6px;
  background: #fff;
  padding: 6px 10px;
  cursor: pointer;
}

.table-responsive {
  overflow-x: auto;
  padding: .5rem;
  background: rgba(255, 255, 255, .94);
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, .15);
}

.table {
  margin-bottom: 0;
}

.fila-encabezado th {
  padding: 13px 12px;
  background: #cc0000 !important;
  color: #fff !important;
  border-bottom: 2px solid #990000;
  text-align: center;
  white-space: nowrap;
}

.table tbody td {
  padding: 13px 12px;
  text-align: center;
  border-bottom: 1px solid rgba(0, 0, 0, .06);
}

.table tbody tr:hover {
  background: rgba(204, 0, 0, .045);
}

.numero {
  font-weight: 700;
  white-space: nowrap;
}

.texto-justificado {
  min-width: 330px;
  max-width: 560px;
  text-align: left !important;
  word-break: break-word;
}

.descripcion {
  margin-top: 5px;
  color: #666;
  font-size: .86rem;
  line-height: 1.45;
}

.status-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: .78rem;
  font-weight: 700;
  white-space: nowrap;
}

.status-badge.vigente { background: #dcfce7; color: #166534; }
.status-badge.derogada { background: #fee2e2; color: #991b1b; }
.status-badge.modificada { background: #fef3c7; color: #92400e; }

.acciones {
  min-width: 115px;
  white-space: nowrap;
}

.sin-archivo {
  color: #888;
  font-size: .8rem;
}

.state-box,
.empty-box {
  max-width: 900px;
  margin: 2rem auto;
  padding: 2rem;
  border-radius: 12px;
  background: rgba(255, 255, 255, .95);
  text-align: center;
  box-shadow: 0 6px 24px rgba(0, 0, 0, .1);
}

.state-box {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
}

.state-error { color: #991b1b; flex-wrap: wrap; }
.empty-box i { font-size: 2.4rem; color: #999; }
.empty-box h3 { margin: .8rem 0 .3rem; }
.empty-box p { margin: 0; color: #777; }

.btn-retry {
  border: 0;
  border-radius: 6px;
  padding: 8px 14px;
  background: #cc0000;
  color: #fff;
  cursor: pointer;
}

@media (max-width: 768px) {
  .normas-titulo { font-size: 1.8rem; }
  .normas-subtitulo { font-size: .95rem; }
  .normas-page { padding: .5rem; }
  .table-responsive { padding: .25rem; }
}
</style>
