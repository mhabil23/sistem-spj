<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistem Informasi Penyelesaian Tagihan | Badan Pusat Statistik</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    @vite(['resources/css/home.css'])
</head>

<body>

    <!-- 1. TOP ANNOUNCEMENT & UTILITY BAR (GAZU STYLE) -->
    <div class="top-announcement-bar">
        <div class="top-bar-inner">
            <div class="top-bar-left">
                <span class="top-bar-dot"></span>
                <span class="top-bar-text">SISTEM INFORMASI PENYELESAIAN TAGIHAN &bull; BPS KABUPATEN KOLAKA UTARA</span>
            </div>
            <div class="top-bar-right">
                <span class="top-bar-address">
                    <i class="bi bi-geo-alt-fill"></i>
                    Jl. Trans Sulawesi Kompleks Perkantoran Pemda Kolaka Utara, Lasusua
                </span>
            </div>
        </div>
    </div>

    <!-- 2. MAIN NAVBAR (GAZU 3-COLUMN CENTERED BRAND) -->
    <header class="navbar">
        <!-- LEFT: NAVIGATION LINKS (Uppercase, Minimalist) -->
        <div class="navbar-left">
            <button type="button" class="mobile-toggle-btn" onclick="toggleMobileNav(event)" aria-label="Menu Navigasi">
                <i class="bi bi-list"></i>
            </button>
            <nav class="nav-links">
                <a href="#beranda" class="nav-link active">BERANDA</a>
                <a href="#monitoring" class="nav-link">MONITORING</a>
                <a href="#alur" class="nav-link">ALUR SPJ</a>
                <a href="#panduan" class="nav-link">PANDUAN</a>
                <a href="#templates" class="nav-link" onclick="openTemplateModal(event)">FORMAT SPJ</a>
            </nav>
        </div>

        <!-- CENTER: BOLD CENTERED BRAND (GAZU STYLE) -->
        <div class="navbar-center">
            <a href="{{ route('home') }}" class="brand-centered" title="Sistem Informasi Penyelesaian Tagihan SPJ">
                <div class="brand-logo-centered">
                    @if(file_exists(public_path('images/logo-bps-app.png')))
                        <img src="{{ asset('images/logo-bps-app.png') }}" alt="Logo BPS">
                    @else
                        <span class="brand-logo-fallback">BPS</span>
                    @endif
                </div>
                <div class="brand-titles-centered">
                    <span class="brand-main-title">BADAN PUSAT STATISTIK</span>
                    <span class="brand-sub-title">KABUPATEN KOLAKA UTARA</span>
                </div>
            </a>
        </div>

        <!-- RIGHT: UTILITY ACTIONS & LOGIN -->
        <div class="navbar-right">
            <a href="#monitoring" class="nav-action-item" title="Cari atau Filter SPJ">
                <i class="bi bi-search"></i>
                <span class="action-label">CARI</span>
            </a>
            <a href="#templates" class="nav-action-item" onclick="openTemplateModal(event)" title="Unduh Format Berkas">
                <i class="bi bi-folder2-open"></i>
                <span class="action-label">BERKAS</span>
            </a>

            <div class="nav-actions">
                @auth
                    @php
                        $role = auth()->user()->role;
                        $dashboardUrl = url('/' . $role . '/dashboard');
                    @endphp
                    <a href="{{ $dashboardUrl }}" class="login-btn dashboard-badge" title="Menuju Dashboard">
                        <i class="bi bi-speedometer2"></i>
                        <span>DASHBOARD ({{ strtoupper($role) }})</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="login-btn" title="Masuk ke Sistem">
                        <i class="bi bi-person"></i>
                        <span>LOGIN</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- MOBILE NAVIGATION DRAWER -->
    <div class="mobile-nav-backdrop" id="mobileNavBackdrop" onclick="closeMobileNav()"></div>
    <div class="mobile-nav-drawer" id="mobileNavDrawer">
        <div class="mobile-drawer-header">
            <div class="brand-centered" style="gap: 10px;">
                <img src="{{ asset('images/logo-bps-app.png') }}" alt="Logo BPS" style="height: 30px; width: auto;">
                <div style="display: flex; flex-direction: column;">
                    <span style="font-weight: 800; font-size: 11.5px; letter-spacing: 0.8px; color: #0c2556; line-height: 1.2;">BADAN PUSAT STATISTIK</span>
                    <span style="font-weight: 700; font-size: 8px; letter-spacing: 1px; color: #0284c7;">KABUPATEN KOLAKA UTARA</span>
                </div>
            </div>
            <button type="button" class="btn-close-drawer" onclick="closeMobileNav()">&times;</button>
        </div>
        <div class="mobile-drawer-links">
            <a href="#beranda" onclick="closeMobileNav()">BERANDA</a>
            <a href="#monitoring" onclick="closeMobileNav()">MONITORING SPJ</a>
            <a href="#alur" onclick="closeMobileNav()">ALUR SPJ</a>
            <a href="#panduan" onclick="closeMobileNav()">PANDUAN</a>
            <a href="#templates" onclick="closeMobileNav(); openTemplateModal(event)">FORMAT BERKAS SPJ</a>
            <div class="mobile-drawer-divider"></div>
            <a href="{{ route('flowchart.preview') }}" target="_blank"><i class="bi bi-diagram-3"></i> FLOWCHART SOP</a>
            <a href="{{ route('panduan-role.preview') }}" target="_blank"><i class="bi bi-file-earmark-pdf"></i> PANDUAN ROLE</a>
        </div>
    </div>


    <!-- MAIN CANVAS -->
    <main class="page-canvas">

        <!-- Clean minimal background — single noise dot layer -->
        <div class="unified-bg-canvas" aria-hidden="true">
            <div class="bg-grid-mesh"></div>
        </div>

        <!-- HERO SECTION -->
        <section class="hero" id="beranda">

            <!-- Left Hero Content -->
            <div class="hero-content">

                <h1 class="hero-main-title">
                    Kelola SPJ Cerdas <br>
                    <span class="gradient-text">&amp; Transparan</span>
                </h1>

            </div>

            <!-- Right Hero Visual (Modern Clean UI Card for SPJ Tracking with Cartoon Mascot) -->
            <div class="hero-card-wrapper" id="heroCardWrapper">

                <!-- Interactive Card (Clean White, Soft Blue, Micro-Animations) -->
                <div class="spj-tracking-card cool-card-reveal is-revealed" id="heroInteractiveCard">

                    <!-- 1. Header: Live Status SPJ & Badge Diproses Bendahara -->
                    <div class="card-head-row">
                        <div class="live-status-indicator">
                            <span class="live-dot-beacon">
                                <span class="beacon-ping"></span>
                                <span class="beacon-core"></span>
                            </span>
                            <span class="live-text-tag">LIVE STATUS SPJ</span>
                        </div>

                        <div class="status-badge-bendahara">
                            <i class="bi bi-hourglass-split"></i>
                            <span>Diproses Bendahara</span>
                        </div>
                    </div>

                    <!-- 2. Progress Tracker: 5 Titik Langkah dengan Dashed Line & Karakter Kartun -->
                    <div class="tracker-box">
                        <div class="tracker-steps-line">

                            <!-- Langkah 1: Teknis (Selesai / Centang) -->
                            <div class="tracker-step-item">
                                <div class="step-check-circle" title="Tahap 1: Teknis (Selesai)">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <span class="step-title">Teknis</span>
                            </div>

                            <!-- Dashed Line 1 - 2 -->
                            <div class="tracker-dashed-connector connector-done"></div>

                            <!-- Langkah 2: Umum (Selesai / Centang) -->
                            <div class="tracker-step-item">
                                <div class="step-check-circle" title="Tahap 2: Subbagian Umum (Selesai)">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <span class="step-title">Umum</span>
                            </div>

                            <!-- Dashed Line 2 - 3 -->
                            <div class="tracker-dashed-connector connector-done"></div>

                            <!-- Langkah 3: PPK (Selesai / Centang) -->
                            <div class="tracker-step-item">
                                <div class="step-check-circle" title="Tahap 3: PPK (Selesai)">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <span class="step-title">PPK</span>
                            </div>

                            <!-- Dashed Line 3 - 4 -->
                            <div class="tracker-dashed-connector connector-done"></div>

                            <!-- Langkah 4: PPSPM (Selesai / Centang) -->
                            <div class="tracker-step-item">
                                <div class="step-check-circle" title="Tahap 4: PPSPM (Selesai)">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <span class="step-title">PPSPM</span>
                            </div>

                            <!-- Dashed Line 4 - 5 (Jalur Menuju Bendahara) -->
                            <div class="tracker-dashed-connector connector-active"></div>

                            <!-- Langkah 5: Bendahara dengan Maskot Kartun 2D/3D Minimalis -->
                            <div class="tracker-step-item step-mascot-target">
                                
                                <!-- Maskot Kartun Amplop Dokumen Berkaki Sedang Menunggu di Meja Bendahara -->
                                <div class="mascot-walker" title="Dokumen SPJ Anda sedang menunggu proses di Bendahara!">
                                    <div class="mascot-bubble">
                                        <span>Menunggu...</span>
                                    </div>

                                    <svg class="mascot-svg" width="56" height="64" viewBox="0 0 56 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <!-- Shadow under feet -->
                                        <ellipse class="mascot-shadow-anim" cx="28" cy="60" rx="14" ry="3" fill="#0284c7" opacity="0.22"/>
                                        
                                        <!-- Left Leg (Standing firm) -->
                                        <g class="leg-left">
                                            <rect x="20" y="43" width="4.5" height="12" rx="2" fill="#0284c7"/>
                                            <path d="M17 54 C17 52 23 52 25 54 L25 57 C25 58.5 17 58.5 17 57 Z" fill="#0f172a"/>
                                        </g>
                                        
                                        <!-- Right Leg (Foot Tapping Idle Animation) -->
                                        <g class="leg-right-tap">
                                            <rect x="31.5" y="43" width="4.5" height="12" rx="2" fill="#0284c7"/>
                                            <path class="foot-tapping" d="M28.5 54 C28.5 52 34.5 52 36.5 54 L36.5 57 C36.5 58.5 28.5 58.5 28.5 57 Z" fill="#0f172a"/>
                                        </g>

                                        <!-- Mascot Body / Document Envelope (Breathing Idle Animation) -->
                                        <g class="mascot-torso">
                                            <!-- Main Envelope / Document Shape -->
                                            <rect x="6.5" y="11" width="43" height="34" rx="8" fill="#ffffff" stroke="#0284c7" stroke-width="2.5"/>
                                            
                                            <!-- Soft inner gradient fill -->
                                            <rect x="9" y="13.5" width="38" height="29" rx="6" fill="url(#mascotGradient)"/>
                                            
                                            <!-- Envelope Flap / Crease Lines -->
                                            <path d="M7.5 13 L28 29 L48.5 13" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M7.5 43 L21 31" stroke="#e0f2fe" stroke-width="1.6" stroke-linecap="round"/>
                                            <path d="M48.5 43 L35 31" stroke="#e0f2fe" stroke-width="1.6" stroke-linecap="round"/>
                                            
                                            <!-- Stamp / Official BPS Ribbon in top right corner -->
                                            <rect x="35" y="15" width="9" height="11" rx="2" fill="#e0f2fe" stroke="#0284c7" stroke-width="1"/>
                                            <line x1="37" y1="18.5" x2="42" y2="18.5" stroke="#0284c7" stroke-width="1"/>
                                            <line x1="37" y1="21.5" x2="41" y2="21.5" stroke="#f97316" stroke-width="1"/>
                                            
                                            <!-- Cute Big Eyes (With Blinking Animation) -->
                                            <g class="mascot-eyes">
                                                <ellipse cx="20.5" cy="32.5" rx="2.4" ry="2.8" fill="#0f172a"/>
                                                <circle cx="19.7" cy="31.5" r="0.9" fill="#ffffff"/>
                                                <ellipse cx="29.5" cy="32.5" rx="2.4" ry="2.8" fill="#0f172a"/>
                                                <circle cx="28.7" cy="31.5" r="0.9" fill="#ffffff"/>
                                            </g>
                                            
                                            <!-- Rosy Blushing Cheeks -->
                                            <ellipse cx="15.5" cy="35.5" rx="2.2" ry="1.4" fill="#fb7185" opacity="0.45"/>
                                            <ellipse cx="34.5" cy="35.5" rx="2.2" ry="1.4" fill="#fb7185" opacity="0.45"/>
                                            
                                            <!-- Cheerful Smile -->
                                            <path d="M23 35.5 Q25 38.5 27 35.5" stroke="#0f172a" stroke-width="1.8" stroke-linecap="round" fill="none"/>
                                            
                                            <!-- Cute Little Arms Resting on Hips -->
                                            <path d="M5.5 28 C3.5 31 3.5 35 6.5 36" stroke="#0284c7" stroke-width="2.4" stroke-linecap="round" fill="none"/>
                                            <path d="M50.5 28 C52.5 31 52.5 35 49.5 36" stroke="#0284c7" stroke-width="2.4" stroke-linecap="round" fill="none"/>
                                        </g>
                                        
                                        <!-- Thinking / Waiting Sparkle Dots -->
                                        <g class="mascot-sparkles">
                                            <circle cx="22" cy="6" r="1.5" fill="#38bdf8"/>
                                            <circle cx="28" cy="3" r="2" fill="#f59e0b"/>
                                            <circle cx="34" cy="6" r="1.5" fill="#38bdf8"/>
                                        </g>
                                        
                                        <defs>
                                            <linearGradient id="mascotGradient" x1="28" y1="13" x2="28" y2="43" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#ffffff"/>
                                                <stop offset="1" stop-color="#f0f9ff"/>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>

                                <!-- Titik 5: Bendahara Node -->
                                <div class="step-target-circle" title="Tahap 5: Bendahara Pengeluaran (Sedang Berjalan)">
                                    <span class="target-pulse-ring"></span>
                                    <span class="step-num">5</span>
                                </div>
                                <span class="step-title title-active">Bendahara</span>
                            </div>

                        </div>
                    </div>

                    <!-- 3. Informasi Detail: Dua Kotak Informasi Sejajar (Kegiatan & Nilai SPJ) -->
                    <div class="card-detail-grid">
                        
                        <!-- Kotak Kiri: Kegiatan -->
                        <div class="info-card-box">
                            <div class="info-box-text">
                                <span class="info-label">Kegiatan</span>
                                <strong class="info-value" title="Survei Sosial Ekonomi (Susenas)">
                                    Survei Sosial Ekonomi (Susenas)
                                </strong>
                            </div>
                            <div class="info-box-icon icon-briefcase">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                        </div>

                        <!-- Kotak Kanan: Nilai SPJ -->
                        <div class="info-card-box">
                            <div class="info-box-text">
                                <span class="info-label">Nilai SPJ</span>
                                <strong class="info-value value-money">
                                    Rp 12.300.000
                                </strong>
                            </div>
                            <div class="info-box-icon icon-money">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>

                    </div>

                    <!-- 4. Footer (CTA): Tombol Aksi Penuh Berwarna Biru -->
                    <div class="card-footer-cta">
                        <a href="#monitoring" onclick="focusDetailSpj('Susenas', event)" class="btn-detail-cta">
                            <span>Lihat Detail Dokumen</span>
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>

                </div>

            </div>

        </section>

        <!-- =========================================================
             VELOCITY SCROLL MARQUEE — satu baris, clean, profesional
        ========================================================= -->
        <div class="velocity-scroll-strip" id="velocityScrollStrip" aria-hidden="true">
            <div class="v-scroll-row">
                <div class="v-scroll-track" id="vScrollTrack1">
                    <span class="v-scroll-item item-accent"><i class="bi bi-broadcast-pin"></i> Live Status SPJ</span>
                    <span class="v-scroll-item"><i class="bi bi-building"></i> BPS Kabupaten Kolaka Utara</span>
                    <span class="v-scroll-item"><i class="bi bi-shield-check"></i> Akuntabel &amp; Transparan</span>
                    <span class="v-scroll-item item-accent"><i class="bi bi-diagram-3"></i> 5 Tahap Alur SOP</span>
                    <span class="v-scroll-item"><i class="bi bi-file-earmark-check"></i> Verifikasi Subbagian Umum</span>
                    <span class="v-scroll-item"><i class="bi bi-patch-check"></i> Persetujuan PPK</span>
                    <span class="v-scroll-item"><i class="bi bi-award"></i> Penerbitan SPM PPSPM</span>
                    <span class="v-scroll-item item-accent"><i class="bi bi-cash-stack"></i> Pencairan Dana Bendahara</span>
                    <span class="v-scroll-item"><i class="bi bi-folder-check"></i> Arsip Digital Permanen</span>
                    <span class="v-scroll-item"><i class="bi bi-speedometer2"></i> Real-time Tracking</span>
                    <!-- duplicate for seamless loop -->
                    <span class="v-scroll-item item-accent"><i class="bi bi-broadcast-pin"></i> Live Status SPJ</span>
                    <span class="v-scroll-item"><i class="bi bi-building"></i> BPS Kabupaten Kolaka Utara</span>
                    <span class="v-scroll-item"><i class="bi bi-shield-check"></i> Akuntabel &amp; Transparan</span>
                    <span class="v-scroll-item item-accent"><i class="bi bi-diagram-3"></i> 5 Tahap Alur SOP</span>
                    <span class="v-scroll-item"><i class="bi bi-file-earmark-check"></i> Verifikasi Subbagian Umum</span>
                    <span class="v-scroll-item"><i class="bi bi-patch-check"></i> Persetujuan PPK</span>
                    <span class="v-scroll-item"><i class="bi bi-award"></i> Penerbitan SPM PPSPM</span>
                    <span class="v-scroll-item item-accent"><i class="bi bi-cash-stack"></i> Pencairan Dana Bendahara</span>
                    <span class="v-scroll-item"><i class="bi bi-folder-check"></i> Arsip Digital Permanen</span>
                    <span class="v-scroll-item"><i class="bi bi-speedometer2"></i> Real-time Tracking</span>
                </div>
            </div>
        </div>

        <!-- =========================================================
             DASHBOARD MONITORING & STATUS SPJ PUBLIK (TANPA LOGIN)
             Fitur READ & FILTER PENCARIAN (NAMA, TANGGAL, JUMLAH)
        ========================================================= -->
        <section class="monitoring-section" id="monitoring">

            <!-- Section Title -->
            <div class="monitoring-header cool-header-reveal">
                <span class="monitoring-badge">
                    <i class="bi bi-broadcast"></i>
                    TRANSPARANSI &amp; AKUNTABILITAS PUBLIK
                </span>
                <h2 class="monitoring-title">
                    Dashboard Monitoring &amp; Status SPJ
                </h2>
                <p class="monitoring-desc">
                    Pantau posisi berkas pengajuan SPJ, proses verifikasi bertahap, dan status pencairan dana secara terbuka dan real-time tanpa harus login.
                </p>
            </div>

            <!-- Summary Stats Counter Cards -->
            <div class="monitoring-stats-grid">
                <div class="m-stat-card cool-card-reveal" onclick="selectSpjStatus('semua')" title="Klik untuk menampilkan semua SPJ">
                    <div class="m-stat-icon m-stat-blue">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div class="m-stat-info">
                        <span class="m-stat-value" id="statValTotal">{{ $stats['total'] ?? count($spjs ?? []) }}</span>
                        <span class="m-stat-label">Total SPJ Terdata</span>
                    </div>
                </div>

                <div class="m-stat-card cool-card-reveal" onclick="selectSpjStatus('diajukan')" title="Klik untuk filter SPJ yang baru diajukan">
                    <div class="m-stat-icon m-stat-amber">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="m-stat-info">
                        <span class="m-stat-value" id="statValDiajukan">{{ $stats['diajukan'] ?? 0 }}</span>
                        <span class="m-stat-label">Menunggu Verifikasi</span>
                    </div>
                </div>

                <div class="m-stat-card cool-card-reveal" onclick="selectSpjStatus('proses')" title="Klik untuk filter SPJ dalam proses">
                    <div class="m-stat-icon m-stat-blue">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="m-stat-info">
                        <span class="m-stat-value" id="statValProses">{{ $stats['proses'] ?? 0 }}</span>
                        <span class="m-stat-label">Sedang Diproses</span>
                    </div>
                </div>

                <div class="m-stat-card cool-card-reveal" onclick="selectSpjStatus('selesai')" title="Klik untuk filter SPJ yang telah selesai">
                    <div class="m-stat-icon m-stat-green">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div class="m-stat-info">
                        <span class="m-stat-value" id="statValSelesai">{{ $stats['selesai'] ?? 0 }}</span>
                        <span class="m-stat-label">Selesai &amp; Lunas</span>
                    </div>
                </div>
            </div>

            <!-- Filter Toolbar Card (Tanggal, Urutan, Reset, Status) -->
            <div class="monitoring-controls-card cool-card-reveal">
                <!-- Baris 1: Filter Tanggal, Urutan Data, & Tombol Reset (Centered) -->
                <div class="m-filter-top-bar">
                    <!-- 1. Filter Berdasarkan Tanggal -->
                    <div class="m-field-group">
                        <label for="filterTanggal">
                            <i class="bi bi-calendar3"></i>
                            <span>Tanggal SPJ</span>
                        </label>
                        <div class="m-field-input-box no-icon">
                            <input type="date" id="filterTanggal" onchange="filterSpjList()" title="Filter berdasarkan tanggal pengajuan / kegiatan">
                            <button type="button" id="clearFilterTanggal" class="m-field-clear" onclick="clearField('filterTanggal')" aria-label="Hapus Tanggal">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>
                    </div>

                    <!-- 2. Urutan Data (Terbaru, Terlama, Nominal) -->
                    <div class="m-field-group">
                        <label for="spjSortSelect">
                            <i class="bi bi-arrow-down-up"></i>
                            <span>Urutan Data</span>
                        </label>
                        <div class="m-field-input-box no-icon">
                            <select id="spjSortSelect" onchange="filterSpjList()">
                                <option value="terbaru" selected>Terbaru (Default)</option>
                                <option value="terlama">Terlama</option>
                                <option value="nilai_tertinggi">Jumlah Tertinggi</option>
                                <option value="nilai_terendah">Jumlah Terendah</option>
                                <option value="nama_asc">Nama Kegiatan (A-Z)</option>
                            </select>
                        </div>
                    </div>

                    <!-- 3. Tombol Reset Filter -->
                    <div class="m-filter-action">
                        <button type="button" class="btn-reset-filters" id="btnResetFilters" onclick="resetSpjFilters()" title="Kembalikan semua filter ke awal">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span>Reset Filter</span>
                        </button>
                    </div>
                </div>

                <!-- Pemisah Halus Antar Baris Filter -->
                <div class="m-filter-divider"></div>

                <!-- Baris 2: Filter Status Berkas -->
                <div class="m-filter-status-row">
                    <div class="m-status-title">
                        <i class="bi bi-funnel-fill"></i>
                        <span>Status:</span>
                    </div>
                    <div class="m-status-chips" id="spjStatusChips">
                        <button type="button" class="m-chip active" data-status="semua" onclick="selectSpjStatus('semua')">
                            <i class="bi bi-grid-fill"></i>
                            <span>Semua Status</span>
                            <span class="chip-count">{{ $stats['total'] ?? count($spjs ?? []) }}</span>
                        </button>
                        <button type="button" class="m-chip" data-status="diajukan" onclick="selectSpjStatus('diajukan')">
                            <i class="bi bi-file-earmark-arrow-up"></i>
                            <span>Diajukan</span>
                            <span class="chip-count">{{ $stats['diajukan'] ?? 0 }}</span>
                        </button>
                        <button type="button" class="m-chip" data-status="proses" onclick="selectSpjStatus('proses')">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Dalam Proses</span>
                            <span class="chip-count">{{ $stats['proses'] ?? 0 }}</span>
                        </button>
                        <button type="button" class="m-chip" data-status="selesai" onclick="selectSpjStatus('selesai')">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Selesai &amp; Lunas</span>
                            <span class="chip-count">{{ $stats['selesai'] ?? 0 }}</span>
                        </button>
                        <button type="button" class="m-chip" data-status="revisi" onclick="selectSpjStatus('revisi')">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>Perlu Revisi</span>
                            <span class="chip-count">{{ $stats['revisi'] ?? 0 }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Card: Hasil Pembacaan Semua Data SPJ -->
            <div class="monitoring-table-card cool-card-reveal">
                <div class="m-table-header-bar">
                    <div class="m-table-header-title">
                        <i class="bi bi-table"></i>
                        <span>Hasil Seluruh Dokumen SPJ Terdaftar</span>
                    </div>
                    <div class="m-table-count-label">
                        Menampilkan <strong id="mFilteredCount">{{ count($spjs ?? []) }}</strong> dari {{ count($spjs ?? []) }} SPJ
                    </div>
                </div>

                <div class="m-table-wrapper">
                    <table class="m-table" id="spjDataTable">
                        <thead>
                            <tr>
                                <th style="width: 250px;">Nomor SPJ</th>
                                <th>Pengaju</th>
                                <th style="width: 240px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="spjTableBody">
                            @forelse(($spjs ?? []) as $spj)
                            @php
                                $badge = $spj->public_badge;
                                $stage = $spj->stage_index;
                                $tgl = $spj->diajukan_at ? $spj->diajukan_at : ($spj->tanggal ? $spj->tanggal : $spj->created_at);
                                $tglYmd = $tgl ? $tgl->format('Y-m-d') : '';
                                $statusGroup = $badge['group'] ?? 'proses';
                                $revisiNote = $spj->catatan_revisi_terbaru;
                            @endphp
                            <tr class="spj-row"
                                onclick="openTrackModal({{ $spj->id }})"
                                title="Klik untuk melacak alur detail SPJ"
                                data-id="{{ $spj->id }}"
                                data-nomor="{{ strtolower($spj->nomor_spj ?? '') }}"
                                data-kegiatan="{{ strtolower($spj->kegiatan ?? '') }}"
                                data-pengaju="{{ strtolower($spj->user->name ?? 'staf teknis') }}"
                                data-tanggal-ymd="{{ $tglYmd }}"
                                data-status="{{ strtolower($spj->status ?? '') }}"
                                data-status-group="{{ $statusGroup }}"
                                data-nilai="{{ (float) $spj->nilai }}"
                                data-nilai-raw="{{ (int) round($spj->nilai) }}"
                                data-created="{{ $tgl ? $tgl->timestamp : 0 }}"
                                data-stage="{{ $stage }}"
                                data-badge-label="{{ $badge['label'] }}"
                                data-badge-class="{{ $badge['class'] }}"
                                data-badge-icon="{{ $badge['icon'] }}"
                                data-tanggal-str="{{ $tgl ? $tgl->format('d M Y') : '—' }}"
                                data-nilai-str="Rp {{ number_format($spj->nilai, 0, ',', '.') }}"
                                data-diajukan-at="{{ $spj->diajukan_at ? $spj->diajukan_at->format('d M Y H:i') : ($spj->created_at ? $spj->created_at->format('d M Y H:i') : '—') }}"
                                data-fisik-at="{{ $spj->berkas_fisik_diterima_at ? $spj->berkas_fisik_diterima_at->format('d M Y H:i') : '' }}"
                                data-umum-at="{{ $spj->disetujui_umum_at ? $spj->disetujui_umum_at->format('d M Y H:i') : '' }}"
                                data-ppk-at="{{ $spj->disetujui_ppk_at ? $spj->disetujui_ppk_at->format('d M Y H:i') : '' }}"
                                data-ppspm-at="{{ $spj->disetujui_ppspm_at ? $spj->disetujui_ppspm_at->format('d M Y H:i') : '' }}"
                                data-spm="{{ $spj->nomor_spm ?? '' }}"
                                data-selesai-at="{{ $spj->diselesaikan_at ? $spj->diselesaikan_at->format('d M Y H:i') : '' }}"
                                data-revisi-note="{{ $revisiNote ?? '' }}">
                                
                                <!-- 1. Nomor SPJ -->
                                <td>
                                    <div class="m-spj-pill" title="Nomor Registrasi SPJ Resmi">
                                        <span>{{ $spj->nomor_spj ?? 'SPJ-'.$spj->id }}</span>
                                        <button type="button" class="m-btn-copy" onclick="copySpjNumber(event, '{{ $spj->nomor_spj ?? $spj->id }}')" title="Salin Nomor SPJ">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                    <div class="m-date-sub" style="margin-top: 5px; font-size: 11.5px; color: #64748b;">
                                        <i class="bi bi-calendar3"></i>
                                        <span>{{ $tgl ? $tgl->format('d M Y') : '—' }}</span>
                                    </div>
                                </td>

                                <!-- 2. Pengaju -->
                                <td class="m-pengaju-cell">
                                    <div class="m-pengaju-title">
                                        <i class="bi bi-person-circle"></i>
                                        <span>{{ $spj->user->name ?? 'Staf Teknis' }}</span>
                                    </div>
                                    <div class="m-kegiatan-sub" title="{{ $spj->kegiatan }}">
                                        {{ $spj->kegiatan }}
                                    </div>
                                </td>

                                <!-- 3. Status -->
                                <td class="m-status-cell">
                                    <span class="m-status-badge {{ $badge['class'] }}">
                                        <i class="{{ $badge['icon'] }}"></i>
                                        <span>{{ $badge['label'] }}</span>
                                    </span>
                                    <div class="m-stage-sub">
                                        @if($stage === 1) Tahap 1: Teknis
                                        @elseif($stage === 2) Tahap 2: Subbag Umum
                                        @elseif($stage === 3) Tahap 3: PPK
                                        @elseif($stage === 4) Tahap 4: PPSPM
                                        @elseif($stage === 5)
                                            {{ $spj->status === 'selesai' ? 'Tahap 5: Selesai &amp; Lunas' : 'Tahap 5: Bendahara' }}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="spjInitialEmpty">
                                <td colspan="3">
                                    <div class="m-empty-state">
                                        <i class="bi bi-inbox-fill"></i>
                                        <h4>Belum Ada Pengajuan SPJ Publik</h4>
                                        <p>Saat ini belum ada dokumen pengajuan SPJ aktif yang tercatat di dalam sistem.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- No Results Search State -->
                    <div class="m-empty-state" id="spjNoFilterResults" style="display: none;">
                        <i class="bi bi-search"></i>
                        <h4>Tidak Ada SPJ yang Cocok</h4>
                        <p>Tidak ditemukan data SPJ yang sesuai dengan kata kunci nama, tanggal, jumlah, atau filter status yang dipilih.</p>
                        <button type="button" class="btn-reset-filters" onclick="resetSpjFilters()">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span>Tampilkan Semua SPJ</span>
                        </button>
                    </div>
                </div>
            </div>

        </section>

        <!-- ALUR 5 TAHAPAN LENGKAP -->
        <section class="workflow" id="alur">

            <div class="section-title cool-header-reveal">
                <span class="section-label">ALUR PROSES TERSTANDAR</span>

                <h2 class="section-heading">
                    Alur Pengajuan &amp; Penyelesaian SPJ
                </h2>

                <p class="section-desc">
                    Setiap SPJ diproses melalui 5 tahapan pemeriksaan terstandar SOP untuk menjamin akuntabilitas belanja APBN.
                </p>
            </div>

            <div class="flow-container">

                <!-- 01: TEKNIS -->
                <div class="flow-card blue cool-card-reveal">
                    <div class="number">01</div>
                    <div class="icon">
                        <i class="bi bi-file-earmark-text" style="font-size: 20px;"></i>
                    </div>
                    <h3>Teknis</h3>
                    <p>Mengajukan dan menyerahkan dokumen SPJ ke Subbagian Umum.</p>
                </div>

                <div class="arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>

                <!-- 02: SUBBAGIAN UMUM -->
                <div class="flow-card green cool-card-reveal">
                    <div class="number">02</div>
                    <div class="icon">
                        <i class="bi bi-clipboard2-check" style="font-size: 20px;"></i>
                    </div>
                    <h3>Subbagian Umum</h3>
                    <p>Memeriksa kelengkapan administrasi dan berkas pendukung SPJ.</p>
                </div>

                <div class="arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>

                <!-- 03: PPK -->
                <div class="flow-card orange cool-card-reveal">
                    <div class="number">03</div>
                    <div class="icon">
                        <i class="bi bi-patch-check" style="font-size: 20px;"></i>
                    </div>
                    <h3>PPK</h3>
                    <p>Memeriksa substansi kegiatan dan menerbitkan persetujuan.</p>
                </div>

                <div class="arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>

                <!-- 04: PPSPM -->
                <div class="flow-card purple cool-card-reveal">
                    <div class="number">04</div>
                    <div class="icon">
                        <i class="bi bi-award" style="font-size: 20px;"></i>
                    </div>
                    <h3>PPSPM</h3>
                    <p>Menguji dokumen tagihan dan menerbitkan SPM ke KPPN.</p>
                </div>

                <div class="arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>

                <!-- 05: BENDAHARA -->
                <div class="flow-card emerald cool-card-reveal">
                    <div class="number">05</div>
                    <div class="icon">
                        <i class="bi bi-cash-coin" style="font-size: 20px;"></i>
                    </div>
                    <h3>Bendahara</h3>
                    <p>Melakukan pembayaran tagihan SPJ dan pengarsipan bukti sah.</p>
                </div>

            </div>

        </section>


        <!-- FEATURES -->
        <section class="features" id="panduan">

            <div class="section-title cool-header-reveal">
                <span class="section-label">FITUR SISTEM</span>

                <h2 class="section-heading">
                    Pengelolaan SPJ Dalam Satu Sistem
                </h2>
            </div>


            <div class="feature-grid">

                <div class="feature cool-card-reveal">
                    <div class="feature-icon feat-blue">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h3>Pengajuan SPJ</h3>
                    <p>
                        Pengajuan SPJ dilakukan secara
                        terstruktur melalui sistem.
                    </p>
                </div>

                <div class="feature cool-card-reveal">
                    <div class="feature-icon feat-green">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3>Pemeriksaan</h3>
                    <p>
                        Setiap dokumen dapat diperiksa
                        sebelum diteruskan.
                    </p>
                </div>

                <div class="feature cool-card-reveal">
                    <div class="feature-icon feat-amber">
                        <i class="bi bi-bell"></i>
                    </div>
                    <h3>Status Real-time</h3>
                    <p>
                        Pantau posisi SPJ dari pengajuan
                        hingga selesai.
                    </p>
                </div>

                <div class="feature cool-card-reveal">
                    <div class="feature-icon feat-purple">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <h3>Arsip Digital</h3>
                    <p>
                        Dokumen dan riwayat SPJ tersimpan
                        secara terstruktur.
                    </p>
                </div>

                <div class="feature feature-link feature-tpl-trigger cool-card-reveal" onclick="openTemplateModal()" id="cardTemplateSpj" role="button" tabindex="0" title="Buka & Unduh Katalog Format Berkas SPJ">
                    <div class="feature-icon feat-rose">
                        <i class="bi bi-file-earmark-arrow-down"></i>
                    </div>
                    <div class="feature-title-row">
                        <h3>Format SPJ</h3>
                        <span class="feature-ext-badge"><i class="bi bi-download"></i></span>
                    </div>
                    <p>
                        Format dan kelengkapan berkas
                        SPJ siap diunduh.
                    </p>
                    <div class="feature-action-hint">
                        <span>Unduh Format ({{ count($templates) }})</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>

            </div>

        </section>

    </main>


    <!-- FOOTER -->
    <footer>

        <div>
            <strong>SISTEM INFORMASI PENYELESAIAN TAGIHAN</strong>

            <p>
                Sistem Informasi Penyelesaian Tagihan
            </p>
        </div>

        <span>
            © {{ date('Y') }} SISTEM INFORMASI PENYELESAIAN TAGIHAN
        </span>

    </footer>

    <!-- Sticky Navbar, 3D Tilt & Live SPJ Simulation Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            /* ---------------------------------------------
               1. STICKY NAVBAR & SCROLLSPY
            --------------------------------------------- */
            const navbar = document.querySelector('.navbar');
            const navLinks = document.querySelectorAll('.nav-links .nav-link');
            const sections = [
                { id: 'beranda', el: document.getElementById('beranda') },
                { id: 'monitoring', el: document.getElementById('monitoring') },
                { id: 'alur', el: document.getElementById('alur') },
                { id: 'panduan', el: document.getElementById('panduan') }
            ];

            const onScroll = () => {
                if (!navbar) return;

                if (window.scrollY > 20) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }

                let currentId = 'beranda';
                sections.forEach(({ id, el }) => {
                    if (el) {
                        const top = el.getBoundingClientRect().top;
                        if (top <= 140) {
                            currentId = id;
                        }
                    }
                });

                navLinks.forEach(link => {
                    const href = link.getAttribute('href');
                    if (
                        href === '#' + currentId ||
                        (currentId === 'beranda' && (href === '#' || href === '#beranda'))
                    ) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });
            };

            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();

            /* ---------------------------------------------
               2. 3D TILT EFFECT (PARALLAX ON HERO CARD)
            --------------------------------------------- */
            const cardWrapper = document.getElementById('heroCardWrapper');
            const glassCard = document.getElementById('heroInteractiveCard');

            if (cardWrapper && glassCard) {
                let isHovering = false;

                cardWrapper.addEventListener('mouseenter', () => {
                    isHovering = true;
                    glassCard.style.transition = 'transform 0.1s ease-out';
                });

                cardWrapper.addEventListener('mousemove', (e) => {
                    if (!isHovering) return;
                    const rect = cardWrapper.getBoundingClientRect();
                    const x = (e.clientX - rect.left) / rect.width - 0.5; // -0.5 to 0.5
                    const y = (e.clientY - rect.top) / rect.height - 0.5;

                    const maxTilt = 12; // degrees
                    const tiltX = -y * maxTilt;
                    const tiltY = x * maxTilt;

                    glassCard.style.transform = `perspective(1000px) rotateX(${tiltX.toFixed(2)}deg) rotateY(${tiltY.toFixed(2)}deg) scale3d(1.02, 1.02, 1.02)`;
                });

                cardWrapper.addEventListener('mouseleave', () => {
                    isHovering = false;
                    glassCard.style.transition = 'transform 0.5s ease-out';
                    glassCard.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
                });
            }

            /* ---------------------------------------------
               3. SCROLL REVEAL + VELOCITY MARQUEE
            --------------------------------------------- */
            // A. Stagger delays for grids (clean, 60ms between cards)
            const cardGridSelectors = ['.monitoring-stats-grid', '.flow-container', '.feature-grid'];
            cardGridSelectors.forEach(selector => {
                const container = document.querySelector(selector);
                if (container) {
                    container.querySelectorAll('.cool-card-reveal').forEach((card, idx) => {
                        card.style.transitionDelay = `${idx * 60}ms`;
                    });
                }
            });

            // B. IntersectionObserver — fade + translateY reveal
            const revealEls = document.querySelectorAll('.cool-card-reveal, .cool-header-reveal');
            if ('IntersectionObserver' in window) {
                const revealObs = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-revealed');
                            obs.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.07, rootMargin: '0px 0px -32px 0px' });
                revealEls.forEach(el => revealObs.observe(el));
            } else {
                revealEls.forEach(el => el.classList.add('is-revealed'));
            }

            // C. Velocity Marquee — single track, speed reacts to scroll velocity
            const track1 = document.getElementById('vScrollTrack1');
            let track1X = 0;
            const baseSpeed = 0.6;
            let scrollVelocity = 0;
            let lastScrollY = window.scrollY;
            let lastScrollTime = performance.now();
            let rafId = null;

            function marqueeLoop() {
                // Decay velocity each frame
                scrollVelocity *= 0.92;
                // Advance marquee — base speed + velocity boost
                track1X += baseSpeed + Math.abs(scrollVelocity) * 0.3;
                if (track1 && track1.scrollWidth > 0) {
                    const half = track1.scrollWidth / 2;
                    if (track1X >= half) track1X -= half;
                    track1.style.transform = `translate3d(${-track1X.toFixed(2)}px, 0, 0)`;
                }
                rafId = requestAnimationFrame(marqueeLoop);
            }

            function onVelocityScroll() {
                const now = performance.now();
                const dy = window.scrollY - lastScrollY;
                const dt = Math.max(now - lastScrollTime, 8);
                scrollVelocity = (dy / dt) * 16;
                lastScrollY = window.scrollY;
                lastScrollTime = now;
            }

            requestAnimationFrame(marqueeLoop);
            window.addEventListener('scroll', onVelocityScroll, { passive: true });

            // Auto-open modal if hash is #templates or #template-spj
            if (window.location.hash === '#templates' || window.location.hash === '#template-spj') {
                setTimeout(() => openTemplateModal(), 300);
            }
        });

        /* =========================================================
           JAVASCRIPT MOBILE DRAWER NAVIGATION (GAZU STYLE)
        ========================================================= */
        function toggleMobileNav(event) {
            if (event) event.preventDefault();
            const drawer = document.getElementById('mobileNavDrawer');
            const backdrop = document.getElementById('mobileNavBackdrop');
            if (!drawer || !backdrop) return;
            const isOpen = drawer.classList.contains('show');
            if (isOpen) {
                closeMobileNav();
            } else {
                drawer.classList.add('show');
                backdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMobileNav() {
            const drawer = document.getElementById('mobileNavDrawer');
            const backdrop = document.getElementById('mobileNavBackdrop');
            if (drawer) drawer.classList.remove('show');
            if (backdrop) backdrop.classList.remove('show');
            document.body.style.overflow = '';
        }

        function focusDetailSpj(keyword, event) {
            if (event) event.preventDefault();
            const elMonitoring = document.getElementById('monitoring');
            if (elMonitoring) {
                elMonitoring.scrollIntoView({ behavior: 'smooth' });
            }
            const elSearch = document.getElementById('searchKeyword');
            if (elSearch && keyword) {
                elSearch.value = keyword;
                if (typeof filterSpjList === 'function') {
                    filterSpjList();
                }
            }
        }

        /* =========================================================
           JAVASCRIPT MODAL TEMPLATE BERKAS SPJ (PUBLIK)
        ========================================================= */
        function openTemplateModal(event) {
            if (event) event.preventDefault();
            const backdrop = document.getElementById('tplModalBackdrop');
            if (!backdrop) return;
            backdrop.classList.add('active');
            document.body.style.overflow = 'hidden';
            const searchBox = document.getElementById('tplSearchBox');
            if (searchBox) setTimeout(() => searchBox.focus(), 150);
        }

        function closeTemplateModal() {
            const backdrop = document.getElementById('tplModalBackdrop');
            if (!backdrop) return;
            backdrop.classList.remove('active');
            document.body.style.overflow = '';
        }

        function closeTemplateModalOnBackdrop(event) {
            if (event.target === document.getElementById('tplModalBackdrop')) {
                closeTemplateModal();
            }
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeTemplateModal();
        });

        let currentCatFilter = 'all';

        function selectTemplateCategory(cat) {
            currentCatFilter = cat;
            document.querySelectorAll('.tpl-chip').forEach(chip => {
                chip.classList.toggle('active', chip.getAttribute('data-cat') === cat);
            });
            filterTemplateCards();
        }

        function filterTemplateCards() {
            const query = (document.getElementById('tplSearchBox')?.value || '').toLowerCase().trim();
            const clearBtn = document.getElementById('tplClearSearch');
            if (clearBtn) clearBtn.style.display = query ? 'inline-flex' : 'none';

            const cards = document.querySelectorAll('.tpl-item-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const cat = card.getAttribute('data-cat') || '';
                const nama = card.getAttribute('data-nama') || '';
                const code = card.getAttribute('data-code') || '';
                const desc = card.getAttribute('data-desc') || '';
                const ext = card.getAttribute('data-ext') || '';

                const matchCat = (currentCatFilter === 'all' || cat === currentCatFilter || cat.startsWith(currentCatFilter) || currentCatFilter.startsWith(cat));
                const matchQuery = !query || nama.includes(query) || code.includes(query) || desc.includes(query) || ext.includes(query);

                if (matchCat && matchQuery) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const noResults = document.getElementById('tplNoResults');
            if (noResults) noResults.style.display = (visibleCount === 0 && cards.length > 0) ? 'block' : 'none';
        }

        function clearTemplateSearch() {
            const searchBox = document.getElementById('tplSearchBox');
            if (searchBox) {
                searchBox.value = '';
                searchBox.focus();
            }
            filterTemplateCards();
        }

        function resetTemplateFilters() {
            const searchBox = document.getElementById('tplSearchBox');
            if (searchBox) searchBox.value = '';
            selectTemplateCategory('all');
        }

        /* =========================================================
           JAVASCRIPT MONITORING & FILTER PENCARIAN SPJ PUBLIK
           (NAMA, TANGGAL, JUMLAH, STATUS, URUTAN)
        ========================================================= */
        let currentStatusFilter = 'semua';

        function selectSpjStatus(status) {
            currentStatusFilter = status;
            document.querySelectorAll('#spjStatusChips .m-chip').forEach(chip => {
                chip.classList.toggle('active', chip.getAttribute('data-status') === status);
            });
            filterSpjList();
        }

        function clearField(fieldId) {
            const el = document.getElementById(fieldId);
            if (el) {
                el.value = '';
                el.focus();
            }
            filterSpjList();
        }

        function resetSpjFilters() {
            const elTgl = document.getElementById('filterTanggal');
            if (elTgl) elTgl.value = '';
            const elSort = document.getElementById('spjSortSelect');
            if (elSort) elSort.value = 'terbaru';

            selectSpjStatus('semua');
        }

        function filterSpjList() {
            const tglQuery = (document.getElementById('filterTanggal')?.value || '').trim();
            const clearTglBtn = document.getElementById('clearFilterTanggal');
            if (clearTglBtn) clearTglBtn.style.display = tglQuery ? 'inline-block' : 'none';

            const sortVal = document.getElementById('spjSortSelect')?.value || 'terbaru';
            const tbody = document.getElementById('spjTableBody');
            const rows = Array.from(document.querySelectorAll('#spjTableBody .spj-row'));

            if (!tbody || rows.length === 0) return;

            // Sort rows
            rows.sort((a, b) => {
                if (sortVal === 'terbaru') {
                    return (parseInt(b.getAttribute('data-created') || 0, 10)) - (parseInt(a.getAttribute('data-created') || 0, 10));
                }
                if (sortVal === 'terlama') {
                    return (parseInt(a.getAttribute('data-created') || 0, 10)) - (parseInt(b.getAttribute('data-created') || 0, 10));
                }
                if (sortVal === 'nilai_tertinggi') {
                    return (parseFloat(b.getAttribute('data-nilai') || 0)) - (parseFloat(a.getAttribute('data-nilai') || 0));
                }
                if (sortVal === 'nilai_terendah') {
                    return (parseFloat(a.getAttribute('data-nilai') || 0)) - (parseFloat(b.getAttribute('data-nilai') || 0));
                }
                if (sortVal === 'nama_asc') {
                    return (a.getAttribute('data-kegiatan') || '').localeCompare(b.getAttribute('data-kegiatan') || '');
                }
                return 0;
            });

            let visibleCount = 0;

            // Re-append and check filter match
            rows.forEach(row => {
                tbody.appendChild(row);

                const rowTglYmd = row.getAttribute('data-tanggal-ymd') || '';
                const rowStatus = row.getAttribute('data-status') || '';
                const rowGroup = row.getAttribute('data-status-group') || '';

                // 1. Filter Tanggal
                const matchTanggal = !tglQuery || rowTglYmd === tglQuery;

                // 2. Filter Status
                let matchStatus = false;
                if (currentStatusFilter === 'semua') {
                    matchStatus = true;
                } else if (currentStatusFilter === 'diajukan') {
                    matchStatus = (rowStatus === 'diajukan' || rowGroup === 'diajukan');
                } else if (currentStatusFilter === 'proses') {
                    matchStatus = (rowGroup === 'proses');
                } else if (currentStatusFilter === 'selesai') {
                    matchStatus = (rowStatus === 'selesai' || rowGroup === 'selesai');
                } else if (currentStatusFilter === 'revisi') {
                    matchStatus = (rowGroup === 'revisi' || rowStatus.startsWith('revisi_') || rowStatus === 'dikembalikan');
                }

                if (matchTanggal && matchStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update counter badge
            const countLabel = document.getElementById('mFilteredCount');
            if (countLabel) countLabel.textContent = visibleCount.toString();

            // Handle no results state
            const noResults = document.getElementById('spjNoFilterResults');
            if (noResults) {
                noResults.style.display = (visibleCount === 0 && rows.length > 0) ? 'block' : 'none';
            }
        }

        /* ---------------------------------------------
           MODAL DETAIL & TRACKING ALUR SPJ
        --------------------------------------------- */
        function openTrackModal(spjId) {
            const row = document.querySelector(`.spj-row[data-id="${spjId}"]`);
            if (!row) return;

            const modal = document.getElementById('trackModalBackdrop');
            if (!modal) return;

            const nomor = row.querySelector('.m-spj-pill span')?.textContent || row.getAttribute('data-nomor');
            const kegiatan = row.querySelector('.m-kegiatan-title')?.textContent || row.getAttribute('data-kegiatan');
            const pengaju = row.getAttribute('data-pengaju') || '—';
            const tanggalStr = row.getAttribute('data-tanggal-str') || '—';
            const nilaiStr = row.getAttribute('data-nilai-str') || '—';
            const badgeLabel = row.getAttribute('data-badge-label') || '';
            const badgeClass = row.getAttribute('data-badge-class') || '';
            const badgeIcon = row.getAttribute('data-badge-icon') || '';
            const stage = parseInt(row.getAttribute('data-stage') || '1', 10);
            const status = row.getAttribute('data-status') || '';
            const revisiNote = row.getAttribute('data-revisi-note') || '';

            // Header & basic info
            document.getElementById('trackModalNomor').textContent = nomor;
            document.getElementById('trackModalKegiatan').textContent = kegiatan;
            document.getElementById('trackModalTanggal').textContent = tanggalStr;
            document.getElementById('trackModalPengaju').textContent = pengaju.toUpperCase();
            document.getElementById('trackModalNilai').textContent = nilaiStr;

            const modalBadge = document.getElementById('trackModalBadge');
            if (modalBadge) {
                modalBadge.className = `m-status-badge ${badgeClass}`;
                modalBadge.innerHTML = `<i class="${badgeIcon}"></i> <span>${badgeLabel}</span>`;
            }

            // Timeline timestamps
            const diajukanAt = row.getAttribute('data-diajukan-at');
            const fisikAt = row.getAttribute('data-fisik-at');
            const umumAt = row.getAttribute('data-umum-at');
            const ppkAt = row.getAttribute('data-ppk-at');
            const ppspmAt = row.getAttribute('data-ppspm-at');
            const spm = row.getAttribute('data-spm');
            const selesaiAt = row.getAttribute('data-selesai-at');

            document.getElementById('trackTime1').textContent = diajukanAt || 'Selesai';
            document.getElementById('trackTime2').textContent = umumAt ? 'Disetujui: ' + umumAt : (fisikAt ? 'Fisik Masuk: ' + fisikAt : (stage > 2 ? 'Selesai' : (stage === 2 ? 'Sedang Diperiksa' : 'Menunggu Berkas')));
            document.getElementById('trackTime3').textContent = ppkAt ? 'Disetujui: ' + ppkAt : (stage > 3 ? 'Selesai' : (stage === 3 ? 'Sedang Diproses' : 'Menunggu'));
            document.getElementById('trackTime4').textContent = ppspmAt ? 'SPM: ' + (spm || 'Terbit') + ' (' + ppspmAt + ')' : (stage > 4 ? 'Selesai' : (stage === 4 ? 'Sedang Diproses' : 'Menunggu'));
            document.getElementById('trackTime5').textContent = selesaiAt ? 'Lunas: ' + selesaiAt : (status === 'selesai' ? 'Lunas' : (stage === 5 ? 'Proses Pencairan' : 'Menunggu'));

            // Set node states (done, active, revisi)
            for (let i = 1; i <= 5; i++) {
                const node = document.getElementById('trackNode' + i);
                const icon = document.getElementById('trackIcon' + i);
                if (!node || !icon) continue;

                node.classList.remove('done', 'active', 'revisi');

                if (i < stage) {
                    node.classList.add('done');
                    icon.innerHTML = '<i class="bi bi-check-lg"></i>';
                } else if (i === stage) {
                    if (status.startsWith('revisi_') || status === 'dikembalikan') {
                        node.classList.add('revisi');
                        icon.innerHTML = '<i class="bi bi-exclamation-lg"></i>';
                    } else if (status === 'selesai') {
                        node.classList.add('done');
                        icon.innerHTML = '<i class="bi bi-check-lg"></i>';
                    } else {
                        node.classList.add('active');
                        icon.textContent = i.toString();
                    }
                } else {
                    icon.textContent = i.toString();
                }
            }

            // Catatan revisi alert box
            const alertRevisi = document.getElementById('trackAlertRevisi');
            const revisiText = document.getElementById('trackRevisiText');
            if (alertRevisi && revisiText) {
                if (revisiNote && (status.startsWith('revisi_') || status === 'dikembalikan')) {
                    revisiText.textContent = revisiNote;
                    alertRevisi.style.display = 'flex';
                } else {
                    alertRevisi.style.display = 'none';
                }
            }

            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeTrackModal() {
            const modal = document.getElementById('trackModalBackdrop');
            if (!modal) return;
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        function closeTrackModalOnBackdrop(event) {
            if (event.target === document.getElementById('trackModalBackdrop')) {
                closeTrackModal();
            }
        }

        function copySpjNumber(event, nomor) {
            if (event) event.stopPropagation();
            if (!nomor) return;

            navigator.clipboard.writeText(nomor).then(() => {
                showToastFeedback(`Nomor SPJ ${nomor} berhasil disalin!`);
            }).catch(() => {
                showToastFeedback(`Nomor SPJ: ${nomor}`);
            });
        }

        function showToastFeedback(msg) {
            const toast = document.getElementById('spjCopyToast');
            const toastText = document.getElementById('spjCopyToastText');
            if (!toast) return;

            if (toastText) toastText.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2600);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeTemplateModal();
                closeTrackModal();
            }
        });
    </script>

    <!-- =========================================================
         MODAL KATALOG FORMAT & TEMPLATE BERKAS SPJ (PUBLIK)
         Dapat diunduh langsung oleh staf teknis atau siapa saja
    ========================================================= -->
    <div class="tpl-modal-backdrop" id="tplModalBackdrop" onclick="closeTemplateModalOnBackdrop(event)" aria-hidden="true">
        <div class="tpl-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="tplModalTitle">

            <!-- Modal Header -->
            <div class="tpl-modal-header">
                <div class="tpl-modal-header-info">
                    <div class="tpl-modal-badge">
                        <i class="bi bi-file-earmark-arrow-down-fill"></i>
                        <span>Pusat Unduhan Resmi</span>
                    </div>
                    <h2 id="tplModalTitle">Format Berkas SPJ</h2>
                    <p>
                        Unduh format dokumen kelengkapan SPJ resmi BPS tanpa perlu login. Berkas siap diisi untuk kelancaran administrasi dan pengajuan tagihan.
                    </p>
                </div>

                <div class="tpl-modal-header-actions">
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.template-berkas.index') }}" class="btn-modal-admin" title="Buka panel admin untuk unggah format baru">
                            <i class="bi bi-gear-fill"></i>
                            <span>Kelola / Unggah Berkas</span>
                        </a>
                    @endif
                    <button type="button" class="tpl-modal-close" onclick="closeTemplateModal()" aria-label="Tutup Modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Controls: Search & Category Pills -->
            <div class="tpl-modal-controls">
                <div class="tpl-search-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" id="tplSearchBox" placeholder="Cari nama berkas, kode dokumen (contoh: KWT, SPT), atau format..." oninput="filterTemplateCards()" autocomplete="off">
                    <button type="button" id="tplClearSearch" onclick="clearTemplateSearch()" style="display: none;" aria-label="Hapus pencarian">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>

                <div class="tpl-cat-chips" id="tplCatChips">
                    <button type="button" class="tpl-chip active" data-cat="all" onclick="selectTemplateCategory('all')">
                        Semua Format <span class="chip-count">({{ count($templates) }})</span>
                    </button>
                    <button type="button" class="tpl-chip" data-cat="administrasi" onclick="selectTemplateCategory('administrasi')">
                        <i class="bi bi-folder-check"></i> Administrasi
                    </button>
                    <button type="button" class="tpl-chip" data-cat="perjalanan-dinas" onclick="selectTemplateCategory('perjalanan-dinas')">
                        <i class="bi bi-briefcase-fill"></i> Perjalanan Dinas
                    </button>
                    <button type="button" class="tpl-chip" data-cat="honor-mitra" onclick="selectTemplateCategory('honor-mitra')">
                        <i class="bi bi-people-fill"></i> Honor &amp; Mitra
                    </button>
                    <button type="button" class="tpl-chip" data-cat="pengadaan-bahan" onclick="selectTemplateCategory('pengadaan-bahan')">
                        <i class="bi bi-box-seam-fill"></i> Pengadaan &amp; Bahan
                    </button>
                    <button type="button" class="tpl-chip" data-cat="pajak-keuangan" onclick="selectTemplateCategory('pajak-keuangan')">
                        <i class="bi bi-receipt-cutoff"></i> Pajak &amp; Keuangan
                    </button>
                    <button type="button" class="tpl-chip" data-cat="lainnya" onclick="selectTemplateCategory('lainnya')">
                        Format Lainnya
                    </button>
                </div>
            </div>

            <!-- Modal Body: Templates Grid -->
            <div class="tpl-modal-body">
                <div class="tpl-items-grid" id="tplItemsGrid">
                    @forelse($templates as $tpl)
                    @php
                        $ext = strtolower($tpl['file_extension'] ?? 'file');
                        $catSlug = $tpl['kategori_slug'] ?? 'lainnya';
                    @endphp
                    <div class="tpl-item-card" data-cat="{{ $catSlug }}" data-nama="{{ strtolower($tpl['nama'] ?? '') }}" data-code="{{ strtolower($tpl['kode_dokumen'] ?? '') }}" data-desc="{{ strtolower($tpl['keterangan'] ?? '') }}" data-ext="{{ $ext }}">
                        <div class="tpl-card-top">
                            <span class="tpl-code-pill">{{ $tpl['kode_dokumen'] ?? 'DOK' }}</span>
                            <span class="tpl-format-badge format-{{ $ext }}">
                                @if(in_array($ext, ['xls', 'xlsx']))
                                    <i class="bi bi-file-earmark-excel-fill"></i> EXCEL
                                @elseif(in_array($ext, ['doc', 'docx']))
                                    <i class="bi bi-file-earmark-word-fill"></i> WORD
                                @elseif($ext === 'pdf')
                                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                @else
                                    <i class="bi bi-file-earmark-fill"></i> {{ strtoupper($ext) }}
                                @endif
                            </span>
                        </div>

                        <h4 class="tpl-item-title">{{ $tpl['nama'] }}</h4>

                        <div class="tpl-item-meta">
                            <span class="tpl-cat-badge">
                                <i class="bi bi-tag-fill"></i> {{ $tpl['kategori'] ?? 'Administrasi' }}
                            </span>
                            <span class="tpl-size-badge">
                                <i class="bi bi-hdd-fill"></i> {{ $tpl['file_size'] ?? '—' }}
                            </span>
                        </div>

                        @if(!empty($tpl['keterangan']))
                            <p class="tpl-item-desc">{{ $tpl['keterangan'] }}</p>
                        @endif

                        <div class="tpl-card-footer">
                            <a href="{{ !empty($tpl['file_name']) ? asset('files/templates/'.$tpl['file_name']) : ($tpl['download_url'] ?? '#') }}" download="{{ $tpl['original_name'] ?? $tpl['file_name'] }}" class="btn-download-tpl">
                                <i class="bi bi-download"></i>
                                <span>Unduh Format</span>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="tpl-empty-box" id="tplEmptyState">
                        <i class="bi bi-inbox-fill"></i>
                        <h4>Belum ada berkas format yang diunggah</h4>
                        <p>Administrator belum menambahkan berkas format SPJ ke sistem.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Empty Search Filter State -->
                <div class="tpl-empty-box" id="tplNoResults" style="display: none;">
                    <i class="bi bi-search"></i>
                    <h4>Tidak ditemukan berkas format yang cocok</h4>
                    <p>Coba kata kunci lain atau pilih tab kategori 'Semua Format'.</p>
                    <button type="button" class="btn-reset-filter" onclick="resetTemplateFilters()">Tampilkan Semua Format</button>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="tpl-modal-footer">
                <div class="tpl-footer-note">
                    <i class="bi bi-shield-check"></i>
                    <span>Format resmi Badan Pusat Statistik Kabupaten Kolaka Utara &bull; Siap digunakan untuk pelaporan SPJ APBN.</span>
                </div>
                <button type="button" class="btn-close-modal" onclick="closeTemplateModal()">Tutup</button>
            </div>

        </div>
    </div>

    <!-- =========================================================
         MODAL DETAIL & PELACAKAN ALUR SPJ (PUBLIK - TANPA LOGIN)
    ========================================================= -->
    <div class="track-modal-backdrop" id="trackModalBackdrop" onclick="closeTrackModalOnBackdrop(event)" aria-hidden="true">
        <div class="track-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="trackModalKegiatan">

            <!-- Modal Header -->
            <div class="track-modal-header">
                <div class="track-modal-header-info">
                    <div class="track-modal-top-tags">
                        <span class="track-modal-id-pill" id="trackModalNomor">SPJ-000</span>
                        <span class="m-status-badge" id="trackModalBadge">
                            <i class="bi bi-clock-history"></i>
                            <span id="trackModalBadgeText">Status</span>
                        </span>
                    </div>
                    <h3 class="track-modal-title" id="trackModalKegiatan">Nama Kegiatan SPJ</h3>
                </div>

                <button type="button" class="track-modal-close" onclick="closeTrackModal()" aria-label="Tutup Modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="track-modal-body">
                <!-- Info Grid -->
                <div class="track-info-grid">
                    <div class="track-info-item">
                        <small>Tanggal Pengajuan</small>
                        <strong id="trackModalTanggal">—</strong>
                    </div>
                    <div class="track-info-item">
                        <small>Pengaju (Teknis)</small>
                        <strong id="trackModalPengaju">—</strong>
                    </div>
                    <div class="track-info-item">
                        <small>Nilai / Nominal Tagihan</small>
                        <strong id="trackModalNilai" style="color: #059669;">Rp 0</strong>
                    </div>
                </div>

                <!-- 5-Stage Visual Stepper Timeline -->
                <h4 class="track-stepper-title">
                    <i class="bi bi-diagram-3-fill" style="color: #0284c7;"></i>
                    <span>Riwayat Alur Proses SOP (5 Tahap)</span>
                </h4>

                <div class="track-timeline" id="trackTimeline">
                    <!-- Node 1: Teknis -->
                    <div class="track-timeline-node" id="trackNode1">
                        <div class="track-node-icon" id="trackIcon1">1</div>
                        <div class="track-node-content">
                            <div class="track-node-header">
                                <span class="track-node-role">Tahap 1: Pengajuan Tim Teknis</span>
                                <span class="track-node-time" id="trackTime1">—</span>
                            </div>
                            <div class="track-node-desc">
                                Berkas SPJ disusun dan diajukan ke sistem serta diserahkan secara fisik ke Subbagian Umum.
                            </div>
                        </div>
                    </div>

                    <!-- Node 2: Subbagian Umum -->
                    <div class="track-timeline-node" id="trackNode2">
                        <div class="track-node-icon" id="trackIcon2">2</div>
                        <div class="track-node-content">
                            <div class="track-node-header">
                                <span class="track-node-role">Tahap 2: Verifikasi Subbagian Umum</span>
                                <span class="track-node-time" id="trackTime2">—</span>
                            </div>
                            <div class="track-node-desc" id="trackDesc2">
                                Pemeriksaan kelengkapan administrasi berkas fisik, kuitansi riil, dan lembar checklist SPJ.
                            </div>
                        </div>
                    </div>

                    <!-- Node 3: PPK -->
                    <div class="track-timeline-node" id="trackNode3">
                        <div class="track-node-icon" id="trackIcon3">3</div>
                        <div class="track-node-content">
                            <div class="track-node-header">
                                <span class="track-node-role">Tahap 3: Persetujuan Komitmen (PPK)</span>
                                <span class="track-node-time" id="trackTime3">—</span>
                            </div>
                            <div class="track-node-desc" id="trackDesc3">
                                Pengujian kesesuaian anggaran DIPA/POK, substansi hasil kegiatan, dan persetujuan bayar.
                            </div>
                        </div>
                    </div>

                    <!-- Node 4: PPSPM -->
                    <div class="track-timeline-node" id="trackNode4">
                        <div class="track-node-icon" id="trackIcon4">4</div>
                        <div class="track-node-content">
                            <div class="track-node-header">
                                <span class="track-node-role">Tahap 4: Pengujian &amp; Penerbitan SPM (PPSPM)</span>
                                <span class="track-node-time" id="trackTime4">—</span>
                            </div>
                            <div class="track-node-desc" id="trackDesc4">
                                Pengujian dokumen tagihan serta penerbitan Surat Perintah Membayar (SPM) resmi.
                            </div>
                        </div>
                    </div>

                    <!-- Node 5: Bendahara -->
                    <div class="track-timeline-node" id="trackNode5">
                        <div class="track-node-icon" id="trackIcon5">5</div>
                        <div class="track-node-content">
                            <div class="track-node-header">
                                <span class="track-node-role">Tahap 5: Pencairan &amp; Pembayaran (Bendahara)</span>
                                <span class="track-node-time" id="trackTime5">—</span>
                            </div>
                            <div class="track-node-desc" id="trackDesc5">
                                Eksekusi pembayaran via CMS / QLOLA BRI, bukti setor pajak NTPN, dan arsip lunas.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan Revisi Alert Box (jika ada catatan) -->
                <div class="track-alert-revisi" id="trackAlertRevisi" style="display: none;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Catatan Perbaikan / Revisi Berkas:</strong>
                        <p id="trackRevisiText">—</p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="track-modal-footer">
                <div class="track-modal-footer-note">
                    <i class="bi bi-shield-check" style="color: #0284c7;"></i>
                    <span>Data dipublikasikan secara transparan sesuai SOP BPS Kolaka Utara.</span>
                </div>
                <button type="button" class="btn-close-track-modal" onclick="closeTrackModal()">Tutup</button>
            </div>

        </div>
    </div>

    <!-- Toast Copy Feedback -->
    <div class="spj-copy-toast" id="spjCopyToast">
        <i class="bi bi-check-circle-fill" style="color: #10b981;"></i>
        <span id="spjCopyToastText">Nomor SPJ berhasil disalin!</span>
    </div>
</body>

</html>