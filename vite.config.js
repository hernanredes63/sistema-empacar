import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
    server: {
        host: '192.168.56.150', // Amarramos Vite a la IP de tu Ubuntu
        port: 5173,
        cors: true,
        watch: {
            usePolling: true, // Muy importante en VirtualBox
        },
        hmr: {
            host: '192.168.56.150'
        }
    }
});