<aside class="sidebar">

    <!-- BRAND -->
    <div class="sidebar-brand">

        <div class="sidebar-logo">
            SPJ
        </div>

        <div class="brand-text">
            <strong>Sistem SPJ</strong>
            <span>Administrator</span>
        </div>

    </div>


    <!-- MENU -->
    <nav class="sidebar-nav">

        <div class="nav-label">
            MENU UTAMA
        </div>


        <a
            href="{{ route('admin.dashboard') }}"
            class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">⌂</span>
            <span>Dashboard</span>
        </a>


        <a
            href="{{ route('admin.spj') }}"
            class="nav-item {{ request()->routeIs('admin.spj') ? 'active' : '' }}">
            <span class="nav-icon">▣</span>
            <span>Data SPJ</span>
        </a>


        <a
            href="{{ route('admin.pengguna.index') }}"
            class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="nav-icon">♙</span>
            <span>Pengguna</span>
        </a>

        <a
            href="#"
            class="nav-item {{ request()->routeIs('admin.history.*') ? 'active' : '' }}">
            <span class="nav-icon">◷</span>
            <span>Riwayat Proses</span>
        </a>


        <!-- LAPORAN -->

        <div class="nav-label menu-space">
            LAPORAN
        </div>


        <a
            href="#"
            class="nav-item">
            <span class="nav-icon">▤</span>
            <span>Laporan SPJ</span>
        </a>


        <a
            href="#"
            class="nav-item">
            <span class="nav-icon">⇩</span>
            <span>Arsip</span>
        </a>


        <!-- SISTEM -->

        <div class="nav-label menu-space">
            SISTEM
        </div>


        <a
            href="#"
            class="nav-item">
            <span class="nav-icon">⚙</span>
            <span>Pengaturan</span>
        </a>

    </nav>


    <!-- BOTTOM -->
    <div class="sidebar-bottom">

        <div class="admin-mini">

            <div class="admin-avatar">
                A
            </div>

            <div class="admin-info">

                <strong>
                    Administrator
                </strong>

                <span>
                    Admin
                </span>

            </div>

            <button
                type="button"
                class="more-btn">
                ⋮
            </button>

        </div>


        <a
            href="#"
            class="logout-btn">
            <span>↪</span>
            <span>Keluar</span>
        </a>

    </div>

</aside>