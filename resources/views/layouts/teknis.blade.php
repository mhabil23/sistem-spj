<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel Teknis' }} | Sistem Informasi Penyelesaian Tagihan</title>
    
    <!-- Theme Flash Prevention Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme-mode');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark-theme');
            } else {
                document.documentElement.classList.remove('dark-theme');
            }
        })();
    </script>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/teknis/layout.css', 'resources/js/app.js'])
    @stack('styles')
    
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body class="dashboard-body">
    <div class="dashboard-app-wrapper">
        <x-teknis.sidebar />
        
        <div class="main-content">
            <x-teknis.navbar :title="$title ?? ''" />

            <x-center-toast />

            <div class="page-content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.toggle('active');
            }
        }

        function toggleThemeMode() {
            const isDark = document.documentElement.classList.toggle('dark-theme');
            localStorage.setItem('theme-mode', isDark ? 'dark' : 'light');
            syncThemeToggleState();
        }

        function syncThemeToggleState() {
            const isDark = document.documentElement.classList.contains('dark-theme');
            const toggles = document.querySelectorAll('.theme-toggle-pill');
            toggles.forEach(toggle => {
                if (isDark) {
                    toggle.classList.add('active');
                } else {
                    toggle.classList.remove('active');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', syncThemeToggleState);
    </script>

    @stack('scripts')
</body>
</html>