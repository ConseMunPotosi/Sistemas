<template>
  <aside class="sidebar" :class="{ collapsed: isCollapsed }">
    <div class="logo-container">
      <h2 v-if="!isCollapsed" class="logo-text">Sistema</h2>
      <h2 v-else class="logo-text-short">S</h2>
    </div>

    <nav class="nav-menu">
      <router-link
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        class="nav-item"
        :class="{ active: $route.path === item.path }"
      >
        <span class="material-icons nav-icon">{{ item.icon }}</span>
        <span v-if="!isCollapsed" class="nav-text">{{ item.name }}</span>
      </router-link>
    </nav>

    <div class="sidebar-footer">
      <div v-if="!isCollapsed" class="user-info">
        <span class="user-name">{{ user?.displayName || user?.usuario }}</span>
        <span class="user-email">{{ user?.correo }}</span>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue';
import { useAuthStore } from '../../api/auth.js';

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

const authStore = useAuthStore();

// Menú dinámico basado en permisos
const menuItems = computed(() => {
  const items = [
    { path: '/dashboard', name: 'Dashboard', icon: 'dashboard', permission: 'dashboard:ver' }
  ];

  // Verificar permisos para mostrar items
  if (authStore.hasPermission('documentos:ver')) {
    items.push({ path: '/documentos', name: 'Documentos', icon: 'description', permission: 'documentos:ver' });
  }

  if (authStore.hasPermission('noticias:ver')) {
    items.push({ path: '/noticias', name: 'Noticias', icon: 'newspaper', permission: 'noticias:ver' });
  }

  if (authStore.hasPermission('usuarios:ver')) {
    items.push({ path: '/usuarios', name: 'Usuarios', icon: 'people', permission: 'usuarios:ver' });
  }

  // Solo administradores
  if (authStore.isAdmin) {
    items.push({ path: '/configuracion', name: 'Configuración', icon: 'settings', permission: 'configuracion:ver' });
  }

  return items;
});
</script>

<style scoped>
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 250px;
  height: 100vh;
  background: #1a1a2e;
  color: white;
  display: flex;
  flex-direction: column;
  transition: width 0.3s ease;
  z-index: 200;
  overflow: hidden;
}

.sidebar.collapsed {
  width: 70px;
}

.logo-container {
  padding: 20px;
  text-align: center;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

.logo-text {
  font-size: 24px;
  font-weight: 700;
  color: #4f46e5;
}

.logo-text-short {
  font-size: 24px;
  font-weight: 700;
  color: #4f46e5;
}

.nav-menu {
  flex: 1;
  padding: 20px 0;
  overflow-y: auto;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  color: rgba(255,255,255,0.7);
  text-decoration: none;
  transition: all 0.2s;
  cursor: pointer;
}

.nav-item:hover {
  background: rgba(255,255,255,0.1);
  color: white;
}

.nav-item.active {
  background: #4f46e5;
  color: white;
}

.nav-icon {
  font-size: 24px;
  margin-right: 15px;
  min-width: 24px;
}

.sidebar.collapsed .nav-text {
  display: none;
}

.sidebar-footer {
  padding: 20px;
  border-top: 1px solid rgba(255,255,255,0.1);
}

.user-info {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 500;
}

.user-email {
  font-size: 12px;
  color: rgba(255,255,255,0.7);
}
</style>
