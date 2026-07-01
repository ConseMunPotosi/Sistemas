<template>
    <footer class="bg-dark text-white mt-5">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="contenedor-mayor">
                        <div class="div-izquierdo escudo">
                            <img src="../../../public/images/escudo-potosi.png" alt="Escudo" class="escudo">
                        </div>
                        <div class="div-derecho">
                            <h5>Concejo Municipal de Potosí</h5>
                            <p class="text-muted">
                                El Concejo Municipal de Potosí es el Órgano Legislativo, Deliberativo y Fiscalizador del Gobierno Municipal de Potosí. Está compuesto por 11 concejalas y concejales elegidos en votación universal, directa y secreta por un período de cinco años.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 justify-content:center align-items:center">
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
                <div class="col-md-4 text-end">
                    <h6>Contáctenos</h6>
                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i> Av. Naciones Unidas #123<br>
                        <i class="fas fa-phone me-2"></i> (02) 1234567<br>
                        <i class="fas fa-envelope me-2"></i> info@concejopotosí.bo
                    </p>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center text-muted">
                Unidad de Sistemas&copy; 2026 Concejo Municipal de Potosí. Todos los derechos reservados.
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
    margin-top: 50px !important;
}

footer .text-muted {
    color: #adb5bd !important;
}

footer .text-muted:hover {
    color: #ffffff !important;
}
.escudo {
  height: 120px;
  width: auto;
  object-fit: contain;
  transition: transform 0.3s ease;
}
.contenedor-mayor {
  display: flex;
  width: auto;
}

.div-izquierdo {
  flex: 1;
  height: auto;
}

.div-derecho {
  flex: 2;
  height: auto;
}
.social-icons-group {
  margin-top: 1.5rem;
  display: flex;
  gap: 1rem;
}

.social-icon {
  background: transparent;
  cursor: pointer;
  border: none;
  padding: 0;
  align-items: center;
}

.social-icon i {
  color: #ffffff;
  font-size: 1.5rem;
  transition: all 0.3s ease;
}

.social-icon.facebook:hover i { color: #1877f2; transform: scale(1.1); }
.social-icon.youtube:hover i { color: #ff0000; transform: scale(1.1); }
.social-icon.instagram:hover i { color: #e4405f; transform: scale(1.1); }
.social-icon.twitter-x:hover i { color: #1da1f2; transform: scale(1.1); }
.social-icon.whatsapp:hover i { color: #25d366; transform: scale(1.1); }
</style>
