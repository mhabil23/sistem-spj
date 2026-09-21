<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        {{ $title ?? 'Dashboard' }} | Sistem SPJ
    </title>
    @vite([
    'resources/css/admin/dashboard.css',
    'resources/css/admin/pengguna.css',
    'resources/css/admin/spj.css',
    'resources/css/admin/riwayat.css',
    'resources/css/admin/spj-create.css',
    'resources/js/app.js'
    ])

</head>

<body>

    <div class="admin-layout">

        @include('components.admin.sidebar')

        <main class="main-content">

            @include('components.admin.navbar')

            <div class="content">

                @yield('content')

            </div>

        </main>

    </div>

    <script>
        function toggleSidebar() {

            const sidebar =
                document.querySelector('.sidebar');

            sidebar.classList.toggle('show');

        }
    </script>

</body>

</html>