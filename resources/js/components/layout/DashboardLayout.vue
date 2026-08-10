<template>
  <div v-if="isReady" class="dashboard-layout">
    <Sidebar :is-collapsed="isCollapsed" @toggle-sidebar="toggleSidebar" />

    <div class="main-content" :class="{ expanded: isCollapsed }">
      <!-- 🔧 Pasar isExpanded al Header -->
      <Header
        :user="user || null"
        :is-expanded="isCollapsed"
        @logout="handleLogout"
        @toggle-sidebar="toggleSidebar"
      />
      <main class="content-area">
        <router-view />
      </main>
    </div>
  </div>
  <div v-else class="loading-screen">
    <div class="spinner"></div>
    <p>Verificando sesión...</p>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeMount, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../api/auth.js';
import Sidebar from './Sidebar.vue';
import Header from './Header.vue';

const router = useRouter();
const authStore = useAuthStore();
const isCollapsed = ref(false);
const isReady = ref(false);
const isLoggingOut = ref(false);

const user = computed(() => authStore.user);
const isAuthenticated = computed(() => authStore.isAuthenticated);

// 🔧 Watch para detectar cuando el usuario se desautentica
watch(isAuthenticated, (newVal) => {
  if (!newVal && !isLoggingOut.value) {
    console.log('🔍 Autenticación perdida, redirigiendo...');
    window.location.replace('/loginCMP');
  }
});

// 🔧 Toggle Sidebar
const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
  console.log('🔍 Sidebar colapsado:', isCollapsed.value);
};

// 🔧 Handle Logout
const handleLogout = async () => {
  if (isLoggingOut.value) return;

  isLoggingOut.value = true;
  console.log('🔍 Cerrando sesión...');

  try {
    await authStore.logout();
    console.log('✅ Sesión cerrada');
    isReady.value = false;
    window.location.replace('/loginCMP');
  } catch (error) {
    console.error('❌ Error al cerrar sesión:', error);
    authStore.clearAuth();
    isReady.value = false;
    window.location.replace('/loginCMP');
  } finally {
    isLoggingOut.value = false;
  }
};

// 🔧 Verificar autenticación
const checkAuth = async () => {
  const token = localStorage.getItem('auth_token');

  if (!token) {
    console.log('❌ No hay token, redirigiendo...');
    window.location.replace('/loginCMP');
    return false;
  }

  if (token && !authStore.user) {
    try {
      await authStore.fetchUser();
      if (!authStore.user) {
        console.log('❌ No se pudo obtener usuario');
        window.location.replace('/loginCMP');
        return false;
      }
    } catch (error) {
      console.error('❌ Error obteniendo usuario:', error);
      window.location.replace('/loginCMP');
      return false;
    }
  }

  return true;
};

onBeforeMount(async () => {
  if (!authStore.isAuthenticated && !localStorage.getItem('auth_token')) {
    window.location.replace('/loginCMP');
    return;
  }
});

onMounted(async () => {
  const isValid = await checkAuth();
  if (!isValid) {
    return;
  }

  isReady.value = true;
});
</script>

<style scoped>
.dashboard-layout {
  display: flex;
  min-height: 100vh;
  background: #f0f2f5;
}

.main-content {
  flex: 1;
  margin-left: 250px;
  transition: margin-left 0.3s ease;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content.expanded {
  margin-left: 70px;
}

.content-area {
  flex: 1;
  padding: 20px;
  margin-top: 70px;
  overflow-y: auto;
}

.loading-screen {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: #f0f2f5;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #e5e7eb;
  border-top: 4px solid #4f46e5;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.loading-screen p {
  margin-top: 16px;
  color: #6b7280;
}

@media (max-width: 768px) {
  .main-content {
    margin-left: 0;
  }
}
</style>
