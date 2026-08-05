<template>
  <header class="header">
    <div class="header-left">
      <button @click="$emit('toggle-sidebar')" class="menu-btn">
        <span class="material-icons">menu</span>
      </button>
      <h1 class="page-title">{{ pageTitle }}</h1>
    </div>

    <div class="header-right">
      <div class="user-info">
        <span class="user-name">{{ user?.displayName || user?.usuario }}</span>
        <span class="user-role">{{ userRoles }}</span>
      </div>

      <button @click="$emit('logout')" class="logout-btn">
        <span class="material-icons">logout</span>
        Cerrar Sesión
      </button>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const props = defineProps({
  user: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['toggle-sidebar', 'logout']);
const route = useRoute();

const pageTitle = computed(() => {
  return route.meta?.title || 'Dashboard';
});

const userRoles = computed(() => {
  if (!props.user?.roles) return 'Usuario';
  return props.user.roles.map(r => r.nombre).join(', ');
});
</script>

<style scoped>
.header {
  position: fixed;
  top: 0;
  right: 0;
  left: 250px;
  height: 70px;
  background: white;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 20px;
  z-index: 100;
  transition: left 0.3s ease;
}

.header.expanded {
  left: 70px;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 15px;
}

.menu-btn {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 24px;
  color: #4f46e5;
}

.page-title {
  font-size: 20px;
  font-weight: 600;
  color: #1a1a2e;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 20px;
}

.user-info {
  text-align: right;
}

.user-name {
  font-weight: 500;
  color: #1a1a2e;
}

.user-role {
  display: block;
  font-size: 12px;
  color: #6b7280;
}

.logout-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  background: #ef4444;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.2s;
}

.logout-btn:hover {
  background: #dc2626;
}

@media (max-width: 768px) {
  .header {
    left: 0;
  }
}
</style>
