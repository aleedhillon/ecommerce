import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

// PrimeVue related imports
import Components from 'unplugin-vue-components/vite';
import { PrimeVueResolver } from '@primevue/auto-import-resolver';

export default defineConfig({
    server: { // Open to load fonts from node_modules directory
        fs: {
            allow: ['node_modules', 'resources', 'vendor'],
        },
    },
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }), // primevue related starts
        Components({
            resolvers: [
                PrimeVueResolver()
            ]
        }),
    ],
});
