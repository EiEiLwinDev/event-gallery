import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; 
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
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
    resolve: {
        alias: {
          'vue': path.resolve(__dirname, 'node_modules/vue/dist/vue.esm-bundler.js'),
        },
      },
      css: {
        preprocessorOptions: {
          css: {
            // Add any additional CSS options here
          },
        },
      },
    server: {
        proxy: {
          '/app': 'http://localhost',
        },
      },
});
