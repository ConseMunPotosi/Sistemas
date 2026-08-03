<template>
  <div class="dashboard-layout">
    <!-- Sidebar -->
    <Sidebar :is-collapsed="isCollapsed" @toggle-sidebar="toggleSidebar" />

    <!-- Contenido principal -->
    <div class="main-content" :class="{ 'expanded': isCollapsed }">
      <!-- Header -->
      <Header @logout="handleLogout" />

      <!-- Contenido dinámico -->
      <main class="content-area">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../api/auth.js';
import Sidebar from './Sidebar.vue';
import Header from './Header.vue';

const router = useRouter();
const authStore = useAuthStore();
const isCollapsed = ref(false);

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
};

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};
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
}

@media (max-width: 768px) {
  .main-content {
    margin-left: 0;
  }

  .main-content.expanded {
    margin-left: 0;
  }
}
</style>
