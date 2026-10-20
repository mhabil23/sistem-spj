<header class="topbar">

    <!-- LOGO & MOBILE MENU -->
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


    <!-- TITLE -->
    <div class="page-title"></div>


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

            <div class="top-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 0;">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name ?? 'Administrator' }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
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