import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        cors: true,// Allows external access (via phone/other devices)
        hmr: {
            host: 'https://6264-180-245-74-56.ngrok-free.app', // Use your IP here
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
