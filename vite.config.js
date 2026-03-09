import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        target: 'esnext',
        minify: 'esbuild',
        rollupOptions: {
            output: {
                manualChunks: {
                    'vendor-axios': ['axios'],
                    'vendor-bootstrap': [
                        'bootstrap/js/dist/util',
                        'bootstrap/js/dist/alert',
                        'bootstrap/js/dist/button',
                        'bootstrap/js/dist/carousel',
                        'bootstrap/js/dist/collapse',
                        'bootstrap/js/dist/dropdown',
                        'bootstrap/js/dist/modal',
                        'bootstrap/js/dist/popover',
                        'bootstrap/js/dist/tooltip',
                    ],
                },
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
            },
        },
        cssCodeSplit: true,
        sourcemap: false,
        reportCompressedSize: true,
    },
});
