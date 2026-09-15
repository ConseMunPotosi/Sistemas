<template>
    <div class="principal">

        <!-- =====================================================
             ENCABEZADO
        ====================================================== -->
        <section class="sesion-header">

            <div class="titulo-icono">
                <i class="fas fa-video"></i>
            </div>

            <h2 class="sesion-titulo">
                Sesiones del Concejo Municipal de Potosí
            </h2>

            <p class="sesion-subtitulo">
                Consulta las grabaciones de las sesiones ordinarias y
                extraordinarias del Concejo Municipal de Potosí, como parte
                del compromiso institucional con la transparencia y el acceso
                ciudadano a la información pública.
            </p>

        </section>


        <!-- =====================================================
             FILTROS
        ====================================================== -->
        <section
            v-if="!cargando && sesiones.length > 0"
            class="filtros-container"
        >

            <div class="filtro-item">

                <label>
                    Gestión
                </label>

                <select
                    v-model="gestionSeleccionada"
                    class="form-select"
                >

                    <option value="">
                        Todas las gestiones
                    </option>

                    <option
                        v-for="gestion in gestionesDisponibles"
                        :key="gestion"
                        :value="gestion"
                    >
                        {{ gestion }}
                    </option>

                </select>

            </div>


            <div class="filtro-item">

                <label>
                    Tipo de sesión
                </label>

                <select
                    v-model="tipoSeleccionado"
                    class="form-select"
                >

                    <option value="">
                        Todos los tipos
                    </option>

                    <option value="Ordinaria">
                        Ordinarias
                    </option>

                    <option value="Extraordinaria">
                        Extraordinarias
                    </option>

                </select>

            </div>


            <div class="resultado-info">

                <strong>
                    {{ sesionesFiltradas.length }}
                </strong>

                <span>
                    {{ sesionesFiltradas.length === 1
                        ? 'sesión disponible'
                        : 'sesiones disponibles'
                    }}
                </span>

            </div>

        </section>


        <!-- =====================================================
             CARGANDO
        ====================================================== -->
        <div
            v-if="cargando"
            class="estado-container"
        >

            <div class="spinner-border text-danger"></div>

            <p>
                Cargando sesiones...
            </p>

        </div>


        <!-- =====================================================
             ERROR
        ====================================================== -->
        <div
            v-else-if="error"
            class="alert alert-danger estado-alerta"
        >

            <i class="fas fa-exclamation-triangle me-2"></i>

            {{ error }}

        </div>


        <!-- =====================================================
             SIN SESIONES
        ====================================================== -->
        <div
            v-else-if="sesiones.length === 0"
            class="sin-sesiones"
        >

            <div class="sin-sesiones-icono">
                <i class="fas fa-video-slash"></i>
            </div>

            <h4>
                No hay sesiones publicadas
            </h4>

            <p>
                Actualmente no existen sesiones disponibles para consulta pública.
            </p>

        </div>


        <!-- =====================================================
             SIN RESULTADOS DE FILTRO
        ====================================================== -->
        <div
            v-else-if="sesionesFiltradas.length === 0"
            class="sin-sesiones"
        >

            <div class="sin-sesiones-icono">
                <i class="fas fa-search"></i>
            </div>

            <h4>
                No se encontraron sesiones
            </h4>

            <p>
                No existen sesiones que coincidan con los filtros seleccionados.
            </p>

            <button
                class="btn btn-outline-danger mt-3"
                @click="limpiarFiltros"
            >
                <i class="fas fa-undo me-2"></i>
                Limpiar filtros
            </button>

        </div>


        <!-- =====================================================
             SESIONES
        ====================================================== -->
        <div
            v-else
            class="row g-4 sesiones-grid"
        >

            <div
                v-for="sesion in sesionesFiltradas"
                :key="sesion.id_sesion"
                class="col-lg-6"
            >

                <article class="sesion-card">

                    <!-- VIDEO -->
                    <div class="video-container">

                        <video
                            v-if="sesion.video"
                            class="video-player"
                            controls
                            preload="metadata"
                            playsinline
                        >

                            <source
                                :src="getVideoUrl(sesion.video)"
                                type="video/mp4"
                            >

                            Su navegador no soporta la reproducción de video.

                        </video>


                        <div
                            v-else
                            class="video-sin-archivo"
                        >

                            <i class="fas fa-video-slash"></i>

                            <span>
                                Video no disponible
                            </span>

                        </div>

                    </div>


                    <!-- INFORMACIÓN -->
                    <div class="sesion-body">

                        <!-- Tipo -->
                        <div class="sesion-meta-top">

                            <span
                                class="tipo-badge"
                                :class="getTipoClase(sesion.tipo_sesion)"
                            >
                                {{ sesion.tipo_sesion }}
                            </span>

                            <span class="gestion-badge">
                                Gestión {{ sesion.gestion }}
                            </span>

                        </div>


                        <!-- Título -->
                        <h3 class="sesion-card-title">
                            {{ sesion.titulo }}
                        </h3>


                        <!-- Datos -->
                        <div class="sesion-datos">

                            <div class="dato">

                                <i class="fas fa-calendar-alt"></i>

                                <span>
                                    {{ formatearFecha(sesion.fecha_sesion) }}
                                </span>

                            </div>


                            <div class="dato">

                                <i class="fas fa-list-ol"></i>

                                <span>
                                    Sesión N.º {{ sesion.numero }}
                                </span>

                            </div>

                        </div>


                        <!-- Acciones -->
                        <div class="sesion-acciones">

                            <a
                                v-if="sesion.video"
                                :href="getVideoUrl(sesion.video)"
                                :download="obtenerNombreVideo(sesion.video)"
                                class="btn btn-outline-danger"
                            >

                                <i class="fas fa-download me-2"></i>

                                Descargar video

                            </a>

                        </div>

                    </div>

                </article>

            </div>

        </div>

    </div>
</template>


<script setup>

import { computed, onMounted, ref } from 'vue';
import axios from 'axios';


/*
|--------------------------------------------------------------------------
| Estado
|--------------------------------------------------------------------------
*/

const sesiones = ref([]);

const cargando = ref(false);

const error = ref('');

const gestionSeleccionada = ref('');

const tipoSeleccionado = ref('');


/*
|--------------------------------------------------------------------------
| Gestiones disponibles
|--------------------------------------------------------------------------
*/

const gestionesDisponibles = computed(() => {

    const gestiones = sesiones.value
        .map((sesion) => sesion.gestion)
        .filter(Boolean);

    return [...new Set(gestiones)]
        .sort((a, b) => Number(b) - Number(a));

});


/*
|--------------------------------------------------------------------------
| Sesiones filtradas
|--------------------------------------------------------------------------
*/

const sesionesFiltradas = computed(() => {

    return sesiones.value.filter((sesion) => {

        const coincideGestion =
            !gestionSeleccionada.value ||
            String(sesion.gestion) ===
            String(gestionSeleccionada.value);


        const coincideTipo =
            !tipoSeleccionado.value ||
            sesion.tipo_sesion ===
            tipoSeleccionado.value;


        return coincideGestion && coincideTipo;

    });

});


/*
|--------------------------------------------------------------------------
| Cargar sesiones públicas
|--------------------------------------------------------------------------
*/

const cargarSesiones = async () => {

    cargando.value = true;

    error.value = '';

    try {

        const response = await axios.get(
            '/api/public/sesiones'
        );

        sesiones.value =
            response.data.data || [];

    } catch (err) {

        console.error(
            'Error al cargar sesiones:',
            err
        );

        error.value =
            'No se pudieron cargar las sesiones en este momento.';

    } finally {

        cargando.value = false;

    }

};


/*
|--------------------------------------------------------------------------
| Limpiar filtros
|--------------------------------------------------------------------------
*/

const limpiarFiltros = () => {

    gestionSeleccionada.value = '';

    tipoSeleccionado.value = '';

};


/*
|--------------------------------------------------------------------------
| Fecha
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
| Clase según tipo
|--------------------------------------------------------------------------
*/

const getTipoClase = (tipo) => {

    if (tipo === 'Extraordinaria') {
        return 'tipo-extraordinaria';
    }

    return 'tipo-ordinaria';

};


/*
|--------------------------------------------------------------------------
| URL pública
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
| Nombre archivo
|--------------------------------------------------------------------------
*/

const obtenerNombreVideo = (ruta) => {

    if (!ruta) {
        return 'video.mp4';
    }

    return ruta.split('/').pop();

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


<style scoped>

.principal {
    width: 100%;
    margin: 0 auto;
    padding: 1.5rem;
    background-image: url('/images/fondo.png');
    background-size: cover;
    background-position: center;
    min-height: 100%;
}


/* =========================================================
   ENCABEZADO
========================================================= */

.sesion-header {
    max-width: 1100px;
    margin: 0 auto 2rem;
    padding: 1rem;
    text-align: center;
}


.titulo-icono {
    width: 62px;
    height: 62px;
    margin: 0 auto 1rem;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #cc0000;
    color: #fff;

    font-size: 1.5rem;

    box-shadow:
        0 8px 20px rgba(204, 0, 0, 0.20);
}


.sesion-titulo {
    margin: 0 0 0.75rem;

    color: #cc0000;

    font-size: 2.2rem;

    font-weight: 800;
}


.sesion-subtitulo {
    max-width: 1000px;

    margin: 0 auto;

    color: #5d5d5d;

    font-size: 1.05rem;

    line-height: 1.7;

    text-align: justify;
}


/* =========================================================
   FILTROS
========================================================= */

.filtros-container {
    max-width: 1200px;

    margin: 0 auto 2rem;

    padding: 1.2rem;

    display: flex;

    align-items: end;

    gap: 1rem;

    background: rgba(255, 255, 255, 0.95);

    border-radius: 12px;

    box-shadow:
        0 4px 18px rgba(0, 0, 0, 0.08);
}


.filtro-item {
    flex: 1;

    min-width: 180px;
}


.filtro-item label {
    display: block;

    margin-bottom: 0.4rem;

    color: #333;

    font-weight: 600;

    font-size: 0.9rem;
}


.resultado-info {
    min-width: 190px;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 0.35rem;

    padding: 0.7rem 1rem;

    color: #666;

    background: #f8f8f8;

    border-radius: 8px;
}


.resultado-info strong {
    color: #cc0000;

    font-size: 1.15rem;
}


/* =========================================================
   GRID
========================================================= */

.sesiones-grid {
    max-width: 1200px;

    margin: 0 auto;
}


/* =========================================================
   TARJETA
========================================================= */

.sesion-card {
    height: 100%;

    overflow: hidden;

    background: #fff;

    border-radius: 14px;

    border: 1px solid rgba(0, 0, 0, 0.06);

    box-shadow:
        0 5px 20px rgba(0, 0, 0, 0.08);

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease;
}


.sesion-card:hover {
    transform: translateY(-4px);

    box-shadow:
        0 12px 28px rgba(0, 0, 0, 0.12);
}


/* =========================================================
   VIDEO
========================================================= */

.video-container {
    width: 100%;

    background: #000;
}


.video-player {
    display: block;

    width: 100%;

    aspect-ratio: 16 / 9;

    object-fit: contain;

    background: #000;
}


.video-sin-archivo {
    aspect-ratio: 16 / 9;

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    gap: 0.6rem;

    color: #888;

    background: #f0f0f0;
}


.video-sin-archivo i {
    font-size: 2.8rem;
}


/* =========================================================
   CUERPO
========================================================= */

.sesion-body {
    padding: 1.2rem;
}


.sesion-meta-top {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 0.5rem;

    margin-bottom: 0.7rem;
}


.tipo-badge,
.gestion-badge {
    display: inline-flex;

    align-items: center;

    padding: 0.4rem 0.7rem;

    border-radius: 20px;

    font-size: 0.78rem;

    font-weight: 700;
}


.tipo-ordinaria {
    background: #e8f5e9;

    color: #2e7d32;
}


.tipo-extraordinaria {
    background: #fff3e0;

    color: #e65100;
}


.gestion-badge {
    color: #666;

    background: #f2f2f2;
}


.sesion-card-title {
    margin: 0 0 1rem;

    color: #222;

    font-size: 1.25rem;

    font-weight: 800;

    line-height: 1.4;
}


/* =========================================================
   DATOS
========================================================= */

.sesion-datos {
    display: flex;

    flex-wrap: wrap;

    gap: 1rem;

    padding-top: 0.8rem;

    border-top: 1px solid #eee;
}


.dato {
    display: flex;

    align-items: center;

    gap: 0.5rem;

    color: #666;

    font-size: 0.9rem;
}


.dato i {
    color: #cc0000;
}


/* =========================================================
   ACCIONES
========================================================= */

.sesion-acciones {
    margin-top: 1rem;
}


/* =========================================================
   ESTADOS
========================================================= */

.estado-container {
    padding: 5rem 1rem;

    text-align: center;

    color: #777;
}


.estado-container p {
    margin-top: 1rem;

    margin-bottom: 0;
}


.estado-alerta {
    max-width: 900px;

    margin: 2rem auto;
}


.sin-sesiones {
    max-width: 700px;

    margin: 2rem auto;

    padding: 4rem 1rem;

    text-align: center;
}


.sin-sesiones-icono {
    margin-bottom: 1rem;

    color: #bbb;

    font-size: 3.5rem;
}


.sin-sesiones h4 {
    color: #555;

    font-weight: 700;
}


.sin-sesiones p {
    color: #888;

    margin-bottom: 0;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .principal {
        padding: 1rem;
    }


    .sesion-titulo {
        font-size: 1.8rem;
    }


    .sesion-subtitulo {
        font-size: 0.95rem;
    }


    .filtros-container {
        flex-direction: column;

        align-items: stretch;
    }


    .filtro-item,
    .resultado-info {
        width: 100%;
        min-width: 0;
    }


    .sesion-meta-top {
        align-items: flex-start;

        flex-direction: column;
    }


    .sesion-datos {
        flex-direction: column;

        gap: 0.6rem;
    }

}

</style>
