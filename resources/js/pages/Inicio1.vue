<template>
    <div class="inicio-page">
        <section class="hero-section position-relative">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <!-- Indicadores -->
                <div class="carousel-indicators">
                    <button v-for="(slide, index) in carrusel" :key="index"
                            type="button" data-bs-target="#heroCarousel"
                            :data-bs-slide-to="index"
                            :class="{ active: index === 0 }"
                            :aria-label="'Slide ' + (index + 1)"></button>
                </div>
                <div class="carousel-inner">
                    <div v-for="(slide, index) in carrusel" :key="index"
                         class="carousel-item" :class="{ active: index === 0 }">
                        <div class="carousel-slide" :style="{ backgroundImage: `url(${slide.imagen})` }">
                            <div class="carousel-overlay"></div>
                            <div class="container h-100 d-flex align-items-center">
                                <div class="row w-100">
                                    <div class="col-lg-8 col-md-10">
                                        <div class="slide-content animate__animated animate__fadeInUp">
                                            <span class="badge bg-primary mb-3 px-4 py-2 fs-6">
                                                <i class="fas fa-building me-2"></i>{{ slide.categoria }}
                                            </span>
                                            <h1 class="display-3 fw-bold text-white mb-3">
                                                {{ slide.titulo }}
                                            </h1>
                                            <p class="lead text-white-50 mb-4 fs-5">
                                                {{ slide.descripcion }}
                                            </p>
                                            <div class="d-flex flex-wrap gap-3">
                                                <a href="#noticias" class="btn btn-primary btn-lg px-5 py-3">
                                                    <i class="fas fa-newspaper me-2"></i>Ver Noticias
                                                </a>
                                                <a href="#sesiones" class="btn btn-outline-light btn-lg px-5 py-3">
                                                    <i class="fas fa-play-circle me-2"></i>Ver Sesiones
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controles -->
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>

            <!-- Franja decorativa inferior -->
            <div class="hero-bottom-bar">
                <div class="container">
                    <div class="row g-4">
                        <div class="col-md-3 col-6" v-for="stat in estadisticas" :key="stat.label">
                            <div class="stat-item text-center">
                                <div class="stat-number">{{ stat.valor }}</div>
                                <div class="stat-label">{{ stat.label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======================================== -->
        <!-- SECCIÓN 2: BIENVENIDA Y MISIÓN          -->
        <!-- ======================================== -->
        <section class="welcome-section py-5">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <div class="welcome-content">
                            <span class="section-badge">Bienvenidos</span>
                            <h2 class="display-4 fw-bold mb-3">
                                Concejo Municipal <br>
                                <span class="text-primary">de Potosí</span>
                            </h2>
                            <div class="divider-line mb-4"></div>
                            <p class="lead text-muted mb-4">
                                El Concejo Municipal de Potosí es el órgano deliberante y fiscalizador
                                del Gobierno Autónomo Municipal, comprometido con el desarrollo integral
                                de nuestra ciudad.
                            </p>
                            <div class="row g-3 mt-3">
                                <div class="col-sm-6">
                                    <div class="feature-box">
                                        <i class="fas fa-handshake text-primary fs-2 mb-2"></i>
                                        <h6>Misión</h6>
                                        <p class="small text-muted">Legislar y fiscalizar para el bienestar de todos los potosinos</p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="feature-box">
                                        <i class="fas fa-eye text-success fs-2 mb-2"></i>
                                        <h6>Visión</h6>
                                        <p class="small text-muted">Ser referente en gestión municipal transparente y participativa</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative">
                            <img src="../../../public/images/concejo-edificio.jpg" alt="Edificio del Concejo"
                                 class="img-fluid rounded-4 shadow-lg" style="max-height: 500px; width: 100%; object-fit: cover;">
                            <div class="floating-card position-absolute">
                                <div class="bg-white p-4 rounded-4 shadow-lg">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-calendar-check text-white fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Próxima Sesión</div>
                                            <div class="text-muted small">25 de junio, 2026</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======================================== -->
        <!-- SECCIÓN 3: VALORES INSTITUCIONALES       -->
        <!-- ======================================== -->
        <section class="values-section py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="section-badge">Nuestros Valores</span>
                    <h2 class="display-4 fw-bold">Principios que nos guían</h2>
                    <p class="text-muted fs-5">Fundamentos que rigen nuestra labor legislativa</p>
                </div>

                <div class="row g-4">
                    <div class="col-lg-3 col-md-6" v-for="valor in valores" :key="valor.titulo">
                        <div class="value-card text-center">
                            <div class="value-icon">
                                <i :class="valor.icono"></i>
                            </div>
                            <h5 class="fw-bold mt-3">{{ valor.titulo }}</h5>
                            <p class="text-muted small">{{ valor.descripcion }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======================================== -->
        <!-- SECCIÓN 4: CONCEJALES DESTACADOS        -->
        <!-- ======================================== -->
        <section class="concejales-section py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="section-badge">Nuestros Representantes</span>
                    <h2 class="display-4 fw-bold">Concejales de Potosí</h2>
                    <p class="text-muted fs-5">Los 11 concejales que trabajan por nuestra ciudad</p>
                </div>

                <div class="row g-4">
                    <div class="col-lg-3 col-md-6" v-for="concejal in concejalesDestacados" :key="concejal.id">
                        <div class="concejal-card">
                            <div class="concejal-image">
                                <img :src="concejal.imagen" :alt="concejal.nombre" class="w-100">
                                <div class="social-overlay">
                                    <a :href="concejal.facebook" class="btn btn-sm btn-primary rounded-circle" v-if="concejal.facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a :href="concejal.twitter" class="btn btn-sm btn-info rounded-circle" v-if="concejal.twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a :href="concejal.instagram" class="btn btn-sm btn-danger rounded-circle" v-if="concejal.instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="concejal-info text-center p-3">
                                <h6 class="fw-bold mb-0">{{ concejal.nombre }}</h6>
                                <small class="text-primary">{{ concejal.cargo }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <router-link to="/concejales" class="btn btn-outline-primary btn-lg px-5">
                        Ver todos los concejales <i class="fas fa-arrow-right ms-2"></i>
                    </router-link>
                </div>
            </div>
        </section>

        <!-- ======================================== -->
        <!-- SECCIÓN 5: COMISIONES                   -->
        <!-- ======================================== -->
        <section class="comisiones-section py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="section-badge">Trabajo Legislativo</span>
                    <h2 class="display-4 fw-bold">Comisiones del Concejo</h2>
                    <p class="text-muted fs-5">10 comisiones especializadas en diferentes áreas</p>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 col-md-6" v-for="comision in comisionesDestacadas" :key="comision.id">
                        <div class="comision-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="comision-icon">
                                    <i :class="comision.icono"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">{{ comision.nombre }}</h6>
                                    <p class="text-muted small mb-0">{{ comision.descripcionCorta }}</p>
                                    <span class="badge bg-primary mt-2">{{ comision.integrantes }} miembros</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <router-link to="/comisiones" class="btn btn-outline-primary btn-lg px-5">
                        Ver todas las comisiones <i class="fas fa-arrow-right ms-2"></i>
                    </router-link>
                </div>
            </div>
        </section>

        <!-- ======================================== -->
        <!-- SECCIÓN 6: ÚLTIMAS NOTICIAS             -->
        <!-- ======================================== -->
        <section class="noticias-section py-5" id="noticias">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="section-badge">Actualidad</span>
                    <h2 class="display-4 fw-bold">Últimas Noticias</h2>
                    <p class="text-muted fs-5">Información relevante del Concejo Municipal</p>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 col-md-6" v-for="noticia in noticiasRecientes" :key="noticia.id">
                        <div class="noticia-card">
                            <div class="noticia-image position-relative">
                                <img :src="noticia.imagen" :alt="noticia.titulo" class="w-100">
                                <span class="noticia-categoria">{{ noticia.categoria }}</span>
                            </div>
                            <div class="noticia-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>{{ noticia.fecha }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-eye me-1"></i>{{ noticia.visitas }}
                                    </small>
                                </div>
                                <h6 class="fw-bold">{{ noticia.titulo }}</h6>
                                <p class="text-muted small">{{ noticia.resumen }}</p>
                                <router-link :to="`/noticia/${noticia.id}`" class="btn btn-link text-primary p-0">
                                    Leer más <i class="fas fa-arrow-right ms-1"></i>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <router-link to="/noticias" class="btn btn-primary btn-lg px-5">
                        Ver todas las noticias <i class="fas fa-arrow-right ms-2"></i>
                    </router-link>
                </div>
            </div>
        </section>

        <!-- ======================================== -->
        <!-- SECCIÓN 7: SESIONES RECIENTES           -->
        <!-- ======================================== -->
        <section class="sesiones-section py-5 bg-primary text-white" id="sesiones">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge bg-light text-primary px-4 py-2 fs-6">
                        <i class="fas fa-video me-2"></i>Transparencia
                    </span>
                    <h2 class="display-4 fw-bold text-white mt-3">Sesiones del Concejo</h2>
                    <p class="text-white-50 fs-5">Accede a las grabaciones de nuestras sesiones</p>
                </div>

                <div class="row g-4">
                    <div class="col-lg-6" v-for="sesion in sesionesRecientes" :key="sesion.id">
                        <div class="sesion-card bg-white rounded-4 overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="sesion-thumbnail position-relative">
                                        <img :src="sesion.thumbnail" alt="Sesión" class="w-100 h-100" style="object-fit: cover;">
                                        <div class="play-overlay">
                                            <i class="fas fa-play-circle"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="p-3">
                                        <h6 class="fw-bold text-dark">{{ sesion.titulo }}</h6>
                                        <p class="text-muted small">
                                            <i class="fas fa-calendar-alt me-1"></i>{{ sesion.fecha }}
                                            <span class="mx-2">|</span>
                                            <i class="fas fa-clock me-1"></i>{{ sesion.duracion }}
                                        </p>
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fas fa-play me-1"></i>Ver sesión
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <router-link to="/sesiones" class="btn btn-outline-light btn-lg px-5">
                        Ver todas las sesiones <i class="fas fa-arrow-right ms-2"></i>
                    </router-link>
                </div>
            </div>
        </section>

        <!-- ======================================== -->
        <!-- SECCIÓN 8: ARCHIVOS LEGISLATIVOS        -->
        <!-- ======================================== -->
        <section class="archivos-section py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="section-badge">Documentación</span>
                    <h2 class="display-4 fw-bold">Archivos Legislativos</h2>
                    <p class="text-muted fs-5">Accede a las normas y documentos oficiales</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-4" v-for="archivo in archivos" :key="archivo.titulo">
                        <router-link :to="archivo.ruta" class="text-decoration-none">
                            <div class="archivo-card text-center">
                                <div class="archivo-icon">
                                    <i :class="archivo.icono"></i>
                                </div>
                                <h5 class="fw-bold mt-3">{{ archivo.titulo }}</h5>
                                <p class="text-muted small">{{ archivo.descripcion }}</p>
                                <span class="badge bg-primary">{{ archivo.cantidad }} documentos</span>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======================================== -->
        <!-- SECCIÓN 9: CONTACTO Y UBICACIÓN         -->
        <!-- ======================================== -->
        <section class="contacto-section py-5 bg-dark text-white">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <h2 class="display-5 fw-bold mb-3">¿Necesitas información?</h2>
                        <p class="text-white-50 fs-5">Contáctanos o visítanos en nuestras oficinas</p>
                        <div class="contact-info mt-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <i class="fas fa-map-marker-alt text-primary fs-3"></i>
                                <div>
                                    <div class="fw-bold">Dirección</div>
                                    <div class="text-white-50">Av. Naciones Unidas #123, Potosí</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <i class="fas fa-phone text-primary fs-3"></i>
                                <div>
                                    <div class="fw-bold">Teléfono</div>
                                    <div class="text-white-50">(02) 123-4567</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <i class="fas fa-envelope text-primary fs-3"></i>
                                <div>
                                    <div class="fw-bold">Correo Electrónico</div>
                                    <div class="text-white-50">info@concejopotosí.bo</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-clock text-primary fs-3"></i>
                                <div>
                                    <div class="fw-bold">Horario de Atención</div>
                                    <div class="text-white-50">Lunes a Viernes: 8:00 - 17:00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="map-container rounded-4 overflow-hidden shadow-lg">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1226.567!2d-65.757!3d-19.589!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93e1b8b8b8b8b8b%3A0x1234567890abcdef!2sPotos%C3%AD%2C%20Bolivia!5e0!3m2!1ses!2s!4v1234567890"
                                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======================================== -->
        <!-- SECCIÓN 10: NEWSLETTER                  -->
        <!-- ======================================== -->
        <section class="newsletter-section py-4 bg-light">
            <div class="container">
                <div class="row align-items-center g-3">
                    <div class="col-lg-6">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-envelope-open-text text-primary me-2"></i>
                            Suscríbete a nuestro boletín
                        </h5>
                        <p class="text-muted small mb-0">Recibe las últimas noticias y actualizaciones</p>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Tu correo electrónico">
                            <button class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Suscribirse
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    name: 'Inicio',
    data() {
        return {
            // Datos del carrusel principal
            carrusel: [
                {
                    imagen: '/images/carousel/concejo1.jpg',
                    titulo: 'Trabajando por el Desarrollo de Potosí',
                    descripcion: 'El Concejo Municipal impulsa políticas que transforman nuestra ciudad para construir un futuro mejor para todos los potosinos.',
                    categoria: 'Gestión Municipal'
                },
                {
                    imagen: '/images/carousel/concejo2.jpg',
                    titulo: 'Participación Ciudadana',
                    descripcion: 'Tu voz es importante. Participa en las sesiones y contribuye al desarrollo de nuestra ciudad.',
                    categoria: 'Democracia Participativa'
                },
                {
                    imagen: '/images/carousel/concejo3.jpg',
                    titulo: 'Transparencia y Rendición de Cuentas',
                    descripcion: 'Gestionamos con transparencia y eficiencia para garantizar el bienestar de todos los potosinos.',
                    categoria: 'Gobierno Abierto'
                }
            ],

            // Estadísticas rápidas
            estadisticas: [
                { valor: '11', label: 'Concejales' },
                { valor: '10', label: 'Comisiones' },
                { valor: '150+', label: 'Leyes Aprobadas' },
                { valor: '200+', label: 'Sesiones Realizadas' }
            ],

            // Valores institucionales
            valores: [
                {
                    icono: 'fas fa-shield-alt',
                    titulo: 'Transparencia',
                    descripcion: 'Actuamos con honestidad y apertura en toda nuestra gestión'
                },
                {
                    icono: 'fas fa-users',
                    titulo: 'Participación',
                    descripcion: 'Fomentamos la participación activa de la ciudadanía'
                },
                {
                    icono: 'fas fa-balance-scale',
                    titulo: 'Justicia',
                    descripcion: 'Velamos por la equidad y el bien común'
                },
                {
                    icono: 'fas fa-handshake',
                    titulo: 'Compromiso',
                    descripcion: 'Trabajamos incansablemente por el desarrollo de Potosí'
                }
            ],

            // Concejales destacados (mostrar 4 en inicio)
            concejalesDestacados: [
                {
                    id: 1,
                    nombre: 'Dra. Ana María Rodríguez',
                    cargo: 'Presidenta del Concejo',
                    imagen: '/images/concejales/concejal1.jpg',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#'
                },
                {
                    id: 2,
                    nombre: 'Lic. Carlos Pérez',
                    cargo: 'Vicepresidente',
                    imagen: '/images/concejales/concejal2.jpg',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#'
                },
                {
                    id: 3,
                    nombre: 'Ing. María Flores',
                    cargo: 'Secretaria',
                    imagen: '/images/concejales/concejal3.jpg',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#'
                },
                {
                    id: 4,
                    nombre: 'Dr. Luis Mendoza',
                    cargo: 'Concejal',
                    imagen: '/images/concejales/concejal4.jpg',
                    facebook: '#',
                    twitter: '#',
                    instagram: '#'
                }
            ],

            // Comisiones destacadas (mostrar 3 en inicio)
            comisionesDestacadas: [
                {
                    id: 1,
                    nombre: 'Desarrollo Urbano',
                    descripcionCorta: 'Planificación y control del crecimiento urbano',
                    icono: 'fas fa-city',
                    integrantes: 5
                },
                {
                    id: 2,
                    nombre: 'Educación y Cultura',
                    descripcionCorta: 'Fomento de la educación y patrimonio cultural',
                    icono: 'fas fa-graduation-cap',
                    integrantes: 4
                },
                {
                    id: 3,
                    nombre: 'Salud y Medio Ambiente',
                    descripcionCorta: 'Políticas de salud pública y protección ambiental',
                    icono: 'fas fa-heart',
                    integrantes: 4
                }
            ],

            // Noticias recientes
            noticiasRecientes: [
                {
                    id: 1,
                    titulo: 'Concejo aprueba presupuesto para obras de infraestructura 2025',
                    resumen: 'El pleno del Concejo Municipal aprobó el presupuesto para la gestión 2025...',
                    fecha: '20/06/2026',
                    categoria: 'Presupuesto',
                    visitas: 245,
                    imagen: '/images/noticias/noticia1.jpg'
                },
                {
                    id: 2,
                    titulo: 'Nueva comisión de transparencia inicia sus funciones',
                    resumen: 'Se instaló la nueva comisión encargada de velar por la transparencia...',
                    fecha: '18/06/2026',
                    categoria: 'Transparencia',
                    visitas: 189,
                    imagen: '/images/noticias/noticia2.jpg'
                },
                {
                    id: 3,
                    titulo: 'Concejo impulsa programa de participación juvenil',
                    resumen: 'Jóvenes potosinos participarán activamente en las decisiones del Concejo...',
                    fecha: '15/06/2026',
                    categoria: 'Juventud',
                    visitas: 312,
                    imagen: '/images/noticias/noticia3.jpg'
                }
            ],

            // Sesiones recientes
            sesionesRecientes: [
                {
                    id: 1,
                    titulo: 'Sesión Ordinaria - Presupuesto 2025',
                    fecha: '20/06/2026',
                    duracion: '2h 30min',
                    thumbnail: '/images/sesiones/sesion1.jpg'
                },
                {
                    id: 2,
                    titulo: 'Sesión Extraordinaria - Proyectos de Ley',
                    fecha: '15/06/2026',
                    duracion: '1h 45min',
                    thumbnail: '/images/sesiones/sesion2.jpg'
                }
            ],

            // Archivos legislativos
            archivos: [
                {
                    titulo: 'Leyes',
                    descripcion: 'Normas legales aprobadas por el Concejo',
                    icono: 'fas fa-gavel',
                    cantidad: 150,
                    ruta: '/leyes'
                },
                {
                    titulo: 'Resoluciones',
                    descripcion: 'Decisiones administrativas del Concejo',
                    icono: 'fas fa-file-alt',
                    cantidad: 320,
                    ruta: '/resoluciones'
                },
                {
                    titulo: 'Ordenanzas',
                    descripcion: 'Regulaciones municipales vigentes',
                    icono: 'fas fa-file-pdf',
                    cantidad: 98,
                    ruta: '/ordenanzas'
                }
            ]
        };
    },
    mounted() {
        // Inicializar carrusel de Bootstrap
        if (typeof bootstrap !== 'undefined') {
            const carouselElement = document.getElementById('heroCarousel');
            if (carouselElement) {
                new bootstrap.Carousel(carouselElement, {
                    interval: 5000,
                    wrap: true
                });
            }
        }

        // Animación al hacer scroll
        this.observeElements();
    },
    methods: {
        observeElements() {
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate');
                        }
                    });
                }, { threshold: 0.1 });

                document.querySelectorAll('.value-card, .concejal-card, .comision-card, .noticia-card, .archivo-card')
                    .forEach(el => observer.observe(el));
            }
        }
    }
};
</script>

<style scoped>
/* ======================================== */
/* ESTILOS GLOBALES                        */
/* ======================================== */
.inicio-page {
    overflow-x: hidden;
}

.section-badge {
    display: inline-block;
    background: rgba(13, 110, 253, 0.1);
    color: #ff0000;
    padding: 8px 24px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 15px;
}

.divider-line {
    width: 70px;
    height: 4px;
    background: linear-gradient(90deg, #ff0000, #412020);
    border-radius: 2px;
}

/* ======================================== */
/* SECCIÓN 1: HERO CON CARRUSEL            */
/* ======================================== */
.hero-section {
    position: relative;
}

.carousel-slide {
    height: 800px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.carousel-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.637) 0%, rgba(0,0,0,0.3) 100%);
}

.slide-content {
    position: relative;
    z-index: 2;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    margin: 0 6px;
}

.carousel-control-prev,
.carousel-control-next {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    margin: 0 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    background: rgba(255,255,255,0.2);
}

/* Barra de estadísticas en hero */
.hero-bottom-bar {
    position: absolute;
    bottom: -40px;
    left: 0;
    right: 0;
    z-index: 10;
}

.hero-bottom-bar .container {
    background: white;
    border-radius: 16px;
    padding: 25px 30px;
    box-shadow: 0 15px 50px rgba(0,0,0,0.15);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #ff0000;
    line-height: 1.2;
}

.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

/* ======================================== */
/* SECCIÓN 2: BIENVENIDA                   */
/* ======================================== */
.welcome-section {
    padding-top: 80px;
    padding-bottom: 60px;
}

.feature-box {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.feature-box:hover {
    background: #e9ecef;
    transform: translateY(-3px);
}

.floating-card {
    bottom: -30px;
    right: 20px;
}

.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ff0000;
}

/* ======================================== */
/* SECCIÓN 3: VALORES                      */
/* ======================================== */
.values-section {
    padding: 80px 0;
}

.value-card {
    padding: 30px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.value-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}

.value-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto;
    background: linear-gradient(135deg, rgba(13,110,253,0.1), rgba(13,202,240,0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #ff0000;
    transition: all 0.3s ease;
}

.value-card:hover .value-icon {
    transform: scale(1.1) rotate(5deg);
}

/* ======================================== */
/* SECCIÓN 4: CONCEJALES                   */
/* ======================================== */
.concejales-section {
    padding: 80px 0;
}

.concejal-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.concejal-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}

.concejal-image {
    position: relative;
    overflow: hidden;
}

.concejal-image img {
    height: 250px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.concejal-card:hover .concejal-image img {
    transform: scale(1.05);
}

.social-overlay {
    position: absolute;
    bottom: -60px;
    left: 0;
    right: 0;
    padding: 15px;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    display: flex;
    justify-content: center;
    gap: 10px;
    transition: bottom 0.3s ease;
}

.concejal-card:hover .social-overlay {
    bottom: 0;
}

.social-overlay .btn {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

/* ======================================== */
/* SECCIÓN 5: COMISIONES                   */
/* ======================================== */
.comisiones-section {
    padding: 80px 0;
}

.comision-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 3px 15px rgba(0,0,0,0.06);
}

.comision-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.comision-icon {
    width: 50px;
    height: 50px;
    min-width: 50px;
    background: rgba(13,110,253,0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #ff0000;
}

/* ======================================== */
/* SECCIÓN 6: NOTICIAS                     */
/* ======================================== */
.noticias-section {
    padding: 80px 0;
}

.noticia-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.noticia-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}

.noticia-image {
    height: 200px;
    overflow: hidden;
}

.noticia-image img {
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.noticia-card:hover .noticia-image img {
    transform: scale(1.05);
}

.noticia-categoria {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #ff0000;
    color: white;
    padding: 4px 14px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* ======================================== */
/* SECCIÓN 7: SESIONES                     */
/* ======================================== */
.sesiones-section {
    padding: 80px 0;
}

.sesion-card {
    transition: all 0.3s ease;
    overflow: hidden;
}

.sesion-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.sesion-thumbnail {
    height: 150px;
    overflow: hidden;
}

.sesion-thumbnail img {
    height: 100%;
    object-fit: cover;
}

.play-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 3rem;
    color: white;
    opacity: 0.8;
    transition: all 0.3s ease;
    text-shadow: 0 0 20px rgba(0,0,0,0.5);
}

.sesion-card:hover .play-overlay {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1.1);
}

/* ======================================== */
/* SECCIÓN 8: ARCHIVOS                     */
/* ======================================== */
.archivos-section {
    padding: 80px 0;
}

.archivo-card {
    background: white;
    padding: 30px 20px;
    border-radius: 16px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.archivo-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    border-color: #ff0000;
}

.archivo-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto;
    background: linear-gradient(135deg, rgba(13,110,253,0.1), rgba(13,202,240,0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #ff0000;
}

/* ======================================== */
/* SECCIÓN 9: CONTACTO                     */
/* ======================================== */
.contacto-section {
    padding: 80px 0;
}

.contact-info .fas {
    width: 30px;
}

.map-container {
    border: 4px solid rgba(255,255,255,0.1);
}

/* ======================================== */
/* SECCIÓN 10: NEWSLETTER                  */
/* ======================================== */
.newsletter-section .input-group {
    max-width: 500px;
    margin-left: auto;
}

/* ======================================== */
/* ANIMACIONES AL HACER SCROLL             */
/* ======================================== */
.value-card,
.concejal-card,
.comision-card,
.noticia-card,
.archivo-card {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.value-card.animate,
.concejal-card.animate,
.comision-card.animate,
.noticia-card.animate,
.archivo-card.animate {
    opacity: 1;
    transform: translateY(0);
}

/* ======================================== */
/* RESPONSIVE                              */
/* ======================================== */
@media (max-width: 992px) {
    .carousel-slide {
        height: 450px;
    }

    .slide-content h1 {
        font-size: 2.5rem !important;
    }

    .hero-bottom-bar {
        position: relative;
        bottom: 0;
        margin-top: -20px;
    }

    .hero-bottom-bar .container {
        border-radius: 0;
        padding: 20px;
    }

    .floating-card {
        position: relative !important;
        bottom: 0 !important;
        right: 0 !important;
        margin-top: 20px;
    }

    .stat-number {
        font-size: 1.5rem;
    }

    .newsletter-section .input-group {
        max-width: 100%;
        margin-left: 0;
    }
}

@media (max-width: 768px) {
    .carousel-slide {
        height: 350px;
    }

    .slide-content h1 {
        font-size: 1.8rem !important;
    }

    .carousel-control-prev,
    .carousel-control-next {
        display: none;
    }

    .hero-bottom-bar .row {
        gap: 10px;
    }

    .stat-number {
        font-size: 1.2rem;
    }
}

@media (max-width: 576px) {
    .carousel-slide {
        height: 300px;
    }

    .slide-content h1 {
        font-size: 1.5rem !important;
    }

    .slide-content .lead {
        font-size: 0.9rem !important;
    }

    .btn-lg {
        padding: 10px 20px !important;
        font-size: 0.9rem !important;
    }

    .values-section,
    .concejales-section,
    .comisiones-section,
    .noticias-section,
    .sesiones-section,
    .archivos-section,
    .contacto-section {
        padding: 40px 0 !important;
    }

    .display-4 {
        font-size: 2rem !important;
    }
}
</style>
