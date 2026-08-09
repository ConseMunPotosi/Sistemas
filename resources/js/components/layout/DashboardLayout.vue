<template>
  <div class="dashboard-layout">
    <!-- Sidebar -->
    <Sidebar
      :is-collapsed="isCollapsed"
      :user="user"
      @toggle-sidebar="toggleSidebar"
    />

    <!-- Contenido principal -->
    <div class="main-content" :class="{ 'expanded': isCollapsed }">
      <!-- Header -->
      <Header
        :user="user"
        @logout="handleLogout"
      />

      <!-- Contenido dinámico -->
      <main class="content-area">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useRouter, useRoute } from 'vue-router';
// ✅ Este import es correcto para tu estructura
import { useAuthStore } from '../../api/auth.js';
import Sidebar from './Sidebar.vue';
import Header from './Header.vue';

// ==========================================
// COMPOSABLES
// ==========================================
const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

// ==========================================
// ESTADO
// ==========================================
const isCollapsed = ref(false);
const isLoading = ref(true);

// ==========================================
// COMPUTED
// ==========================================
const user = computed(() => authStore.user);
const isAuthenticated = computed(() => authStore.isAuthenticated);
const userPermissions = computed(() => authStore.userPermissions);

// ==========================================
// MÉTODOS
// ==========================================
const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
};

// 🔧 CORREGIDO: Usar el nombre correcto de la ruta
const handleLogout = async () => {
  try {
    await authStore.logout();
    // 🔧 CAMBIO IMPORTANTE: Usar 'loginCMP' en lugar de 'login'
    await router.replace({ name: 'loginCMP' });

  } catch (error) {

    // Si hay error, forzar logout local
    authStore.clearAuth();
    // 🔧 Fallback con URL directa
    window.location.href = '/loginCMP';
  }
};

// ==========================================
// CICLO DE VIDA
// ==========================================
onMounted(async () => {
  // Verificar autenticación
  if (!authStore.isAuthenticated) {
    console.log('🔍 DashboardLayout - No autenticado');
    // 🔧 CAMBIO: Usar 'loginCMP'
    router.replace({ name: 'loginCMP' });
    return;
  }

  // Si está autenticado pero no tiene datos del usuario, obtenerlos
  if (authStore.token && !authStore.user) {
    try {
      console.log('🔍 DashboardLayout - Obteniendo usuario...');
      await authStore.fetchUser();
      console.log('🔍 DashboardLayout - Usuario obtenido:', authStore.user);
    } catch (error) {
      console.error('❌ Error al obtener datos del usuario:', error);
      authStore.clearAuth();
      // 🔧 CAMBIO: Usar 'loginCMP'
      router.replace({ name: 'loginCMP' });
      return;
    }
  }

  // Verificar si el usuario está activo
  if (authStore.user && authStore.user.activo === false) {
    console.log('🔍 DashboardLayout - Usuario inactivo');
    authStore.clearAuth();
    // 🔧 CAMBIO: Usar 'loginCMP'
    router.replace({ name: 'loginCMP' });
    return;
  }

  // Verificar permisos para la ruta actual
  const requiredPermission = route.meta.requiresPermission;
  if (requiredPermission) {
    const hasPermission = authStore.hasPermission(requiredPermission);
    if (!hasPermission) {
      console.log('🔍 DashboardLayout - Sin permiso:', requiredPermission);
      // ✅ Esto está bien, 'dashboard' es el nombre correcto
      router.replace({ name: 'dashboard' });
      return;
    }
  }

  isLoading.value = false;
});

onBeforeUnmount(() => {
  console.log('🔍 DashboardLayout - Desmontando');
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

/* Estilos para estado de carga */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #4f46e5;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
  .main-content {
    margin-left: 0;
  }

  .main-content.expanded {
    margin-left: 0;
  }

  .content-area {
    padding: 15px;
    margin-top: 60px;
  }
}

/* Transiciones */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
