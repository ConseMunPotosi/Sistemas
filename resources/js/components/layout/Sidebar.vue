<template>
  <aside class="sidebar" :class="{ collapsed: isCollapsed }">
    <!-- Logo -->
    <div class="sidebar-header">
      <div class="logo">
        <span v-if="!isCollapsed" class="logo-text">
          <img src="/images/Logo_blanco.png" alt="Logo Concejo Municipal" class="logo-image" />
        </span>
        <span v-else class="logo-icon">⚙️</span>
      </div>
    </div>

    <!-- Navegación -->
    <nav class="sidebar-nav">
      <router-link
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        class="nav-item"
        :class="{ active: $route.path === item.path }"
      >
        <span class="nav-icon">{{ item.icon }}</span>
        <span v-if="!isCollapsed" class="nav-text">{{ item.name }}</span>
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
import { computed } from 'vue';
import { useAuthStore } from '../../api/auth.js';

// Props
const props = defineProps({
  isCollapsed: {
    type: Boolean,
    default: false
  },
  user: {
    type: Object,
    default: null
  }
});

// Store
const authStore = useAuthStore();

// 🔧 Menú items con verificación de permisos (sin usar hasPermission)
const menuItems = computed(() => {
  const items = [
    { path: '/dashboard', name: 'Dashboard', icon: '📊', permission: 'dashboard:ver' }
  ];

  // 🔧 Verificar si el usuario tiene permisos de forma segura
  const userPermissions = authStore.user?.permissions || {};
  const userRoles = authStore.user?.roles || [];

  // Verificar si es admin (tiene rol Administrador)
  const isAdmin = userRoles.some(r => r.nombre === 'Administrador');

  // Verificar permisos de documentos
  if (userPermissions.documentos?.includes('ver') || isAdmin) {
    items.push({ path: '/documentos', name: 'Documentos', icon: '📄', permission: 'documentos:ver' });
  }

  // Verificar permisos de noticias
  if (userPermissions.noticias?.includes('ver') || isAdmin) {
    items.push({ path: '/noticias-admin', name: 'Noticias', icon: '📰', permission: 'noticias:ver' });
  }

  // Verificar permisos de usuarios (solo admin)
  if (isAdmin) {
    items.push({ path: '/users', name: 'Usuarios', icon: '👥', permission: 'usuarios:ver' });
    items.push({ path: '/settings', name: 'Configuración', icon: '⚙️', permission: 'configuracion:ver' });
  }

  // Verificar permisos de profile (siempre visible)
  items.push({ path: '/dashboard/profile', name: 'Mi Perfil', icon: '👤' });

  return items;
});
</script>

<style scoped>
/* ==========================================
   SIDEBAR - ESTILOS
   ========================================== */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 250px;
  height: 100vh;
  background: #474A4E;
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
}

.logo-text {
  font-size: 20px;
  font-weight: 700;
  color: #fff;
  letter-spacing: 1px;
}

.logo-icon {
  font-size: 28px;
}

.sidebar-nav {
  flex: 1;
  padding: 20px 12px;
  overflow-y: auto;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 12px 16px;
  margin-bottom: 4px;
  border-radius: 10px;
  color: rgba(255, 255, 255, 0.6);
  text-decoration: none;
  transition: all 0.2s ease;
  cursor: pointer;
}

.logo-image {
  width: 70%;
  object-fit: contain; /* Mantiene la proporción */
  display: block;
  margin: 0 auto;     /* Centra horizontalmente */
}

/* 🔧 Cuando el sidebar está colapsado */
.sidebar.collapsed .logo-image {
  width: 40px;
  height: 40px;
}

.nav-item:hover {
  background: rgba(255, 255, 255, 0.08);
  color: white;
}

.nav-item.active {
  background: rgba(255, 255, 255, 0.08);;
  color: white;
  box-shadow: inset 3px 0 0 white;
}

.nav-icon {
  font-size: 20px;
  min-width: 30px;
  text-align: center;
}

.nav-text {
  margin-left: 12px;
  font-size: 14px;
  font-weight: 500;
  white-space: nowrap;
}

.sidebar.collapsed .nav-text {
  display: none;
}

.sidebar.collapsed .nav-item {
  justify-content: center;
  padding: 12px;
}

/* Scrollbar */
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

/* Responsive */
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
