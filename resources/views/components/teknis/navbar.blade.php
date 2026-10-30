@props(['title' => ''])

@php
    $hour = (int) now()->timezone('Asia/Makassar')->format('H');
    if ($hour >= 5 && $hour < 11) {
        $greeting = 'Selamat Pagi';
        $greetIcon = 'sunny-outline';
        $greetColor = '#eab308';
    } elseif ($hour >= 11 && $hour < 15) {
        $greeting = 'Selamat Siang';
        $greetIcon = 'sunny';
        $greetColor = '#f59e0b';
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = 'Selamat Sore';
        $greetIcon = 'partly-sunny-outline';
        $greetColor = '#f97316';
    } else {
        $greeting = 'Selamat Malam';
        $greetIcon = 'moon-outline';
        $greetColor = '#6366f1';
    }

    $userName = Auth::user()->name ?? 'Staf Teknis';
    $firstName = explode(' ', trim($userName))[0];
    $userInitial = strtoupper(substr($firstName, 0, 1));
    $userPhoto = Auth::user()->foto ?? null;
@endphp

<header class="topbar-container">
    <div class="topbar-left">
        <button type="button" class="mobile-toggle-btn" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
            <ion-icon name="menu-outline"></ion-icon>
        </button>

        <div class="topbar-greeting-box">
            <div class="topbar-salutation">
                <ion-icon name="{{ $greetIcon }}" style="color: {{ $greetColor }};"></ion-icon>
                <span>{{ $greeting }}, <strong class="topbar-user-name">{{ $firstName }}</strong></span>
                <span class="topbar-role-badge">
                    <ion-icon name="person-circle-outline"></ion-icon>
                    Staf Teknis
                </span>
            </div>
            <div class="topbar-sub-meta">
                <span class="topbar-live-date">
                    <ion-icon name="calendar-outline"></ion-icon>
                    {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                </span>
                <span class="topbar-meta-divider">•</span>
                <span class="topbar-live-system">
                    <span class="topbar-live-dot"></span>
                    Sistem SPJ Aktif
                </span>
            </div>
        </div>
    </div>

    <div class="topbar-right">
        <!-- Quick SOP Flowchart Chip -->
        <a href="{{ route('flowchart.preview') }}" target="_blank" class="topbar-chip-btn" title="Buka Flowchart & Alur SOP SPJ Digital (PDF)">
            <ion-icon name="git-network-outline"></ion-icon>
            <span>Alur SOP</span>
        </a>

        <!-- Search Pill Form -->
        <form action="{{ route('teknis.spj.index') }}" method="GET" class="topbar-search-form">
            <div class="search-pill-wrapper">
                <input 
                    type="text" 
                    name="search" 
                    id="globalSearchInput"
                    class="search-pill-input" 
                    placeholder="Cari kegiatan SPJ..."
                    value="{{ request('search') }}"
                    autocomplete="off"
                >
                <kbd class="search-kbd-hint" title="Tekan Ctrl+K untuk mencari">Ctrl K</kbd>
                <button type="submit" class="search-pill-btn" aria-label="Cari SPJ">
                    <ion-icon name="search-outline"></ion-icon>
                </button>
            </div>
        </form>

        <!-- Dark Mode Toggle Switch -->
        <div class="theme-toggle-pill" id="themeToggleBtn" title="Ubah Mode Gelap / Terang" onclick="toggleThemeMode()" role="button" tabindex="0" aria-label="Toggle Theme">
            <span class="toggle-thumb"></span>
            <ion-icon name="moon" class="toggle-moon-icon"></ion-icon>
        </div>

        <!-- User Mini Profile Chip -->
        <a href="{{ route('teknis.profil.index') }}" class="topbar-user-chip" title="Kelola Profil & Akun">
            <div class="topbar-user-avatar">
                @if($userPhoto)
                    <img src="{{ asset('storage/' . $userPhoto) }}" alt="{{ $userName }}">
                @else
                    {{ $userInitial }}
                @endif
            </div>
            <div>
                <div class="topbar-user-chip-name">{{ $firstName }}</div>
                <div class="topbar-user-chip-role">Pengaju Teknis</div>
            </div>
        </a>
    </div>
</header>

<script>
    if (!window.ctrlKListenerAttached) {
        window.ctrlKListenerAttached = true;
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
                e.preventDefault();
                const input = document.getElementById('globalSearchInput');
                if (input) {
                    input.focus();
                    input.select();
                }
            }
        });
    }
</script>