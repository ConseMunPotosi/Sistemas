<template>
  <div v-if="show" class="modal-overlay" @click.self="closeModal">
    <div class="modal-container">
      <div class="modal-header">
        <h3>{{ isEditing ? 'Editar Noticia' : 'Nueva Noticia' }}</h3>
        <button class="close-btn" @click="closeModal">×</button>
      </div>

      <form @submit.prevent="handleSubmit" class="modal-form">
        <!-- Título -->
        <div class="form-group">
          <label for="titulo" class="form-label">Título *</label>
          <input
            id="titulo"
            v-model="form.titulo"
            type="text"
            class="form-input"
            placeholder="Ingresa el título de la noticia"
            required
            maxlength="200"
          />
        </div>

        <!-- Resumen -->
        <div class="form-group">
          <label for="resumen" class="form-label">Resumen</label>
          <textarea
            id="resumen"
            v-model="form.resumen"
            class="form-textarea"
            placeholder="Breve resumen de la noticia..."
            rows="2"
          ></textarea>
        </div>

        <!-- Contenido -->
        <div class="form-group">
          <label for="contenido" class="form-label">Contenido *</label>
          <textarea
            id="contenido"
            v-model="form.contenido"
            class="form-textarea"
            placeholder="Escribe el contenido completo de la noticia..."
            required
            rows="6"
          ></textarea>
        </div>

        <!-- Categoría -->
        <div class="form-group">
          <label for="id_categoria" class="form-label">Categoría *</label>
          <select
            id="id_categoria"
            v-model="form.id_categoria"
            class="form-select"
            required
          >
            <option
              v-if="isEditing && props.noticia?.id_categoria && !store.categorias.some(c => c.id_categoria === props.noticia.id_categoria)"
              :value="props.noticia.id_categoria"
            >
              {{ getCategoriaNombre(props.noticia.id_categoria) }} (Inactiva)
            </option>

            <option value="" disabled>Selecciona una categoría</option>

            <template v-if="store.categorias && Array.isArray(store.categorias)">
              <option
                v-for="cat in store.categorias.filter(c => c.estado === true || c.estado === 1)"
                :key="cat.id_categoria || cat.id"
                :value="cat.id_categoria || cat.id"
              >
                {{ cat.nombre }}
              </option>
            </template>

            <option v-else disabled>
              {{ store.loading ? 'Cargando categorías...' : 'No hay categorías disponibles' }}
            </option>
          </select>
        </div>

        <!-- Opciones de Publicación -->
        <h4 class="section-title">Opciones de Publicación</h4>

        <div class="form-group">
          <label for="estado_publicacion" class="form-label">Estado de Publicación *</label>
          <select
            id="estado_publicacion"
            v-model="form.estado_publicacion"
            class="form-select"
          >
            <option value="borrador">Borrador</option>
            <option value="programado">Programado</option>
            <option value="publicado">Publicado</option>
          </select>
        </div>
        <div class="form-group">
          <label for="fecha_publicacion">Fecha de Publicación</label>
          <input
            id="fecha_publicacion"
            v-model="form.fecha_publicacion"
            type="date"
            class="form-input"
          />
        </div>

        <!-- Redes Sociales -->
        <h4 class="section-title">Redes Sociales</h4>

        <div class="grid-2">
          <div class="form-group checkbox-group">
            <label class="checkbox-label">
              <input v-model="form.publicado_web" type="checkbox" />
              <span>Publicar en Web</span>
            </label>
          </div>
          <div class="form-group checkbox-group">
            <label class="checkbox-label">
              <input v-model="form.publicado_facebook" type="checkbox" />
              <span>Publicar en Facebook</span>
            </label>
          </div>
        </div>

        <div v-if="form.publicado_facebook" class="form-group">
          <label for="enlace_facebook" class="form-label">Enlace a Facebook</label>
          <input
            id="enlace_facebook"
            v-model="form.enlace_facebook"
            type="url"
            class="form-input"
            placeholder="https://facebook.com/tu-publicacion"
          />
        </div>

        <!-- ===== SECCIÓN: SUBIR ARCHIVOS ===== -->
        <h4 class="section-title">📁 Archivos Adjuntos</h4>

        <div class="file-upload-wrapper">
          <div class="file-input-group">
            <input
              id="archivos"
              ref="fileInput"
              type="file"
              class="form-input-file"
              multiple
              accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip,.mp4,.avi,.mov,.wmv,.flv,.mkv,.webm"
              @change="handleFileUpload"
            />
            <label for="archivos" class="btn-select-files">
              📁 Seleccionar Archivos
            </label>
          </div>

          <small class="form-hint">
            Formatos permitidos: Imágenes (JPG, PNG), Documentos (PDF),
            Videos (MP4, AVI, MOV) - Máx. 10MB por archivo
          </small>
        </div>

        <!-- Lista de archivos seleccionados -->
        <div v-if="selectedFiles.length > 0" class="files-list">
          <div class="files-list-header">
            <span>📁 Archivos seleccionados ({{ selectedFiles.length }})</span>
            <button type="button" class="btn-clear-files" @click="clearFiles">
              🗑️ Limpiar todos
            </button>
          </div>

          <div v-for="(file, index) in selectedFiles" :key="index" class="file-item">
            <div class="file-info">
              <span class="file-icon">{{ getFileIcon(file.type) }}</span>
              <span class="file-name">{{ file.name }}</span>
              <span class="file-size">{{ formatFileSize(file.size) }}</span>
              <span class="file-type-badge">{{ getFileType(file.type) }}</span>
            </div>
            <button type="button" class="btn-remove-file" @click="removeFile(index)">
              ✕
            </button>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- LISTA DE ARCHIVOS EXISTENTES (AL EDITAR)    -->
        <!-- ========================================== -->
        <div v-if="isEditing && archivosExistentes.length > 0" class="files-list existing-files">
          <div class="files-list-header">
            <span>📁 Archivos existentes ({{ archivosExistentes.length }})</span>
          </div>

          <div v-for="archivo in archivosExistentes" :key="archivo.id_archivo" class="file-item">
            <div class="file-info">
              <span class="file-icon">{{ getFileIconByExtension(archivo.extension) }}</span>
              <span class="file-name">{{ archivo.nombre_archivo }}</span>
              <span class="file-size">{{ formatFileSize(archivo.peso_bytes) }}</span>
              <span class="file-type-badge">{{ archivo.extension?.toUpperCase() || 'Archivo' }}</span>
            </div>
            <div class="file-actions">
              <a
                v-if="archivo.ruta_archivo"
                :href="getFileUrl(archivo.ruta_archivo)"
                target="_blank"
                class="btn-download-file"
                title="Descargar archivo"
              >
                ⬇️
              </a>
              <button type="button" class="btn-delete-file" @click="deleteExistingFile(archivo.id_archivo)">
                🗑️
              </button>
            </div>
          </div>
        </div>

        <!-- Botones de acción -->
        <div class="modal-footer">
          <button type="button" class="btn-cancel" @click="closeModal">
            Cancelar
          </button>
          <button type="submit" class="btn-save" :disabled="isSubmitting">
            {{ isSubmitting ? 'Guardando...' : isEditing ? 'Actualizar Noticia' : 'Crear Noticia' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';
import { useNoticiasStore } from '../../stores/noticias.js';
import axios from 'axios';

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  noticia: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['update:show', 'saved']);

const store = useNoticiasStore();
const fileInput = ref(null);
const isSubmitting = ref(false);
const selectedFiles = ref([]);
const archivosExistentes = ref([]);

// Estado del formulario
const isEditing = computed(() => props.noticia !== null);

const form = reactive({
  titulo: '',
  resumen: '',
  contenido: '',
  id_categoria: '',
  estado_publicacion: 'borrador',
  publicado_web: true,
  publicado_facebook: false,
  enlace_facebook: '',
});

// ==========================================
// MÉTODOS PARA ARCHIVOS
// ==========================================

const getFileIcon = (mimeType) => {
  if (!mimeType) return '📄';
  if (mimeType.startsWith('video/')) return '🎬';
  if (mimeType.startsWith('image/')) return '🖼️';
  if (mimeType.includes('pdf')) return '📕';
  if (mimeType.includes('word') || mimeType.includes('document')) return '📘';
  if (mimeType.includes('excel') || mimeType.includes('sheet')) return '📗';
  if (mimeType.includes('zip') || mimeType.includes('compressed')) return '📦';
  return '📄';
};

const getFileIconByExtension = (extension) => {
  if (!extension) return '📄';
  const ext = extension.toLowerCase();
  const videoExts = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];
  if (videoExts.includes(ext)) return '🎬';
  const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
  if (imageExts.includes(ext)) return '🖼️';
  if (ext === 'pdf') return '📕';
  if (['doc', 'docx'].includes(ext)) return '📘';
  if (['xls', 'xlsx'].includes(ext)) return '📗';
  if (ext === 'zip') return '📦';
  return '📄';
};

const getFileType = (mimeType) => {
  if (!mimeType) return 'Archivo';
  if (mimeType.startsWith('video/')) return 'Video';
  if (mimeType.startsWith('image/')) return 'Imagen';
  if (mimeType.includes('pdf')) return 'PDF';
  if (mimeType.includes('word') || mimeType.includes('document')) return 'Documento';
  if (mimeType.includes('excel') || mimeType.includes('sheet')) return 'Excel';
  if (mimeType.includes('zip') || mimeType.includes('compressed')) return 'Comprimido';
  return 'Archivo';
};

const getFileUrl = (ruta) => {
  if (!ruta) return '#';
  return `/${ruta}`;
};

const formatFileSize = (bytes) => {
  if (!bytes) return '0 B';
  const k = 1024;
  const sizes = ['B', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const handleFileUpload = (event) => {
  const files = event.target.files;
  if (files.length > 0) {
    if (selectedFiles.value.length + files.length > 8) {
      alert('Máximo 5 archivos por noticia');
      fileInput.value.value = '';
      return;
    }
    for (const file of files) {
      if (file.size > 10 * 1024 * 1024) {
        alert(`El archivo "${file.name}" excede el límite de 10MB`);
        fileInput.value.value = '';
        return;
      }
    }
    selectedFiles.value = [...selectedFiles.value, ...Array.from(files)];
  }
  if (fileInput.value) fileInput.value.value = '';
};

const removeFile = (index) => {
  selectedFiles.value.splice(index, 1);
};

const clearFiles = () => {
  if (confirm('¿Eliminar todos los archivos seleccionados?')) {
    selectedFiles.value = [];
    if (fileInput.value) fileInput.value.value = '';
  }
};

// ==========================================
// ELIMINAR ARCHIVOS EXISTENTES (AL EDITAR)
// ==========================================
const deleteExistingFile = async (idArchivo) => {
  if (confirm('¿Eliminar este archivo permanentemente?')) {
    try {
      await store.deleteArchivo(idArchivo);
      // Recargamos la lista de archivos existentes desde la noticia actual
      archivosExistentes.value = archivosExistentes.value.filter(a => a.id_archivo !== idArchivo);
      alert('✅ Archivo eliminado exitosamente');
    } catch (error) {
      alert('❌ Error al eliminar el archivo');
    }
  }
};

// ==========================================
// MÉTODOS DEL FORMULARIO
// ==========================================

const getCategoriaNombre = (id) => {
  if (!id) return 'Sin categoría';
  const cat = store.categorias.find(c => c.id_categoria === id);
  return cat ? cat.nombre : 'Categoría no encontrada';
};

const resetForm = () => {
  Object.assign(form, {
    titulo: '',
    resumen: '',
    contenido: '',
    id_categoria: '',
    estado_publicacion: 'borrador',
    publicado_web: true,
    publicado_facebook: false,
    enlace_facebook: '',
  });
  selectedFiles.value = [];
  archivosExistentes.value = [];
  if (fileInput.value) fileInput.value.value = '';
};

watch(() => props.show, async (val) => {
  if (val) {
    if (!store.categorias || store.categorias.length === 0) {
      await store.fetchCategorias();
    }

    if (props.noticia) {
      Object.assign(form, {
        titulo: props.noticia.titulo || '',
        resumen: props.noticia.resumen || '',
        contenido: props.noticia.contenido || '',
        id_categoria: props.noticia.id_categoria || '',
        estado_publicacion: props.noticia.estado_publicacion || 'borrador',
        publicado_web: props.noticia.publicado_web !== undefined ? props.noticia.publicado_web : true,
        publicado_facebook: props.noticia.publicado_facebook || false,
        enlace_facebook: props.noticia.enlace_facebook || '',
      });

      // 🔥 CORRECCIÓN: Usamos los archivos que ya vienen en el objeto noticia
      archivosExistentes.value = props.noticia.archivos || [];

    } else {
      resetForm();
    }
  }
}, { immediate: true });

const closeModal = () => {
  emit('update:show', false);
  resetForm();
};

// ==========================================
// ✅ ENVÍO DEL FORMULARIO (CREAR Y ACTUALIZAR)
// ==========================================
const handleSubmit = async () => {
  if (!form.titulo.trim()) {
    alert('El título es obligatorio');
    return;
  }
  if (!form.contenido.trim()) {
    alert('El contenido es obligatorio');
    return;
  }
  if (!form.id_categoria) {
    alert('Debes seleccionar una categoría');
    return;
  }

  isSubmitting.value = true;

  try {
    const formData = new FormData();

    // 🔥 CORRECCIÓN DEFINITIVA: Si el estado NO es 'publicado', NO enviar fecha.
    // Si el estado es 'publicado', el Backend (Laravel) pondrá la fecha actual automáticamente
    // si el campo llega vacío. Así que simplemente NO enviamos el campo si no es necesario.
    if (form.estado_publicacion === 'publicado') {
        // Si el usuario seleccionó una fecha manualmente, la enviamos.
        if (form.fecha_publicacion) {
            const dateObj = new Date(form.fecha_publicacion);
            if (!isNaN(dateObj.getTime())) {
                const year = dateObj.getFullYear();
                const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                const day = String(dateObj.getDate()).padStart(2, '0');
                formData.append('fecha_publicacion', `${year}-${month}-${day}`);
            }
        }
        // Si no seleccionó fecha, NO enviamos nada. Laravel pondrá now() automáticamente.
    }

    // Agregamos el resto de campos (excluyendo fecha_publicacion si ya la enviamos)
    Object.keys(form).forEach(key => {
      if (form[key] !== undefined && form[key] !== null && key !== 'fecha_publicacion') {
        formData.append(key, form[key]);
      }
    });

    // Agregar NUEVOS archivos seleccionados
    selectedFiles.value.forEach((file) => {
      formData.append('archivos[]', file);
    });

    const token = localStorage.getItem('auth_token');
    if (!token) {
      alert('No se encontró el token de autenticación.');
      isSubmitting.value = false;
      return;
    }

    const config = {
        headers: {
            'Content-Type': 'multipart/form-data',
            'Authorization': `Bearer ${token}`
        }
    };

    let response;
    if (isEditing.value) {
      formData.append('_method', 'PUT');
      response = await axios.post(`/api/noticias/${props.noticia.id_noticia}`, formData, config);
    } else {
      response = await axios.post('/api/noticias', formData, config);
    }

    emit('saved');
    closeModal();
    alert(isEditing.value ? '✅ Noticia actualizada exitosamente' : '✅ Noticia creada exitosamente');

  } catch (error) {
    console.error('Error completo:', error);
    let mensajeError = '❌ Error al guardar la noticia.';
    if (error.response && error.response.status === 401) {
        mensajeError = '❌ Sesión expirada.';
    } else if (error.response && error.response.status === 422) {
        const erroresBackend = error.response.data.errors;
        let detalles = '';
        for (const campo in erroresBackend) {
            detalles += `\n- ${erroresBackend[campo].join(' ')}`;
        }
        mensajeError = `❌ Error de validación:${detalles}`;
    } else if (error.response) {
        mensajeError = `❌ Error del servidor (${error.response.status})`;
    }
    alert(mensajeError);
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => {
  if (!store.categorias || store.categorias.length === 0) {
    store.fetchCategorias();
  }
});
</script>

<style scoped>
/* ===== MODAL ===== */
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
  max-width: 800px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideIn 0.3s ease;
}

@keyframes slideIn {
  from {
    transform: translateY(-30px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

/* ===== HEADER ===== */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
  margin: 0;
  font-size: 20px;
  color: #1a1a2e;
}

.close-btn {
  background: none;
  border: none;
  font-size: 28px;
  color: #6b7280;
  cursor: pointer;
  padding: 0 8px;
  transition: color 0.2s;
}

.close-btn:hover {
  color: #cc0000;
}

/* ===== FORM ===== */
.modal-form {
  padding: 24px;
}

.section-title {
  font-size: 16px;
  font-weight: 600;
  color: #374151;
  margin: 20px 0 16px 0;
  padding-bottom: 8px;
  border-bottom: 2px solid #f3f4f6;
}

.form-group {
  margin-bottom: 18px;
}

.form-label {
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

.form-input:disabled,
.form-select:disabled {
  background: #f3f4f6;
  cursor: not-allowed;
  opacity: 0.7;
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
  font-family: inherit;
}

.form-input-file {
  display: block;
  width: 100%;
  padding: 8px 0;
  border: none;
}

.form-hint {
  display: block;
  margin-top: 4px;
  font-size: 12px;
  color: #6b7280;
}

.text-warning {
  color: #b45309;
}

/* ===== GRID ===== */
.grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 18px;
}

/* ===== CHECKBOX ===== */
.checkbox-group {
  margin-bottom: 0;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: normal;
  cursor: pointer;
  padding: 8px 12px;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  transition: all 0.2s;
}

.checkbox-label:hover {
  background: #f9fafb;
  border-color: #d1d5db;
}

.checkbox-label input[type="checkbox"] {
  width: 18px;
  height: 18px;
  cursor: pointer;
  accent-color: #cc0000;
}

.checkbox-label span {
  font-size: 14px;
  color: #374151;
}

/* ===== IMAGE PREVIEW ===== */
.image-preview {
  margin-top: 12px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
}

.image-preview img {
  max-width: 200px;
  max-height: 150px;
  border-radius: 6px;
  border: 1px solid #e5e7eb;
  object-fit: cover;
}

.btn-remove-image {
  padding: 4px 12px;
  background: #fee2e2;
  color: #991b1b;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
}

.btn-remove-image:hover {
  background: #fecaca;
}

/* ===== FOOTER ===== */
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

.btn-cancel:hover {
  background: #e5e7eb;
}

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

.btn-save:hover:not(:disabled) {
  background: #a30000;
}

.btn-save:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* ===== SCROLLBAR ===== */
.modal-container::-webkit-scrollbar {
  width: 8px;
}

.modal-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.modal-container::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 4px;
}

.modal-container::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

/* ===== ARCHIVOS ===== */
.files-list {
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 12px;
  margin-bottom: 18px;
  background: #fafafa;
}

.files-list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  font-weight: 500;
  color: #374151;
}

.btn-clear-files {
  padding: 4px 12px;
  background: #fee2e2;
  color: #991b1b;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
}

.btn-clear-files:hover {
  background: #fecaca;
}

.file-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 12px;
  background: white;
  border-radius: 4px;
  margin-bottom: 6px;
  border: 1px solid #f3f4f6;
}

.file-item:hover {
  background: #f9fafb;
}

.file-info {
  display: flex;
  align-items: center;
  gap: 10px;
  flex: 1;
}

.file-icon {
  font-size: 18px;
}

.file-name {
  font-size: 14px;
  color: #1a1a2e;
  word-break: break-all;
}

.file-size {
  font-size: 12px;
  color: #6b7280;
  margin-left: auto;
}

.btn-remove-file {
  background: none;
  border: none;
  color: #6b7280;
  cursor: pointer;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 14px;
}

.btn-remove-file:hover {
  background: #fee2e2;
  color: #991b1b;
}

.btn-delete-file {
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 14px;
}

.btn-delete-file:hover {
  background: #fee2e2;
}

.existing-files {
  border-color: #d1fae5;
  background: #f0fdf4;
}
/* ===== FILE UPLOAD ===== */
.file-upload-wrapper {
  margin-bottom: 18px;
}

.file-input-group {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: center;
}

.form-input-file {
  display: none;
}

.btn-select-files {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  background: #f3f4f6;
  color: #374151;
  border: 2px solid #d1d5db;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
  flex: 1;
  min-width: 150px;
}

.btn-select-files:hover {
  background: #e5e7eb;
  border-color: #9ca3af;
}

.btn-upload-files {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 24px;
  background: #cc0000;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
  white-space: nowrap;
}

.btn-upload-files:hover:not(:disabled) {
  background: #a30000;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(204, 0, 0, 0.3);
}

.btn-upload-files:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* ===== FILES LIST ===== */
.files-list {
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 12px;
  margin-top: 12px;
  margin-bottom: 18px;
  background: #fafafa;
}

.files-list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  font-weight: 500;
  color: #374151;
}

.btn-clear-files {
  padding: 4px 12px;
  background: #fee2e2;
  color: #991b1b;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
}

.btn-clear-files:hover {
  background: #fecaca;
}

.file-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 12px;
  background: white;
  border-radius: 4px;
  margin-bottom: 6px;
  border: 1px solid #f3f4f6;
  gap: 8px;
}

.file-item:hover {
  background: #f9fafb;
}

.file-info {
  display: flex;
  align-items: center;
  gap: 10px;
  flex: 1;
  min-width: 0;
  flex-wrap: wrap;
}

.file-icon {
  font-size: 20px;
  flex-shrink: 0;
}

.file-name {
  font-size: 14px;
  color: #1a1a2e;
  word-break: break-all;
  flex: 1;
  min-width: 100px;
}

.file-size {
  font-size: 12px;
  color: #6b7280;
  flex-shrink: 0;
}

.file-type-badge {
  font-size: 11px;
  padding: 2px 8px;
  background: #e5e7eb;
  border-radius: 12px;
  color: #374151;
  flex-shrink: 0;
}

.file-actions {
  display: flex;
  gap: 6px;
  flex-shrink: 0;
}

.btn-remove-file {
  background: none;
  border: none;
  color: #6b7280;
  cursor: pointer;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 14px;
  flex-shrink: 0;
}

.btn-remove-file:hover {
  background: #fee2e2;
  color: #991b1b;
}

.btn-download-file {
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 14px;
  text-decoration: none;
  color: #6b7280;
}

.btn-download-file:hover {
  background: #d1fae5;
  color: #065f46;
}

.btn-delete-file {
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 14px;
  flex-shrink: 0;
}

.btn-delete-file:hover {
  background: #fee2e2;
}

.existing-files {
  border-color: #d1fae5;
  background: #f0fdf4;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 640px) {
  .file-input-group {
    flex-direction: column;
    width: 100%;
  }

  .btn-select-files {
    width: 100%;
    justify-content: center;
  }

  .btn-upload-files {
    width: 100%;
    justify-content: center;
  }

  .file-item {
    flex-wrap: wrap;
  }

  .file-info {
    width: 100%;
    gap: 6px;
  }

  .file-name {
    min-width: 80px;
  }

  .file-actions {
    width: 100%;
    justify-content: flex-end;
  }

  .file-type-badge {
    font-size: 10px;
    padding: 1px 6px;
  }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 640px) {
  .modal-container {
    width: 95%;
    max-height: 95vh;
  }

  .modal-form {
    padding: 16px;
  }

  .grid-2 {
    grid-template-columns: 1fr;
    gap: 0;
  }

  .modal-footer {
    flex-direction: column-reverse;
  }

  .btn-cancel,
  .btn-save {
    width: 100%;
    text-align: center;
  }
}
</style>
