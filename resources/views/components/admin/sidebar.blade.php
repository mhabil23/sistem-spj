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


        {{-- DASHBOARD --}}
        <a
            href="{{ route('admin.dashboard') }}"
            class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

            <span class="nav-icon">⌂</span>

            <span>Dashboard</span>

        </a>


        {{-- DATA SPJ --}}
        <a
            href="{{ route('admin.spj.index') }}"
            class="nav-item {{ request()->routeIs('admin.spj.*') ? 'active' : '' }}">

            <span class="nav-icon">▣</span>

            <span>Data SPJ</span>

        </a>


        {{-- PENGGUNA --}}
        <a
            href="{{ route('admin.pengguna.index') }}"
            class="nav-item {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">

            <span class="nav-icon">♙</span>

            <span>Pengguna</span>

        </a>


        {{-- RIWAYAT --}}
        <a
            href="{{ route('admin.riwayat') }}"
            class="nav-item {{ request()->routeIs('admin.riwayat') ? 'active' : '' }}">

            <span class="nav-icon">↻</span>

            <span>Riwayat Proses</span>

        </a>

        {{-- LAPORAN SPJ --}}
        <a
            href="{{ route('admin.laporan.spj') }}"
            class="nav-item {{ request()->routeIs('admin.laporan.spj') ? 'active' : '' }}">

            <span class="nav-icon">▤</span>

            <span>Laporan SPJ</span>

        </a>

    </nav>

</aside>