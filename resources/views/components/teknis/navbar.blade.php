<header class="topbar">

    <button
        type="button"
        class="mobile-menu"
        onclick="toggleSidebar()">
        ☰
    </button>


    <div class="page-title">

        <h1>
            {{ $title ?? 'Dashboard' }}
        </h1>

        <p>
            {{ $subtitle ?? 'Selamat datang di Sistem SPJ.' }}
        </p>

    </div>


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