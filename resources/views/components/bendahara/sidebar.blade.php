<aside class="sidebar">
    <!-- User Profile Header -->
    <div class="sidebar-profile">
        <div class="profile-avatar-ring">
            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name ?? 'Profile Avatar' }}" class="profile-avatar-img">
        </div>
        <div class="profile-name">{{ Auth::user()->name ?? 'Pengguna Bendahara' }}</div>
        <div class="profile-email">Bendahara Pengeluaran</div>
    </div>

    <!-- Navigation Menu (SPJ Modul Bendahara) -->
    <nav class="sidebar-nav">
        <div class="sidebar-section-label">MENU UTAMA</div>

        <!-- Dashboard -->
        <a href="{{ route('bendahara.dashboard') }}" class="nav-item {{ request()->routeIs('bendahara.dashboard') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="grid" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Dashboard</span>
        </a>

        <!-- Kotak Masuk SPJ -->
        <a href="{{ route('bendahara.spj.index') }}" class="nav-item {{ request()->routeIs('bendahara.spj.*') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="file-tray-full-outline" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Kotak Masuk SPJ</span>
        </a>

        <!-- Verifikasi Berkas -->
        <a href="{{ route('bendahara.berkas.index') }}" class="nav-item {{ request()->routeIs('bendahara.berkas.*') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="checkbox-outline" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Verifikasi Berkas</span>
        </a>

        <div class="sidebar-section-label" style="margin-top: 14px;">ARSIP & LAPORAN</div>

        <!-- Riwayat Pemeriksaan -->
        <a href="{{ route('bendahara.riwayat.index') }}" class="nav-item {{ request()->routeIs('bendahara.riwayat.*') ? 'active' : '' }}">
            <span class="nav-icon-wrap">
                <ion-icon name="time-outline" class="nav-icon"></ion-icon>
            </span>
            <span class="nav-label">Riwayat Pemeriksaan</span>
        </a>

        <div class="sidebar-section-label" style="margin-top: 14px;">PENGATURAN</div>

        <!-- Profil Akun -->
        <a href="{{ route('bendahara.profil.index') }}" class="nav-item {{ request()->routeIs('bendahara.profil.*') ? 'active' : '' }}">
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
