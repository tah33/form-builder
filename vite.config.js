import { fileURLToPath, URL } from 'node:url';

import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import vueDevTools from 'vite-plugin-vue-devtools';
import laravel from 'laravel-vite-plugin';
import tsconfigPaths from 'vite-tsconfig-paths';


export default defineConfig({
    plugins: [
        vue(),
        vueDevTools(),
        tsconfigPaths(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/src/app.ts'],
            refresh: true
        })
    ],
    build: {
        target: 'es2022',
    },
    resolve: {
        alias: {
            '@': '/resources/js/src',
        }
    }
});
