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


        <div class="top-user">

            <div class="top-avatar">
                T
            </div>

            <div class="top-user-info">

                <strong>
                    Pengguna Teknis
                </strong>

                <small>
                    Teknis
                </small>

            </div>

        </div>

    </div>

</header>