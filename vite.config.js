
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Homepage-only: tiny loader; Three.js is a lazy chunk it imports after load.
                'resources/js/hero3d.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});