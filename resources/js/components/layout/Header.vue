<template>
  <header class="header">
    <div class="header-left">
      <button class="menu-toggle" @click="toggleMobileMenu">
        <i class="fas fa-bars"></i>
      </button>
      <h1 class="page-title">{{ pageTitle }}</h1>
    </div>

    <div class="header-right">
      <!-- Buscador -->
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input
          type="text"
          placeholder="Buscar..."
          v-model="searchQuery"
          @keyup.enter="handleSearch"
        />
      </div>

      <!-- Notificaciones -->
      <div class="notification-wrapper" @click="toggleNotifications">
        <button class="icon-btn">
          <i class="fas fa-bell"></i>
          <span v-if="unreadCount > 0" class="badge">{{ unreadCount }}</span>
        </button>
        <div v-if="showNotifications" class="dropdown-menu notifications-dropdown">
          <div class="dropdown-header">
            <span>Notificaciones</span>
            <button class="mark-all">Marcar todas como leídas</button>
          </div>
          <div class="notification-item" v-for="notif in notifications" :key="notif.id">
            <i :class="notif.icon"></i>
            <div>
              <p>{{ notif.message }}</p>
              <span>{{ notif.time }}</span>
            </div>
          </div>
          <div class="dropdown-footer">
            <a href="#">Ver todas las notificaciones</a>
          </div>
        </div>
      </div>

      <!-- Usuario -->
      <div class="user-menu-wrapper" @click="toggleUserMenu">
        <button class="user-btn">
          <div class="user-avatar">{{ userInitials }}</div>
          <span class="user-name">{{ userName }}</span>
          <i class="fas fa-chevron-down" :class="{ 'rotated': showUserMenu }"></i>
        </button>

        <div v-if="showUserMenu" class="dropdown-menu user-dropdown">
          <div class="dropdown-header">
            <div class="user-info-dropdown">
              <div class="user-avatar-large">{{ userInitials }}</div>
              <div>
                <div class="user-name-full">{{ userName }}</div>
                <div class="user-email">{{ userEmail }}</div>
              </div>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <router-link to="/dashboard/profile" class="dropdown-item">
            <i class="fas fa-user"></i> Mi Perfil
          </router-link>
          <router-link to="/dashboard/settings" class="dropdown-item">
            <i class="fas fa-cog"></i> Configuración
          </router-link>
          <div class="dropdown-divider"></div>
          <button @click="handleLogout" class="dropdown-item logout">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useAuthStore } from '../../api/auth';
import { useRoute } from 'vue-router';

const emit = defineEmits(['logout']);
const authStore = useAuthStore();
const route = useRoute();

// Estado
const searchQuery = ref('');
const showNotifications = ref(false);
const showUserMenu = ref(false);
const unreadCount = ref(3);

// Computed
const userName = computed(() => authStore.user?.name || 'Usuario');
const userEmail = computed(() => authStore.user?.email || 'usuario@email.com');
const userInitials = computed(() => {
  return userName.value
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);
});

const pageTitle = computed(() => {
  const titles = {
    '/dashboard': 'Dashboard',
    '/dashboard/users': 'Usuarios',
    '/dashboard/products': 'Productos',
    '/dashboard/orders': 'Pedidos',
    '/dashboard/profile': 'Mi Perfil',
    '/dashboard/settings': 'Configuración',
    '/dashboard/statistics': 'Estadísticas'
  };
  return titles[route.path] || 'Dashboard';
});

// Notificaciones de ejemplo
const notifications = ref([
  { id: 1, icon: 'fas fa-user-plus', message: 'Nuevo usuario registrado', time: 'Hace 5 min' },
  { id: 2, icon: 'fas fa-shopping-cart', message: 'Nuevo pedido #1234', time: 'Hace 15 min' },
  { id: 3, icon: 'fas fa-comment', message: 'Nuevo comentario en tu publicación', time: 'Hace 1 hora' },
]);

// Métodos
const toggleMobileMenu = () => {
  const sidebar = document.querySelector('.sidebar');
  sidebar.classList.toggle('collapsed');
};

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
  if (showNotifications.value) {
    showUserMenu.value = false;
  }
};

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
  if (showUserMenu.value) {
    showNotifications.value = false;
  }
};

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    console.log('Buscando:', searchQuery.value);
    // Implementar búsqueda aquí
  }
};

const handleLogout = () => {
  emit('logout');
  showUserMenu.value = false;
};

// Cerrar dropdowns al hacer clic fuera
const handleClickOutside = (event) => {
  if (!event.target.closest('.user-menu-wrapper') && !event.target.closest('.notification-wrapper')) {
    showUserMenu.value = false;
    showNotifications.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
.header {
  position: fixed;
  top: 0;
  right: 0;
  left: 250px;
  height: 70px;
  background: #fff;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  z-index: 900;
  transition: left 0.3s ease;
}

.main-content.expanded .header {
  left: 70px;
}

/* Header Left */
.header-left {
  display: flex;
  align-items: center;
  gap: 20px;
}

.menu-toggle {
  display: none;
  background: none;
  border: none;
  font-size: 1.2rem;
  color: #333;
  cursor: pointer;
  padding: 5px;
}

.page-title {
  font-size: 1.3rem;
  font-weight: 600;
  color: #1a1a2e;
  margin: 0;
}

/* Header Right */
.header-right {
  display: flex;
  align-items: center;
  gap: 15px;
}

/* Search Box */
.search-box {
  position: relative;
  display: flex;
  align-items: center;
}

.search-box i {
  position: absolute;
  left: 12px;
  color: #999;
}

.search-box input {
  padding: 8px 15px 8px 38px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 0.9rem;
  width: 220px;
  transition: all 0.3s;
  background: #f7fafc;
}

.search-box input:focus {
  outline: none;
  border-color: #667eea;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  width: 260px;
}

/* Icon Buttons */
.icon-btn {
  position: relative;
  background: none;
  border: none;
  font-size: 1.2rem;
  color: #555;
  cursor: pointer;
  padding: 8px;
  border-radius: 8px;
  transition: all 0.2s;
}

.icon-btn:hover {
  background: #f7fafc;
  color: #667eea;
}

.badge {
  position: absolute;
  top: 2px;
  right: 2px;
  background: #e53e3e;
  color: #fff;
  font-size: 0.6rem;
  padding: 2px 6px;
  border-radius: 50%;
  min-width: 18px;
  text-align: center;
}

/* User Button */
.user-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 5px 10px;
  border-radius: 8px;
  transition: all 0.2s;
}

.user-btn:hover {
  background: #f7fafc;
}

.user-avatar {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-weight: bold;
  font-size: 0.9rem;
}

.user-name {
  font-size: 0.9rem;
  color: #333;
  font-weight: 500;
}

.user-btn .fa-chevron-down {
  font-size: 0.7rem;
  color: #999;
  transition: transform 0.2s;
}

.user-btn .fa-chevron-down.rotated {
  transform: rotate(180deg);
}

/* Dropdown Menus */
.dropdown-menu {
  position: absolute;
  top: calc(100% + 10px);
  right: 0;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
  min-width: 280px;
  overflow: hidden;
  animation: slideDown 0.2s ease;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.notification-wrapper {
  position: relative;
}

.notifications-dropdown {
  right: -80px;
  min-width: 320px;
  max-height: 400px;
  overflow-y: auto;
}

.dropdown-header {
  padding: 15px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 600;
  color: #1a1a2e;
}

.mark-all {
  background: none;
  border: none;
  color: #667eea;
  font-size: 0.8rem;
  cursor: pointer;
}

.notification-item {
  display: flex;
  gap: 12px;
  padding: 12px 20px;
  border-bottom: 1px solid #f0f2f5;
  transition: background 0.2s;
}

.notification-item:hover {
  background: #f7fafc;
}

.notification-item i {
  margin-top: 3px;
  color: #667eea;
}

.notification-item p {
  margin: 0;
  font-size: 0.9rem;
  color: #333;
}

.notification-item span {
  font-size: 0.75rem;
  color: #999;
}

.dropdown-footer {
  padding: 12px 20px;
  text-align: center;
  border-top: 1px solid #f0f2f5;
}

.dropdown-footer a {
  color: #667eea;
  text-decoration: none;
  font-size: 0.9rem;
}

/* User Dropdown */
.user-menu-wrapper {
  position: relative;
}

.user-dropdown {
  min-width: 260px;
}

.user-info-dropdown {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 0;
}

.user-avatar-large {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-weight: bold;
  font-size: 1.2rem;
}

.user-name-full {
  font-weight: 600;
  color: #1a1a2e;
}

.user-email {
  font-size: 0.8rem;
  color: #999;
}

.dropdown-divider {
  height: 1px;
  background: #f0f2f5;
  margin: 0 10px;
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 20px;
  color: #333;
  text-decoration: none;
  transition: background 0.2s;
  width: 100%;
  border: none;
  background: none;
  cursor: pointer;
  font-size: 0.9rem;
}

.dropdown-item:hover {
  background: #f7fafc;
}

.dropdown-item i {
  width: 18px;
  color: #667eea;
}

.dropdown-item.logout {
  color: #e53e3e;
}

.dropdown-item.logout i {
  color: #e53e3e;
}

/* Responsive */
@media (max-width: 1024px) {
  .search-box input {
    width: 150px;
  }

  .search-box input:focus {
    width: 180px;
  }
}

@media (max-width: 768px) {
  .header {
    left: 0;
    padding: 0 15px;
  }

  .menu-toggle {
    display: block;
  }

  .search-box input {
    width: 120px;
  }

  .search-box input:focus {
    width: 150px;
  }

  .user-name {
    display: none;
  }

  .notifications-dropdown {
    right: -120px;
    min-width: 280px;
  }
}

@media (max-width: 480px) {
  .search-box {
    display: none;
  }

  .notifications-dropdown {
    right: -140px;
    min-width: 260px;
  }
}
</style>
