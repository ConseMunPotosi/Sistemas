<template>
  <div class="inf-page">
    <div class="manager-container">
      <div class="manager-header">
        <h2>Gestión de Normas</h2>
        <button class="btn-primary" @click="openCreateCurrent">
          + Nueva {{ activeTab === 'normas' ? 'Norma' : activeTab === 'tipos' ? 'Tipo de Norma' : 'Estado de Norma' }}
        </button>
      </div>

      <!-- Pestañas (Tabs) -->
      <div class="tabs-container">
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'normas' }"
          @click="activeTab = 'normas'"
        >
          📄 Normas
        </button>
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'tipos' }"
          @click="activeTab = 'tipos'"
        >
          🏷️ Tipos de Norma
        </button>
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'estados' }"
          @click="activeTab = 'estados'"
        >
          📊 Estados de Norma
        </button>
      </div>

      <!-- Contenido de las pestañas -->
      <div class="tab-content">

        <!-- ========================================== -->
        <!-- PESTAÑA 1: LISTA DE NORMAS                 -->
        <!-- ========================================== -->
        <div v-if="activeTab === 'normas'" class="tab-panel">
          <div class="table-wrapper">
            <table class="data-table">
              <thead>
                <tr>
                  <th class="col-auto">Número</th>
                  <th class="col-rest">Título</th>
                  <th class="col-auto">Fecha</th>
                  <th class="col-auto">Tipo</th>
                  <th class="col-auto">Estado</th>
                  <th class="col-auto">Archivos</th>
                  <th class="col-actions">Acciones</th>
                </tr>
              </thead>
              <tbody v-if="!normas || normas.length === 0">
                <tr>
                  <td colspan="7" class="empty-text">No hay normas registradas.</td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr v-for="norma in normas" :key="norma.id_norma">
                  <td class="col-auto font-bold">{{ norma.numero }}/{{ norma.gestion }}</td>
                  <td class="col-rest text-sm">{{ norma.titulo }}</td>
                  <td class="col-auto">{{ formatDate(norma.fecha_publicacion) }}</td>
                  <td class="col-auto">
                    <span class="categoria-badge">{{ norma.tipo ? norma.tipo.nombre_tipo : '-' }}</span>
                  </td>
                  <td class="col-auto">
                    <span class="status-badge" :class="norma.estado ? 'active' : 'inactive'">
                      {{ norma.estado ? norma.estado.nombre_estado : '-' }}
                    </span>
                  </td>

                  <!-- 🔥 NUEVA COLUMNA: ARCHIVOS -->
                  <td class="col-auto">
                    <a
                      v-if="norma.archivos && norma.archivos.length > 0"
                      :href="getFileUrl(norma.archivos[0].ruta_archivo)"
                      target="_blank"
                      class="btn-download"
                    >
                      📄 PDF
                    </a>
                    <span v-else>-</span>
                  </td>

                  <td class="col-actions">
                    <div class="action-buttons">
                      <button class="btn-edit" @click="openCreateCurrent(norma)" title="Editar">✏️</button>
                      <button class="btn-delete" @click="deleteNorma(norma.id_norma)" title="Eliminar">🗑️</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- PESTAÑA 2: LISTA DE TIPOS DE NORMA         -->
        <!-- ========================================== -->
        <div v-if="activeTab === 'tipos'" class="tab-panel">
          <div class="table-wrapper">
            <table class="data-table">
              <thead>
                <tr>
                  <th class="col-auto">Nombre</th>
                  <th class="col-rest">Descripción</th>
                  <th class="col-auto">Estado</th>
                  <th class="col-actions">Acciones</th>
                </tr>
              </thead>
              <tbody v-if="!tipos || tipos.length === 0">
                <tr>
                  <td colspan="4" class="empty-text">No hay tipos de norma registrados.</td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr v-for="tipo in tipos" :key="tipo.id_tipo_norma">
                  <td class="col-auto font-bold">{{ tipo.nombre_tipo }}</td>
                  <td class="col-rest text-sm">{{ tipo.descripcion || '-' }}</td>
                  <td class="col-auto">
                    <span class="status-badge" :class="tipo.estado ? 'active' : 'inactive'">
                      {{ tipo.estado ? 'Activo' : 'Inactivo' }}
                    </span>
                  </td>
                  <td class="col-actions">
                    <div class="action-buttons">
                      <button class="btn-edit" @click="openCreateCurrent(tipo)" title="Editar">✏️</button>
                      <button class="btn-delete" @click="deleteTipo(tipo.id_tipo_norma)" title="Eliminar">🗑️</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- PESTAÑA 3: LISTA DE ESTADOS DE NORMA       -->
        <!-- ========================================== -->
        <div v-if="activeTab === 'estados'" class="tab-panel">
          <div class="table-wrapper">
            <table class="data-table">
              <thead>
                <tr>
                  <th class="col-auto">Nombre</th>
                  <th class="col-rest">Descripción</th>
                  <th class="col-auto">Estado</th>
                  <th class="col-actions">Acciones</th>
                </tr>
              </thead>
              <tbody v-if="!estados || estados.length === 0">
                <tr>
                  <td colspan="4" class="empty-text">No hay estados de norma registrados.</td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr v-for="estado in estados" :key="estado.id_estado_norma">
                  <td class="col-auto font-bold">{{ estado.nombre_estado }}</td>
                  <td class="col-rest text-sm">{{ estado.descripcion || '-' }}</td>
                  <td class="col-auto">
                    <span class="status-badge" :class="estado.estado ? 'active' : 'inactive'">
                      {{ estado.estado ? 'Activo' : 'Inactivo' }}
                    </span>
                  </td>
                  <td class="col-actions">
                    <div class="action-buttons">
                      <button class="btn-edit" @click="openCreateCurrent(estado)" title="Editar">✏️</button>
                      <button class="btn-delete" @click="deleteEstado(estado.id_estado_norma)" title="Eliminar">🗑️</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- ========================================== -->
      <!-- MODAL DINÁMICO (Para las 3 pestañas)        -->
      <!-- ========================================== -->
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-container">
          <!-- HEADER FIJO -->
          <div class="modal-header">
            <h3>{{ isEditing ? 'Editar' : 'Nueva' }} {{ activeTab === 'normas' ? 'Norma' : activeTab === 'tipos' ? 'Tipo de Norma' : 'Estado de Norma' }}</h3>
            <button class="close-btn" @click="closeModal">×</button>
          </div>

          <!-- CUERPO CON SCROLL -->
          <div class="modal-body-scroll">
            <form @submit.prevent="saveCurrent" class="modal-form">

              <!-- Campos para NORMAS -->
              <template v-if="activeTab === 'normas'">
                <div class="form-group">
                  <label>Número</label>
                  <input type="number" v-model="formNorma.numero" class="form-input" required />
                </div>
                <div class="form-group">
                  <label>Gestión (Año)</label>
                  <input type="number" v-model="formNorma.gestion" class="form-input" required />
                </div>
                <div class="form-group">
                  <label>Título</label>
                  <input type="text" v-model="formNorma.titulo" class="form-input" required maxlength="500" />
                </div>
                <div class="form-group">
                  <label>Descripción</label>
                  <textarea v-model="formNorma.desripcion" class="form-textarea" rows="3"></textarea>
                </div>
                <div class="form-group">
                  <label>Fecha de Publicación</label>
                  <input type="date" v-model="formNorma.fecha_publicacion" class="form-input" />
                </div>
                <div class="form-group">
                  <label>Tipo de Norma</label>
                  <select v-model="formNorma.id_tipo_norma" class="form-select" required>
                    <option value="" disabled>Seleccionar</option>
                    <option v-for="tipo in tipos" :key="tipo.id_tipo_norma" :value="tipo.id_tipo_norma">
                      {{ tipo.nombre_tipo }}
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Estado de Norma</label>
                  <select v-model="formNorma.id_estado_norma" class="form-select" required>
                    <option value="" disabled>Seleccionar</option>
                    <option v-for="estado in estados" :key="estado.id_estado_norma" :value="estado.id_estado_norma">
                      {{ estado.nombre_estado }}
                    </option>
                  </select>
                </div>

                <!-- 🔥 NUEVO CAMPO PARA SUBIR PDF -->
                <div class="form-group">
                  <label>📎 Archivo PDF (Opcional)</label>
                  <input
                    type="file"
                    id="archivoPDFInput"
                    accept=".pdf"
                    class="form-input"
                    @change="handleFileChange"
                  />
                </div>
              </template>

              <!-- Campos para TIPOS -->
              <template v-if="activeTab === 'tipos'">
                <div class="form-group">
                  <label>Nombre</label>
                  <input type="text" v-model="formTipo.nombre_tipo" class="form-input" required maxlength="100" />
                </div>
                <div class="form-group">
                  <label>Descripción</label>
                  <textarea v-model="formTipo.descripcion" class="form-textarea" rows="3"></textarea>
                </div>
                <div class="form-group">
                  <label>Estado</label>
                  <select v-model="formTipo.estado" class="form-select">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                  </select>
                </div>
              </template>

              <!-- Campos para ESTADOS -->
              <template v-if="activeTab === 'estados'">
                <div class="form-group">
                  <label>Nombre</label>
                  <input type="text" v-model="formEstado.nombre_estado" class="form-input" required maxlength="50" />
                </div>
                <div class="form-group">
                  <label>Descripción</label>
                  <textarea v-model="formEstado.descripcion" class="form-textarea" rows="3"></textarea>
                </div>
                <div class="form-group">
                  <label>Estado</label>
                  <select v-model="formEstado.estado" class="form-select">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                  </select>
                </div>
              </template>

              <div class="modal-footer">
                <button type="button" class="btn-cancel" @click="closeModal">Cancelar</button>
                <button type="submit" class="btn-save" :disabled="isSubmitting">
                  {{ isSubmitting ? 'Guardando...' : 'Guardar' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';

// ===== AUTENTICACIÓN =====
const token = localStorage.getItem('auth_token');
const config = {
  headers: { 'Authorization': `Bearer ${token}` }
};

// ===== ESTADO =====
const activeTab = ref('normas');
const showModal = ref(false);
const isEditing = ref(false);
const isSubmitting = ref(false);

// ===== NORMAS =====
const normas = ref([]);
const formNorma = reactive({
  id_tipo_norma: '',
  id_estado_norma: '',
  numero: '',
  gestion: '',
  titulo: '',
  desripcion: '',
  fecha_publicacion: ''
});
const editingNormaId = ref(null);

// ===== TIPOS =====
const tipos = ref([]);
const formTipo = reactive({
  nombre_tipo: '',
  descripcion: '',
  estado: true
});
const editingTipoId = ref(null);

// ===== ESTADOS =====
const estados = ref([]);
const formEstado = reactive({
  nombre_estado: '',
  descripcion: '',
  estado: true
});
const editingEstadoId = ref(null);

// ===== ARCHIVOS =====
const archivoPDF = ref(null);

const handleFileChange = (event) => {
    archivoPDF.value = event.target.files[0];
};

const uploadArchivo = async (idNorma) => {
    if (!archivoPDF.value) return;

    const formData = new FormData();
    formData.append('id_norma', idNorma);
    formData.append('archivo', archivoPDF.value);

    try {
        await axios.post('/api/gaceta/archivos-norma', formData, config);
        alert('✅ Archivo PDF subido correctamente');
        archivoPDF.value = null;
        // Limpiar input file
        document.getElementById('archivoPDFInput').value = '';
    } catch (error) {
        console.error('Error al subir archivo:', error);
        alert('❌ Error al subir el archivo');
    }
};

// ===== MÉTODOS CRUD =====

const fetchNormas = async () => {
  try {
    const response = await axios.get('/api/gaceta/normas', config);
    normas.value = response.data.data || [];
  } catch (error) {
    console.error('Error al fetch normas:', error);
    normas.value = [];
  }
};

const fetchTipos = async () => {
  try {
    const response = await axios.get('/api/gaceta/tipos-norma', config);
    tipos.value = response.data.data || [];
  } catch (error) {
    console.error('Error al fetch tipos:', error);
    tipos.value = [];
  }
};

const fetchEstados = async () => {
  try {
    const response = await axios.get('/api/gaceta/estados-norma', config);
    estados.value = response.data.data || [];
  } catch (error) {
    console.error('Error al fetch estados:', error);
    estados.value = [];
  }
};

// ===== ABRIR MODAL Y GUARDAR (Según pestaña) =====
const openCreateCurrent = (item = null) => {
  isEditing.value = !!item;
  showModal.value = true;

  if (activeTab.value === 'normas') {
    // 🔥 CAPTURA CORRECTA DEL ID
    editingNormaId.value = item ? item.id_norma : null;

    Object.assign(formNorma, item || {
      id_tipo_norma: '',
      id_estado_norma: '',
      numero: '',
      gestion: '',
      titulo: '',
      desripcion: '',
      fecha_publicacion: ''
    });
  } else if (activeTab.value === 'tipos') {
    editingTipoId.value = item ? item.id_tipo_norma : null;
    Object.assign(formTipo, item || { nombre_tipo: '', descripcion: '', estado: true });
  } else if (activeTab.value === 'estados') {
    editingEstadoId.value = item ? item.id_estado_norma : null;
    Object.assign(formEstado, item || { nombre_estado: '', descripcion: '', estado: true });
  }
};

const closeModal = () => {
  showModal.value = false;
};

const saveCurrent = async () => {
  isSubmitting.value = true;
  try {
    if (activeTab.value === 'normas') {
      let nuevaNorma;

      // 🔥 VERIFICAR SI EL ID EXISTE ANTES DE LLAMAR AL PUT
      if (isEditing.value && editingNormaId.value) {
        await axios.put(`/api/gaceta/normas/${editingNormaId.value}`, formNorma, config);
      } else {
        const response = await axios.post('/api/gaceta/normas', formNorma, config);
        nuevaNorma = response.data.data; // Capturamos la nueva norma
      }

      // 🔥 SUBIR EL ARCHIVO DESPUÉS DE CREAR/EDITAR LA NORMA
      if (nuevaNorma && archivoPDF.value) {
        await uploadArchivo(nuevaNorma.id_norma);
      } else if (archivoPDF.value) {
        await uploadArchivo(editingNormaId.value);
      }

      await fetchNormas();
    } else if (activeTab.value === 'tipos') {
      if (isEditing.value && editingTipoId.value) {
        await axios.put(`/api/gaceta/tipos-norma/${editingTipoId.value}`, formTipo, config);
      } else {
        await axios.post('/api/gaceta/tipos-norma', formTipo, config);
      }
      await fetchTipos();
    } else if (activeTab.value === 'estados') {
      if (isEditing.value && editingEstadoId.value) {
        await axios.put(`/api/gaceta/estados-norma/${editingEstadoId.value}`, formEstado, config);
      } else {
        await axios.post('/api/gaceta/estados-norma', formEstado, config);
      }
      await fetchEstados();
    }

    closeModal();
  } catch (error) {
    console.error('Error:', error);
    let message = 'Error al guardar';
    if (error.response?.data?.message) message = error.response.data.message;
    if (error.response?.data?.errors) {
      message = Object.values(error.response.data.errors).flat().join('\n');
    }
    alert(message);
  } finally {
    isSubmitting.value = false;
  }
};

// ===== ELIMINAR =====
const deleteNorma = async (id) => {
  if (confirm('¿Eliminar esta norma?')) {
    await axios.delete(`/api/gaceta/normas/${id}`, config);
    await fetchNormas();
  }
};

const deleteTipo = async (id) => {
  if (confirm('¿Eliminar este tipo de norma?')) {
    await axios.delete(`/api/gaceta/tipos-norma/${id}`, config);
    await fetchTipos();
  }
};

const deleteEstado = async (id) => {
  if (confirm('¿Eliminar este estado de norma?')) {
    await axios.delete(`/api/gaceta/estados-norma/${id}`, config);
    await fetchEstados();
  }
};

// ===== UTILIDADES =====
const getFileUrl = (ruta) => {
  if (!ruta) return '#';
  return `/${ruta}`;
};

const formatDate = (date) => {
  if (!date) return '-';
  try {
    const d = new Date(date);
    return isNaN(d.getTime()) ? '-' : d.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
  } catch { return '-'; }
};

// ===== CICLO DE VIDA =====
onMounted(async () => {
  try {
    await Promise.all([fetchNormas(), fetchTipos(), fetchEstados()]);
  } catch (error) {
    console.error('❌ Error al cargar datos:', error);
  }
});
</script>

<style scoped>
.inf-page {
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  padding: 20px;
  margin: -20px;
  height: auto;
  min-height: 100vh;
}

.manager-container {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.manager-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}
.manager-header h2 { margin: 0; font-size: 20px; color: #1a1a2e; }

.btn-primary {
  padding: 8px 20px;
  background: #cc0000;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.btn-primary:hover { background: #a30000; }

/* Tabs */
.tabs-container { display: flex; border-bottom: 2px solid #eee; margin-bottom: 20px; }
.tab-btn {
  padding: 10px 20px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  color: #6b7280;
  position: relative;
  transition: all 0.3s;
}
.tab-btn:hover { color: #1a1a2e; }
.tab-btn.active { color: #cc0000; }
.tab-btn.active::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  right: 0;
  height: 2px;
  background: #cc0000;
}

/* Tabla */
.table-wrapper { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; table-layout: auto; }
.data-table th, .data-table td {
  padding: 14px 16px;
  text-align: left;
  border-bottom: 1px solid #eee;
  vertical-align: middle;
}

.data-table th.col-auto, .data-table td.col-auto { width: auto; white-space: nowrap; }
.data-table td.col-rest { width: 100%; max-width: 0; white-space: normal; word-wrap: break-word; }

.data-table th.col-actions, .data-table td.col-actions { width: 120px; text-align: center; }
.data-table th { background: #f9fafb; font-weight: 600; color: #374151; }
.data-table tr:hover { background: #f9fafb; }

/* Textos auxiliares */
.font-bold { font-weight: 600; }
.text-sm { font-size: 0.875rem; }

/* Badges */
.categoria-badge {
  display: inline-block;
  padding: 2px 10px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 500;
  background: #e0f2fe;
  color: #0369a1;
  border: 1px solid #bae6fd;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
}
.status-badge.active { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.status-badge.inactive { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

/* Acciones */
.action-buttons {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-height: 60px;
  padding: 4px 10px;
}
.btn-edit { background: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn-edit:hover { background: #ffe4e4; }
.btn-delete { background: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn-delete:hover { background: #ffe4e4; }

/* Descargar PDF */
.btn-download {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  font-size: 12px;
  font-weight: 600;
  color: #cc0000;
  text-decoration: none;
  padding: 4px 8px;
  border-radius: 4px;
  background: #fef2f2;
}
.btn-download:hover {
  background: #fee2e2;
}

.empty-text { text-align: center; padding: 20px; color: #6b7280; }

/* ========================================== */
/* ESTILO DEL MODAL (Inspirado en Noticias)    */
/* ========================================== */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  backdrop-filter: blur(4px);
}

.modal-container {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideIn 0.3s ease;
}

@keyframes slideIn {
  from { transform: translateY(-30px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

/* HEADER FIJO */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
  background: #cc0000; /* ROJO */
  border-radius: 12px 12px 0 0;
  flex-shrink: 0;
}

.modal-header h3 {
  margin: 0;
  font-size: 20px;
  color: white; /* Texto blanco */
}

.close-btn {
  background: none;
  border: none;
  font-size: 28px;
  color: white;
  cursor: pointer;
  padding: 0 8px;
  transition: color 0.2s;
}

.close-btn:hover { color: #fee2e2; }

/* CUERPO CON SCROLL (Solo esto se mueve) */
.modal-body-scroll {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
}

/* Scroll PLOMO (Gris) */
.modal-body-scroll::-webkit-scrollbar {
  width: 8px;
}
.modal-body-scroll::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}
.modal-body-scroll::-webkit-scrollbar-thumb {
  background: #9ca3af; /* PLOMO */
  border-radius: 10px;
}
.modal-body-scroll::-webkit-scrollbar-thumb:hover {
  background: #6b7280;
}

/* Cambiar padding en el form */
.modal-form { padding: 0; }

.form-group { margin-bottom: 18px; }
.form-group label {
  display: block;
  font-weight: 500;
  color: #374151;
  margin-bottom: 6px;
  font-size: 14px;
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.2s;
  background: white;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: #cc0000;
  box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
}

.form-textarea { resize: vertical; min-height: 80px; }

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding-top: 20px;
  border-top: 1px solid #e5e7eb;
  margin-top: 20px;
}

.btn-cancel {
  padding: 10px 24px;
  background: #f3f4f6;
  color: #374151;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.2s;
}
.btn-cancel:hover { background: #e5e7eb; }

.btn-save {
  padding: 10px 24px;
  background: #cc0000;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.2s;
}
.btn-save:hover:not(:disabled) { background: #a30000; }
.btn-save:disabled { opacity: 0.6; cursor: not-allowed; }
</style>
