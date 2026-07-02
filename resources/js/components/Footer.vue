<template>
    <footer class="bg-dark text-white mt-5">
        <div class="container-fluid py-4">
            <div class="row align-items-start">
                <!-- Columna Izquierda -->
                <div class="col-md-4 text-start">
                    <h5>Concejo Municipal de Potosí</h5>
                    <p class="text-muted">
                        El Concejo Municipal de Potosí es el Órgano Legislativo, Deliberativo y Fiscalizador del Gobierno Municipal de Potosí. Está compuesto por 11 concejalas y concejales elegidos en votación universal, directa y secreta por un período de cinco años.
                    </p>
                </div>

                <!-- Columna Centro -->
                <div class="col-md-4 d-flex flex-column align-items-center">
                    <div>
                        <img src="../../../public/images/escudo-potosi.png" alt="Escudo" class="escudo">
                    </div>
                    <div class="social-icons-group">
                        <button class="social-icon youtube" aria-label="Youtube">
                            <i class="bi bi-youtube"></i>
                        </button>
                        <button class="social-icon facebook" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </button>
                        <button class="social-icon instagram" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </button>
                        <button class="social-icon twitter-x" aria-label="Twitter">
                            <i class="bi bi-twitter-x"></i>
                        </button>
                        <button class="social-icon whatsapp" aria-label="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </button>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div class="col-md-4 text-center">
                    <h6>CONTÁCTENOS</h6>
                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt me-2" style="color: #ff6b6b;"></i> Plaza 10 de noviembre, Edificio Municipal Planta Alta<br>
                        <i class="fas fa-phone me-2" style="color: #ff6b6b;"></i> (2) 6223142<br>
                        <i class="fas fa-envelope me-2" style="color: #ff6b6b;"></i> info@concejomunicipalpotosí.gob.bo
                    </p>
                </div>
            </div>

            <hr class="bg-secondary">
            <div class="text-center text-muted">
                Unidad de Sistemas &copy; 2026 Concejo Municipal de Potosí. <br/>
                Todos los derechos reservados.<br/>
                Gestión Administrativa 2026 - 2031<br/>
                Potosí - Bolivia
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'Footer'
};
</script>

<style scoped>
footer {
    margin-top: 0px !important;
}

footer .text-muted {
    color: #adb5bd !important;
    transition: color 0.3s ease;
}

footer .text-muted:hover {
    color: #ffffff !important;
}

.escudo {
    height: 80px;
    width: auto;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.escudo:hover {
    transform: scale(1.05);
}

.social-icons-group {
    margin-top: 0.5rem; /* Reducido para menos separación */
    display: flex;
    gap: 0.8rem; /* Espacio entre iconos */
}

.social-icon {
    background: transparent;
    cursor: pointer;
    border: none;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.social-icon i {
    color: #ffffff;
    font-size: 1.5rem;
    transition: all 0.3s ease;
}

.social-icon:hover i {
    transform: scale(1.2);
}

.social-icon.facebook:hover i { color: #1877f2; }
.social-icon.youtube:hover i { color: #ff0000; }
.social-icon.instagram:hover i { color: #e4405f; }
.social-icon.twitter-x:hover i { color: #1da1f2; }
.social-icon.whatsapp:hover i { color: #25d366; }

/* Asegurar que el contenido de la columna centro esté centrado */
.col-md-4.d-flex {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: flex-start !important;
}

/* Responsive para móviles */
@media (max-width: 768px) {
    .col-md-4 {
        text-align: center !important;
        margin-bottom: 2rem;
    }

    .col-md-4.text-end {
        text-align: center !important;
    }
}
</style>
