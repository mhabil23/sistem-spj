<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        {{ $title ?? 'Dashboard' }} | Sistem Informasi Penyelesaian Tagihan
    </title>

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite CSS & JS --}}
    @vite([
    'resources/css/admin/dashboard.css',
    'resources/css/admin/pengguna.css',
    'resources/css/admin/spj.css',
    'resources/css/admin/riwayat.css',
    'resources/css/admin/spj-create.css',
    'resources/css/admin/laporan-spj.css',
    'resources/js/app.js'
    ])

    @stack('styles')

</head>

<body>

    <div class="admin-layout">

        {{-- SIDEBAR --}}
        @include('components.admin.sidebar')


        {{-- MAIN CONTENT --}}
        <main class="main-content">

            {{-- NAVBAR --}}
            @include('components.admin.navbar')

            <x-center-toast />
            <x-session-timeout-monitor />


            {{-- CONTENT --}}
            <div class="content">

                @yield('content')

            </div>

        </main>

    </div>


    {{-- SIDEBAR MOBILE --}}
    <script>
        function toggleSidebar() {

            const sidebar =
                document.querySelector('.sidebar');

            if (sidebar) {
                sidebar.classList.toggle('show');
            }

        }
    </script>

    @stack('scripts')

</body>

</html>