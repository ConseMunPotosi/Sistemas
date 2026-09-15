<template>
    <div class="container-fluid py-4">

        <!-- ENCABEZADO -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Gestión de Concejales</h2>
                <p class="text-muted mb-0">
                    Administración de los concejales del Consejo Municipal de Potosí
                </p>
            </div>

            <button
                type="button"
                class="btn btn-primary"
                @click="abrirNuevo"
            >
                + Nuevo Concejal
            </button>
        </div>

        <!-- MENSAJE -->
        <div
            v-if="mensaje"
            :class="[
                'alert',
                mensajeTipo === 'success'
                    ? 'alert-success'
                    : 'alert-danger'
            ]"
        >
            {{ mensaje }}
        </div>

        <!-- TABLA -->
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        Lista de Concejales
                    </h5>

                    <span class="badge bg-secondary">
                        {{ concejales.length }} registros
                    </span>
                </div>

                <div
                    v-if="cargando"
                    class="text-center py-5"
                >
                    <div
                        class="spinner-border text-primary"
                        role="status"
                    ></div>

                    <p class="mt-2 mb-0">
                        Cargando concejales...
                    </p>
                </div>

                <div
                    v-else-if="concejales.length === 0"
                    class="text-center text-muted py-5"
                >
                    No existen concejales registrados.
                </div>

                <div
                    v-else
                    class="table-responsive"
                >
                    <table class="table table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th width="70">Foto</th>
                                <th>Nombre</th>
                                <th>Cargo</th>
                                <th>Comisión</th>
                                <th>Distritos</th>
                                <th>Estado</th>
                                <th width="180">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="concejal in concejales"
                                :key="concejal.id_concejal"
                            >

                                <!-- FOTO -->
                                <td>
                                    <div
                                        v-if="concejal.imagen"
                                        class="foto-concejal"
                                    >
                                        <img
                                            :src="getImagenUrl(concejal.imagen)"
                                            :alt="concejal.nombre"
                                            @error="imagenError"
                                        >
                                    </div>

                                    <div
                                        v-else
                                        class="foto-vacia"
                                    >
                                        👤
                                    </div>
                                </td>

                                <!-- NOMBRE -->
                                <td>
                                    <strong>
                                        {{ concejal.nombre }}
                                    </strong>

                                    <div
                                        v-if="concejal.descripcion"
                                        class="small text-muted mt-1"
                                    >
                                        {{ concejal.descripcion }}
                                    </div>
                                </td>

                                <!-- CARGO -->
                                <td>
                                    {{ concejal.cargo || '-' }}
                                </td>

                                <!-- COMISIÓN -->
                                <td>
                                    {{ concejal.comision || '-' }}
                                </td>

                                <!-- DISTRITOS -->
                                <td>
                                    {{ concejal.distritos || '-' }}
                                </td>

                                <!-- ESTADO -->
                                <td>
                                    <span
                                        v-if="concejal.estado"
                                        class="badge bg-success"
                                    >
                                        Activo
                                    </span>

                                    <span
                                        v-else
                                        class="badge bg-secondary"
                                    >
                                        Inactivo
                                    </span>
                                </td>

                                <!-- ACCIONES -->
                                <td>
                                    <div class="d-flex gap-2">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning"
                                            @click="editar(concejal)"
                                        >
                                            ✏️ Editar
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger"
                                            @click="eliminar(concejal)"
                                        >
                                            🗑️
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        <!-- MODAL -->
        <div
            v-if="mostrarModal"
            class="modal-backdrop-custom"
        >
            <div class="modal-dialog-custom">

                <div class="card shadow-lg">

                    <!-- CABECERA MODAL -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            {{ editando ? 'Editar Concejal' : 'Nuevo Concejal' }}
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            @click="cerrarModal"
                        ></button>
                    </div>

                    <!-- FORMULARIO -->
                    <form @submit.prevent="guardar">

                        <div class="card-body">

                            <div class="row">

                                <!-- NOMBRE -->
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">
                                        Nombre completo *
                                    </label>

                                    <input
                                        v-model="form.nombre"
                                        type="text"
                                        class="form-control"
                                        maxlength="150"
                                        required
                                    >
                                </div>

                                <!-- ESTADO -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        Estado
                                    </label>

                                    <select
                                        v-model="form.estado"
                                        class="form-select"
                                    >
                                        <option :value="true">
                                            Activo
                                        </option>

                                        <option :value="false">
                                            Inactivo
                                        </option>
                                    </select>
                                </div>

                                <!-- CARGO -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Cargo
                                    </label>

                                    <select
                                        v-model="form.cargo"
                                        class="form-select"
                                    >
                                        <option value="">
                                            Seleccionar cargo
                                        </option>

                                        <option value="Concejal">
                                            Concejal
                                        </option>

                                        <option value="Concejal - Presidente">
                                            Concejal - Presidente
                                        </option>

                                        <option value="Concejal - Vicepresidente">
                                            Concejal - Vicepresidente
                                        </option>

                                        <option value="Concejal - Secretaria">
                                            Concejal - Secretaria
                                        </option>
                                    </select>
                                </div>

                                <!-- COMISIÓN -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Comisión
                                    </label>

                                    <input
                                        v-model="form.comision"
                                        type="text"
                                        class="form-control"
                                        maxlength="150"
                                        placeholder="Ej.: Comisión de Planificación"
                                    >
                                </div>

                                <!-- DISTRITOS -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">
                                        Distritos asignados
                                    </label>

                                    <input
                                        v-model="form.distritos"
                                        type="text"
                                        class="form-control"
                                        placeholder="Ej.: 9 - 12"
                                    >

                                    <small class="text-muted">
                                        Puedes colocar uno o varios distritos.
                                    </small>
                                </div>

                                <!-- DESCRIPCIÓN -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">
                                        Descripción
                                    </label>

                                    <textarea
                                        v-model="form.descripcion"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Información pública del concejal"
                                    ></textarea>
                                </div>

                                <!-- FOTO -->
                                <div class="col-md-12 mb-3">

                                    <label class="form-label">
                                        Fotografía
                                    </label>

                                    <input
                                        ref="imagenInput"
                                        type="file"
                                        class="form-control"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        @change="seleccionarImagen"
                                    >

                                    <small class="text-muted">
                                        Formatos permitidos: JPG, JPEG, PNG y WEBP.
                                        Máximo 10 MB.
                                    </small>

                                </div>

                                <!-- VISTA PREVIA -->
                                <div
                                    v-if="imagenPreview"
                                    class="col-md-12 mb-3"
                                >
                                    <label class="form-label">
                                        Vista previa
                                    </label>

                                    <div class="preview-container">
                                        <img
                                            :src="imagenPreview"
                                            alt="Vista previa"
                                            class="imagen-preview"
                                        >
                                    </div>
                                </div>

                            </div>

                        </div>

                        <!-- FOOTER -->
                        <div class="card-footer d-flex justify-content-end gap-2">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                :disabled="guardando"
                                @click="cerrarModal"
                            >
                                Cancelar
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary"
                                :disabled="guardando"
                            >
                                <span
                                    v-if="guardando"
                                    class="spinner-border spinner-border-sm me-1"
                                ></span>

                                {{ guardando ? 'Guardando...' : 'Guardar' }}
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'

/*
|--------------------------------------------------------------------------
| ESTADO
|--------------------------------------------------------------------------
*/

const concejales = ref([])
const cargando = ref(false)
const guardando = ref(false)

const mostrarModal = ref(false)
const editando = ref(false)

const concejalEditando = ref(null)

const imagenInput = ref(null)
const imagenPreview = ref(null)
const imagenSeleccionada = ref(null)

const mensaje = ref('')
const mensajeTipo = ref('success')

/*
|--------------------------------------------------------------------------
| FORMULARIO
|--------------------------------------------------------------------------
*/

const form = reactive({
    nombre: '',
    cargo: '',
    comision: '',
    distritos: '',
    descripcion: '',
    estado: true
})

/*
|--------------------------------------------------------------------------
| TOKEN
|--------------------------------------------------------------------------
*/

const getConfig = () => {
    const token = localStorage.getItem('auth_token')

    return {
        headers: {
            Authorization: `Bearer ${token}`
        }
    }
}

/*
|--------------------------------------------------------------------------
| CARGAR CONCEJALES
|--------------------------------------------------------------------------
*/

const cargarConcejales = async () => {

    cargando.value = true

    try {

        const response = await axios.get(
            '/api/concejales',
            getConfig()
        )

        if (response.data.success) {
            concejales.value = response.data.data
        }

    } catch (error) {

        console.error(
            'Error al cargar concejales:',
            error
        )

        mostrarMensaje(
            'No se pudieron cargar los concejales.',
            'danger'
        )

    } finally {

        cargando.value = false

    }
}

/*
|--------------------------------------------------------------------------
| NUEVO
|--------------------------------------------------------------------------
*/

const abrirNuevo = () => {

    editando.value = false
    concejalEditando.value = null

    limpiarFormulario()

    mostrarModal.value = true
}

/*
|--------------------------------------------------------------------------
| EDITAR
|--------------------------------------------------------------------------
*/

const editar = (concejal) => {

    editando.value = true

    concejalEditando.value = concejal

    form.nombre = concejal.nombre || ''
    form.cargo = concejal.cargo || ''
    form.comision = concejal.comision || ''
    form.distritos = concejal.distritos || ''
    form.descripcion = concejal.descripcion || ''
    form.estado = concejal.estado === true || concejal.estado === 1

    imagenSeleccionada.value = null

    if (imagenInput.value) {
        imagenInput.value.value = ''
    }

    if (concejal.imagen) {
        imagenPreview.value = getImagenUrl(concejal.imagen)
    } else {
        imagenPreview.value = null
    }

    mostrarModal.value = true
}

/*
|--------------------------------------------------------------------------
| SELECCIONAR IMAGEN
|--------------------------------------------------------------------------
*/

const seleccionarImagen = (event) => {

    const archivo = event.target.files[0]

    if (!archivo) {
        imagenSeleccionada.value = null
        return
    }

    /*
     * Validar tamaño.
     */
    if (archivo.size > 10 * 1024 * 1024) {

        mostrarMensaje(
            'La fotografía no puede superar los 10 MB.',
            'danger'
        )

        event.target.value = ''
        imagenSeleccionada.value = null
        return
    }

    /*
     * Validar formato.
     */
    const tiposPermitidos = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ]

    if (!tiposPermitidos.includes(archivo.type)) {

        mostrarMensaje(
            'Formato de imagen no permitido.',
            'danger'
        )

        event.target.value = ''
        imagenSeleccionada.value = null
        return
    }

    imagenSeleccionada.value = archivo

    /*
     * Crear vista previa.
     */
    if (imagenPreview.value) {
        URL.revokeObjectURL(imagenPreview.value)
    }

    imagenPreview.value = URL.createObjectURL(archivo)
}

/*
|--------------------------------------------------------------------------
| GUARDAR
|--------------------------------------------------------------------------
*/

const guardar = async () => {

    guardando.value = true

    try {

        const formData = new FormData()

        formData.append(
            'nombre',
            form.nombre
        )

        formData.append(
            'cargo',
            form.cargo || ''
        )

        formData.append(
            'comision',
            form.comision || ''
        )

        formData.append(
            'distritos',
            form.distritos || ''
        )

        formData.append(
            'descripcion',
            form.descripcion || ''
        )

        formData.append(
            'estado',
            form.estado ? '1' : '0'
        )

        /*
         * Solo enviamos imagen si se seleccionó una.
         */
        if (imagenSeleccionada.value) {

            formData.append(
                'imagen',
                imagenSeleccionada.value
            )
        }

        const token = localStorage.getItem('auth_token')

        const config = {
            headers: {
                Authorization: `Bearer ${token}`,
                'Content-Type': 'multipart/form-data'
            }
        }

        if (editando.value && concejalEditando.value) {

            /*
             * Laravel recibe PUT mediante _method
             * porque estamos enviando multipart/form-data.
             */
            formData.append(
                '_method',
                'PUT'
            )

            await axios.post(
                `/api/concejales/${concejalEditando.value.id_concejal}`,
                formData,
                config
            )

            mostrarMensaje(
                'Concejal actualizado correctamente.',
                'success'
            )

        } else {

            await axios.post(
                '/api/concejales',
                formData,
                config
            )

            mostrarMensaje(
                'Concejal registrado correctamente.',
                'success'
            )
        }

        cerrarModal()

        await cargarConcejales()

    } catch (error) {

        console.error(
            'Error al guardar concejal:',
            error
        )

        if (error.response?.data?.errors) {

            const errores = error.response.data.errors

            const primerCampo = Object.keys(errores)[0]

            if (primerCampo) {

                mostrarMensaje(
                    errores[primerCampo][0],
                    'danger'
                )

            } else {

                mostrarMensaje(
                    'No se pudo guardar el concejal.',
                    'danger'
                )
            }

        } else {

            mostrarMensaje(
                error.response?.data?.message ||
                'No se pudo guardar el concejal.',
                'danger'
            )
        }

    } finally {

        guardando.value = false

    }
}

/*
|--------------------------------------------------------------------------
| ELIMINAR
|--------------------------------------------------------------------------
*/

const eliminar = async (concejal) => {

    const confirmar = window.confirm(
        `¿Está seguro de eliminar a "${concejal.nombre}"?`
    )

    if (!confirmar) {
        return
    }

    try {

        await axios.delete(
            `/api/concejales/${concejal.id_concejal}`,
            getConfig()
        )

        mostrarMensaje(
            'Concejal eliminado correctamente.',
            'success'
        )

        await cargarConcejales()

    } catch (error) {

        console.error(
            'Error al eliminar:',
            error
        )

        mostrarMensaje(
            error.response?.data?.message ||
            'No se pudo eliminar el concejal.',
            'danger'
        )
    }
}

/*
|--------------------------------------------------------------------------
| CERRAR MODAL
|--------------------------------------------------------------------------
*/

const cerrarModal = () => {

    mostrarModal.value = false

    editando.value = false

    concejalEditando.value = null

    imagenSeleccionada.value = null

    if (imagenPreview.value) {

        /*
         * Solo revocamos object URLs.
         */
        if (imagenPreview.value.startsWith('blob:')) {
            URL.revokeObjectURL(imagenPreview.value)
        }
    }

    imagenPreview.value = null

    if (imagenInput.value) {
        imagenInput.value.value = ''
    }

    limpiarFormulario()
}

/*
|--------------------------------------------------------------------------
| LIMPIAR FORMULARIO
|--------------------------------------------------------------------------
*/

const limpiarFormulario = () => {

    form.nombre = ''
    form.cargo = ''
    form.comision = ''
    form.distritos = ''
    form.descripcion = ''
    form.estado = true
}

/*
|--------------------------------------------------------------------------
| URL DE IMAGEN
|--------------------------------------------------------------------------
*/

const getImagenUrl = (imagen) => {

    if (!imagen) {
        return ''
    }

    if (
        imagen.startsWith('http://') ||
        imagen.startsWith('https://')
    ) {
        return imagen
    }

    return imagen.startsWith('/')
        ? imagen
        : `/${imagen}`
}

/*
|--------------------------------------------------------------------------
| ERROR DE IMAGEN
|--------------------------------------------------------------------------
*/

const imagenError = (event) => {

    event.target.style.display = 'none'
}

/*
|--------------------------------------------------------------------------
| MENSAJES
|--------------------------------------------------------------------------
*/

const mostrarMensaje = (texto, tipo = 'success') => {

    mensaje.value = texto
    mensajeTipo.value = tipo

    setTimeout(() => {

        mensaje.value = ''

    }, 4000)
}

/*
|--------------------------------------------------------------------------
| INICIO
|--------------------------------------------------------------------------
*/

onMounted(() => {
    cargarConcejales()
})
</script>

<style scoped>
.modal-backdrop-custom {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.55);
    z-index: 1050;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
}

.modal-dialog-custom {
    width: 100%;
    max-width: 900px;
    margin: auto;
}

.foto-concejal {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    overflow: hidden;
    background: #f1f1f1;
}

.foto-concejal img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.foto-vacia {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
}

.preview-container {
    width: 180px;
    height: 220px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 5px;
    background: #f8f9fa;
}

.imagen-preview {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 5px;
}

.table td {
    vertical-align: middle;
}
</style>
