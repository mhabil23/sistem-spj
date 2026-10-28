<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel Bendahara' }} | Sistem Informasi Penyelesaian Tagihan</title>
    @vite(['resources/css/app.css', 'resources/css/bendahara/layout.css'])
    @stack('styles')
    
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <x-bendahara.sidebar />
    <div class="main-content">
        <x-bendahara.navbar />

        <x-center-toast />
        <x-session-timeout-monitor />

        @yield('content')
    </div>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
    
    @stack('scripts')
</body>
</html>

