import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import vue from '@vitejs/plugin-vue'; // Import the Vue plugin

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app-frontend.css',
                'resources/js/app-frontend.js',
                'resources/sass/app-backend.scss',
                'resources/js/app-backend.js',
                'resources/vue/src/App.vue', // Add your Vue app entry point
            ],
            refresh: [
                'app/View/Components/**',
                'lang/**',
                'resources/lang/**',
                'resources/views/**',
                'resources/routes/**',
                'routes/**',
                'Modules/**/Resources/lang/**',
                'Modules/**/Resources/views/**/*.blade.php',
                'resources/vue/**', // Add vue to refresh paths
            ],
        }),
        vue({ // Add the Vue plugin to the plugins array
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
            '~coreui': path.resolve(__dirname, 'node_modules/@coreui/coreui'),
            '@': path.resolve(__dirname, 'resources/vue/src/'), // Add an alias for your Vue app directory (optional)
        }
    },
});
