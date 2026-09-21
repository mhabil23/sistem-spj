<aside class="sidebar">

    <!-- BRAND -->
    <div class="sidebar-brand">

        <div class="sidebar-logo">
            SPJ
        </div>

        <div class="brand-text">
            <strong>Sistem SPJ</strong>
            <span>Teknis</span>
        </div>

    </div>


    <!-- MENU -->
    <nav class="sidebar-nav">

        <div class="nav-label">
            MENU UTAMA
        </div>


        <a
            href="{{ route('teknis.dashboard') }}"
            class="nav-item {{ request()->routeIs('teknis.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">⌂</span>
            <span>Dashboard</span>
        </a>


        <a
            href="#"
            class="nav-item">
            <span class="nav-icon">+</span>
            <span>Ajukan SPJ</span>
        </a>


        <a
            href="#"
            class="nav-item">
            <span class="nav-icon">▣</span>
            <span>SPJ Saya</span>
        </a>


        <a
            href="#"
            class="nav-item">
            <span class="nav-icon">◷</span>
            <span>Riwayat</span>
        </a>


        <div class="nav-label menu-space">
            DOKUMEN
        </div>


        <a
            href="#"
            class="nav-item">
            <span class="nav-icon">▤</span>
            <span>Dokumen SPJ</span>
        </a>


        <div class="nav-label menu-space">
            AKUN
        </div>


        <a
            href="#"
            class="nav-item">
            <span class="nav-icon">⚙</span>
            <span>Profil</span>
        </a>

    </nav>


    <!-- BOTTOM -->
    <div class="sidebar-bottom">

        <div class="admin-mini">

            <div class="admin-avatar">
                T
            </div>

            <div class="admin-info">

                <strong>
                    Pengguna Teknis
                </strong>

                <span>
                    Teknis
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