<template>
  <aside class="sidebar" :class="{ 'collapsed': isCollapsed }">
    <!-- Logo -->
    <div class="sidebar-header">
      <div class="logo">
        <span v-if="!isCollapsed" class="logo-text">Mi App</span>
        <span v-else class="logo-icon">📊</span>
      </div>
      <button class="toggle-btn" @click="toggleSidebar">
        <i :class="isCollapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left'"></i>
      </button>
    </div>

    <!-- Menú de navegación -->
    <nav class="sidebar-nav">
      <div class="nav-section" v-for="section in menuSections" :key="section.title">
        <div v-if="!isCollapsed" class="nav-section-title">{{ section.title }}</div>

        <router-link
          v-for="item in section.items"
          :key="item.path"
          :to="item.path"
          class="nav-item"
          :class="{ 'active': isActiveRoute(item.path) }"
        >
          <i :class="item.icon" class="nav-icon"></i>
          <span v-if="!isCollapsed" class="nav-label">{{ item.label }}</span>
          <span v-if="isCollapsed" class="nav-tooltip">{{ item.label }}</span>
        </router-link>
      </div>
    </nav>

    <!-- Footer del Sidebar -->
    <div class="sidebar-footer">
      <div class="user-info" v-if="!isCollapsed">
        <div class="user-avatar">{{ userInitials }}</div>
        <div class="user-details">
          <div class="user-name">{{ userName }}</div>
          <div class="user-role">{{ userRole }}</div>
        </div>
      </div>
      <div v-else class="user-avatar-small">{{ userInitials }}</div>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '../../api/auth';

const props = defineProps({
  isCollapsed: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['toggleSidebar']);
const route = useRoute();
const authStore = useAuthStore();

// Datos del usuario
const userName = computed(() => authStore.user?.name || 'Usuario');
const userInitials = computed(() => {
  return userName.value
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);
});
const userRole = computed(() => {
  const roleMap = {
    admin: 'Administrador',
    user: 'Usuario',
    editor: 'Editor'
  };
  return roleMap[authStore.user?.role] || 'Usuario';
});

// Menú de navegación
const menuSections = [
  {
    title: 'Principal',
    items: [
      { label: 'Dashboard', path: '/dashboard', icon: 'fas fa-home' },
      { label: 'Estadísticas', path: '/dashboard/statistics', icon: 'fas fa-chart-bar' },
    ]
  },
  {
    title: 'Gestión',
    items: [
      { label: 'Usuarios', path: '/dashboard/users', icon: 'fas fa-users' },
      { label: 'Productos', path: '/dashboard/products', icon: 'fas fa-box' },
      { label: 'Pedidos', path: '/dashboard/orders', icon: 'fas fa-shopping-cart' },
    ]
  },
  {
    title: 'Configuración',
    items: [
      { label: 'Perfil', path: '/dashboard/profile', icon: 'fas fa-user-cog' },
      { label: 'Ajustes', path: '/dashboard/settings', icon: 'fas fa-cog' },
    ]
  }
];

const isActiveRoute = (path) => {
  return route.path === path || route.path.startsWith(path + '/');
};

const toggleSidebar = () => {
  emit('toggleSidebar');
};
</script>

<style scoped>
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 250px;
  background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  color: #fff;
  transition: width 0.3s ease;
  display: flex;
  flex-direction: column;
  z-index: 1000;
  overflow: hidden;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar.collapsed {
  width: 70px;
}

/* Header del Sidebar */
.sidebar-header {
  padding: 20px 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  min-height: 70px;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  color: #fff;
  display: flex;
  align-items: center;
}

.logo-text {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.logo-icon {
  font-size: 1.8rem;
  -webkit-text-fill-color: #fff;
}

.toggle-btn {
  background: rgba(255, 255, 255, 0.1);
  border: none;
  color: #fff;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.toggle-btn:hover {
  background: rgba(255, 255, 255, 0.2);
}

/* Navegación */
.sidebar-nav {
  flex: 1;
  padding: 20px 0;
  overflow-y: auto;
  overflow-x: hidden;
}

.sidebar-nav::-webkit-scrollbar {
  width: 4px;
}

.sidebar-nav::-webkit-scrollbar-track {
  background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 2px;
}

.nav-section {
  margin-bottom: 20px;
}

.nav-section-title {
  padding: 0 20px 8px;
  font-size: 0.7rem;
  text-transform: uppercase;
  color: rgba(255, 255, 255, 0.4);
  letter-spacing: 1px;
  font-weight: 600;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  color: rgba(255, 255, 255, 0.7);
  text-decoration: none;
  transition: all 0.2s;
  position: relative;
  cursor: pointer;
}

.nav-item:hover {
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
}

.nav-item.active {
  background: rgba(102, 126, 234, 0.2);
  color: #667eea;
  border-right: 3px solid #667eea;
}

.nav-item.active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  height: 100%;
  width: 3px;
  background: #667eea;
}

.nav-icon {
  width: 20px;
  font-size: 1.1rem;
  text-align: center;
  margin-right: 15px;
  flex-shrink: 0;
}

.sidebar.collapsed .nav-icon {
  margin-right: 0;
}

.nav-label {
  font-size: 0.9rem;
  white-space: nowrap;
}

/* Tooltip para collapsed */
.nav-tooltip {
  display: none;
  position: absolute;
  left: 80px;
  top: 50%;
  transform: translateY(-50%);
  background: #1a1a2e;
  padding: 5px 12px;
  border-radius: 4px;
  font-size: 0.8rem;
  white-space: nowrap;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.nav-item:hover .nav-tooltip {
  display: block;
}

/* Footer del Sidebar */
.sidebar-footer {
  padding: 15px 20px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1rem;
  flex-shrink: 0;
}

.user-avatar-small {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 0.9rem;
  margin: 0 auto;
}

.user-details {
  flex: 1;
  min-width: 0;
}

.user-name {
  font-size: 0.9rem;
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-role {
  font-size: 0.75rem;
  color: rgba(255, 255, 255, 0.5);
}

@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }

  .sidebar:not(.collapsed) {
    transform: translateX(0);
  }

  .sidebar.collapsed {
    width: 0;
  }
}
</style>
