import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'node:path';

export default defineConfig({
    //   server: {
    //     host: '0.0.0.0', // ← permite acceso externo desde ngrok
    //     port: 5173,
    //     cors: {
    //       origin: ['https://*.ngrok-free.app', 'http://localhost:8000', 'http://127.0.0.1:8000'],
    //       methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    //       allowedHeaders: ['Content-Type', 'Authorization'],
    //       credentials: true,
    //     },
    //   },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            ssr: 'resources/js/ssr.tsx',
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    esbuild: {
        jsx: 'automatic',
    },
    resolve: {
        alias: {
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
        },
    },
});
