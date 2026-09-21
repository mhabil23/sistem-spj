<header class="topbar">

    <!-- MOBILE MENU -->
    <button
        type="button"
        class="mobile-menu"
        onclick="toggleSidebar()">
        ☰
    </button>


    <!-- TITLE -->
    <div class="page-title">

        <h1>
            {{ $title ?? 'Dashboard' }}
        </h1>

        <p>
            {{ $subtitle ?? 'Selamat datang kembali, Administrator.' }}
        </p>

    </div>


    <!-- RIGHT -->
    <div class="topbar-right">

        <!-- NOTIFICATION -->
        <button
            type="button"
            class="notification">

            🔔

            <span></span>

        </button>


        <!-- USER -->
        <div class="top-user">

            <div class="top-avatar">
                A
            </div>

            <div class="top-user-info">

                <strong>
                    Administrator
                </strong>

                <small>
                    Admin
                </small>

            </div>

        </div>

    </div>

</header>