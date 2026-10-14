<aside class="sidebar">

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

    <!-- BOTTOM -->
    <div class="sidebar-bottom">
        <div class="admin-mini">
            <div class="admin-avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="admin-info">
                <strong>{{ Auth::user()->name ?? 'Administrator' }}</strong>
                <span>{{ ucfirst(Auth::user()->role ?? 'Admin') }}</span>
            </div>
        </div>

        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();" class="logout-btn">
            <span style="display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            </span>
            <span>Keluar</span>
        </a>
        <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

</aside>