<template>
  <aside class="sidebar" :class="{ collapsed: isCollapsed }">

    <!-- Logo -->
    <div class="sidebar-header">
      <div class="logo">
        <img
          v-if="!isCollapsed"
          src="/images/Logo_blanco.png"
          alt="Logo Concejo Municipal"
          class="logo-image"
        />

        <span v-else class="logo-icon">🏛️</span>
      </div>
    </div>

    <!-- Navegación -->
    <nav class="sidebar-nav">

      <!-- =====================================================
           1. GESTIÓN INSTITUCIONAL
           ===================================================== -->
      <div class="menu-section">

        <div
          class="nav-item menu-toggle"
          :class="{ active: activeMenu === 'institucional' }"
          @click="toggleMenu('institucional')"
        >
          <span class="nav-icon">🏛️</span>

          <span v-if="!isCollapsed" class="nav-text">
            Gestión Institucional
          </span>

          <span
            v-if="!isCollapsed"
            class="menu-arrow"
            :class="{ open: activeMenu === 'institucional' }"
          >
            ▶
          </span>
        </div>

        <div
          v-if="!isCollapsed"
          class="submenu"
          :class="{ open: activeMenu === 'institucional' }"
        >
          <div class="submenu-item submenu-disabled">
            <span class="submenu-icon">ℹ️</span>
            Información institucional
          </div>

          <router-link
            :to="{ name: 'gestionConfiguracion' }"
            class="submenu-item"
            :class="{
              active: $route.name === 'gestionConfiguracion'
            }"
          >
            <span class="submenu-icon">⚙️</span>
            Configuración Institucional
          </router-link>
        </div>

      </div>


      <!-- =====================================================
           2. GESTIÓN DE CONCEJALES
           ===================================================== -->
      <div class="menu-section">

        <div
          class="nav-item menu-toggle"
          :class="{ active: activeMenu === 'concejales' }"
          @click="toggleMenu('concejales')"
        >
          <span class="nav-icon">👥</span>

          <span v-if="!isCollapsed" class="nav-text">
            Gestión de Concejales
          </span>

          <span
            v-if="!isCollapsed"
            class="menu-arrow"
            :class="{ open: activeMenu === 'concejales' }"
          >
            ▶
          </span>
        </div>

        <div
          v-if="!isCollapsed"
          class="submenu"
          :class="{ open: activeMenu === 'concejales' }"
        >
          <router-link
            :to="{ name: 'gestionConcejales' }"
            class="submenu-item"
            :class="{
              active: $route.name === 'gestionConcejales'
            }"
          >
            <span class="submenu-icon">👥</span>
            Concejales
          </router-link>

        </div>

      </div>


      <!-- =====================================================
           3. GESTIÓN COMUNICACIONAL
           ===================================================== -->
      <div class="menu-section">

        <div
          class="nav-item menu-toggle"
          :class="{ active: activeMenu === 'comunicacional' }"
          @click="toggleMenu('comunicacional')"
        >
          <span class="nav-icon">📢</span>

          <span v-if="!isCollapsed" class="nav-text">
            Gestión Comunicacional
          </span>

          <span
            v-if="!isCollapsed"
            class="menu-arrow"
            :class="{ open: activeMenu === 'comunicacional' }"
          >
            ▶
          </span>
        </div>

        <div
          v-if="!isCollapsed"
          class="submenu"
          :class="{ open: activeMenu === 'comunicacional' }"
        >
          <router-link
            :to="{ name: 'informacion' }"
            class="submenu-item"
            :class="{ active: $route.name === 'informacion' }"
          >
            <span class="submenu-icon">📰</span>
            Información
          </router-link>
        </div>

      </div>


      <!-- =====================================================
           4. GACETA
           ===================================================== -->
      <div class="menu-section">

        <div
          class="nav-item menu-toggle"
          :class="{ active: activeMenu === 'normas' }"
          @click="toggleMenu('normas')"
        >
          <span class="nav-icon">📜</span>

          <span v-if="!isCollapsed" class="nav-text">
            Gaceta
          </span>

          <span
            v-if="!isCollapsed"
            class="menu-arrow"
            :class="{ open: activeMenu === 'normas' }"
          >
            ▶
          </span>
        </div>

        <div
          v-if="!isCollapsed"
          class="submenu"
          :class="{ open: activeMenu === 'normas' }"
        >
          <router-link
            to="/dashboard/gestion-normas"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/dashboard/gestion-normas')
            }"
          >
            <span class="submenu-icon">🗂️</span>
            Archivos
          </router-link>
        </div>

      </div>


      <!-- =====================================================
           5. GESTIÓN DE SESIONES
           ===================================================== -->
      <div class="menu-section">

        <div
          class="nav-item menu-toggle"
          :class="{ active: activeMenu === 'sesiones' }"
          @click="toggleMenu('sesiones')"
        >
          <span class="nav-icon">🎥</span>

          <span v-if="!isCollapsed" class="nav-text">
            Gestión de Sesiones
          </span>

          <span
            v-if="!isCollapsed"
            class="menu-arrow"
            :class="{ open: activeMenu === 'sesiones' }"
          >
            ▶
          </span>
        </div>

        <div
          v-if="!isCollapsed"
          class="submenu"
          :class="{ open: activeMenu === 'sesiones' }"
        >
          <router-link
            :to="{ name: 'gestionSesiones' }"
            class="submenu-item"
            :class="{
              active: $route.name === 'gestionSesiones'
            }"
          >
            <span class="submenu-icon">🎬</span>
            Sesiones y Videos
          </router-link>
        </div>

      </div>


      <!-- =====================================================
           6. GESTIÓN DOCUMENTAL
           ===================================================== -->
      <div class="menu-section">

        <div
          class="nav-item menu-toggle"
          :class="{ active: activeMenu === 'documental' }"
          @click="toggleMenu('documental')"
        >
          <span class="nav-icon">📄</span>

          <span v-if="!isCollapsed" class="nav-text">
            Gestión Documental
          </span>

          <span
            v-if="!isCollapsed"
            class="menu-arrow"
            :class="{ open: activeMenu === 'documental' }"
          >
            ▶
          </span>
        </div>

        <div
          v-if="!isCollapsed"
          class="submenu"
          :class="{ open: activeMenu === 'documental' }"
        >
          <router-link
            to="/documental/documentos"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/documental/documentos')
            }"
          >
            <span class="submenu-icon">📄</span>
            Documentos
          </router-link>

          <router-link
            to="/documental/tipos"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/documental/tipos')
            }"
          >
            <span class="submenu-icon">🏷️</span>
            Tipos de Documento
          </router-link>

          <router-link
            to="/documental/archivos"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/documental/archivos')
            }"
          >
            <span class="submenu-icon">📁</span>
            Archivos
          </router-link>
        </div>

      </div>


      <!-- =====================================================
           7. RECURSOS HUMANOS
           ===================================================== -->
      <div class="menu-section">

        <div
          class="nav-item menu-toggle"
          :class="{ active: activeMenu === 'rrhh' }"
          @click="toggleMenu('rrhh')"
        >
          <span class="nav-icon">👥</span>

          <span v-if="!isCollapsed" class="nav-text">
            Recursos Humanos
          </span>

          <span
            v-if="!isCollapsed"
            class="menu-arrow"
            :class="{ open: activeMenu === 'rrhh' }"
          >
            ▶
          </span>
        </div>

        <div
          v-if="!isCollapsed"
          class="submenu"
          :class="{ open: activeMenu === 'rrhh' }"
        >
          <router-link
            to="/rrhh/empleados"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/rrhh/empleados')
            }"
          >
            <span class="submenu-icon">👨‍💼</span>
            Empleados
          </router-link>

          <router-link
            to="/rrhh/asistencias"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/rrhh/asistencias')
            }"
          >
            <span class="submenu-icon">📅</span>
            Asistencias
          </router-link>

          <router-link
            to="/rrhh/vacaciones"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/rrhh/vacaciones')
            }"
          >
            <span class="submenu-icon">🏖️</span>
            Vacaciones
          </router-link>

          <router-link
            to="/rrhh/permisos"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/rrhh/permisos')
            }"
          >
            <span class="submenu-icon">📋</span>
            Permisos
          </router-link>

          <router-link
            to="/rrhh/contratos"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/rrhh/contratos')
            }"
          >
            <span class="submenu-icon">📄</span>
            Contratos
          </router-link>
        </div>

      </div>


      <!-- =====================================================
           8. ACTIVOS FIJOS
           ===================================================== -->
      <div class="menu-section">

        <div
          class="nav-item menu-toggle"
          :class="{ active: activeMenu === 'activos' }"
          @click="toggleMenu('activos')"
        >
          <span class="nav-icon">💻</span>

          <span v-if="!isCollapsed" class="nav-text">
            Activos Fijos
          </span>

          <span
            v-if="!isCollapsed"
            class="menu-arrow"
            :class="{ open: activeMenu === 'activos' }"
          >
            ▶
          </span>
        </div>

        <div
          v-if="!isCollapsed"
          class="submenu"
          :class="{ open: activeMenu === 'activos' }"
        >
          <router-link
            to="/activos/inventario"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/activos/inventario')
            }"
          >
            <span class="submenu-icon">📦</span>
            Inventario
          </router-link>

          <router-link
            to="/activos/bienes"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/activos/bienes')
            }"
          >
            <span class="submenu-icon">🏷️</span>
            Bienes
          </router-link>

          <router-link
            to="/activos/categorias"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/activos/categorias')
            }"
          >
            <span class="submenu-icon">📂</span>
            Categorías
          </router-link>

          <router-link
            to="/activos/asignaciones"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/activos/asignaciones')
            }"
          >
            <span class="submenu-icon">📋</span>
            Asignaciones
          </router-link>

          <router-link
            to="/activos/mantenimientos"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/activos/mantenimientos')
            }"
          >
            <span class="submenu-icon">🔧</span>
            Mantenimientos
          </router-link>

          <router-link
            to="/activos/bajas"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/activos/bajas')
            }"
          >
            <span class="submenu-icon">🗑️</span>
            Bajas
          </router-link>

          <router-link
            to="/activos/reportes"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/activos/reportes')
            }"
          >
            <span class="submenu-icon">📊</span>
            Reportes
          </router-link>

          <router-link
            to="/activos/proveedores"
            class="submenu-item"
            :class="{
              active: $route.path.includes('/activos/proveedores')
            }"
          >
            <span class="submenu-icon">🏢</span>
            Proveedores
          </router-link>
        </div>

      </div>

    </nav>

  </aside>
</template>


<script setup>

import { ref } from 'vue';

const props = defineProps({

  isCollapsed: {

    type: Boolean,

    default: false

  }

});

const activeMenu = ref(null);

const toggleMenu = (menu) => {

  if (activeMenu.value === menu) {

    activeMenu.value = null;

  } else {

    activeMenu.value = menu;

  }

};

</script>


<style scoped>

.sidebar {

  position: fixed;

  top: 0;

  left: 0;

  width: 250px;

  height: 100vh;

  background: #212529;

  color: white;

  display: flex;

  flex-direction: column;

  transition: width 0.3s ease;

  z-index: 1000;

  overflow: hidden;

  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);

}

.sidebar.collapsed {

  width: 70px;

}

.sidebar-header {

  padding: 20px;

  border-bottom: 1px solid rgba(255, 255, 255, 0.05);

  display: flex;

  align-items: center;

  justify-content: center;

  min-height: 70px;

}

.logo {

  display: flex;

  align-items: center;

  justify-content: center;

  width: 100%;

}

.logo-image {

  width: 80%;

  max-width: 200px;

  height: auto;

  object-fit: contain;

  display: block;

}

.sidebar.collapsed .logo-image {

  width: 35px;

  height: 35px;

}

.logo-icon {

  font-size: 32px;

}

.sidebar-nav {

  flex: 1;

  padding: 10px 12px;

  overflow-y: auto;

  overflow-x: hidden;

}

.nav-item {

  display: flex;

  align-items: center;

  padding: 10px 14px;

  margin-bottom: 2px;

  border-radius: 8px;

  color: rgba(255, 255, 255, 0.7);

  text-decoration: none;

  transition: all 0.2s ease;

  cursor: pointer;

  position: relative;

}

.nav-item:hover {

  background: rgba(255, 255, 255, 0.08);

  color: white;

}

.nav-item.active {

  background: #706b6b79;

  color: white;

  box-shadow: inset 3px 0 0 #CC0000;

}

.nav-icon {

  font-size: 18px;

  min-width: 30px;

  text-align: center;

  flex-shrink: 0;

}

.nav-text {

  margin-left: 10px;

  font-size: 14px;

  font-weight: 500;

  white-space: nowrap;

}

.menu-toggle {

  user-select: none;

}

.menu-arrow {

  margin-left: auto;

  font-size: 12px;

  transition: transform 0.3s ease;

  color: rgba(255, 255, 255, 0.4);

}

.menu-arrow.open {

  transform: rotate(90deg);

}

.submenu {

  max-height: 0;

  overflow: hidden;

  transition:
    max-height 0.3s ease,
    opacity 0.3s ease;

  opacity: 0;

  margin-left: 10px;

  border-left: 1px solid rgba(255, 255, 255, 0.08);

}

.submenu.open {

  max-height: 500px;

  opacity: 1;

}

.submenu-item {

  display: flex;

  align-items: center;

  padding: 8px 14px 8px 20px;

  margin: 1px 0;

  border-radius: 6px;

  color: rgba(255, 255, 255, 0.6);

  text-decoration: none;

  transition: all 0.2s ease;

  font-size: 13px;

}

.submenu-item:hover {

  background: rgba(255, 255, 255, 0.06);

  color: white;

}

.submenu-item.active {

  background: rgba(118, 117, 146, 0.2);

  color: white;

  box-shadow: inset 2px 0 0 #CC0000;

}

.submenu-icon {

  font-size: 14px;

  min-width: 24px;

  text-align: center;

}

.menu-section {

  margin-bottom: 4px;

}

.submenu-disabled {

  cursor: default;

  opacity: 0.7;

}

.sidebar.collapsed .nav-text {

  display: none;

}

.sidebar.collapsed .nav-item {

  justify-content: center;

  padding: 10px;

}

.sidebar.collapsed .menu-arrow {

  display: none;

}

.sidebar.collapsed .submenu {

  display: none;

}

.sidebar-nav::-webkit-scrollbar {

  width: 4px;

}

.sidebar-nav::-webkit-scrollbar-track {

  background: transparent;

}

.sidebar-nav::-webkit-scrollbar-thumb {

  background: rgba(255, 255, 255, 0.2);

  border-radius: 4px;

}

.sidebar-nav::-webkit-scrollbar-thumb:hover {

  background: rgba(255, 255, 255, 0.3);

}

@media (max-width: 768px) {

  .sidebar {

    width: 0;

    transform: translateX(-100%);

  }

  .sidebar:not(.collapsed) {

    width: 250px;

    transform: translateX(0);

  }

}

</style>


