<template>
  <div
    v-if="show"
    class="modal-overlay"
    @click.self="closeModal"
  >
    <div class="modal-container">

      <!-- CABECERA -->
      <div class="modal-header">
        <h3>
          {{ isEditing ? 'Editar Noticia' : 'Nueva Noticia' }}
        </h3>

        <button
          type="button"
          class="close-btn"
          @click="closeModal"
        >
          ×
        </button>
      </div>

      <!-- FORMULARIO -->
      <form
        class="modal-form"
        @submit.prevent="handleSubmit"
      >

        <!-- =============================== -->
        <!-- INFORMACIÓN DE LA NOTICIA -->
        <!-- =============================== -->

        <h4 class="section-title">
          Información de la noticia
        </h4>

        <div class="form-group">
          <label
            for="titulo"
            class="form-label"
          >
            Título *
          </label>

          <input
            id="titulo"
            v-model="form.titulo"
            type="text"
            class="form-input"
            placeholder="Ingresa el título de la noticia"
            maxlength="200"
            required
          />
        </div>

        <div class="form-group">
          <label
            for="resumen"
            class="form-label"
          >
            Resumen
          </label>

          <textarea
            id="resumen"
            v-model="form.resumen"
            class="form-textarea"
            placeholder="Breve resumen de la noticia..."
            rows="3"
            maxlength="500"
          ></textarea>
        </div>

        <div class="form-group">
          <label
            for="contenido"
            class="form-label"
          >
            Contenido *
          </label>

          <textarea
            id="contenido"
            v-model="form.contenido"
            class="form-textarea"
            placeholder="Escribe el contenido de la noticia..."
            rows="7"
            required
          ></textarea>
        </div>

        <!-- =============================== -->
        <!-- CATEGORÍA -->
        <!-- =============================== -->

        <div class="form-group">
          <label
            for="id_categoria"
            class="form-label"
          >
            Categoría *
          </label>

          <select
            id="id_categoria"
            v-model="form.id_categoria"
            class="form-select"
            required
          >
            <option value="" disabled>
              Selecciona una categoría
            </option>

            <!-- Categoría actual inactiva -->
            <option
              v-if="
                isEditing &&
                props.noticia?.id_categoria &&
                !store.categorias.some(
                  c => Number(c.id_categoria) === Number(props.noticia.id_categoria)
                )
              "
              :value="props.noticia.id_categoria"
            >
              {{
                getCategoriaNombre(props.noticia.id_categoria)
              }}
              (Inactiva)
            </option>

            <!-- Categorías activas -->
            <option
              v-for="cat in categoriasActivas"
              :key="cat.id_categoria || cat.id"
              :value="cat.id_categoria || cat.id"
            >
              {{ cat.nombre }}
            </option>
          </select>
        </div>

        <!-- =============================== -->
        <!-- PUBLICACIÓN -->
        <!-- =============================== -->

        <h4 class="section-title">
          Opciones de publicación
        </h4>

        <div class="form-group">
          <label
            for="estado_publicacion"
            class="form-label"
          >
            Comisión a la que pertenece la noticia
          </label>

          <select
            id="estado_publicacion"
            v-model="form.estado_publicacion"
            class="form-select"
          >
            <option value="CMP">
              Institucional
            </option>

            <option value="CJyDI">
              Comisión Jurídica y Desarrollo Institucional
            </option>

            <option value="CEF">
              Comisión Económica Financiera
            </option>

            <option value="CDTyL">
              Comisión de Desarrollo Territorial y Límites
            </option>

            <option value="CT">
              Comisión Técnica
            </option>

            <option value="CDH">
              Comisión de Desarrollo Humano
            </option>

            <option value="CGG">
              Comisión de Género Generacional
            </option>

            <option value="CDEPyA">
              Comisión de Desarrollo Económico, Productivo y Agropecuario
            </option>

            <option value="CTCyPAH">
              Comisión de Turismo, Cultura y Preservación de Áreas Históricas
            </option>

            <option value="CMAMyF">
              Comisión de Medio Ambiente, Minería y Forestación
            </option>

            <option value="CSP">
              Comisión de Servicios Públicos
            </option>
          </select>
        </div>

        <div class="form-group">
          <label
            for="fecha_publicacion"
            class="form-label"
          >
            Fecha de Publicación
          </label>

          <input
            id="fecha_publicacion"
            v-model="form.fecha_publicacion"
            type="date"
            class="form-input"
          />
        </div>

        <!-- =============================== -->
        <!-- REDES SOCIALES -->
        <!-- =============================== -->

        <h4 class="section-title">
          Redes Sociales
        </h4>

        <div class="grid-2">

          <div class="checkbox-group">
            <label class="checkbox-label">
              <input
                v-model="form.publicado_web"
                type="checkbox"
              />

              <span>
                Publicar en Web
              </span>
            </label>
          </div>

          <div class="checkbox-group">
            <label class="checkbox-label">
              <input
                v-model="form.publicado_facebook"
                type="checkbox"
              />

              <span>
                Publicar en Facebook
              </span>
            </label>
          </div>

        </div>

        <div
          v-if="form.publicado_facebook"
          class="form-group"
        >
          <label
            for="enlace_facebook"
            class="form-label"
          >
            Enlace a Facebook
          </label>

          <input
            id="enlace_facebook"
            v-model="form.enlace_facebook"
            type="url"
            class="form-input"
            placeholder="https://facebook.com/tu-publicacion"
          />
        </div>

        <!-- =============================== -->
        <!-- ARCHIVOS -->
        <!-- =============================== -->

        <h4 class="section-title">
          📁 Archivos adjuntos
        </h4>

        <div class="file-upload-box">

          <input
            id="archivos"
            ref="fileInput"
            type="file"
            class="form-input-file"
            multiple
            accept=".jpg,.jpeg,.png,.gif,.bmp,.webp,.pdf,.doc,.docx,.xls,.xlsx,.zip,.mp4,.avi,.mov,.wmv,.flv,.mkv,.webm"
            @change="handleFileUpload"
          />

          <label
            for="archivos"
            class="btn-select-files"
          >
            📁 Seleccionar archivos
          </label>

          <div class="upload-info">
            <strong>Máximo 8 archivos</strong>
            <span>•</span>
            <strong>Máximo 10 MB por archivo</strong>
          </div>

          <small class="form-hint">
            Imágenes, PDF, Word, Excel, ZIP y videos.
          </small>

        </div>

        <!-- =============================== -->
        <!-- ARCHIVOS NUEVOS -->
        <!-- =============================== -->

        <div
          v-if="selectedFiles.length > 0"
          class="files-list"
        >

          <div class="files-list-header">
            <span>
              📎 Nuevos archivos
              ({{ selectedFiles.length }})
            </span>

            <button
              type="button"
              class="btn-clear-files"
              @click="clearFiles"
            >
              Limpiar todos
            </button>
          </div>

          <div
            v-for="(file, index) in selectedFiles"
            :key="file.name + file.size + index"
            class="file-item"
          >

            <div class="file-info">

              <span class="file-icon">
                {{ getFileIcon(file.type, file.name) }}
              </span>

              <div class="file-details">

                <span class="file-name">
                  {{ file.name }}
                </span>

                <span class="file-size">
                  {{ formatFileSize(file.size) }}
                </span>

                <span class="file-type-badge">
                  {{ getFileType(file.type, file.name) }}
                </span>

              </div>
            </div>

            <button
              type="button"
              class="btn-remove-file"
              title="Quitar archivo"
              @click="removeFile(index)"
            >
              ✕
            </button>

          </div>
        </div>

        <!-- =============================== -->
        <!-- ARCHIVOS EXISTENTES -->
        <!-- =============================== -->

        <div
          v-if="isEditing && archivosExistentes.length > 0"
          class="files-list existing-files"
        >

          <div class="files-list-header">
            <span>
              📂 Archivos existentes
              ({{ archivosExistentes.length }})
            </span>
          </div>

          <div
            v-for="archivo in archivosExistentes"
            :key="archivo.id_archivo"
            class="file-item"
          >

            <div class="file-info">

              <span class="file-icon">
                {{
                  getFileIconByExtension(
                    archivo.extension
                  )
                }}
              </span>

              <div class="file-details">

                <span class="file-name">
                  {{ archivo.nombre_archivo }}
                </span>

                <span
                  v-if="archivo.peso || archivo.peso_bytes"
                  class="file-size"
                >
                  {{
                    formatFileSize(
                      archivo.peso ||
                      archivo.peso_bytes
                    )
                  }}
                </span>

                <span class="file-type-badge">
                  {{
                    archivo.extension
                      ?.toUpperCase() ||
                    'Archivo'
                  }}
                </span>

              </div>

            </div>

            <div class="file-actions">

              <a
                v-if="archivo.ruta_archivo"
                :href="getFileUrl(archivo.ruta_archivo)"
                target="_blank"
                rel="noopener noreferrer"
                class="btn-download-file"
                title="Abrir archivo"
              >
                👁️
              </a>

            </div>

          </div>
        </div>

        <!-- =============================== -->
        <!-- RESUMEN -->
        <!-- =============================== -->

        <div class="upload-summary">

          <div>
            <strong>
              Archivos nuevos:
            </strong>

            {{ selectedFiles.length }}
          </div>

          <div v-if="isEditing">
            <strong>
              Archivos existentes:
            </strong>

            {{ archivosExistentes.length }}
          </div>

        </div>

        <!-- =============================== -->
        <!-- BOTONES -->
        <!-- =============================== -->

        <div class="modal-footer">

          <button
            type="button"
            class="btn-cancel"
            :disabled="isSubmitting"
            @click="closeModal"
          >
            Cancelar
          </button>

          <button
            type="submit"
            class="btn-save"
            :disabled="isSubmitting"
          >
            {{
              isSubmitting
                ? 'Guardando...'
                : isEditing
                  ? 'Actualizar Noticia'
                  : 'Crear Noticia'
            }}
          </button>

        </div>

      </form>
    </div>
  </div>
</template>

<script setup>
import {
  ref,
  reactive,
  watch,
  computed,
  onMounted
} from 'vue'

import {
  useNoticiasStore
} from '../../stores/noticias.js'

import api from '../../api/axios.js'

/* ==========================================
   PROPS / EMITS
========================================== */

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },

  noticia: {
    type: Object,
    default: null
  }
})

const emit = defineEmits([
  'update:show',
  'saved'
])

/* ==========================================
   STORE
========================================== */

const store = useNoticiasStore()

/* ==========================================
   REFERENCIAS
========================================== */

const fileInput = ref(null)
const isSubmitting = ref(false)

const selectedFiles = ref([])
const archivosExistentes = ref([])

/* ==========================================
   FORMULARIO
========================================== */

const form = reactive({
  titulo: '',
  resumen: '',
  contenido: '',
  id_categoria: '',
  estado_publicacion: 'CMP',
  fecha_publicacion: '',
  publicado_web: true,
  publicado_facebook: false,
  enlace_facebook: ''
})

/* ==========================================
   COMPUTADAS
========================================== */

const isEditing = computed(() => {
  return props.noticia !== null
})

const categoriasActivas = computed(() => {

  if (!Array.isArray(store.categorias)) {
    return []
  }

  return store.categorias.filter(
    categoria =>
      categoria.estado === true ||
      categoria.estado === 1
  )
})

/* ==========================================
   ARCHIVOS
========================================== */

const EXTENSIONES_IMAGEN = [
  'jpg',
  'jpeg',
  'png',
  'gif',
  'bmp',
  'webp'
]

const EXTENSIONES_VIDEO = [
  'mp4',
  'avi',
  'mov',
  'wmv',
  'flv',
  'mkv',
  'webm'
]

const EXTENSIONES_PERMITIDAS = [
  ...EXTENSIONES_IMAGEN,
  ...EXTENSIONES_VIDEO,
  'pdf',
  'doc',
  'docx',
  'xls',
  'xlsx',
  'zip'
]

const MAX_ARCHIVOS = 8
const MAX_TAMANO = 10 * 1024 * 1024

const getExtension = filename => {

  if (!filename) {
    return ''
  }

  const partes = filename
    .toLowerCase()
    .split('.')

  return partes.length > 1
    ? partes.pop()
    : ''
}

const getFileIcon = (mimeType, filename) => {

  const mime =
    mimeType?.toLowerCase() || ''

  const extension =
    getExtension(filename)

  if (
    mime.startsWith('video/') ||
    EXTENSIONES_VIDEO.includes(extension)
  ) {
    return '🎬'
  }

  if (
    mime.startsWith('image/') ||
    EXTENSIONES_IMAGEN.includes(extension)
  ) {
    return '🖼️'
  }

  if (
    mime.includes('pdf') ||
    extension === 'pdf'
  ) {
    return '📕'
  }

  if (
    mime.includes('word') ||
    extension === 'doc' ||
    extension === 'docx'
  ) {
    return '📘'
  }

  if (
    mime.includes('excel') ||
    mime.includes('spreadsheet') ||
    extension === 'xls' ||
    extension === 'xlsx'
  ) {
    return '📗'
  }

  if (
    extension === 'zip' ||
    mime.includes('compressed')
  ) {
    return '📦'
  }

  return '📄'
}

const getFileIconByExtension = extension => {
  return getFileIcon(
    '',
    `archivo.${extension || ''}`
  )
}

const getFileType = (mimeType, filename) => {

  const mime =
    mimeType?.toLowerCase() || ''

  const extension =
    getExtension(filename)

  if (
    mime.startsWith('image/') ||
    EXTENSIONES_IMAGEN.includes(extension)
  ) {
    return 'Imagen'
  }

  if (
    mime.startsWith('video/') ||
    EXTENSIONES_VIDEO.includes(extension)
  ) {
    return 'Video'
  }

  if (
    mime.includes('pdf') ||
    extension === 'pdf'
  ) {
    return 'PDF'
  }

  if (
    mime.includes('word') ||
    extension === 'doc' ||
    extension === 'docx'
  ) {
    return 'Documento'
  }

  if (
    mime.includes('excel') ||
    mime.includes('spreadsheet') ||
    extension === 'xls' ||
    extension === 'xlsx'
  ) {
    return 'Excel'
  }

  if (
    extension === 'zip' ||
    mime.includes('compressed')
  ) {
    return 'ZIP'
  }

  return extension
    ? extension.toUpperCase()
    : 'Archivo'
}

const formatFileSize = bytes => {

  const valor = Number(bytes)

  if (!valor || Number.isNaN(valor)) {
    return '0 B'
  }

  if (valor < 1024) {
    return `${valor} B`
  }

  if (valor < 1024 * 1024) {
    return `${(valor / 1024).toFixed(1)} KB`
  }

  if (valor < 1024 * 1024 * 1024) {
    return `${(
      valor /
      (1024 * 1024)
    ).toFixed(1)} MB`
  }

  return `${(
    valor /
    (1024 * 1024 * 1024)
  ).toFixed(1)} GB`
}

const getFileUrl = ruta => {

  if (!ruta) {
    return '#'
  }

  if (
    ruta.startsWith('http://') ||
    ruta.startsWith('https://')
  ) {
    return ruta
  }

  return `/${ruta.replace(/^\/+/, '')}`
}

/* ==========================================
   MANEJO DE ARCHIVOS
========================================== */

const handleFileUpload = event => {

  const files =
    Array.from(
      event.target.files || []
    )

  if (!files.length) {
    return
  }

  const espacioDisponible =
    MAX_ARCHIVOS -
    selectedFiles.value.length

  if (files.length > espacioDisponible) {

    alert(
      `Solo puedes tener ${MAX_ARCHIVOS} archivos por noticia.`
    )

    resetFileInput()
    return
  }

  const archivosValidos = []

  for (const file of files) {

    const extension =
      getExtension(file.name)

    if (
      !EXTENSIONES_PERMITIDAS.includes(
        extension
      )
    ) {

      alert(
        `El archivo "${file.name}" no tiene un formato permitido.`
      )

      continue
    }

    if (file.size > MAX_TAMANO) {

      alert(
        `El archivo "${file.name}" supera el límite de 10 MB.`
      )

      continue
    }

    const duplicado =
      selectedFiles.value.some(
        existente =>
          existente.name === file.name &&
          existente.size === file.size
      )

    if (duplicado) {

      alert(
        `El archivo "${file.name}" ya fue seleccionado.`
      )

      continue
    }

    const duplicadoEnLote =
      archivosValidos.some(
        existente =>
          existente.name === file.name &&
          existente.size === file.size
      )

    if (duplicadoEnLote) {
      continue
    }

    archivosValidos.push(file)
  }

  selectedFiles.value = [
    ...selectedFiles.value,
    ...archivosValidos
  ]

  resetFileInput()
}

const resetFileInput = () => {

  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const removeFile = index => {
  selectedFiles.value.splice(
    index,
    1
  )
}

const clearFiles = () => {

  if (!selectedFiles.value.length) {
    return
  }

  if (
    confirm(
      '¿Eliminar todos los archivos seleccionados?'
    )
  ) {

    selectedFiles.value = []

    resetFileInput()
  }
}

/* ==========================================
   CATEGORÍAS
========================================== */

const getCategoriaNombre = id => {

  if (!id) {
    return 'Sin categoría'
  }

  const categoria =
    store.categorias.find(
      c =>
        Number(c.id_categoria) ===
        Number(id)
    )

  return categoria
    ? categoria.nombre
    : 'Categoría no encontrada'
}

/* ==========================================
   FORMULARIO
========================================== */

const resetForm = () => {

  Object.assign(
    form,
    {
      titulo: '',
      resumen: '',
      contenido: '',
      id_categoria: '',
      estado_publicacion: 'CMP',
      fecha_publicacion: '',
      publicado_web: true,
      publicado_facebook: false,
      enlace_facebook: ''
    }
  )

  selectedFiles.value = []
  archivosExistentes.value = []

  resetFileInput()
}

const prepararEdicion = noticia => {

  Object.assign(
    form,
    {
      titulo:
        noticia.titulo || '',

      resumen:
        noticia.resumen || '',

      contenido:
        noticia.contenido || '',

      id_categoria:
        noticia.id_categoria || '',

      estado_publicacion:
        noticia.estado_publicacion || 'CMP',

      fecha_publicacion:
        normalizarFecha(
          noticia.fecha_publicacion
        ),

      publicado_web:
        noticia.publicado_web !== undefined
          ? Boolean(noticia.publicado_web)
          : true,

      publicado_facebook:
        Boolean(
          noticia.publicado_facebook
        ),

      enlace_facebook:
        noticia.enlace_facebook || ''
    }
  )

  archivosExistentes.value =
    Array.isArray(noticia.archivos)
      ? noticia.archivos
      : []

  selectedFiles.value = []

  resetFileInput()
}

const normalizarFecha = fecha => {

  if (!fecha) {
    return ''
  }

  if (
    typeof fecha === 'string' &&
    /^\d{4}-\d{2}-\d{2}$/.test(fecha)
  ) {
    return fecha
  }

  const date =
    new Date(fecha)

  if (Number.isNaN(date.getTime())) {
    return ''
  }

  const year =
    date.getFullYear()

  const month =
    String(
      date.getMonth() + 1
    ).padStart(2, '0')

  const day =
    String(
      date.getDate()
    ).padStart(2, '0')

  return `${year}-${month}-${day}`
}

/* ==========================================
   WATCH MODAL
========================================== */

watch(
  () => props.show,
  async visible => {

    if (!visible) {
      return
    }

    if (
      !Array.isArray(store.categorias) ||
      store.categorias.length === 0
    ) {
      await store.fetchCategorias()
    }

    if (props.noticia) {
      prepararEdicion(props.noticia)
    } else {
      resetForm()
    }
  },
  {
    immediate: true
  }
)

/* ==========================================
   CERRAR
========================================== */

const closeModal = () => {

  if (isSubmitting.value) {
    return
  }

  emit(
    'update:show',
    false
  )

  resetForm()
}

/* ==========================================
   GUARDAR
========================================== */

const handleSubmit = async () => {

  /* Título */
  if (
    !form.titulo ||
    !form.titulo.trim()
  ) {

    alert(
      'El título es obligatorio.'
    )

    return
  }

  /* Contenido */
  if (
    !form.contenido ||
    !form.contenido.trim()
  ) {

    alert(
      'El contenido es obligatorio.'
    )

    return
  }

  /* Categoría */
  if (!form.id_categoria) {

    alert(
      'Debes seleccionar una categoría.'
    )

    return
  }

  /* Facebook */
  if (
    form.publicado_facebook &&
    form.enlace_facebook &&
    !/^https?:\/\//i.test(
      form.enlace_facebook
    )
  ) {

    alert(
      'El enlace de Facebook debe comenzar con http:// o https://.'
    )

    return
  }

  isSubmitting.value = true

  try {

    const formData =
      new FormData()

    formData.append(
      'titulo',
      form.titulo.trim()
    )

    formData.append(
      'resumen',
      form.resumen || ''
    )

    formData.append(
      'contenido',
      form.contenido.trim()
    )

    formData.append(
      'id_categoria',
      String(form.id_categoria)
    )

    formData.append(
      'estado_publicacion',
      form.estado_publicacion || 'CMP'
    )

    formData.append(
      'fecha_publicacion',
      form.fecha_publicacion || ''
    )

    formData.append(
      'publicado_web',
      form.publicado_web ? '1' : '0'
    )

    formData.append(
      'publicado_facebook',
      form.publicado_facebook
        ? '1'
        : '0'
    )

    formData.append(
      'enlace_facebook',
      form.enlace_facebook || ''
    )

    /* Archivos */
    selectedFiles.value.forEach(
      file => {

        formData.append(
          'archivos[]',
          file
        )
      }
    )

    let response

    if (isEditing.value) {

      formData.append(
        '_method',
        'PUT'
      )

      response =
        await api.post(
          `/noticias/${props.noticia.id_noticia}`,
          formData
        )

    } else {

      response =
        await api.post(
          '/noticias',
          formData
        )
    }

    console.log(
      'Noticia guardada:',
      response.data
    )

    await store.fetchNoticias()

    emit('saved')

    emit(
      'update:show',
      false
    )

    resetForm()

    alert(
      isEditing.value
        ? '✅ Noticia actualizada exitosamente.'
        : '✅ Noticia creada exitosamente.'
    )

  } catch (error) {

    console.error(
      'Error al guardar noticia:',
      error
    )

    if (
      error.response?.status === 401
    ) {

      alert(
        '❌ Sesión expirada. Vuelve a iniciar sesión.'
      )

      return
    }

    if (
      error.response?.status === 422
    ) {

      const errores =
        error.response.data?.errors || {}

      let detalles = ''

      Object.keys(errores)
        .forEach(campo => {

          const mensajes =
            Array.isArray(
              errores[campo]
            )
              ? errores[campo]
              : [errores[campo]]

          detalles +=
            `\n- ${mensajes.join(' ')}`
        })

      alert(
        `❌ Error de validación:${detalles}`
      )

      return
    }

    const mensaje =
      error.response?.data?.message

    alert(
      mensaje
        ? `❌ ${mensaje}`
        : '❌ Error al guardar la noticia.'
    )

  } finally {

    isSubmitting.value = false
  }
}

/* ==========================================
   CARGA INICIAL DE CATEGORÍAS
========================================== */

onMounted(async () => {

  if (
    !Array.isArray(store.categorias) ||
    store.categorias.length === 0
  ) {
    await store.fetchCategorias()
  }
})
</script>

<style scoped>
/* ==========================================
   MODAL
========================================== */

.modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background: rgba(0, 0, 0, 0.55);
  backdrop-filter: blur(4px);
}

.modal-container {
  width: 90%;
  max-width: 850px;
  max-height: 92vh;
  overflow-y: auto;
  background: white;
  border-radius: 14px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

/* ==========================================
   HEADER
========================================== */

.modal-header {
  position: sticky;
  top: 0;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 24px;
  background: #cc0000;
  color: white;
}

.modal-header h3 {
  margin: 0;
  font-size: 20px;
  font-weight: 700;
}

.close-btn {
  border: none;
  background: transparent;
  color: white;
  font-size: 30px;
  line-height: 1;
  cursor: pointer;
}

.close-btn:hover {
  opacity: 0.8;
}

/* ==========================================
   FORMULARIO
========================================== */

.modal-form {
  padding: 24px;
}

.section-title {
  margin: 22px 0 16px;
  padding-bottom: 8px;
  border-bottom: 2px solid #f1f1f1;
  color: #374151;
  font-size: 16px;
  font-weight: 700;
}

.form-group {
  margin-bottom: 18px;
}

.form-label {
  display: block;
  margin-bottom: 7px;
  color: #374151;
  font-size: 14px;
  font-weight: 600;
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  box-sizing: border-box;
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 7px;
  background: white;
  font-family: inherit;
  font-size: 14px;
}

.form-textarea {
  min-height: 85px;
  resize: vertical;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: #cc0000;
  box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
}

/* ==========================================
   GRID
========================================== */

.grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

/* ==========================================
   CHECKBOX
========================================== */

.checkbox-group {
  margin-bottom: 16px;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border: 1px solid #e5e7eb;
  border-radius: 7px;
  cursor: pointer;
}

.checkbox-label:hover {
  background: #fafafa;
}

.checkbox-label input {
  width: 18px;
  height: 18px;
  accent-color: #cc0000;
}

/* ==========================================
   ARCHIVOS
========================================== */

.file-upload-box {
  padding: 18px;
  border: 2px dashed #d1d5db;
  border-radius: 10px;
  background: #fafafa;
}

.form-input-file {
  display: none;
}

.btn-select-files {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  box-sizing: border-box;
  padding: 12px 18px;
  border: 2px solid #cc0000;
  border-radius: 7px;
  background: white;
  color: #cc0000;
  font-weight: 700;
  cursor: pointer;
}

.btn-select-files:hover {
  background: #fff5f5;
}

.upload-info {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 12px;
  color: #374151;
  font-size: 13px;
}

.form-hint {
  display: block;
  margin-top: 6px;
  color: #6b7280;
  text-align: center;
  font-size: 12px;
}

/* ==========================================
   LISTA DE ARCHIVOS
========================================== */

.files-list {
  margin-top: 14px;
  margin-bottom: 18px;
  padding: 12px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  background: #fafafa;
}

.existing-files {
  border-color: #d1fae5;
  background: #f0fdf4;
}

.files-list-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  margin-bottom: 10px;
  color: #374151;
  font-weight: 600;
}

.btn-clear-files {
  padding: 5px 10px;
  border: none;
  border-radius: 5px;
  background: #fee2e2;
  color: #991b1b;
  font-size: 12px;
  cursor: pointer;
}

.btn-clear-files:hover {
  background: #fecaca;
}

.file-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 7px;
  padding: 10px 12px;
  border: 1px solid #f1f1f1;
  border-radius: 7px;
  background: white;
}

.file-item:last-child {
  margin-bottom: 0;
}

.file-info {
  display: flex;
  align-items: center;
  gap: 10px;
  min-width: 0;
  flex: 1;
}

.file-icon {
  flex: 0 0 auto;
  font-size: 22px;
}

.file-details {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 7px;
  min-width: 0;
}

.file-name {
  min-width: 120px;
  color: #1f2937;
  font-size: 14px;
  word-break: break-all;
}

.file-size {
  color: #6b7280;
  font-size: 12px;
}

.file-type-badge {
  padding: 2px 7px;
  border-radius: 12px;
  background: #e5e7eb;
  color: #374151;
  font-size: 10px;
  font-weight: 600;
}

.btn-remove-file,
.btn-download-file {
  flex: 0 0 auto;
  padding: 5px 9px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  text-decoration: none;
}

.btn-remove-file {
  background: #fee2e2;
  color: #991b1b;
}

.btn-download-file {
  background: #e0f2fe;
  color: #0369a1;
}

.btn-remove-file:hover {
  background: #fecaca;
}

.btn-download-file:hover {
  background: #bae6fd;
}

/* ==========================================
   RESUMEN
========================================== */

.upload-summary {
  display: flex;
  justify-content: flex-end;
  gap: 20px;
  margin-top: 10px;
  padding: 10px 0;
  color: #6b7280;
  font-size: 13px;
}

/* ==========================================
   FOOTER
========================================== */

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 24px;
  padding-top: 20px;
  border-top: 1px solid #e5e7eb;
}

.btn-cancel,
.btn-save {
  padding: 10px 24px;
  border: none;
  border-radius: 7px;
  font-weight: 600;
  cursor: pointer;
}

.btn-cancel {
  background: #f3f4f6;
  color: #374151;
}

.btn-save {
  background: #cc0000;
  color: white;
}

.btn-save:hover:not(:disabled) {
  background: #a30000;
}

.btn-save:disabled,
.btn-cancel:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* ==========================================
   RESPONSIVE
========================================== */

@media (max-width: 640px) {

  .modal-container {
    width: 96%;
    max-height: 95vh;
  }

  .modal-form {
    padding: 16px;
  }

  .grid-2 {
    grid-template-columns: 1fr;
    gap: 0;
  }

  .upload-info {
    flex-direction: column;
    align-items: center;
    gap: 2px;
  }

  .file-item {
    align-items: flex-start;
  }

  .file-details {
    flex-direction: column;
    align-items: flex-start;
  }

  .upload-summary {
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
  }

  .modal-footer {
    flex-direction: column-reverse;
  }

  .btn-cancel,
  .btn-save {
    width: 100%;
  }
}
</style>
