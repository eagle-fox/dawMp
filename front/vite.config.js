import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import imagemin from 'vite-plugin-imagemin'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        vue(),
        imagemin({
            gifsicle: {
                optimizationLevel: 7,
                interlaced: false,
            },
            optipng: {
                optimizationLevel: 7,
            },
            mozjpeg: {
                quality: 70,
            },
            svgo: {
                plugins: [{ removeViewBox: false },
                    { cleanupIDs: false }],
            },
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 2024,
    },
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url)),
        },
    },
    optimizeDeps: {
        include: ['bootstrap'],
    },
})