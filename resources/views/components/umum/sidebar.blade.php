<aside class="sidebar">
    <!-- User Profile Header -->
    <div class="sidebar-profile">
        <div class="profile-avatar-ring">
            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name ?? 'Profile Avatar' }}" class="profile-avatar-img">
        </div>
        <div class="profile-name">{{ Auth::user()->name ?? 'Pengguna Umum' }}</div>
        <div class="profile-email">{{ Auth::user()->email ?? 'umum@bps.go.id' }}</div>
    </div>

    <!-- Navigation Menu (SPJ Modul Umum) -->
    <nav class="sidebar-nav">
        <div class="sidebar-section-label">MENU UTAMA</div>

        <!-- Dashboard -->
        <a href="{{ route('umum.dashboard') }}" class="nav-item {{ request()->routeIs('umum.dashboard') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="grid" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Dashboard</span>
        </a>

        <!-- Kotak Masuk SPJ -->
        <a href="{{ route('umum.spj.index') }}" class="nav-item {{ request()->routeIs('umum.spj.*') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="file-tray-full-outline" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Kotak Masuk SPJ</span>
        </a>

        <div class="sidebar-section-label" style="margin-top: 14px;">ARSIP & LAPORAN</div>

        <!-- Laporan SPJ -->
        <a href="{{ route('umum.laporan.index') }}" class="nav-item {{ request()->routeIs('umum.laporan.*') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="document-text-outline" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Laporan SPJ</span>
        </a>

        <!-- Riwayat Pemeriksaan -->
        <a href="{{ route('umum.riwayat.index') }}" class="nav-item {{ request()->routeIs('umum.riwayat.*') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="time-outline" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Riwayat Pemeriksaan</span>
        </a>

        <!-- Profil Akun -->
        <a href="{{ route('umum.profil.index') }}" class="nav-item {{ request()->routeIs('umum.profil.*') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="person-circle-outline" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Profil Akun</span>
        </a>
    </nav>
    
    <!-- Logout Footer -->
    <div class="sidebar-bottom">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-item nav-logout-btn">
                <span class="nav-icon-wrap">
                    <ion-icon name="power-outline" class="nav-icon"></ion-icon>
                </span>
                <span class="nav-label">Keluar</span>
            </button>
        </form>
    </div>
</aside>