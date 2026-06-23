<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold text-cmp-primary">
                🚀 Proyecto CMP
            </h1>
            <p class="text-gray-600 mt-2">Bienvenido al sistema</p>
            <div class="mt-4 space-x-4">
                <router-link to="/login" class="text-cmp-primary hover:underline">
                    Iniciar Sesión
                </router-link>
                <router-link to="/register" class="text-cmp-secondary hover:underline">
                    Registrarse
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
// Componente Home
</script>