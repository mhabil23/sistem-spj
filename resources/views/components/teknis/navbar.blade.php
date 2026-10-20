@props(['title' => ''])

<header class="topbar-container">
    <div class="topbar-left">
        <button type="button" class="mobile-toggle-btn" onclick="toggleSidebar()">
            <ion-icon name="menu-outline"></ion-icon>
        </button>
        <h1 class="welcome-heading">
            Welcome {{ Auth::user()->name ? explode(' ', Auth::user()->name)[0] : 'Teknis' }} !
        </h1>
    </div>

    <div class="topbar-right">
        <!-- Search Pill -->
        <div class="search-pill-wrapper">
            <input type="text" class="search-pill-input" placeholder="Search">
            <button type="button" class="search-pill-btn" aria-label="Cari">
                <ion-icon name="search-outline"></ion-icon>
            </button>
        </div>

        <!-- Dark Mode Toggle Switch -->
        <div class="theme-toggle-pill" id="themeToggleBtn" title="Ubah Mode Gelap / Terang" onclick="toggleThemeMode()" role="button" tabindex="0">
            <span class="toggle-thumb"></span>
            <ion-icon name="moon" class="toggle-moon-icon"></ion-icon>
        </div>

        <!-- Notification Bell -->
        <div class="notif-bell-btn" title="Notifikasi">
            <ion-icon name="notifications-outline"></ion-icon>
            <span class="notif-dot"></span>
        </div>
    </div>
</header>