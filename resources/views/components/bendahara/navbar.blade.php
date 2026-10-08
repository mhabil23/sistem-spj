<header class="topbar">
    <div style="display: flex; align-items: center;">
        <button type="button" class="mobile-menu" onclick="toggleSidebar()" style="margin-right: 1rem;">
            ☰
        </button>
        <img src="{{ asset('images/logo-bps.png') }}" alt="Logo BPS" style="height: 40px; margin-right: 1.5rem;" class="navbar-logo">
    </div>
    <div class="page-title"></div>
    <div class="topbar-right">
        <div class="top-user">
            <div class="top-avatar">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="top-user-info">
                <strong>{{ Auth::user()->name ?? 'Pengguna Umum' }}</strong>
                <small>Bendahara</small>
            </div>
        </div>
    </div>
</header>

