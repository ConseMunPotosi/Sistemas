<template>
  <div class="default-layout">
    <!-- Header -->
    <header-component />

    <!-- Contenido principal -->
    <main class="main-content">
      <router-view />
    </main>

    <!-- Footer -->
    <footer-component />
  </div>
</template>

<script setup>
import HeaderComponent from '@/components/Header.vue';
import FooterComponent from '@/components/Footer.vue';
</script>

<style scoped>
.default-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  padding-top: 70px; /* Altura del header */
}
</style>
