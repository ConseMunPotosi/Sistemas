<template>
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fas fa-video me-2"></i>
                    Gestión de Sesiones
                </h2>

                <p class="text-muted mb-0">
                    Administración de las sesiones del Concejo Municipal de Potosí.
                </p>
            </div>

            <button
                class="btn btn-primary"
                @click="abrirFormulario"
            >
                <i class="fas fa-plus me-2"></i>
                Nueva sesión
            </button>
        </div>


        <!-- =====================================================
             FORMULARIO
        ====================================================== -->
        <div
            v-if="mostrarFormulario"
            class="card shadow-sm border-0 mb-4"
        >

            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    {{ editando ? 'Editar sesión' : 'Nueva sesión' }}
                </h5>
            </div>


            <div class="card-body">

                <form @submit.prevent="guardarSesion">

                    <div class="row g-3">

                        <!-- Número -->
                        <div class="col-md-2">

                            <label class="form-label">
                                N.º de sesión
                            </label>

                            <input
                                v-model.number="form.numero"
                                type="number"
                                min="1"
                                class="form-control"
                                required
                            >

                        </div>


                        <!-- Gestión -->
                        <div class="col-md-2">

                            <label class="form-label">
                                Gestión
                            </label>

                            <input
                                v-model.number="form.gestion"
                                type="number"
                                min="2000"
                                max="2100"
                                class="form-control"
                                required
                            >

                        </div>


                        <!-- Tipo -->
                        <div class="col-md-3">

                            <label class="form-label">
                                Tipo de sesión
                            </label>

                            <select
                                v-model="form.tipo_sesion"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Seleccione...
                                </option>

                                <option value="Ordinaria">
                                    Ordinaria
                                </option>

                                <option value="Extraordinaria">
                                    Extraordinaria
                                </option>

                            </select>

                        </div>


                        <!-- Fecha -->
                        <div class="col-md-3">

                            <label class="form-label">
                                Fecha
                            </label>

                            <input
                                v-model="form.fecha_sesion"
                                type="date"
                                class="form-control"
                                required
                            >

                        </div>


                        <!-- Estado -->
                        <div class="col-md-2">

                            <label class="form-label">
                                Estado
                            </label>

                            <select
                                v-model="form.estado"
                                class="form-select"
                            >

                                <option :value="true">
                                    Publicada
                                </option>

                                <option :value="false">
                                    Oculta
                                </option>

                            </select>

                        </div>


                        <!-- Título -->
                        <div class="col-12">

                            <label class="form-label">
                                Título
                            </label>

                            <input
                                v-model="form.titulo"
                                type="text"
                                class="form-control"
                                maxlength="255"
                                required
                            >

                        </div>


                        <!-- Video -->
                        <div class="col-12">

                            <label class="form-label">
                                Video de la sesión
                            </label>

                            <input
                                ref="videoInput"
                                type="file"
                                class="form-control"
                                accept=".mp4,.mov,.avi,.webm,.mkv"
                                @change="seleccionarVideo"
                                :required="!editando"
                            >

                            <div class="form-text">
                                Formatos permitidos: MP4, MOV, AVI, WEBM o MKV.
                            </div>

                            <div
                                v-if="videoSeleccionado"
                                class="mt-2 text-success"
                            >

                                <i class="fas fa-check-circle me-1"></i>

                                {{ videoSeleccionado.name }}

                            </div>


                            <div
                                v-if="editando && form.video"
                                class="mt-2 text-muted"
                            >

                                Video actual:

                                <strong>
                                    {{ obtenerNombreVideo(form.video) }}
                                </strong>

                            </div>

                        </div>

                    </div>


                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="cerrarFormulario"
                            :disabled="guardando"
                        >
                            Cancelar
                        </button>


                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="guardando"
                        >

                            <span v-if="guardando">

                                <span
                                    class="spinner-border spinner-border-sm me-2"
                                ></span>

                                Guardando...

                            </span>


                            <span v-else>

                                <i class="fas fa-save me-2"></i>

                                {{ editando ? 'Actualizar' : 'Guardar' }}

                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>


        <!-- =====================================================
             LISTADO
        ====================================================== -->
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <h5 class="mb-0 fw-bold">
                    Sesiones registradas
                </h5>

            </div>


            <div class="card-body p-0">

                <!-- Cargando -->
                <div
                    v-if="cargando"
                    class="text-center py-5"
                >

                    <div class="spinner-border text-primary"></div>

                    <p class="mt-2 text-muted">
                        Cargando sesiones...
                    </p>

                </div>


                <!-- Sin registros -->
                <div
                    v-else-if="sesiones.length === 0"
                    class="text-center py-5"
                >

                    <i
                        class="fas fa-video-slash fa-2x text-muted mb-3"
                    ></i>

                    <p class="text-muted mb-0">
                        No existen sesiones registradas.
                    </p>

                </div>


                <!-- Tabla -->
                <div
                    v-else
                    class="table-responsive"
                >

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>N.º</th>
                                <th>Gestión</th>
                                <th>Tipo</th>
                                <th>Título</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Video</th>
                                <th class="text-center">
                                    Acciones
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="sesion in sesiones"
                                :key="sesion.id_sesion"
                            >

                                <td>
                                    {{ sesion.numero }}
                                </td>


                                <td>
                                    {{ sesion.gestion }}
                                </td>


                                <td>

                                    <span class="badge bg-info">
                                        {{ sesion.tipo_sesion }}
                                    </span>

                                </td>


                                <td>
                                    <strong>
                                        {{ sesion.titulo }}
                                    </strong>
                                </td>


                                <td>
                                    {{ formatearFecha(sesion.fecha_sesion) }}
                                </td>


                                <td>

                                    <span
                                        v-if="sesion.estado"
                                        class="badge bg-success"
                                    >
                                        Publicada
                                    </span>

                                    <span
                                        v-else
                                        class="badge bg-secondary"
                                    >
                                        Oculta
                                    </span>

                                </td>


                                <td>

                                    <a
                                        v-if="sesion.video"
                                        :href="getVideoUrl(sesion.video)"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="btn btn-sm btn-outline-primary"
                                    >

                                        <i class="fas fa-play me-1"></i>
                                        Ver

                                    </a>


                                    <span
                                        v-else
                                        class="text-muted"
                                    >
                                        Sin video
                                    </span>

                                </td>


                                <td class="text-center">

                                    <button
                                        class="btn btn-sm btn-outline-warning me-1"
                                        @click="editarSesion(sesion)"
                                        title="Editar"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>


                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        @click="eliminarSesion(sesion.id_sesion)"
                                        title="Eliminar"
                                    >
                                        <i class="fas fa-trash"></i>
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


<script setup>

import { onMounted, ref } from 'vue';
import axios from 'axios';


/*
|--------------------------------------------------------------------------
| Estado
|--------------------------------------------------------------------------
*/

const sesiones = ref([]);

const cargando = ref(false);
const guardando = ref(false);

const mostrarFormulario = ref(false);
const editando = ref(false);

const videoSeleccionado = ref(null);
const videoInput = ref(null);


/*
|--------------------------------------------------------------------------
| Formulario
|--------------------------------------------------------------------------
*/

const form = ref({
    id_sesion: null,
    numero: '',
    gestion: new Date().getFullYear(),
    tipo_sesion: '',
    titulo: '',
    fecha_sesion: '',
    video: '',
    estado: true
});


/*
|--------------------------------------------------------------------------
| Configuración de autenticación
|--------------------------------------------------------------------------
|
| El token ya se encuentra almacenado por el sistema al iniciar sesión.
| Aquí lo enviamos explícitamente a la API.
|
*/

const getAuthConfig = () => {

    const token = localStorage.getItem('auth_token');

    return {
        headers: {
            Authorization: token ? `Bearer ${token}` : '',
            Accept: 'application/json'
        }
    };
};


/*
|--------------------------------------------------------------------------
| Reset formulario
|--------------------------------------------------------------------------
*/

const resetFormulario = () => {

    form.value = {
        id_sesion: null,
        numero: '',
        gestion: new Date().getFullYear(),
        tipo_sesion: '',
        titulo: '',
        fecha_sesion: '',
        video: '',
        estado: true
    };

    videoSeleccionado.value = null;


    if (videoInput.value) {
        videoInput.value.value = '';
    }

};


/*
|--------------------------------------------------------------------------
| Abrir formulario
|--------------------------------------------------------------------------
*/

const abrirFormulario = () => {

    editando.value = false;

    resetFormulario();

    mostrarFormulario.value = true;

};


/*
|--------------------------------------------------------------------------
| Cerrar formulario
|--------------------------------------------------------------------------
*/

const cerrarFormulario = () => {

    mostrarFormulario.value = false;

    editando.value = false;

    resetFormulario();

};


/*
|--------------------------------------------------------------------------
| Seleccionar video
|--------------------------------------------------------------------------
*/

const seleccionarVideo = (event) => {

    videoSeleccionado.value =
        event.target.files[0] || null;

};


/*
|--------------------------------------------------------------------------
| Cargar sesiones
|--------------------------------------------------------------------------
*/

const cargarSesiones = async () => {

    cargando.value = true;

    try {

        const response = await axios.get(
            '/api/sesiones',
            getAuthConfig()
        );

        sesiones.value =
            response.data.data || [];

    } catch (error) {

        console.error(
            'Error al cargar sesiones:',
            error
        );

        if (error.response?.status === 401) {

            alert(
                'La sesión de usuario ha expirado. Vuelva a iniciar sesión.'
            );

        } else {

            alert(
                'No se pudieron cargar las sesiones.'
            );

        }

    } finally {

        cargando.value = false;

    }

};


/*
|--------------------------------------------------------------------------
| Guardar sesión
|--------------------------------------------------------------------------
*/

const guardarSesion = async () => {

    guardando.value = true;

    try {

        const formData = new FormData();


        formData.append(
            'numero',
            form.value.numero
        );

        formData.append(
            'gestion',
            form.value.gestion
        );

        formData.append(
            'tipo_sesion',
            form.value.tipo_sesion
        );

        formData.append(
            'titulo',
            form.value.titulo
        );

        formData.append(
            'fecha_sesion',
            form.value.fecha_sesion
        );

        formData.append(
            'estado',
            form.value.estado ? '1' : '0'
        );


        /*
         * Agregar video solamente si se seleccionó uno.
         */
        if (videoSeleccionado.value) {

            formData.append(
                'video',
                videoSeleccionado.value
            );

        }


        let config = getAuthConfig();

        config.headers['Content-Type'] =
            'multipart/form-data';


        /*
         * Crear
         */
        if (!editando.value) {

            await axios.post(
                '/api/sesiones',
                formData,
                config
            );

            alert(
                '✅ Sesión creada y video subido correctamente.'
            );

        }


        /*
         * Actualizar
         *
         * Usamos POST + _method=PUT porque las cargas
         * multipart son más compatibles de esta manera.
         */
        else {

            formData.append(
                '_method',
                'PUT'
            );

            await axios.post(
                `/api/sesiones/${form.value.id_sesion}`,
                formData,
                config
            );

            alert(
                '✅ Sesión actualizada correctamente.'
            );

        }


        cerrarFormulario();

        await cargarSesiones();


    } catch (error) {

        console.error(
            'Error al guardar sesión:',
            error
        );


        if (error.response?.status === 401) {

            alert(
                '❌ No autenticado. Vuelva a iniciar sesión.'
            );

        } else if (
            error.response?.data?.message
        ) {

            alert(
                `❌ ${error.response.data.message}`
            );

        } else if (
            error.response?.data?.errors
        ) {

            const errores =
                Object.values(
                    error.response.data.errors
                )
                    .flat()
                    .join('\n');

            alert(
                `❌ Error de validación:\n${errores}`
            );

        } else {

            alert(
                '❌ Error al guardar la sesión.'
            );

        }

    } finally {

        guardando.value = false;

    }

};


/*
|--------------------------------------------------------------------------
| Editar sesión
|--------------------------------------------------------------------------
*/

const editarSesion = (sesion) => {

    editando.value = true;

    form.value = {
        id_sesion: sesion.id_sesion,
        numero: sesion.numero,
        gestion: sesion.gestion,
        tipo_sesion: sesion.tipo_sesion,
        titulo: sesion.titulo,
        fecha_sesion: convertirFecha(
            sesion.fecha_sesion
        ),
        video: sesion.video || '',
        estado: Boolean(sesion.estado)
    };

    videoSeleccionado.value = null;

    mostrarFormulario.value = true;

};


/*
|--------------------------------------------------------------------------
| Eliminar sesión
|--------------------------------------------------------------------------
*/

const eliminarSesion = async (id) => {

    if (
        !confirm(
            '¿Está seguro de eliminar esta sesión y su video?'
        )
    ) {
        return;
    }


    try {

        await axios.delete(
            `/api/sesiones/${id}`,
            getAuthConfig()
        );

        alert(
            '✅ Sesión eliminada correctamente.'
        );

        await cargarSesiones();

    } catch (error) {

        console.error(
            'Error al eliminar sesión:',
            error
        );


        if (error.response?.status === 401) {

            alert(
                '❌ No autenticado. Vuelva a iniciar sesión.'
            );

        } else {

            alert(
                '❌ No se pudo eliminar la sesión.'
            );

        }

    }

};


/*
|--------------------------------------------------------------------------
| Convertir fecha
|--------------------------------------------------------------------------
*/

const convertirFecha = (fecha) => {

    if (!fecha) {
        return '';
    }

    return fecha.substring(0, 10);

};


/*
|--------------------------------------------------------------------------
| Formatear fecha
|--------------------------------------------------------------------------
*/

const formatearFecha = (fecha) => {

    if (!fecha) {
        return '';
    }

    const partes =
        fecha
            .substring(0, 10)
            .split('-');

    if (partes.length !== 3) {
        return fecha;
    }

    return `${partes[2]}/${partes[1]}/${partes[0]}`;

};


/*
|--------------------------------------------------------------------------
| Obtener nombre de video
|--------------------------------------------------------------------------
*/

const obtenerNombreVideo = (ruta) => {

    if (!ruta) {
        return '';
    }

    return ruta.split('/').pop();

};


/*
|--------------------------------------------------------------------------
| URL del video
|--------------------------------------------------------------------------
*/

const getVideoUrl = (ruta) => {

    if (!ruta) {
        return '#';
    }

    return ruta.startsWith('/')
        ? ruta
        : `/${ruta}`;

};


/*
|--------------------------------------------------------------------------
| Inicialización
|--------------------------------------------------------------------------
*/

onMounted(() => {

    cargarSesiones();

});

</script>
