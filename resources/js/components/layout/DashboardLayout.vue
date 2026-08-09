<template>
  <div v-if="isReady" class="dashboard-layout">
    <Sidebar :is-collapsed="isCollapsed" @toggle-sidebar="toggleSidebar" />
    <div class="main-content" :class="{ expanded: isCollapsed }">
      <Header :user="user" @logout="handleLogout" />
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
import { ref, computed, onMounted, onBeforeMount } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../api/auth.js';
import Sidebar from './Sidebar.vue';
import Header from './Header.vue';

const router = useRouter();
const authStore = useAuthStore();
const isCollapsed = ref(false);
const isReady = ref(false);

const user = computed(() => authStore.user);

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
};

const handleLogout = async () => {
  console.log('🔍 Cerrando sesión...');
  await authStore.logout();
  console.log('✅ Sesión cerrada');

  // 🔧 Redirigir y reemplazar el historial para evitar back/forward
  window.location.replace('/loginCMP');
};

// 🔧 Verificar autenticación ANTES de montar el componente
onBeforeMount(() => {
  console.log('🔍 DashboardLayout - Verificando autenticación...');

  if (!authStore.isAuthenticated) {
    console.log('❌ No autenticado, redirigiendo...');
    window.location.replace('/loginCMP');
    return;
  }
});

onMounted(async () => {
  // Si no está autenticado, redirigir
  if (!authStore.isAuthenticated) {
    window.location.replace('/loginCMP');
    return;
  }

  // Si hay token pero no usuario, obtenerlo
  if (authStore.token && !authStore.user) {
    try {
      await authStore.fetchUser();
      if (!authStore.user) {
        window.location.replace('/loginCMP');
        return;
      }
    } catch (error) {
      window.location.replace('/loginCMP');
      return;
    }
  }

  isReady.value = true;
  console.log('✅ DashboardLayout - Listo');
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
