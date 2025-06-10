import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/css/ckeditor-tailwind-reset.css',
            ],
            refresh: true,
        }),
        vue(),
    ],
    build: {
        // Enable minification
        minify: 'terser',
        // Generate source maps for production
        sourcemap: false,
        // Configure chunk size warnings
        chunkSizeWarningLimit: 1000,
        // Configure rollup options
        rollupOptions: {
            output: {
                // Ensure proper chunking
                manualChunks: {
                    'vendor': ['vue'],
                },
                // Configure asset file names
                assetFileNames: (assetInfo) => {
                    let extType = assetInfo.name.split('.').at(1);
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(extType)) {
                        extType = 'img';
                    }
                    return `assets/${extType}/[name]-[hash][extname]`;
                },
                // Configure chunk file names
                chunkFileNames: 'assets/js/[name]-[hash].js',
                // Configure entry file names
                entryFileNames: 'assets/js/[name]-[hash].js',
            },
        },
    },
    // Configure server options
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
