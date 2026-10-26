import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/home.css',
                'resources/css/login.css',
                // Admin CSS
                'resources/css/admin/dashboard.css',
                'resources/css/admin/pengguna.css',
                'resources/css/admin/spj.css',
                'resources/css/admin/riwayat.css',
                'resources/css/admin/laporan-spj.css',
                'resources/css/admin/spj-create.css',
                'resources/css/admin/profil.css',
                
                // Teknis CSS
                'resources/css/teknis/layout.css',
                'resources/css/teknis/dashboard.css',
                'resources/css/teknis/spj.css',
                'resources/css/teknis/riwayat.css',
                'resources/css/teknis/profil.css',
                
                // Umum CSS
                'resources/css/umum/layout.css',
                'resources/css/umum/dashboard.css',
                'resources/css/umum/spj.css',
                'resources/css/umum/riwayat.css',
                'resources/css/umum/profil.css',
                'resources/css/umum/laporan.css',
                
                // PPK CSS
                'resources/css/ppk/layout.css',
                'resources/css/ppk/dashboard.css',
                'resources/css/ppk/spj.css',
                'resources/css/ppk/riwayat.css',
                // Bendahara CSS
                'resources/css/bendahara/layout.css',
                'resources/css/bendahara/dashboard.css',
                'resources/css/bendahara/spj.css',
                'resources/css/bendahara/riwayat.css',
                'resources/css/bendahara/profil.css',

                'resources/js/app.js',
            ],

            refresh: true,

            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),

        tailwindcss(),
    ],

    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
