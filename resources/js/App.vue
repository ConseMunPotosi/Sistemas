<script>
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';

export default {
    name: 'App',
    components: {
        Header,
        Footer
    }
};
</script>

<template>
    <div id="app">
        <Header />
        <main class="container-fluid p-0">
            <router-view />
        </main>
        <Footer />
    </div>
</template>

<style>
@import 'bootstrap/dist/css/bootstrap.min.css';

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding-top: 70px;
}

main {
    min-height: calc(100vh - 200px);
}
</style>
