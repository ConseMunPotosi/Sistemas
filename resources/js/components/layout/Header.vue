<template>
  <header class="header" :class="{ expanded: isExpanded }">
    <div class="header-left">
      <button @click="toggleSidebar" class="menu-btn" title="Toggle Sidebar">
        <svg class="menu-icon" viewBox="0 0 24 24" width="24" height="24">
          <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" fill="currentColor"/>
        </svg>
      </button>
      <p class="page-title">Concejo Municipal de Potosí</p>
    </div>

    <div class="header-right">
      <!-- Fecha y Hora -->
      <div class="datetime">
        <span class="date">{{ currentDate }}</span>
        <span class="time">{{ currentTime }}</span>
      </div>

      <div class="divider"></div>

      <!-- Notificaciones -->
      <div class="notification-wrapper" @click.stop="toggleNotifications">
        <button class="icon-btn notification-btn" title="Notificaciones">
          <svg class="icon" viewBox="0 0 24 24" width="22" height="22">
            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z" fill="currentColor"/>
          </svg>
          <span v-if="unreadNotifications > 0" class="badge">{{ unreadNotifications }}</span>
        </button>

        <div v-if="showNotifications" class="dropdown-menu notification-dropdown">
          <div class="dropdown-header">
            <span>Notificaciones</span>
            <button @click="markAllAsRead" class="mark-read-btn">Marcar todas</button>
          </div>
          <div class="dropdown-body">
            <div v-if="notifications.length === 0" class="empty-state">
              <span>No hay notificaciones</span>
            </div>
            <div v-for="notif in notifications" :key="notif.id" class="notification-item" :class="{ unread: !notif.read }">
              <span class="notification-icon">{{ notif.icon || '📌' }}</span>
              <div class="notification-content">
                <p class="notification-text">{{ notif.message }}</p>
                <span class="notification-time">{{ notif.time }}</span>
              </div>
            </div>
          </div>
          <div class="dropdown-footer">
            <button @click="viewAllNotifications" class="view-all-btn">Ver todas</button>
          </div>
        </div>
      </div>

      <div class="divider"></div>

      <!-- Perfil de Usuario con Submenú -->
      <!-- 🔧 Mostrar solo si hay usuario -->
      <div v-if="user" class="user-profile" @click.stop="toggleUserMenu">
        <div class="user-info">
          <span class="user-name">{{ user?.displayName || user?.usuario || 'Usuario' }}</span>
          <span class="user-role">{{ userRoles }}</span>
        </div>
        <div class="user-avatar">
          <span class="avatar-text">{{ userInitials }}</span>
        </div>
        <svg class="dropdown-arrow" :class="{ rotated: showUserMenu }" viewBox="0 0 24 24" width="18" height="18">
          <path d="M7 10l5 5 5-5z" fill="currentColor"/>
        </svg>

        <!-- Submenú de Usuario -->
        <div v-if="showUserMenu" class="dropdown-menu user-dropdown">
          <!-- Header del dropdown -->
          <div class="dropdown-header user-dropdown-header">
            <div class="user-avatar-large">
              <span class="avatar-text-large">{{ userInitials }}</span>
            </div>
            <div class="user-dropdown-info">
              <span class="user-dropdown-name">{{ user?.displayName || user?.usuario || 'Usuario' }}</span>
              <span class="user-dropdown-email">{{ user?.correo || 'usuario@ejemplo.com' }}</span>
            </div>
          </div>

          <!-- Opciones del dropdown -->
          <div class="dropdown-body">
            <router-link to="/dashboard/profile" class="dropdown-item" @click="closeDropdown">
              <svg class="dropdown-icon" viewBox="0 0 24 24" width="20" height="20">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/>
              </svg>
              <span>Mi Perfil</span>
            </router-link>

            <router-link to="/dashboard/settings" class="dropdown-item" @click="closeDropdown">
              <svg class="dropdown-icon" viewBox="0 0 24 24" width="20" height="20">
                <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.07.62-.07.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" fill="currentColor"/>
              </svg>
              <span>Configuración</span>
            </router-link>

            <router-link to="/dashboard/users" class="dropdown-item" @click="closeDropdown">
              <svg class="dropdown-icon" viewBox="0 0 24 24" width="20" height="20">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" fill="currentColor"/>
              </svg>
              <span>Usuarios</span>
            </router-link>

            <div class="dropdown-divider"></div>

            <button @click="openChangePassword" class="dropdown-item">
              <svg class="dropdown-icon" viewBox="0 0 24 24" width="20" height="20">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" fill="currentColor"/>
              </svg>
              <span>Cambiar Contraseña</span>
            </button>

            <button @click="openColorSettings" class="dropdown-item">
              <svg class="dropdown-icon" viewBox="0 0 24 24" width="20" height="20">
                <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.38-.61-.38-.99 0-.83.67-1.5 1.5-1.5H16c2.76 0 5-2.24 5-5 0-4.42-4.03-8-9-8zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 9 6.5 9 8 9.67 8 10.5 7.33 12 6.5 12zm3-4C8.67 8 8 7.33 8 6.5S8.67 5 9.5 5s1.5.67 1.5 1.5S10.33 8 9.5 8zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 5 14.5 5s1.5.67 1.5 1.5S15.33 8 14.5 8zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 9 17.5 9s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z" fill="currentColor"/>
              </svg>
              <span>Ajustes de Color</span>
            </button>

            <div class="dropdown-divider"></div>

            <!-- 🔧 Cerrar Sesión con manejo de evento -->
            <button @click="handleLogout" class="dropdown-item logout-item">
              <svg class="dropdown-icon" viewBox="0 0 24 24" width="20" height="20">
                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" fill="currentColor"/>
              </svg>
              <span>Cerrar Sesión</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';

// Props
const props = defineProps({
  user: {
    type: Object,
    default: null
  },
  isExpanded: {
    type: Boolean,
    default: false
  }
});

// Emits
const emit = defineEmits(['toggle-sidebar', 'logout']);

// Router
const router = useRouter();

// ==========================================
// ESTADO
// ==========================================
const currentDate = ref('');
const currentTime = ref('');
const showUserMenu = ref(false);
const showNotifications = ref(false);
const unreadNotifications = ref(3);

// ==========================================
// DATOS DE EJEMPLO
// ==========================================
const notifications = ref([
  { id: 1, icon: '📄', message: 'Nuevo documento asignado', time: 'Hace 5 min', read: false },
  { id: 2, icon: '✅', message: 'Documento aprobado', time: 'Hace 15 min', read: false },
  { id: 3, icon: '📌', message: 'Reunión programada', time: 'Hace 2 horas', read: false }
]);

// ==========================================
// COMPUTED
// ==========================================
const userInitials = computed(() => {
  if (!props.user) return 'U';
  const name = props.user?.displayName || props.user?.usuario || 'U';
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
});

const userRoles = computed(() => {
  if (!props.user?.roles) return 'Usuario';
  return props.user.roles.map(r => r.nombre).join(', ');
});

// ==========================================
// MÉTODOS DE FECHA Y HORA
// ==========================================
const updateDateTime = () => {
  const now = new Date();
  const options = {
    weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  };
  currentDate.value = now.toLocaleDateString('es-ES', options);
  currentTime.value = now.toLocaleTimeString('es-ES', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  });
};

// ==========================================
// MÉTODOS DE TOGGLE
// ==========================================
const toggleSidebar = () => {
  emit('toggle-sidebar');
};

const toggleUserMenu = (event) => {
  event.stopPropagation();
  showUserMenu.value = !showUserMenu.value;
  showNotifications.value = false;
};

const toggleNotifications = (event) => {
  event.stopPropagation();
  showNotifications.value = !showNotifications.value;
  showUserMenu.value = false;
};

const closeDropdown = () => {
  showUserMenu.value = false;
  showNotifications.value = false;
};

const closeAllDropdowns = () => {
  showUserMenu.value = false;
  showNotifications.value = false;
};

// ==========================================
// ACCIONES DEL SUBMENÚ
// ==========================================
// 🔧 Handle Logout - Emitir evento al padre
const handleLogout = () => {
  showUserMenu.value = false;
  emit('logout');
};

const goToProfile = () => {
  showUserMenu.value = false;
  router.push({ name: 'profile' });
};

const openChangePassword = () => {
  showUserMenu.value = false;
  alert('Función de cambio de contraseña en desarrollo');
};

const openColorSettings = () => {
  showUserMenu.value = false;
  alert('Función de ajustes de color en desarrollo');
};

// ==========================================
// ACCIONES DE NOTIFICACIONES
// ==========================================
const markAllAsRead = () => {
  notifications.value.forEach(n => n.read = true);
  unreadNotifications.value = 0;
};

const viewAllNotifications = () => {
  showNotifications.value = false;
  alert('Ver todas las notificaciones');
};

// ==========================================
// CICLO DE VIDA
// ==========================================
onMounted(() => {
  updateDateTime();
  const interval = setInterval(updateDateTime, 1000);

  document.addEventListener('click', closeAllDropdowns);

  onUnmounted(() => {
    clearInterval(interval);
    document.removeEventListener('click', closeAllDropdowns);
  });
});
</script>



<style scoped>
/* ==========================================
   HEADER PRINCIPAL
   ========================================== */
.header {
  position: fixed;
  top: 0;
  right: 0;
  left: 250px;
  height: 70px;
  background: linear-gradient(to bottom, #cc0000 0%, #8B0000 100%);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 25px;
  z-index: 1000;
  transition: left 0.3s ease;
}

.header.expanded {
  left: 70px;
}

/* ==========================================
   LADO IZQUIERDO
   ========================================== */
.header-left {
  display: flex;
  align-items: center;
  gap: 18px;
}

.menu-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 6px;
  border-radius: 8px;
  color: rgba(255, 255, 255, 0.8);
  transition: all 0.3s;
}

.menu-btn:hover {
  background: rgba(255, 255, 255, 0.15);
  color: white;
}

.menu-icon {
  display: block;
}

.page-title {
  font-size: 20px;
  font-weight: 600;
  color: white;
  margin: 0;
  letter-spacing: 0.5px;
}

/* ==========================================
   LADO DERECHO
   ========================================== */
.header-right {
  display: flex;
  align-items: center;
  gap: 15px;
}

/* ==========================================
   FECHA Y HORA
   ========================================== */
.datetime {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  padding: 4px 0;
}

.date {
  font-size: 13px;
  font-weight: 500;
  color: rgba(255, 255, 255, 0.95);
  line-height: 1.2;
}

.time {
  font-size: 12px;
  color: rgba(255, 255, 255, 0.7);
  line-height: 1.2;
}

/* ==========================================
   DIVISOR
   ========================================== */
.divider {
  width: 1px;
  height: 35px;
  background: rgba(255, 255, 255, 0.2);
}

/* ==========================================
   ICONO DE NOTIFICACIONES
   ========================================== */
.notification-wrapper {
  position: relative;
}

.icon-btn {
  position: relative;
  background: none;
  border: none;
  cursor: pointer;
  padding: 8px;
  border-radius: 50%;
  color: rgba(255, 255, 255, 0.8);
  transition: all 0.3s;
}

.icon-btn:hover {
  background: rgba(255, 255, 255, 0.15);
  color: white;
}

.icon {
  display: block;
}

.badge {
  position: absolute;
  top: 2px;
  right: 2px;
  background: #ef4444;
  color: white;
  font-size: 10px;
  font-weight: 700;
  min-width: 18px;
  height: 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #cc0000;
}

/* ==========================================
   PERFIL DE USUARIO
   ========================================== */
.user-profile {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  padding: 6px 12px 6px 6px;
  border-radius: 50px;
  transition: all 0.3s;
  position: relative;
}

.user-profile:hover {
  background: rgba(255, 255, 255, 0.1);
}

.user-avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: linear-gradient(135deg, #ff6b6b, #cc0000);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 14px;
  flex-shrink: 0;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.avatar-text {
  text-transform: uppercase;
}

.user-info {
  display: flex;
  flex-direction: column;
  line-height: 1.2;
}

.user-name {
  font-weight: 600;
  color: white;
  font-size: 14px;
}

.user-role {
  font-size: 11px;
  color: rgba(255, 255, 255, 0.7);
}

.dropdown-arrow {
  color: rgba(255, 255, 255, 0.6);
  transition: transform 0.3s;
  flex-shrink: 0;
}

.dropdown-arrow.rotated {
  transform: rotate(180deg);
}

/* ==========================================
   DROPDOWN - ESTILOS PRINCIPALES
   ========================================== */
.dropdown-menu {
  position: absolute;
  top: calc(100% + 10px);
  right: 0;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  min-width: 260px;
  max-width: 320px;
  z-index: 9999;
  overflow: hidden;
  animation: slideDown 0.2s ease;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* ==========================================
   NOTIFICACIONES DROPDOWN
   ========================================== */
.notification-dropdown {
  min-width: 320px;
}

/* ==========================================
   USUARIO DROPDOWN
   ========================================== */
.user-dropdown {
  min-width: 280px;
}

/* Header del dropdown */
.dropdown-header {
  padding: 16px 20px;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.dropdown-header span {
  font-weight: 600;
  color: #1f2937;
  font-size: 14px;
}

/* Header del usuario */
.user-dropdown-header {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 16px 20px;
}

.user-avatar-large {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: linear-gradient(135deg, #ff6b6b, #cc0000);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: 18px;
  flex-shrink: 0;
}

.avatar-text-large {
  text-transform: uppercase;
}

.user-dropdown-info {
  flex: 1;
  min-width: 0;
}

.user-dropdown-name {
  font-weight: 600;
  color: #1f2937;
  font-size: 15px;
  display: block;
}

.user-dropdown-email {
  font-size: 12px;
  color: #6b7280;
}

/* Cuerpo del dropdown */
.dropdown-body {
  max-height: 350px;
  overflow-y: auto;
  padding: 6px 0;
}

/* Items del dropdown */
.dropdown-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 20px;
  background: none;
  border: none;
  width: 100%;
  cursor: pointer;
  color: #374151;
  font-size: 14px;
  transition: all 0.15s;
  text-align: left;
  text-decoration: none;
  font-family: inherit;
}

.dropdown-item:hover {
  background: #f3f4f6;
  color: #1f2937;
}

.dropdown-item:active {
  background: #e5e7eb;
}

.dropdown-item.logout-item {
  color: #dc2626;
}

.dropdown-item.logout-item:hover {
  background: #fef2f2;
}

.dropdown-icon {
  width: 20px;
  height: 20px;
  color: #6b7280;
  flex-shrink: 0;
}

.dropdown-item.logout-item .dropdown-icon {
  color: #dc2626;
}

.dropdown-divider {
  height: 1px;
  background: #e5e7eb;
  margin: 4px 12px;
}

/* Footer del dropdown */
.dropdown-footer {
  padding: 10px 20px;
  border-top: 1px solid #e5e7eb;
  text-align: center;
}

/* ==========================================
   NOTIFICACIONES ITEMS
   ========================================== */
.notification-item {
  display: flex;
  gap: 12px;
  padding: 12px 20px;
  border-bottom: 1px solid #f3f4f6;
  transition: background 0.2s;
}

.notification-item:hover {
  background: #f9fafb;
}

.notification-item.unread {
  background: #eff6ff;
}

.notification-item.unread:hover {
  background: #dbeafe;
}

.notification-icon {
  font-size: 20px;
  flex-shrink: 0;
}

.notification-content {
  flex: 1;
  min-width: 0;
}

.notification-text {
  font-size: 13px;
  color: #1f2937;
  margin: 0 0 4px 0;
  line-height: 1.4;
}

.notification-time {
  font-size: 11px;
  color: #9ca3af;
}

/* ==========================================
   BOTONES DE ACCIÓN
   ========================================== */
.view-all-btn,
.mark-read-btn {
  background: none;
  border: none;
  color: #4f46e5;
  font-weight: 500;
  font-size: 13px;
  cursor: pointer;
  padding: 4px 8px;
  transition: color 0.3s;
}

.view-all-btn:hover,
.mark-read-btn:hover {
  color: #4338ca;
}

.empty-state {
  padding: 40px 20px;
  text-align: center;
  color: #9ca3af;
  font-size: 14px;
}

/* ==========================================
   RESPONSIVE
   ========================================== */
@media (max-width: 1024px) {
  .user-info {
    display: none;
  }

  .user-profile {
    padding: 6px;
  }
}

@media (max-width: 768px) {
  .header {
    left: 0;
    padding: 0 15px;
  }

  .header.expanded {
    left: 0;
  }

  .datetime {
    display: none;
  }

  .divider {
    display: none;
  }

  .page-title {
    font-size: 16px;
  }

  .dropdown-menu {
    min-width: 240px;
    right: -10px;
  }

  .user-dropdown {
    min-width: 240px;
  }

  .notification-dropdown {
    min-width: 260px;
  }
}

@media (max-width: 480px) {
  .dropdown-menu {
    min-width: 200px;
    right: -20px;
  }

  .user-avatar {
    width: 32px;
    height: 32px;
    font-size: 12px;
  }

  .user-dropdown-header {
    padding: 12px 16px;
  }

  .user-avatar-large {
    width: 40px;
    height: 40px;
    font-size: 15px;
  }

  .dropdown-item {
    padding: 8px 16px;
    font-size: 13px;
  }
}
/* 🔧 FORZAR VISIBILIDAD DEL DROPDOWN */
.user-dropdown {
  display: block !important;
}

.user-dropdown .dropdown-body {
  display: block !important;
}

.user-dropdown .dropdown-item {
  display: flex !important;
}
</style>
