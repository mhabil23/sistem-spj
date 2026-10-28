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
            <span class="nav-icon"><i class="bi bi-grid-1x2-fill"></i></span>
            <span>Dashboard</span>
        </a>

        {{-- DATA SPJ --}}
        <a
            href="{{ route('admin.spj.index') }}"
            class="nav-item {{ request()->routeIs('admin.spj.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-folder2-open"></i></span>
            <span>Data SPJ</span>
        </a>

        {{-- PENGGUNA --}}
        <a
            href="{{ route('admin.pengguna.index') }}"
            class="nav-item {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-people-fill"></i></span>
            <span>Pengguna</span>
        </a>

        {{-- RIWAYAT --}}
        <a
            href="{{ route('admin.riwayat') }}"
            class="nav-item {{ request()->routeIs('admin.riwayat') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-clock-history"></i></span>
            <span>Riwayat Proses</span>
        </a>

        {{-- LAPORAN SPJ --}}
        <a
            href="{{ route('admin.laporan.spj') }}"
            class="nav-item {{ request()->routeIs('admin.laporan.spj') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-file-earmark-bar-graph-fill"></i></span>
            <span>Laporan SPJ</span>
        </a>

        {{-- TEMPLATE BERKAS SPJ --}}
        <a
            href="{{ route('admin.template-berkas.index') }}"
            class="nav-item {{ request()->routeIs('admin.template-berkas.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-file-earmark-arrow-down-fill"></i></span>
            <span>Template Berkas SPJ</span>
        </a>
        <div class="nav-label" style="margin-top: 1.5rem;">
            PENGATURAN
        </div>

        {{-- PROFIL AKUN --}}
        <a
            href="{{ route('admin.profil.index') }}"
            class="nav-item {{ request()->routeIs('admin.profil.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-person-circle"></i></span>
            <span>Profil Akun</span>
        </a>

    </nav>

    <!-- BOTTOM -->
    <div class="sidebar-bottom">
        <div class="admin-mini">
            <div class="admin-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 0;">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name ?? 'Administrator' }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <div class="admin-info">
                <strong>{{ Auth::user()->name ?? 'Administrator' }}</strong>
                <span>{{ ucfirst(Auth::user()->role ?? 'Admin') }}</span>
            </div>
        </div>

        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();" class="logout-btn">
            <span style="display: flex; align-items: center;">
                <i class="bi bi-box-arrow-right" style="font-size: 16px;"></i>
            </span>
            <span>Keluar</span>
        </a>
        <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

</aside>