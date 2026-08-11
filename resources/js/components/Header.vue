<template>
    <header>
        <!-- Barra superior con logo y nombre -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow">
            <div class="container-fluid">
                <div class="logo-container">
                    <img
                    src="../../../public/images/Logo.png"
                    alt="Logo Municipal" class="logo">
                </div>

                <!-- Botón hamburguesa para móviles -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menú principal -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/" exact-active-class="active">
                                <i class="fas fa-home me-1"></i> Inicio
                            </router-link>
                        </li>

                        <!-- Dropdown: Concejo -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarConcejo" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bank2"></i> Concejo
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarConcejo">
                                <li>
                                    <router-link class="dropdown-item" to="/directiva">
                                        <i class="fas fa-user-tie me-2"></i> Directiva
                                    </router-link>
                                </li>
                                <li>
                                    <router-link class="dropdown-item" to="/concejales">
                                        <i class="fas fa-users me-1"></i> Concejales
                                    </router-link>
                                </li>
                                <li>
                                    <router-link class="dropdown-item" to="/comisiones">
                                        <i class="bi bi-diagram-3"></i> Comisiones
                                    </router-link>
                                </li>
                            </ul>
                        </li>

                        <!-- Dropdown: Archivos -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarArchivos" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-archive-fill me-1"></i> Archivos
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarArchivos">
                                <li>
                                    <router-link class="dropdown-item" to="/leyes">
                                        <i class="fas fa-gavel me-2"></i> Leyes
                                    </router-link>
                                </li>
                                <li>
                                    <router-link class="dropdown-item" to="/resoluciones">
                                        <i class="bi bi-file-earmark-text"></i> Resoluciones
                                    </router-link>
                                </li>
                                <li>
                                    <router-link class="dropdown-item" to="/ordenanzas">
                                        <i class="bi bi-file-text"></i> Ordenanzas
                                    </router-link>
                                </li>
                            </ul>
                        </li>

                        <!-- Dropdown: Comunicación -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarComunicacion" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-megaphone-fill"></i> Comunicación
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarComunicacion">
                                <li>
                                    <router-link class="dropdown-item" to="/Noticias">
                                        <i class="bi bi-newspaper"></i> Noticias
                                    </router-link>
                                </li>
                                <li>
                                    <router-link class="dropdown-item" to="/Boletines">
                                        <i class="bi bi-file-richtext-fill"></i> Boletines de Prensa
                                    </router-link>
                                </li>
                                <li>
                                    <router-link class="dropdown-item" to="/Comunicados">
                                        <i class="bi bi-chat-left-text-fill"></i> Comunicados
                                    </router-link>
                                </li>
                                <li>
                                    <router-link class="dropdown-item" to="/Audiovisuales">
                                        <i class="bi bi-play-circle-fill"></i> Material Audiovisual
                                    </router-link>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <router-link class="nav-link" to="/sesiones" exact-active-class="active">
                                <i class="fas fa-video me-1"></i> Sesiones
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>
import 'bootstrap-icons/font/bootstrap-icons.css';
export default {
    name: 'Header',
};
</script>

<style scoped>
.navbar {
    background: linear-gradient(135deg, #ff0000 0%, #f70101 100%);
    padding: 12px 0;
}

.navbar-brand {
    font-size: 1.3rem;
    font-weight: 600;
}

.nav-link {
    font-weight: 500;
    padding: 10px 18px;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border-radius: 12px;
    margin-top: 8px;
    min-width: 220px;
}

.dropdown-item {
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background-color: #F2F2F2;
    color: #f70404;
    transform: translateX(1px);
}

.router-link-exact-active {
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
}

/* Logo */
.logo-container {
  flex-shrink: 0;
}

.logo {
  height: 50px;
  width: auto;
  object-fit: contain;
  transition: transform 0.3s ease;
}

.logo:hover {
  transform: scale(1.05);
}

@media (max-width: 992px) {
    .navbar-nav {
        padding: 15px 0;
    }

    .nav-item {
        margin: 5px 0;
    }
}
</style>
