<header class="topbar">

    <div style="display: flex; align-items: center;">
        <button
            type="button"
            class="mobile-menu"
            onclick="toggleSidebar()"
            style="margin-right: 1rem;">
            ☰
        </button>
        <img src="{{ asset('images/logo-bps.png') }}" alt="Logo BPS" style="height: 40px; margin-right: 1.5rem;" class="navbar-logo">
    </div>


    <div class="page-title"></div>


    <div class="topbar-right">

        <button
            type="button"
            class="notification">
            🔔
            <span></span>
        </button>


        <a href="{{ route('teknis.profil.index') }}" class="top-user" style="text-decoration: none; color: inherit;">

            <div class="top-avatar">
                {{ substr(Auth::user()->name ?? 'T', 0, 1) }}
            </div>

            <div class="top-user-info">

                <strong>
                    {{ Auth::user()->name ?? 'Pengguna Teknis' }}
                </strong>

                <small>
                    Teknis
                </small>

            </div>

        </a>

    </div>

</header>