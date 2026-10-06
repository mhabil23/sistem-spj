<header class="topbar">
    <button type="button" class="mobile-menu" onclick="toggleSidebar()">
        ☰
    </button>
    <div class="page-title">
        <h1>{{ $title ?? 'Dashboard' }}</h1>
        <p>{{ $subtitle ?? 'Selamat datang di Panel Umum/PPSPM.' }}</p>
    </div>
    <div class="topbar-right">
        <div class="top-user">
            <div class="top-avatar">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="top-user-info">
                <strong>{{ Auth::user()->name ?? 'Pengguna Umum' }}</strong>
                <small>Umum/PPSPM</small>
            </div>
        </div>
    </div>
</header>