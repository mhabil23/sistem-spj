<header class="topbar">

    <button type="button"
        class="mobile-menu"
        onclick="toggleSidebar()">
        ☰
    </button>

    <div class="page-title">
        <h1>{{ $title ?? 'Dashboard' }}</h1>
        <p>{{ $subtitle ?? 'Kelola pemeriksaan SPJ yang masuk.' }}</p>
    </div>

    <div class="topbar-right">

        <button type="button" class="notification">
            🔔
            <span></span>
        </button>

        <div class="top-user">

            <div class="top-avatar">
                U
            </div>

            <div class="top-user-info">
                <strong>Pengguna Umum</strong>
                <small>Umum / PPSPM</small>
            </div>

        </div>

    </div>

</header>