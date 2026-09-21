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
            href="{{ route('teknis.spj.create') }}"
            class="nav-item {{ request()->routeIs('teknis.spj.create') ? 'active' : '' }}">
            <span class="nav-icon">+</span>
            <span>Ajukan SPJ</span>
        </a>


        <a
            href="{{ route('teknis.spj.index') }}"
            class="nav-item {{ request()->routeIs('teknis.spj.index') || request()->routeIs('teknis.spj.show') || request()->routeIs('teknis.spj.edit') ? 'active' : '' }}">
            <span class="nav-icon">▣</span>
            <span>SPJ Saya</span>
        </a>


        <a
            href="{{ route('teknis.riwayat.index') }}"
            class="nav-item {{ request()->routeIs('teknis.riwayat.index') ? 'active' : '' }}">
            <span class="nav-icon">◷</span>
            <span>Riwayat</span>
        </a>


        <div class="nav-label menu-space">
            DOKUMEN
        </div>


        <a
            href="{{ route('teknis.dokumen.index') }}"
            class="nav-item {{ request()->routeIs('teknis.dokumen.index') ? 'active' : '' }}">
            <span class="nav-icon">▤</span>
            <span>Dokumen SPJ</span>
        </a>


        <div class="nav-label menu-space">
            AKUN
        </div>


        <a
            href="{{ route('teknis.profil.index') }}"
            class="nav-item {{ request()->routeIs('teknis.profil.index') ? 'active' : '' }}">
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
                    {{ Auth::user()->name ?? 'Pengguna Teknis' }}
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
            href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="logout-btn">
            <span>↪</span>
            <span>Keluar</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    </div>

</aside>