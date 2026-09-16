<template>
    <div class="gestion-configuracion">
        <div class="page-header">
            <div>
                <h1>Configuración Institucional</h1>
                <p>
                    Administra la información general del Consejo Municipal
                    de Potosí.
                </p>
            </div>
        </div>

        <!-- Mensajes -->
        <div v-if="mensaje" class="mensaje mensaje-exito">
            {{ mensaje }}
        </div>

        <div v-if="error" class="mensaje mensaje-error">
            {{ error }}
        </div>

        <!-- Cargando -->
        <div v-if="cargando" class="cargando">
            Cargando configuración institucional...
        </div>

        <!-- Configuraciones -->
        <div v-else class="configuracion-card">
            <div class="card-header">
                <h2>Información institucional</h2>
                <p>
                    Modifica los valores que utiliza el sitio web público.
                </p>
            </div>

            <div class="configuracion-lista">
                <div
                    v-for="configuracion in configuraciones"
                    :key="configuracion.id_configuracion"
                    class="configuracion-item"
                >
                    <div class="configuracion-info">
                        <label>
                            {{ obtenerTitulo(configuracion.clave) }}
                        </label>

                        <span v-if="configuracion.descripcion">
                            {{ configuracion.descripcion }}
                        </span>
                    </div>

                    <div class="configuracion-edicion">
                        <!-- Nombre de institución -->
                        <input
                            v-if="configuracion.clave === 'nombre_institucion'"
                            v-model="configuracion.valor"
                            type="text"
                            placeholder="Nombre de la institución"
                        />

                        <!-- Periodo constitucional -->
                        <input
                            v-else-if="
                                configuracion.clave ===
                                'periodo_constitucional'
                            "
                            v-model="configuracion.valor"
                            type="text"
                            placeholder="Ejemplo: 2026-2027"
                        />

                        <!-- Gestión actual -->
                        <input
                            v-else-if="
                                configuracion.clave === 'gestion_actual'
                            "
                            v-model="configuracion.valor"
                            type="text"
                            placeholder="Ejemplo: 2026"
                        />

                        <!-- Dirección -->
                        <input
                            v-else-if="configuracion.clave === 'direccion'"
                            v-model="configuracion.valor"
                            type="text"
                            placeholder="Dirección institucional"
                        />

                        <!-- Teléfono -->
                        <input
                            v-else-if="configuracion.clave === 'telefono'"
                            v-model="configuracion.valor"
                            type="text"
                            placeholder="Teléfono institucional"
                        />

                        <!-- Correo -->
                        <input
                            v-else-if="configuracion.clave === 'correo'"
                            v-model="configuracion.valor"
                            type="email"
                            placeholder="Correo electrónico institucional"
                        />

                        <!-- Campo general -->
                        <input
                            v-else
                            v-model="configuracion.valor"
                            type="text"
                            placeholder="Valor"
                        />

                        <button
                            type="button"
                            class="btn-guardar"
                            :disabled="
                                guardando === configuracion.clave
                            "
                            @click="guardarConfiguracion(configuracion)"
                        >
                            {{
                                guardando === configuracion.clave
                                    ? 'Guardando...'
                                    : 'Guardar'
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información -->
        <div class="info-card">
            <div class="info-icon">ℹ️</div>

            <div>
                <h3>Importante</h3>

                <p>
                    Los cambios realizados aquí podrán ser utilizados por
                    las páginas públicas del sitio web, como Directiva,
                    Inicio y otras secciones institucionales.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'

const configuraciones = ref([])
const cargando = ref(true)
const guardando = ref(null)
const mensaje = ref('')
const error = ref('')

const authToken = localStorage.getItem('auth_token')

const axiosConfig = {
    headers: {
        Authorization: `Bearer ${authToken}`,
        Accept: 'application/json'
    }
}

/**
 * Cargar configuraciones desde el API.
 */
const cargarConfiguraciones = async () => {
    cargando.value = true
    error.value = ''

    try {
        const response = await axios.get(
            '/api/public/configuracion'
        )

        if (response.data && response.data.success) {
            configuraciones.value = response.data.data || []
        } else {
            error.value =
                'No se pudo cargar la configuración institucional.'
        }
    } catch (err) {
        console.error(
            'Error al cargar configuración:',
            err
        )

        error.value =
            'Ocurrió un error al cargar la configuración institucional.'
    } finally {
        cargando.value = false
    }
}

/**
 * Guardar una configuración.
 */
const guardarConfiguracion = async (configuracion) => {
    mensaje.value = ''
    error.value = ''
    guardando.value = configuracion.clave

    try {
        const response = await axios.put(
            `/api/configuracion/${configuracion.clave}`,
            {
                valor: configuracion.valor ?? ''
            },
            axiosConfig
        )

        if (response.data && response.data.success) {
            const indice = configuraciones.value.findIndex(
                item =>
                    item.id_configuracion ===
                    configuracion.id_configuracion
            )

            if (indice !== -1) {
                configuraciones.value[indice] =
                    response.data.data
            }

            mensaje.value =
                'Configuración actualizada correctamente.'

            setTimeout(() => {
                mensaje.value = ''
            }, 3000)
        } else {
            error.value =
                'No se pudo actualizar la configuración.'
        }
    } catch (err) {
        console.error(
            'Error al guardar configuración:',
            err
        )

        if (
            err.response &&
            err.response.status === 401
        ) {
            error.value =
                'Tu sesión ha expirado. Inicia sesión nuevamente.'
        } else if (
            err.response &&
            err.response.data &&
            err.response.data.message
        ) {
            error.value =
                err.response.data.message
        } else {
            error.value =
                'Ocurrió un error al guardar la configuración.'
        }
    } finally {
        guardando.value = null
    }
}

/**
 * Convertir las claves técnicas en nombres amigables.
 */
const obtenerTitulo = (clave) => {
    const titulos = {
        nombre_institucion:
            'Nombre de la institución',

        periodo_constitucional:
            'Período constitucional',

        gestion_actual:
            'Gestión actual',

        direccion:
            'Dirección institucional',

        telefono:
            'Teléfono institucional',

        correo:
            'Correo electrónico institucional'
    }

    return titulos[clave] || clave
}

onMounted(() => {
    cargarConfiguraciones()
})
</script>

<style scoped>
.gestion-configuracion {
    width: 100%;
    padding: 24px;
    box-sizing: border-box;
}

/* Encabezado */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.page-header h1 {
    margin: 0 0 6px;
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
}

.page-header p {
    margin: 0;
    color: #6b7280;
    font-size: 15px;
}

/* Mensajes */

.mensaje {
    width: 100%;
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-sizing: border-box;
    font-size: 14px;
}

.mensaje-exito {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

.mensaje-error {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

/* Cargando */

.cargando {
    padding: 40px;
    text-align: center;
    color: #6b7280;
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

/* Card principal */

.configuracion-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.card-header {
    padding: 22px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.card-header h2 {
    margin: 0 0 5px;
    font-size: 20px;
    color: #1f2937;
}

.card-header p {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
}

/* Lista */

.configuracion-lista {
    width: 100%;
}

.configuracion-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
    padding: 22px 24px;
    border-bottom: 1px solid #f0f0f0;
}

.configuracion-item:last-child {
    border-bottom: none;
}

.configuracion-info {
    flex: 1;
    min-width: 220px;
}

.configuracion-info label {
    display: block;
    margin-bottom: 5px;
    font-size: 15px;
    font-weight: 600;
    color: #1f2937;
}

.configuracion-info span {
    display: block;
    font-size: 13px;
    color: #6b7280;
}

/* Edición */

.configuracion-edicion {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 55%;
}

.configuracion-edicion input {
    flex: 1;
    min-width: 0;
    height: 42px;
    padding: 0 13px;
    border: 1px solid #d1d5db;
    border-radius: 7px;
    outline: none;
    font-size: 14px;
    color: #1f2937;
    background: white;
    box-sizing: border-box;
}

.configuracion-edicion input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

.btn-guardar {
    height: 42px;
    padding: 0 18px;
    border: none;
    border-radius: 7px;
    background: #2563eb;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: 0.2s;
}

.btn-guardar:hover:not(:disabled) {
    background: #1d4ed8;
}

.btn-guardar:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Información */

.info-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-top: 24px;
    padding: 18px 20px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
}

.info-icon {
    font-size: 22px;
}

.info-card h3 {
    margin: 0 0 5px;
    color: #1e40af;
    font-size: 15px;
}

.info-card p {
    margin: 0;
    color: #374151;
    font-size: 14px;
    line-height: 1.5;
}

/* Responsive */

@media (max-width: 900px) {
    .configuracion-item {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }

    .configuracion-edicion {
        width: 100%;
    }
}

@media (max-width: 600px) {
    .gestion-configuracion {
        padding: 15px;
    }

    .page-header h1 {
        font-size: 23px;
    }

    .configuracion-item {
        padding: 18px;
    }

    .configuracion-edicion {
        flex-direction: column;
        align-items: stretch;
    }

    .configuracion-edicion input {
        width: 100%;
    }

    .btn-guardar {
        width: 100%;
    }
}
</style>
