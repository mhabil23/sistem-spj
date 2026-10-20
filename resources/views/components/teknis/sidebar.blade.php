<aside class="sidebar">
    <!-- User Profile Header -->
    <div class="sidebar-profile">
        <div class="profile-avatar-ring">
            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name ?? 'Profile Avatar' }}" class="profile-avatar-img">
        </div>
        <div class="profile-name">{{ Auth::user()->name ?? 'Pengguna Teknis' }}</div>
        <div class="profile-email">Tim Teknis Kegiatan</div>
    </div>

    <!-- Navigation Menu (SPJ Modul Teknis) -->
    <nav class="sidebar-nav">
        <div class="sidebar-section-label">MENU UTAMA</div>

        <!-- Dashboard -->
        <a href="{{ route('teknis.dashboard') }}" class="nav-item {{ request()->routeIs('teknis.dashboard') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="grid" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Dashboard</span>
        </a>

        <!-- Pengajuan SPJ -->
        <a href="{{ route('teknis.spj.index') }}" class="nav-item {{ request()->routeIs('teknis.spj.*') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="document-text-outline" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Pengajuan SPJ</span>
        </a>

        <!-- Kelengkapan Dokumen -->
        <a href="{{ route('teknis.dokumen.index') }}" class="nav-item {{ request()->routeIs('teknis.dokumen.*') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="folder-open-outline" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Kelengkapan Dokumen</span>
        </a>

        <div class="sidebar-section-label" style="margin-top: 14px;">ARSIP & LAPORAN</div>

        <!-- Riwayat SPJ -->
        <a href="{{ route('teknis.riwayat.index') }}" class="nav-item {{ request()->routeIs('teknis.riwayat.*') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="time-outline" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Riwayat SPJ</span>
        </a>

        <!-- Laporan SPJ -->
        <a href="{{ route('teknis.laporan.spj') }}" class="nav-item {{ request()->routeIs('teknis.laporan.spj') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="bar-chart-outline" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Laporan SPJ</span>
        </a>

        <div class="sidebar-section-label" style="margin-top: 14px;">PENGATURAN</div>

        <!-- Profil Akun -->
        <a href="{{ route('teknis.profil.index') }}" class="nav-item {{ request()->routeIs('teknis.profil.*') ? 'active' : '' }}">
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