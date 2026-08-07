import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'], // 👈 Punto de entrada
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '~': path.resolve(__dirname, 'resources'),
        },
    },
    server: {
        hmr: {
            host: 'localhost',
        },
        cors: true,
        headers: {
            'Cache-Control': 'no-store',
        },
    },
    optimizeDeps: {
        include: ['vue', 'vue-router', 'pinia', 'axios'],
    },
});
