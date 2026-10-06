<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel Umum/PPSPM' }} | Sistem SPJ</title>
    @vite(['resources/css/app.css', 'resources/css/umum/layout.css'])
    @stack('styles')
    
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <x-umum.sidebar />
    <div class="main-content">
        <x-umum.navbar />

        @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; margin-bottom: 1rem; border-radius: 0.375rem; border: 1px solid #34d399;">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; margin-bottom: 1rem; border-radius: 0.375rem; border: 1px solid #f87171;">
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </div>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>