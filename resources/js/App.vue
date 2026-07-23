<script>
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';

export default {
    name: 'App',
    components: {
        Header,
        Footer
    },
    computed: {
        isEmptyLayout() {
            return this.$route.meta.layout === 'empty';
        }
    }
};
</script>

<template>
    <div id="app">

        <!-- 👇 Solo si NO es login -->
        <Header v-if="!isEmptyLayout" />

        <main
            class="container-fluid p-0"
            :class="{ 'with-header': !isEmptyLayout }"
        >
            <router-view />
        </main>

        <!-- 👇 Solo si NO es login -->
        <Footer v-if="!isEmptyLayout" />

    </div>
</template>
<style>
.with-header {
    margin-top: 70px; /* 👈 altura de tu header */
}
</style>
