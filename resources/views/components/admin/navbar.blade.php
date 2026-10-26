<header class="topbar">

    <!-- LOGO & MOBILE MENU -->
    <div style="display: flex; align-items: center;">
        <button
            type="button"
            class="mobile-menu"
            onclick="toggleSidebar()"
            style="margin-right: 1rem; border: none; background: transparent; font-size: 20px; cursor: pointer; color: #334155;">
            <i class="bi bi-list"></i>
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
            class="notification"
            title="Notifikasi">
            <i class="bi bi-bell" style="font-size: 18px; color: #64748b;"></i>
            <span></span>
        </button>

        <!-- USER -->
        <a href="{{ route('admin.profil.index') }}" class="top-user" style="text-decoration: none; color: inherit; transition: opacity 0.2s;" title="Buka Profil Akun">
            <div class="top-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 0;">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name ?? 'Administrator' }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <div class="top-user-info">
                <strong>
                    {{ Auth::user()->name ?? 'Administrator' }}
                </strong>
                <small>
                    {{ ucfirst(Auth::user()->role ?? 'Admin') }}
                </small>
            </div>
        </a>

    </div>

</header>