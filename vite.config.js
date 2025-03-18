import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/css/profile.scss',
                'resources/js/main.ts',
                'resources/js/page-slugs/index.ts',
            ],
            refresh: false,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name].[ext]',
            },
            external: [],
        },
        manifest: false,
        emptyOutDir: true,
        outDir: 'public/assets',
        target: 'esnext',
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: '0.0.0.0',
        }
    },
});