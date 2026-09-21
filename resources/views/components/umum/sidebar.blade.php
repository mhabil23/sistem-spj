<aside class="sidebar">

    <!-- LOGO -->
    <div class="sidebar-header">
        <div class="logo-icon">
            SPJ
        </div>

        <div class="logo-text">
            <strong>Sistem SPJ</strong>
            <span>BPS Kolaka Utara</span>
        </div>
    </div>

    <!-- MENU -->
    <nav class="sidebar-nav">

        <div class="menu-label">
            MENU UTAMA
        </div>

        <a href="{{ route('umum.dashboard') }}"
            class="nav-item {{ request()->routeIs('umum.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">▦</span>
            <span>Dashboard</span>
        </a>

        <a href="#"
            class="nav-item">
            <span class="nav-icon">▣</span>
            <span>SPJ Masuk</span>
            <span class="menu-badge">5</span>
        </a>

        <a href="#"
            class="nav-item">
            <span class="nav-icon">✓</span>
            <span>Pemeriksaan</span>
        </a>

        <a href="#"
            class="nav-item">
            <span class="nav-icon">↩</span>
            <span>Dikembalikan</span>
            <span class="menu-badge warning">2</span>
        </a>

        <a href="#"
            class="nav-item">
            <span class="nav-icon">◷</span>
            <span>Riwayat</span>
        </a>

        <a href="#"
            class="nav-item">
            <span class="nav-icon">▤</span>
            <span>Arsip SPJ</span>
        </a>

        <div class="menu-label">
            PENGATURAN
        </div>

        <a href="#"
            class="nav-item">
            <span class="nav-icon">⚙</span>
            <span>Profil</span>
        </a>

    </nav>

    <!-- USER -->
    <div class="sidebar-user">

        <div class="user-avatar">
            U
        </div>

        <div class="user-info">
            <strong>Pengguna Umum</strong>
            <span>Umum / PPSPM</span>
        </div>

        <button class="logout-btn" type="button">
            ↪
        </button>

    </div>

</aside>