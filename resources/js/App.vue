<template>
  <router-view />
</template>

<script setup>
import { onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from './api/auth.js';

const router = useRouter();
const authStore = useAuthStore();

// 🔧 Función para verificar autenticación en cambios de historial
const handlePopState = () => {
  const currentPath = window.location.pathname;

  console.log('🔍 PopState - Path:', currentPath);
  console.log('🔍 PopState - Autenticado:', authStore.isAuthenticated);

  // Si está en dashboard y no autenticado, redirigir
  if (currentPath.includes('/dashboard') && !authStore.isAuthenticated) {
    console.log('❌ PopState - No autenticado en dashboard, redirigiendo...');
    window.location.replace('/loginCMP');
    return;
  }

  // Si está en login y autenticado, redirigir a dashboard
  if (currentPath.includes('/loginCMP') && authStore.isAuthenticated) {
    console.log('🔄 PopState - Autenticado en login, redirigiendo a dashboard...');
    window.location.replace('/dashboard');
  }
};

onMounted(() => {
  // Verificar autenticación al cargar la app
  if (authStore.token && !authStore.user) {
    authStore.fetchUser();
  }

  // 🔧 Escuchar cambios en el historial (back/forward)
  window.addEventListener('popstate', handlePopState);

  // 🔧 También verificar al cargar la página (por si el usuario recarga)
  handlePopState();
});

onBeforeUnmount(() => {
  window.removeEventListener('popstate', handlePopState);
});
</script>
