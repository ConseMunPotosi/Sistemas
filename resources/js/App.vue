<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from './api/auth';

const authStore = useAuthStore();

onMounted(() => {
  if (authStore.token) {
    authStore.fetchUser();
  }
});
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  background: #f0f2f5;
}

#app {
  min-height: 100vh;
}
</style>
