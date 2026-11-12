<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistem Informasi Penyelesaian Tagihan | Badan Pusat Statistik</title>

    <x-favicon />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    @vite(['resources/css/home.css', 'resources/js/app.js'])
</head>

<body class="formal-bps-theme">
    @include('components.paint-transition')

    <!-- 1. TOP ANNOUNCEMENT & UTILITY BAR (RESMI PEMERINTAHAN BPS) -->
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

    <!-- 2. MAIN NAVBAR (FORMAL EXECUTIVE BPS GLASS) -->
    <header class="navbar">
        <div class="navbar-inner">
            <!-- LEFT: NAVIGATION LINKS -->
            <div class="navbar-left">
                <button type="button" class="mobile-toggle-btn" onclick="toggleMobileNav(event)" aria-label="Menu Navigasi">
                    <i class="bi bi-list"></i>
                </button>
                <nav class="nav-links">
                    <a href="#beranda" class="nav-link active">BERANDA</a>
                    <a href="#alur" class="nav-link">ALUR SPJ</a>
                    <a href="#monitoring" class="nav-link">MONITORING</a>
                    <a href="#panduan" class="nav-link" onclick="openPanduanModal(event)">PANDUAN</a>
                    <a href="#templates" class="nav-link" onclick="openTemplateModal(event)">TEMPLATE</a>
                </nav>
            </div>

            <!-- CENTER: BOLD CENTERED BRAND -->
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
        </div>
    </header>

    <!-- MOBILE NAVIGATION DRAWER -->
    <div class="mobile-nav-backdrop" id="mobileNavBackdrop" onclick="closeMobileNav()"></div>
    <div class="mobile-nav-drawer" id="mobileNavDrawer">
        <div class="mobile-drawer-header">
            <div class="brand-centered" style="gap: 10px;">
                <img src="{{ asset('images/logo-bps-app.png') }}" alt="Logo BPS" style="height: 32px; width: auto;">
                <div style="display: flex; flex-direction: column;">
                    <span style="font-weight: 800; font-size: 11.5px; letter-spacing: 0.8px; color: #0c2556; line-height: 1.2;">BADAN PUSAT STATISTIK</span>
                    <span style="font-weight: 700; font-size: 8px; letter-spacing: 1px; color: #0284c7;">KABUPATEN KOLAKA UTARA</span>
                </div>
            </div>
            <button type="button" class="btn-close-drawer" onclick="closeMobileNav()">&times;</button>
        </div>
        <div class="mobile-drawer-links">
            <a href="#beranda" onclick="closeMobileNav()">BERANDA</a>
            <a href="#alur" onclick="closeMobileNav()">ALUR SPJ</a>
            <a href="#monitoring" onclick="closeMobileNav()">MONITORING SPJ</a>
            <a href="#templates" onclick="closeMobileNav(); openTemplateModal(event)">TEMPLATE BERKAS</a>
            <div class="mobile-drawer-divider"></div>
            <a href="{{ route('flowchart.preview') }}" target="_blank"><i class="bi bi-diagram-3"></i> FLOWCHART SOP</a>
            <a href="#panduan" onclick="closeMobileNav(); openPanduanModal(event)"><i class="bi bi-play-circle-fill"></i> PANDUAN VIDEO TUTORIAL</a>
            <a href="{{ route('panduan-role.preview') }}" target="_blank"><i class="bi bi-file-earmark-pdf"></i> PANDUAN ROLE (PDF)</a>
        </div>
    </div>

    <!-- MAIN SEAMLESS FORMAL EXECUTIVE CANVAS -->
    <main class="page-canvas">

        <!-- Unified background ambient light blue glows -->
        <div class="unified-bg-canvas" aria-hidden="true">
            <div class="formal-ambient-glow glow-hero-top"></div>
            <div class="formal-ambient-glow glow-showcase"></div>
            <div class="formal-ambient-glow glow-pipeline"></div>
            <div class="formal-ambient-glow glow-monitoring"></div>
        </div>

        <!-- =========================================================
             1. HERO SECTION (FORMAL EXECUTIVE BPS STYLE)
        ========================================================= -->
        <section class="hero" id="beranda">
            <div class="hero-inner">

            <!-- Left Hero Content -->
            <div class="hero-content reveal-slide-left">

                <h1 class="hero-main-title">
                    Kelola SPJ Cerdas <br>
                    <span class="hero-brand-text">&amp; Akuntabel.</span>
                </h1>

            </div>

            <!-- Right Hero Visual: 3D Floating Card Shuffle Deck (Living Official BPS Documents) -->
            <div class="hero-deck-wrapper reveal-pop delay-1" id="heroDeckWrapper">

                <!-- Ambient Subtle Floating Tags -->
                <div class="deck-floating-tag tag-top">
                    <span class="tag-pulse-dot"></span>
                    <i class="bi bi-shield-check"></i>
                    <span>5 TAHAP SOP &bull; AUDIT-READY APBN</span>
                </div>

                <div class="deck-floating-tag tag-bottom">
                    <i class="bi bi-broadcast"></i>
                    <span>MONITORING REAL-TIME 24/7</span>
                </div>

                <!-- 3D Card Stack Container -->
                <div class="deck-cards-stage" id="deckCardsStage" title="Klik kartu untuk shuffle ke dokumen berikutnya">

                    <!-- CARD 1: PERJALANAN DINAS -->
                    <article class="deck-card deck-card-1 card-pos-front" data-card-index="0">
                        <!-- Card Header Strip -->
                        <div class="dcard-header">
                            <div class="dcard-header-left">
                                <span class="dcard-type-badge badge-salmon">
                                    <i class="bi bi-airplane-fill"></i>
                                    <span>SURAT TUGAS &amp; PERJADIN</span>
                                </span>
                                <span class="dcard-ref-no">SPJ/2026/02/084</span>
                            </div>
                            <div class="dcard-status-pill pill-lunas">
                                <span class="dcard-pulse-dot dot-green"></span>
                                <span>Selesai &amp; Lunas</span>
                            </div>
                        </div>

                        <!-- Card Body Content -->
                        <div class="dcard-body">
                            <div class="dcard-title-row">
                                <h3 class="dcard-title">Survei Pendataan Potensi Desa (Podes) 2026</h3>
                                <span class="dcard-loc-tag">
                                    <i class="bi bi-geo-alt-fill"></i> Lasusua &ndash; Kendari
                                </span>
                            </div>

                            <!-- Document Metrics Grid -->
                            <div class="dcard-metrics-grid">
                                <div class="dcard-metric-item">
                                    <span class="dmetric-label">Pelaksana / Tanggung Jawab</span>
                                    <strong class="dmetric-val">Tim Teknis Statistik Sosial</strong>
                                </div>
                                <div class="dcard-metric-item">
                                    <span class="dmetric-label">Nominal Realisasi SP2D</span>
                                    <strong class="dmetric-val val-nominal">Rp 4.750.000</strong>
                                </div>
                            </div>

                            <!-- 5-Step Miniature Audit Pipeline Indicator -->
                            <div class="dcard-pipeline-wrap">
                                <div class="dpipeline-header">
                                    <span class="dpipeline-title">Audit Trail &amp; Verifikasi SOP</span>
                                    <span class="dpipeline-sla"><i class="bi bi-check-circle-fill"></i> SOP APBN Selesai</span>
                                </div>
                                <div class="dpipeline-nodes">
                                    <div class="dp-node is-done" title="Tahap 1: Teknis"><i class="bi bi-check-lg"></i><span>Teknis</span></div>
                                    <div class="dp-connector is-done"></div>
                                    <div class="dp-node is-done" title="Tahap 2: Subbag Umum"><i class="bi bi-check-lg"></i><span>Umum</span></div>
                                    <div class="dp-connector is-done"></div>
                                    <div class="dp-node is-done" title="Tahap 3: PPK"><i class="bi bi-check-lg"></i><span>PPK</span></div>
                                    <div class="dp-connector is-done"></div>
                                    <div class="dp-node is-done" title="Tahap 4: PPSPM"><i class="bi bi-check-lg"></i><span>PPSPM</span></div>
                                    <div class="dp-connector is-done"></div>
                                    <div class="dp-node is-done node-lunas" title="Tahap 5: Bendahara"><i class="bi bi-cash-stack"></i><span>Lunas</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer / Authentic Stamp Seal -->
                        <div class="dcard-footer">
                            <div class="dcard-stamp-seal stamp-salmon">
                                <div class="stamp-inner">
                                    <span class="stamp-org">BPS KOLUT</span>
                                    <span class="stamp-main">VERIFIED</span>
                                    <span class="stamp-code">SP2D &bull; LUNAS</span>
                                </div>
                            </div>
                            <div class="dcard-footer-info">
                                <span class="dcard-footer-caption">Telah diverifikasi sesuai Standar Biaya Masukan (SBM) PMK APBN</span>
                            </div>
                        </div>
                    </article>

                    <!-- CARD 2: HONORARIUM MITRA -->
                    <article class="deck-card deck-card-2 card-pos-middle" data-card-index="1">
                        <!-- Card Header Strip -->
                        <div class="dcard-header">
                            <div class="dcard-header-left">
                                <span class="dcard-type-badge badge-amber">
                                    <i class="bi bi-people-fill"></i>
                                    <span>HONORARIUM MITRA</span>
                                </span>
                                <span class="dcard-ref-no">SPJ/2026/03/112</span>
                            </div>
                            <div class="dcard-status-pill pill-ppspm">
                                <span class="dcard-pulse-dot dot-cyan"></span>
                                <span>Tahap 4: Penerbitan SPM</span>
                            </div>
                        </div>

                        <!-- Card Body Content -->
                        <div class="dcard-body">
                            <div class="dcard-title-row">
                                <h3 class="dcard-title">Honor Petugas Lapangan Sensus Ekonomi Lanjutan</h3>
                                <span class="dcard-loc-tag">
                                    <i class="bi bi-person-badge-fill"></i> 14 Mitra Statistik
                                </span>
                            </div>

                            <!-- Document Metrics Grid -->
                            <div class="dcard-metrics-grid">
                                <div class="dcard-metric-item">
                                    <span class="dmetric-label">Penanggung Jawab Kegiatan</span>
                                    <strong class="dmetric-val">Tim Nerwilis &amp; Distribusi</strong>
                                </div>
                                <div class="dcard-metric-item">
                                    <span class="dmetric-label">Alokasi Anggaran DIPA</span>
                                    <strong class="dmetric-val val-nominal">Rp 8.400.000</strong>
                                </div>
                            </div>

                            <!-- 5-Step Miniature Audit Pipeline Indicator -->
                            <div class="dcard-pipeline-wrap">
                                <div class="dpipeline-header">
                                    <span class="dpipeline-title">Audit Trail &amp; Verifikasi SOP</span>
                                    <span class="dpipeline-sla"><i class="bi bi-stopwatch-fill"></i> Estimasi: 1 Hari</span>
                                </div>
                                <div class="dpipeline-nodes">
                                    <div class="dp-node is-done" title="Tahap 1: Teknis"><i class="bi bi-check-lg"></i><span>Teknis</span></div>
                                    <div class="dp-connector is-done"></div>
                                    <div class="dp-node is-done" title="Tahap 2: Subbag Umum"><i class="bi bi-check-lg"></i><span>Umum</span></div>
                                    <div class="dp-connector is-done"></div>
                                    <div class="dp-node is-done" title="Tahap 3: PPK"><i class="bi bi-check-lg"></i><span>PPK</span></div>
                                    <div class="dp-connector is-active"></div>
                                    <div class="dp-node is-current" title="Tahap 4: PPSPM (Sedang Diproses)"><i class="bi bi-arrow-repeat"></i><span>PPSPM</span></div>
                                    <div class="dp-connector"></div>
                                    <div class="dp-node is-pending" title="Tahap 5: Bendahara"><span>5</span><span>Bendahara</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer / Authentic Stamp Seal -->
                        <div class="dcard-footer">
                            <div class="dcard-stamp-seal stamp-blue">
                                <div class="stamp-inner">
                                    <span class="stamp-org">KOLAKA UTARA</span>
                                    <span class="stamp-main">KOMITMEN</span>
                                    <span class="stamp-code">PPK &bull; VALID</span>
                                </div>
                            </div>
                            <div class="dcard-footer-info">
                                <span class="dcard-footer-caption">Daftar hadir fisik &amp; bukti potong PPh 21 telah diverifikasi lengkap</span>
                            </div>
                        </div>
                    </article>

                    <!-- CARD 3: PENGADAAN & ATK -->
                    <article class="deck-card deck-card-3 card-pos-back" data-card-index="2">
                        <!-- Card Header Strip -->
                        <div class="dcard-header">
                            <div class="dcard-header-left">
                                <span class="dcard-type-badge badge-sage">
                                    <i class="bi bi-box-seam-fill"></i>
                                    <span>BELANJA OPERASIONAL</span>
                                </span>
                                <span class="dcard-ref-no">SPJ/2026/03/129</span>
                            </div>
                            <div class="dcard-status-pill pill-umum">
                                <span class="dcard-pulse-dot dot-orange"></span>
                                <span>Tahap 2: Verifikasi Subbag Umum</span>
                            </div>
                        </div>

                        <!-- Card Body Content -->
                        <div class="dcard-body">
                            <div class="dcard-title-row">
                                <h3 class="dcard-title">Penggandaan Dokumen &amp; ATK Penunjang Sensus</h3>
                                <span class="dcard-loc-tag">
                                    <i class="bi bi-card-checklist"></i> Bukti Riil Lengkap
                                </span>
                            </div>

                            <!-- Document Metrics Grid -->
                            <div class="dcard-metrics-grid">
                                <div class="dcard-metric-item">
                                    <span class="dmetric-label">Unit Pengaju Berkas</span>
                                    <strong class="dmetric-val">Subbagian Umum &amp; Teknis</strong>
                                </div>
                                <div class="dcard-metric-item">
                                    <span class="dmetric-label">Nilai Kuitansi Riil</span>
                                    <strong class="dmetric-val val-nominal">Rp 2.850.000</strong>
                                </div>
                            </div>

                            <!-- 5-Step Miniature Audit Pipeline Indicator -->
                            <div class="dcard-pipeline-wrap">
                                <div class="dpipeline-header">
                                    <span class="dpipeline-title">Audit Trail &amp; Verifikasi SOP</span>
                                    <span class="dpipeline-sla"><i class="bi bi-clock-history"></i> Pemeriksaan Fisik</span>
                                </div>
                                <div class="dpipeline-nodes">
                                    <div class="dp-node is-done" title="Tahap 1: Teknis"><i class="bi bi-check-lg"></i><span>Teknis</span></div>
                                    <div class="dp-connector is-active"></div>
                                    <div class="dp-node is-current" title="Tahap 2: Subbag Umum (Verifikasi Dokumen)"><i class="bi bi-search"></i><span>Umum</span></div>
                                    <div class="dp-connector"></div>
                                    <div class="dp-node is-pending" title="Tahap 3: PPK"><span>3</span><span>PPK</span></div>
                                    <div class="dp-connector"></div>
                                    <div class="dp-node is-pending" title="Tahap 4: PPSPM"><span>4</span><span>PPSPM</span></div>
                                    <div class="dp-connector"></div>
                                    <div class="dp-node is-pending" title="Tahap 5: Bendahara"><span>5</span><span>Bendahara</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer / Authentic Stamp Seal -->
                        <div class="dcard-footer">
                            <div class="dcard-stamp-seal stamp-green">
                                <div class="stamp-inner">
                                    <span class="stamp-org">SUBBAG UMUM</span>
                                    <span class="stamp-main">CHECKED</span>
                                    <span class="stamp-code">BUKTI SAH</span>
                                </div>
                            </div>
                            <div class="dcard-footer-info">
                                <span class="dcard-footer-caption">Kuitansi sah bermeterai &amp; faktur toko telah diserahkan fisik</span>
                            </div>
                        </div>
                    </article>

                </div>

                <!-- Deck Navigation & Interaction Deck -->
                <div class="deck-controls-strip">
                    <div class="deck-dots" id="deckDotsWrap">
                        <button type="button" class="deck-dot is-active" data-deck-target="0" aria-label="Lihat Dokumen 1"></button>
                        <button type="button" class="deck-dot" data-deck-target="1" aria-label="Lihat Dokumen 2"></button>
                        <button type="button" class="deck-dot" data-deck-target="2" aria-label="Lihat Dokumen 3"></button>
                    </div>
                    <div class="deck-action-buttons">
                        <button type="button" class="btn-deck-shuffle" id="btnDeckShuffle" title="Geser ke dokumen SPJ berikutnya">
                            <span>Geser Dokumen</span>
                            <i class="bi bi-arrow-right-short"></i>
                        </button>
                        <a href="#monitoring" class="btn-deck-detail" title="Lihat semua data di Dashboard Monitoring">
                            <span>Semua SPJ</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>

            </div>

            </div>

        </section>

        <!-- =========================================================
             TORN PAPER DIVIDER 1: Hero (Sage) to Showcase (Cream)
        ========================================================= -->
        <div class="torn-paper-divider torn-down" aria-hidden="true">
            <svg viewBox="0 0 1440 90" preserveAspectRatio="none">
                <path class="torn-shadow" fill="rgba(0,0,0,0.12)" transform="translate(0, 3)" d="M0,90 L0,39.9 L0,39.9 L12,41.9 L24,48.3 L36,49.7 L48,49.6 L60,42.5 L72,48.3 L84,50.3 L96,48.4 L108,46.2 L120,51.1 L132,44.6 L144,46.3 L156,46.9 L168,43.1 L180,44.3 L192,45.5 L204,41.3 L216,44.3 L228,45.9 L240,38 L252,45.1 L264,39.7 L276,42.4 L288,36.5 L300,38.7 L312,39.5 L324,31.3 L336,31.7 L348,36 L360,29.8 L372,28.7 L384,26.9 L396,33.3 L408,26.4 L420,30.3 L432,27.1 L444,25.3 L456,26.9 L468,27 L480,31.8 L492,29.4 L504,26.9 L516,29.5 L528,25.8 L540,34.2 L552,31.7 L564,28.5 L576,28.1 L588,37.2 L600,36.2 L612,37.5 L624,31.2 L636,34 L648,40.6 L660,36.2 L672,42.8 L684,36.8 L696,43.3 L708,40.3 L720,40.8 L732,45.2 L744,46.4 L756,48.7 L768,43.3 L780,49 L792,44 L804,48.2 L816,46.6 L828,47.9 L840,43.3 L852,46.6 L864,50 L876,44.1 L888,50.4 L900,45.3 L912,46.4 L924,48 L936,46 L948,41.4 L960,46.8 L972,42.6 L984,44.6 L996,36.6 L1008,42.7 L1020,35.9 L1032,33 L1044,38.1 L1056,38.6 L1068,36.7 L1080,34.6 L1092,29.1 L1104,30.2 L1116,35.3 L1128,26.3 L1140,27 L1152,32.7 L1164,28 L1176,27.1 L1188,26.7 L1200,31.1 L1212,33.1 L1224,31.3 L1236,29.3 L1248,33.6 L1260,28.9 L1272,26.3 L1284,34.2 L1296,30.2 L1308,32.1 L1320,34.9 L1332,30.3 L1344,34.4 L1356,35.9 L1368,35.6 L1380,39.6 L1392,41.3 L1404,40.7 L1416,45.2 L1428,45.9 L1440,44.9 L1440,90 Z" />
                <path class="torn-paper-fill" fill="#FAF5EB" d="M0,90 L0,39.9 L0,39.9 L12,41.9 L24,48.3 L36,49.7 L48,49.6 L60,42.5 L72,48.3 L84,50.3 L96,48.4 L108,46.2 L120,51.1 L132,44.6 L144,46.3 L156,46.9 L168,43.1 L180,44.3 L192,45.5 L204,41.3 L216,44.3 L228,45.9 L240,38 L252,45.1 L264,39.7 L276,42.4 L288,36.5 L300,38.7 L312,39.5 L324,31.3 L336,31.7 L348,36 L360,29.8 L372,28.7 L384,26.9 L396,33.3 L408,26.4 L420,30.3 L432,27.1 L444,25.3 L456,26.9 L468,27 L480,31.8 L492,29.4 L504,26.9 L516,29.5 L528,25.8 L540,34.2 L552,31.7 L564,28.5 L576,28.1 L588,37.2 L600,36.2 L612,37.5 L624,31.2 L636,34 L648,40.6 L660,36.2 L672,42.8 L684,36.8 L696,43.3 L708,40.3 L720,40.8 L732,45.2 L744,46.4 L756,48.7 L768,43.3 L780,49 L792,44 L804,48.2 L816,46.6 L828,47.9 L840,43.3 L852,46.6 L864,50 L876,44.1 L888,50.4 L900,45.3 L912,46.4 L924,48 L936,46 L948,41.4 L960,46.8 L972,42.6 L984,44.6 L996,36.6 L1008,42.7 L1020,35.9 L1032,33 L1044,38.1 L1056,38.6 L1068,36.7 L1080,34.6 L1092,29.1 L1104,30.2 L1116,35.3 L1128,26.3 L1140,27 L1152,32.7 L1164,28 L1176,27.1 L1188,26.7 L1200,31.1 L1212,33.1 L1224,31.3 L1236,29.3 L1248,33.6 L1260,28.9 L1272,26.3 L1284,34.2 L1296,30.2 L1308,32.1 L1320,34.9 L1332,30.3 L1344,34.4 L1356,35.9 L1368,35.6 L1380,39.6 L1392,41.3 L1404,40.7 L1416,45.2 L1428,45.9 L1440,44.9 L1440,90 Z" />
            </svg>
        </div>

        <!-- =========================================================
             2. SPLIT ALTERNATE SHOWCASE (KENDALI DOKUMEN DIGITAL)
        ========================================================= -->
        <section class="split-showcase-section">
            <div class="split-showcase-inner">

                <!-- Left Visual Preview: Lembar Kendali Digital -->
                <div class="showcase-visual-wrapper reveal-slide-left">
                    <!-- Soft organic pastel blob behind preview -->
                    <div class="showcase-organic-blob" aria-hidden="true"></div>

                    <div class="formal-hud-panel">
                        <div class="hud-panel-header">
                            <div class="hud-header-title">
                                <i class="bi bi-check2-square"></i>
                                <span>LEMBAR KENDALI DOKUMEN DIGITAL</span>
                            </div>
                            <span class="hud-live-tag">VERIFIED SOP</span>
                        </div>

                        <div class="hud-checklist-list">
                            <div class="hud-check-item is-checked">
                                <div class="hud-check-badge"><i class="bi bi-check2"></i></div>
                                <div class="hud-check-info">
                                    <strong>Kuitansi Riil &amp; Bukti Pembayaran Sah</strong>
                                    <span>Telah diverifikasi sesuai standar PMK SBM</span>
                                </div>
                                <span class="hud-time-tag">HARI H</span>
                            </div>

                            <div class="hud-check-item is-checked">
                                <div class="hud-check-badge"><i class="bi bi-check2"></i></div>
                                <div class="hud-check-info">
                                    <strong>Checklist Administrasi Subbagian Umum</strong>
                                    <span>Dokumen fisik lengkap dan tanda tangan sah</span>
                                </div>
                                <span class="hud-time-tag">1 HARI</span>
                            </div>

                            <div class="hud-check-item is-checked">
                                <div class="hud-check-badge"><i class="bi bi-check2"></i></div>
                                <div class="hud-check-info">
                                    <strong>Pengujian Komitmen &amp; Pagu DIPA (PPK)</strong>
                                    <span>Kesesuaian alokasi anggaran dan output kegiatan</span>
                                </div>
                                <span class="hud-time-tag">1 HARI</span>
                            </div>

                            <div class="hud-check-item is-checked">
                                <div class="hud-check-badge"><i class="bi bi-check2"></i></div>
                                <div class="hud-check-info">
                                    <strong>Penerbitan Surat Perintah Membayar (SPM)</strong>
                                    <span>Validasi PPSPM dan transmisi data ke KPPN</span>
                                </div>
                                <span class="hud-time-tag">1 HARI</span>
                            </div>

                            <div class="hud-check-item is-pulse">
                                <div class="hud-check-badge pulse-badge"><i class="bi bi-arrow-repeat"></i></div>
                                <div class="hud-check-info">
                                    <strong>Pencairan Dana &amp; SP2D Bendahara</strong>
                                    <span>Penyelesaian transfer kas negara ke pelaksana</span>
                                </div>
                                <span class="hud-time-tag active-tag">PROSES</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Copy & Action -->
                <div class="showcase-content-wrapper reveal-slide-right">
                    <span class="section-micro-label">TRANSPARANSI BIROKRASI BPS</span>
                    <h2 class="showcase-title">
                        Pantau Posisi Berkas Kapan Saja <br>
                    </h2>
                    <p class="showcase-desc">
                        Seluruh tahapan penyelesaian tagihan dapat dipantau langsung oleh staf teknis, pimpinan, maupun mitra kerja. Sistem menjamin keterbukaan informasi anggaran demi mewujudkan tata kelola BPS yang akuntabel, efisien, dan melayani.
                    </p>
                    <div class="showcase-action-row">
                        <a href="#monitoring" class="btn-formal-primary">
                            <i class="bi bi-search"></i>
                            <span>CARI DOKUMEN SPJ</span>
                        </a>
                  
                    </div>
                </div>

            </div>
        </section>

        <!-- =========================================================
             3. VERTICAL FORMAL PIPELINE (5 TAHAPAN SPJ)
        ========================================================= -->
        <section class="pipeline-section" id="alur">

            <div class="section-title-centered reveal-slide-up">
                <span class="section-micro-label">ALUR PROSES TERSTANDAR</span>
                <h2 class="section-main-heading">Alur 5 Tahapan Pengajuan &amp; Penyelesaian SPJ</h2>
                <p class="section-main-desc">
                    Setiap dokumen tagihan diproses melalui 5 tahap pemeriksaan SOP untuk menjamin akuntabilitas belanja APBN.
                </p>
            </div>

            <!-- The Vertical Timeline Container -->
            <div class="vertical-pipeline-container">
                <!-- Vertical connector line -->
                <div class="v-pipeline-line"></div>

                <!-- Stage 01: TEKNIS -->
                <div class="v-pipeline-step">
                    <div class="v-step-node">
                        <div class="v-node-dot">1</div>
                    </div>
                    <div class="v-step-card reveal-slide-left">
                        <div class="v-card-top">
                            <span class="v-step-num">TAHAP 01</span>
                            <span class="v-sla-badge"><i class="bi bi-clock"></i> Estimasi: Hari H</span>
                        </div>
                        <h3 class="v-step-title">Pengajuan Tim Teknis Kegiatan</h3>
                        <p class="v-step-desc">
                            Menginput rincian SPJ ke dalam sistem, melampirkan berkas bukti sah (kuitansi, faktur riil, daftar hadir), dan menyerahkan dokumen fisik ke Subbagian Umum.
                        </p>
                        <div class="v-step-badges">
                            <span class="v-badge"><i class="bi bi-file-earmark-check"></i> Input Berkas</span>
                            <span class="v-badge"><i class="bi bi-box-arrow-in-right"></i> Serah Terima Fisik</span>
                        </div>
                    </div>
                </div>

                <!-- Stage 02: SUBBAGIAN UMUM -->
                <div class="v-pipeline-step">
                    <div class="v-step-node">
                        <div class="v-node-dot">2</div>
                    </div>
                    <div class="v-step-card reveal-slide-right">
                        <div class="v-card-top">
                            <span class="v-step-num">TAHAP 02</span>
                            <span class="v-sla-badge"><i class="bi bi-clock"></i> Estimasi: 1 – 2 Hari Kerja</span>
                        </div>
                        <h3 class="v-step-title">Verifikasi Administrasi Subbagian Umum</h3>
                        <p class="v-step-desc">
                            Memeriksa kelengkapan checklist berkas fisik, keabsahan tanda tangan, format dokumen, dan kelengkapan bukti pendukung sebelum diteruskan ke PPK.
                        </p>
                        <div class="v-step-badges">
                            <span class="v-badge"><i class="bi bi-clipboard2-check"></i> Uji Kelengkapan</span>
                            <span class="v-badge"><i class="bi bi-pen"></i> Validasi Tanda Tangan</span>
                        </div>
                    </div>
                </div>

                <!-- Stage 03: PPK -->
                <div class="v-pipeline-step">
                    <div class="v-step-node">
                        <div class="v-node-dot">3</div>
                    </div>
                    <div class="v-step-card reveal-slide-left">
                        <div class="v-card-top">
                            <span class="v-step-num">TAHAP 03</span>
                            <span class="v-sla-badge"><i class="bi bi-clock"></i> Estimasi: 1 – 2 Hari Kerja</span>
                        </div>
                        <h3 class="v-step-title">Pengujian Komitmen oleh PPK</h3>
                        <p class="v-step-desc">
                            Pengujian kesesuaian anggaran DIPA/POK, substansi hasil kegiatan, dan penerbitan persetujuan komitmen belanja negara.
                        </p>
                        <div class="v-step-badges">
                            <span class="v-badge"><i class="bi bi-check-circle"></i> Pagu DIPA/POK</span>
                            <span class="v-badge"><i class="bi bi-patch-check"></i> Persetujuan Komitmen</span>
                        </div>
                    </div>
                </div>

                <!-- Stage 04: PPSPM -->
                <div class="v-pipeline-step">
                    <div class="v-step-node">
                        <div class="v-node-dot">4</div>
                    </div>
                    <div class="v-step-card reveal-slide-right">
                        <div class="v-card-top">
                            <span class="v-step-num">TAHAP 04</span>
                            <span class="v-sla-badge"><i class="bi bi-clock"></i> Estimasi: 1 – 2 Hari Kerja</span>
                        </div>
                        <h3 class="v-step-title">Validasi Tagihan &amp; Penerbitan SPM (PPSPM)</h3>
                        <p class="v-step-desc">
                            Pengujian dokumen tagihan, validasi ketersediaan dana kas negara, dan penerbitan Surat Perintah Membayar (SPM) resmi ke KPPN.
                        </p>
                        <div class="v-step-badges">
                            <span class="v-badge"><i class="bi bi-award"></i> Penerbitan SPM</span>
                            <span class="v-badge"><i class="bi bi-send-check"></i> Transmisi KPPN</span>
                        </div>
                    </div>
                </div>

                <!-- Stage 05: BENDAHARA -->
                <div class="v-pipeline-step">
                    <div class="v-step-node">
                        <div class="v-node-dot">5</div>
                    </div>
                    <div class="v-step-card reveal-pop">
                        <div class="v-card-top">
                            <span class="v-step-num">TAHAP 05</span>
                            <span class="v-sla-badge sla-green"><i class="bi bi-check2-circle"></i> Estimasi: 1 Hari Kerja</span>
                        </div>
                        <h3 class="v-step-title">Pencairan Dana &amp; Arsip Bendahara</h3>
                        <p class="v-step-desc">
                            Validasi SP2D KPPN, transfer pembayaran ke pelaksana kegiatan atau pihak ketiga, serta pengarsipan bukti setor sah secara permanen.
                        </p>
                        <div class="v-step-badges">
                            <span class="v-badge"><i class="bi bi-cash-coin"></i> Pembayaran SP2D</span>
                            <span class="v-badge"><i class="bi bi-archive"></i> Arsip Sah Permanen</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="pipeline-bottom-action">
                <a href="{{ route('flowchart.preview') }}" target="_blank" class="btn-formal-primary">
                    <i class="bi bi-diagram-3-fill"></i>
                    <span>LIHAT FLOWCHART RESMI SOP (PDF)</span>
                </a>
            </div>

        </section>

        <!-- =========================================================
             TORN PAPER DIVIDER 2: Pipeline (Cream) to Monitoring (Sage)
        ========================================================= -->
        <div class="torn-paper-divider torn-up" aria-hidden="true">
            <svg viewBox="0 0 1440 90" preserveAspectRatio="none">
                <path class="torn-shadow" fill="rgba(0,0,0,0.12)" transform="translate(0, 3)" d="M0,0 L1440,0 L1440,47.9 L1428,46.3 L1416,49.5 L1404,46.5 L1392,38.9 L1380,41.4 L1368,39 L1356,38.6 L1344,39.6 L1332,36.9 L1320,35.3 L1308,33.3 L1296,35.2 L1284,36.4 L1272,33.3 L1260,32.5 L1248,33.8 L1236,36.1 L1224,35.5 L1212,28.6 L1200,35.9 L1188,32.6 L1176,28.3 L1164,34.3 L1152,36.4 L1140,31.8 L1128,33.1 L1116,31.6 L1104,39.5 L1092,31.8 L1080,34.6 L1068,39.9 L1056,42.6 L1044,36.8 L1032,43.2 L1020,40.3 L1008,38.3 L996,47.2 L984,48.8 L972,48.2 L960,44.1 L948,48.3 L936,46.1 L924,46.1 L912,46.3 L900,50.4 L888,46.7 L876,50.4 L864,51 L852,51 L840,55.8 L828,48.1 L816,50.2 L804,49.4 L792,47.4 L780,49.3 L768,53.2 L756,53.6 L744,49.9 L732,52.1 L720,44.3 L708,45.9 L696,49 L684,47 L672,43.6 L660,41.5 L648,42.8 L636,40.3 L624,35.6 L612,41.5 L600,40.2 L588,37 L576,40.2 L564,32.3 L552,33.3 L540,38.5 L528,37 L516,36.6 L504,34.1 L492,32.7 L480,30.5 L468,33.3 L456,33.9 L444,36.2 L432,36 L420,36.1 L408,30.9 L396,35.5 L384,37 L372,39.4 L360,34.2 L348,40.7 L336,38.1 L324,43.6 L312,44.9 L300,42.6 L288,43.6 L276,40.7 L264,40.9 L252,48.2 L240,42.3 L228,45.9 L216,43.8 L204,45.3 L192,47.9 L180,52.7 L168,54.1 L156,51 L144,54.7 L132,54.1 L120,49.1 L108,47.4 L96,54 L84,47.3 L72,54.5 L60,47.4 L48,46 L36,49.4 L24,52.8 L12,48.9 L0,44.3 Z" />
                <path class="torn-paper-fill" fill="#FAF5EB" d="M0,0 L1440,0 L1440,47.9 L1428,46.3 L1416,49.5 L1404,46.5 L1392,38.9 L1380,41.4 L1368,39 L1356,38.6 L1344,39.6 L1332,36.9 L1320,35.3 L1308,33.3 L1296,35.2 L1284,36.4 L1272,33.3 L1260,32.5 L1248,33.8 L1236,36.1 L1224,35.5 L1212,28.6 L1200,35.9 L1188,32.6 L1176,28.3 L1164,34.3 L1152,36.4 L1140,31.8 L1128,33.1 L1116,31.6 L1104,39.5 L1092,31.8 L1080,34.6 L1068,39.9 L1056,42.6 L1044,36.8 L1032,43.2 L1020,40.3 L1008,38.3 L996,47.2 L984,48.8 L972,48.2 L960,44.1 L948,48.3 L936,46.1 L924,46.1 L912,46.3 L900,50.4 L888,46.7 L876,50.4 L864,51 L852,51 L840,55.8 L828,48.1 L816,50.2 L804,49.4 L792,47.4 L780,49.3 L768,53.2 L756,53.6 L744,49.9 L732,52.1 L720,44.3 L708,45.9 L696,49 L684,47 L672,43.6 L660,41.5 L648,42.8 L636,40.3 L624,35.6 L612,41.5 L600,40.2 L588,37 L576,40.2 L564,32.3 L552,33.3 L540,38.5 L528,37 L516,36.6 L504,34.1 L492,32.7 L480,30.5 L468,33.3 L456,33.9 L444,36.2 L432,36 L420,36.1 L408,30.9 L396,35.5 L384,37 L372,39.4 L360,34.2 L348,40.7 L336,38.1 L324,43.6 L312,44.9 L300,42.6 L288,43.6 L276,40.7 L264,40.9 L252,48.2 L240,42.3 L228,45.9 L216,43.8 L204,45.3 L192,47.9 L180,52.7 L168,54.1 L156,51 L144,54.7 L132,54.1 L120,49.1 L108,47.4 L96,54 L84,47.3 L72,54.5 L60,47.4 L48,46 L36,49.4 L24,52.8 L12,48.9 L0,44.3 Z" />
            </svg>
        </div>

        <!-- =========================================================
             5. MONITORING & STATUS SPJ PUBLIK (EXECUTIVE DASHBOARD)
        ========================================================= -->
        <section class="monitoring-section" id="monitoring">

            <!-- Section Title -->
            <div class="monitoring-header reveal-slide-up">
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

            <!-- =====================================================
                 ORGANIC BENTO GRID FOR STATS (PILIHAN 1)
            ====================================================== -->
            <div class="monitoring-bento-grid">
                
                <!-- Bento Featured Hero Card: Total SPJ -->
                <div class="bento-hero-card reveal-pop delay-1 is-active-filter" data-stat-filter="semua" onclick="selectSpjStatus('semua')" title="Klik untuk menampilkan semua dokumen SPJ">
                    <div class="bento-hero-blob" aria-hidden="true"></div>
                    <div class="bento-hero-content">
                        <div class="bento-hero-top">
                            <span class="bento-tag">
                                <i class="bi bi-shield-check"></i>
                                ARSIP UTAMA AKTIF
                            </span>
                            <div class="bento-action-badge">
                                <span>SEMUA</span>
                                <i class="bi bi-arrow-up-right"></i>
                            </div>
                        </div>

                        <div class="bento-hero-body">
                            <div class="bento-hero-metric">
                                <span class="bento-hero-val" id="statValTotal">{{ $stats['total'] ?? count($spjs ?? []) }}</span>
                                <span class="bento-hero-unit">SPJ TERDATA</span>
                            </div>
                            <p class="bento-hero-desc">
                                Seluruh berkas pertanggungjawaban belanja APBN terarsip dan dipantau transparan 24/7.
                            </p>
                        </div>

                        <div class="bento-hero-footer">
                            <div class="bento-pill-indicator">
                                <span class="bento-pulse-dot"></span>
                                <span>Real-time Audit Ready</span>
                            </div>
                            <span class="bento-click-hint">Tampilkan Semua &rarr;</span>
                        </div>
                    </div>
                </div>

                <!-- Bento Right Cluster: 3 Sculpted Pebble Cards -->
                <div class="bento-pebbles-column">
                    
                    <!-- Pebble 1: Menunggu Verifikasi -->
                    <div class="bento-pebble-card pebble-diajukan reveal-pop delay-2" data-stat-filter="diajukan" onclick="selectSpjStatus('diajukan')" title="Klik untuk filter SPJ yang baru diajukan">
                        <div class="pebble-icon-box">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="pebble-content">
                            <div class="pebble-metric-row">
                                <span class="pebble-val" id="statValDiajukan">{{ $stats['diajukan'] ?? 0 }}</span>
                                <span class="pebble-status-tag tag-amber">TAHAP 01</span>
                            </div>
                            <span class="pebble-label">Menunggu Verifikasi</span>
                            <small class="pebble-sub">Pemeriksaan fisik dokumen awal</small>
                        </div>
                        <div class="pebble-arrow"><i class="bi bi-chevron-right"></i></div>
                    </div>

                    <!-- Pebble 2: Sedang Diproses -->
                    <div class="bento-pebble-card pebble-proses reveal-pop delay-3" data-stat-filter="proses" onclick="selectSpjStatus('proses')" title="Klik untuk filter SPJ dalam proses verifikasi">
                        <div class="pebble-icon-box">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div class="pebble-content">
                            <div class="pebble-metric-row">
                                <span class="pebble-val" id="statValProses">{{ $stats['proses'] ?? 0 }}</span>
                                <span class="pebble-status-tag tag-salmon">TAHAP 02–04</span>
                            </div>
                            <span class="pebble-label">Sedang Diproses</span>
                            <small class="pebble-sub">Pengujian SOP, PPK &amp; SPM</small>
                        </div>
                        <div class="pebble-arrow"><i class="bi bi-chevron-right"></i></div>
                    </div>

                    <!-- Pebble 3: Selesai & Lunas -->
                    <div class="bento-pebble-card pebble-selesai reveal-pop delay-4" data-stat-filter="selesai" onclick="selectSpjStatus('selesai')" title="Klik untuk filter SPJ yang telah selesai & cair">
                        <div class="pebble-icon-box">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div class="pebble-content">
                            <div class="pebble-metric-row">
                                <span class="pebble-val" id="statValSelesai">{{ $stats['selesai'] ?? 0 }}</span>
                                <span class="pebble-status-tag tag-green">TAHAP 05</span>
                            </div>
                            <span class="pebble-label">Selesai &amp; Lunas</span>
                            <small class="pebble-sub">Transfer kas negara &amp; SP2D terbit</small>
                        </div>
                        <div class="pebble-arrow"><i class="bi bi-chevron-right"></i></div>
                    </div>

                </div>

            </div>

            <!-- =====================================================
                 INTEGRATED FLOATING CONTROL DECK / COMMAND BAR
            ====================================================== -->
            <div class="monitoring-filter-island reveal-slide-up">
                
                <!-- Left: Status Filter Pills (Segmented Dock) -->
                <div class="filter-island-status-chips" id="spjStatusChips">
                    <button type="button" class="m-chip active" data-status="semua" onclick="selectSpjStatus('semua')">
                        <i class="bi bi-grid-fill"></i>
                        <span>Semua</span>
                        <span class="chip-count">{{ $stats['total'] ?? count($spjs ?? []) }}</span>
                    </button>
                    <button type="button" class="m-chip" data-status="diajukan" onclick="selectSpjStatus('diajukan')">
                        <i class="bi bi-file-earmark-arrow-up"></i>
                        <span>Diajukan</span>
                        <span class="chip-count">{{ $stats['diajukan'] ?? 0 }}</span>
                    </button>
                    <button type="button" class="m-chip" data-status="proses" onclick="selectSpjStatus('proses')">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Diproses</span>
                        <span class="chip-count">{{ $stats['proses'] ?? 0 }}</span>
                    </button>
                    <button type="button" class="m-chip" data-status="selesai" onclick="selectSpjStatus('selesai')">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Selesai</span>
                        <span class="chip-count">{{ $stats['selesai'] ?? 0 }}</span>
                    </button>
                    <button type="button" class="m-chip" data-status="revisi" onclick="selectSpjStatus('revisi')">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>Revisi</span>
                        <span class="chip-count">{{ $stats['revisi'] ?? 0 }}</span>
                    </button>
                </div>

                <!-- Right: Quick Tools (Date, Sort, Reset) -->
                <div class="filter-island-tools">
                    <div class="filter-tool-pill tool-date" title="Filter Berdasarkan Tanggal SPJ">
                        <i class="bi bi-calendar3"></i>
                        <input type="date" id="filterTanggal" onchange="filterSpjList()" aria-label="Tanggal SPJ">
                        <button type="button" id="clearFilterTanggal" class="filter-tool-clear" onclick="clearField('filterTanggal')" aria-label="Hapus Filter Tanggal">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>

                    <div class="filter-tool-pill tool-sort" title="Urutan Dokumen">
                        <i class="bi bi-arrow-down-up"></i>
                        <select id="spjSortSelect" onchange="filterSpjList()" aria-label="Urutan Dokumen">
                            <option value="terbaru" selected>Terbaru</option>
                            <option value="terlama">Terlama</option>
                            <option value="nilai_tertinggi">Nominal &uarr;</option>
                            <option value="nilai_terendah">Nominal &darr;</option>
                            <option value="nama_asc">Nama (A-Z)</option>
                        </select>
                    </div>

                    <button type="button" class="btn-filter-reset-pill" id="btnResetFilters" onclick="resetSpjFilters()" title="Kembalikan semua filter ke awal">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span class="reset-text">Reset</span>
                    </button>
                </div>

            </div>

            <!-- =====================================================
                 FLOATING RECORD CARDS LIST (PILIHAN 2)
            ====================================================== -->
            <div class="monitoring-records-container reveal-slide-up">
                
                <!-- Deck Header Bar -->
                <div class="records-deck-header">
                    <div class="deck-header-left">
                        <div class="deck-icon-badge">
                            <i class="bi bi-layers-half"></i>
                        </div>
                        <div>
                            <h3 class="deck-header-title">Dokumen SPJ Terdaftar</h3>
                            <p class="deck-header-sub">Klik kartu untuk membuka audit trail dan rincian alur verifikasi</p>
                        </div>
                    </div>
                    <div class="deck-header-right">
                        <span class="deck-count-badge">
                            Menampilkan <strong id="mFilteredCount">{{ count($spjs ?? []) }}</strong> dari {{ count($spjs ?? []) }} Dokumen
                        </span>
                    </div>
                </div>

                <!-- Skeleton Shimmer Loading Stack (aktif saat filtering & loading data) -->
                <div id="spjSkeletonStack" class="skeleton-records-stack" style="display: none;" aria-hidden="true">
                    @for($i = 0; $i < 4; $i++)
                    <div class="skeleton-record-card">
                        <div class="skel-meta-block">
                            <div class="skel-shimmer-dark skel-pill"></div>
                            <div class="skel-shimmer-dark skel-date"></div>
                        </div>
                        <div class="skel-main-block">
                            <div class="skel-pengaju-row">
                                <div class="skel-shimmer-dark skel-avatar"></div>
                                <div class="skel-shimmer-dark skel-name"></div>
                            </div>
                            <div class="skel-shimmer-dark skel-kegiatan"></div>
                        </div>
                        <div class="skel-action-block">
                            <div class="skel-status-group">
                                <div class="skel-shimmer-dark skel-badge"></div>
                                <div class="skel-shimmer-dark skel-stage"></div>
                            </div>
                            <div class="skel-shimmer-dark skel-chevron"></div>
                        </div>
                    </div>
                    @endfor
                </div>

                <!-- Floating Cards Stack Container (acting as #spjTableBody for filters/sorting) -->
                <div id="spjTableBody" class="records-cards-stack">
                    @forelse(($spjs ?? []) as $spj)
                    @php
                        $badge = $spj->public_badge;
                        $stage = $spj->stage_index;
                        $tgl = $spj->diajukan_at ? $spj->diajukan_at : ($spj->tanggal ? $spj->tanggal : $spj->created_at);
                        $tglYmd = $tgl ? $tgl->format('Y-m-d') : '';
                        $statusGroup = $badge['group'] ?? 'proses';
                        $revisiNote = $spj->catatan_revisi_terbaru;
                        $initial = strtoupper(substr($spj->user->name ?? 'T', 0, 1));
                    @endphp
                    <div class="m-record-card spj-row"
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
                        
                        <!-- 1. Left Column: Token Nomor SPJ & Tanggal -->
                        <div class="record-meta-block">
                            <div class="m-spj-pill" title="Nomor Registrasi SPJ Resmi">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>{{ $spj->nomor_spj ?? 'SPJ-'.$spj->id }}</span>
                                <button type="button" class="m-btn-copy" onclick="copySpjNumber(event, '{{ $spj->nomor_spj ?? $spj->id }}')" title="Salin Nomor SPJ">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                            <div class="m-date-sub">
                                <i class="bi bi-calendar3"></i>
                                <span>{{ $tgl ? $tgl->format('d M Y') : '—' }}</span>
                            </div>
                        </div>

                        <!-- 2. Center Column: Pengaju Avatar & Detail Kegiatan -->
                        <div class="record-main-block">
                            <div class="record-pengaju-row">
                                <div class="record-avatar">
                                    {{ $initial }}
                                </div>
                                <span class="record-pengaju-name">
                                    {{ $spj->user->name ?? 'Staf Teknis' }}
                                </span>
                            </div>
                            <h4 class="m-kegiatan-sub" title="{{ $spj->kegiatan }}">
                                {{ $spj->kegiatan }}
                            </h4>
                        </div>

                        <!-- 3. Right Column: Luminous Status Badge & Interactive Step Arrow -->
                        <div class="record-action-block">
                            <div class="record-status-group">
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
                                        @if($spj->status === 'selesai')
                                            Tahap 5: Selesai &amp; Lunas
                                        @elseif($spj->status === 'menunggu_otp_kpa')
                                            Tahap 5: Menunggu OTP KPA
                                        @elseif($spj->status === 'proses_ls')
                                            Tahap 5: Pembayaran LS
                                        @else
                                            Tahap 5: Bendahara
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="record-chevron-btn" aria-hidden="true">
                                <i class="bi bi-arrow-right-short"></i>
                            </div>
                        </div>

                    </div>
                    @empty
                    <div id="spjInitialEmpty" class="m-empty-state">
                        <i class="bi bi-inbox-fill"></i>
                        <h4>Belum Ada Pengajuan SPJ Publik</h4>
                        <p>Saat ini belum ada dokumen pengajuan SPJ aktif yang tercatat di dalam sistem.</p>
                    </div>
                    @endforelse
                </div>

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

        </section>

        <!-- =========================================================
             6. TECHNICAL SPECIFICATIONS GRID (FORMAL REGULASI)
        ========================================================= -->
        <section class="specs-section">
            <div class="section-title-centered reveal-slide-up">
                <span class="section-micro-label">REGULASI &amp; STANDAR OPERASIONAL</span>
                <h2 class="section-main-heading">Spesifikasi &amp; Ketentuan Layanan</h2>
                <p class="section-main-desc">
                    Kerangka kepatuhan perbendaharaan negara yang menjadi landasan operasional penyelesaian tagihan SPJ.
                </p>
            </div>

            <div class="specs-grid">
                <!-- Col 1 -->
                <div class="specs-col reveal-pop delay-1">
                    <h4 class="specs-col-title">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>DASAR HUKUM &amp; REGULASI</span>
                    </h4>
                    <ul class="specs-list">
                        <li>
                            <span class="specs-item-label">UU No. 17 / 2003</span>
                            <span class="specs-item-val">Tentang Keuangan Negara</span>
                        </li>
                        <li>
                            <span class="specs-item-label">PMK Standar Biaya</span>
                            <span class="specs-item-val">Standar Biaya Masukan (SBM) APBN</span>
                        </li>
                        <li>
                            <span class="specs-item-label">Perka BPS No. 7 / 2021</span>
                            <span class="specs-item-val">Tata Laksana Administrasi BPS</span>
                        </li>
                        <li>
                            <span class="specs-item-label">DIPA / POK</span>
                            <span class="specs-item-val">Petunjuk Operasional Kegiatan Kolut</span>
                        </li>
                    </ul>
                </div>

                <!-- Col 2 -->
                <div class="specs-col reveal-pop delay-2">
                    <h4 class="specs-col-title">
                        <i class="bi bi-stopwatch-fill"></i>
                        <span>SERVICE LEVEL AGREEMENT (SLA)</span>
                    </h4>
                    <ul class="specs-list">
                        <li>
                            <span class="specs-item-label">Tahap 1 (Teknis)</span>
                            <span class="specs-item-val">Input &amp; Serah Fisik (Hari H)</span>
                        </li>
                        <li>
                            <span class="specs-item-label">Tahap 2 (Umum)</span>
                            <span class="specs-item-val">Verifikasi Berkas (1 &ndash; 2 Hari)</span>
                        </li>
                        <li>
                            <span class="specs-item-label">Tahap 3 (PPK)</span>
                            <span class="specs-item-val">Pengujian Komitmen (1 &ndash; 2 Hari)</span>
                        </li>
                        <li>
                            <span class="specs-item-label">Tahap 4 (PPSPM)</span>
                            <span class="specs-item-val">Penerbitan SPM (1 &ndash; 2 Hari)</span>
                        </li>
                        <li>
                            <span class="specs-item-label">Tahap 5 (Bendahara)</span>
                            <span class="specs-item-val">Pembayaran Kas (1 Hari Kerja)</span>
                        </li>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div class="specs-col reveal-pop delay-3">
                    <h4 class="specs-col-title">
                        <i class="bi bi-file-earmark-check-fill"></i>
                        <span>PERSYARATAN BERKAS</span>
                    </h4>
                    <ul class="specs-list">
                        <li>
                            <span class="specs-item-label">Bukti Riil</span>
                            <span class="specs-item-val">Kuitansi Sah &amp; Nota Pembelian</span>
                        </li>
                        <li>
                            <span class="specs-item-label">Lampiran Bukti</span>
                            <span class="specs-item-val">Daftar Hadir, Notula &amp; Foto</span>
                        </li>
                        <li>
                            <span class="specs-item-label">Surat Tugas</span>
                            <span class="specs-item-val">ST &amp; Laporan Perjalanan Dinas</span>
                        </li>
                        <li>
                            <span class="specs-item-label">Pajak Negara</span>
                            <span class="specs-item-val">Bukti Potong PPh 21 / 22 / 23</span>
                        </li>
                    </ul>
                </div>

                <!-- Col 4 -->
                <div class="specs-col reveal-pop delay-4">
                    <h4 class="specs-col-title">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>KEAMANAN &amp; AKUNTABILITAS</span>
                    </h4>
                    <ul class="specs-list">
                        <li>
                            <span class="specs-item-label">Audit Trail</span>
                            <span class="specs-item-val">Pencatatan Waktu &amp; Aktor Resmi</span>
                        </li>
                        <li>
                            <span class="specs-item-label">Transparansi</span>
                            <span class="specs-item-val">Pelacakan Publik Tanpa Login</span>
                        </li>
                        <li>
                            <span class="specs-item-label">Verifikasi Rangkap</span>
                            <span class="specs-item-val">Sinkronisasi Fisik &amp; Digital</span>
                        </li>
                        <li>
                            <span class="specs-item-label">Helpdesk</span>
                            <span class="specs-item-val">Tim Keuangan BPS Kolaka Utara</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

    </main>

    <!-- =========================================================
         7. FOOTER (DIGNIFIED BPS OFFICIAL NAVY)
    ========================================================= -->
    <footer class="formal-footer">
        <div class="formal-footer-inner">
            <div class="footer-brand-side">
                <div class="footer-brand">
                    <span class="footer-title">BADAN PUSAT STATISTIK</span>
                    <span class="footer-sub">KABUPATEN KOLAKA UTARA &bull; SISTEM INFORMASI PENYELESAIAN TAGIHAN</span>
                </div>
                <p class="footer-desc">
                    Mewujudkan tata kelola perbendaharaan belanja APBN yang akuntabel, tepat sasaran, dan transparan untuk Indonesia Maju.
                </p>
            </div>

            <div class="footer-links-side">
                <a href="#beranda">BERANDA</a>
                <a href="#alur">ALUR SPJ</a>
                <a href="#monitoring">MONITORING</a>
                <a href="#panduan" onclick="openPanduanModal(event)">VIDEO TUTORIAL</a>
                <a href="{{ route('panduan-role.preview') }}" target="_blank">PANDUAN ROLE (PDF)</a>
                <a href="{{ route('flowchart.preview') }}" target="_blank">FLOWCHART SOP</a>
            </div>
        </div>

        <div class="footer-bottom-bar">
            <span>&copy; {{ date('Y') }} BADAN PUSAT STATISTIK KABUPATEN KOLAKA UTARA. HAK CIPTA DILINDUNGI.</span>
            <span>PRESISI &bull; TRANSPARAN &bull; AKUNTABEL</span>
        </div>
    </footer>

    <!-- =========================================================
         MODAL DETAIL & TRACKING ALUR SPJ (FORMAL LIGHT DIALOG)
    ========================================================= -->
    <div class="track-modal-backdrop" id="trackModalBackdrop" onclick="closeTrackModalOnBackdrop(event)" aria-hidden="true">
        <div class="track-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="trackModalTitle">

            <div class="track-modal-header">
                <div>
                    <span class="track-modal-tag">DETAIL STATUS PENYELESAIAN SPJ</span>
                    <h3 id="trackModalNomor" class="track-modal-title">SPJ-XXXX</h3>
                    <p id="trackModalKegiatan" class="track-modal-sub">Nama Kegiatan SPJ</p>
                </div>
                <button type="button" class="track-modal-close" onclick="closeTrackModal()" aria-label="Tutup Modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="track-modal-body">
                <!-- Meta Info Strip -->
                <div class="track-info-strip">
                    <div class="track-info-item">
                        <small>Tanggal</small>
                        <span id="trackModalTanggal">—</span>
                    </div>
                    <div class="track-info-item">
                        <small>Pengaju</small>
                        <span id="trackModalPengaju">—</span>
                    </div>
                    <div class="track-info-item">
                        <small>Nilai Nominal</small>
                        <span id="trackModalNilai" class="val-highlight">—</span>
                    </div>
                    <div class="track-info-item">
                        <small>Status Terkini</small>
                        <span id="trackModalBadge" class="m-status-badge badge-proses">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Diproses</span>
                        </span>
                    </div>
                </div>

                <!-- Revisi Alert Note -->
                <div class="track-alert-revisi" id="trackAlertRevisi" style="display: none;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Catatan Revisi / Pengembalian:</strong>
                        <p id="trackRevisiText">—</p>
                    </div>
                </div>

                <!-- Modal Vertical Stepper -->
                <div class="track-stepper-list">

                    <div class="track-step-row" id="trackNode1">
                        <div class="track-step-bullet" id="trackIcon1">1</div>
                        <div class="track-step-text">
                            <strong>1. Tim Teknis Kegiatan (Pengajuan)</strong>
                            <p>Pengajuan berkas rincian SPJ dan bukti riil transaksi oleh pelaksana kegiatan.</p>
                            <span class="track-timestamp" id="trackTime1">—</span>
                        </div>
                    </div>

                    <div class="track-step-row" id="trackNode2">
                        <div class="track-step-bullet" id="trackIcon2">2</div>
                        <div class="track-step-text">
                            <strong>2. Verifikasi Subbagian Umum</strong>
                            <p>Pemeriksaan checklist kelengkapan berkas fisik dan keabsahan tanda tangan.</p>
                            <span class="track-timestamp" id="trackTime2">—</span>
                        </div>
                    </div>

                    <div class="track-step-row" id="trackNode3">
                        <div class="track-step-bullet" id="trackIcon3">3</div>
                        <div class="track-step-text">
                            <strong>3. Pengujian Komitmen oleh PPK</strong>
                            <p>Pengujian kesesuaian anggaran DIPA/POK dan penerbitan persetujuan.</p>
                            <span class="track-timestamp" id="trackTime3">—</span>
                        </div>
                    </div>

                    <div class="track-step-row" id="trackNode4">
                        <div class="track-step-bullet" id="trackIcon4">4</div>
                        <div class="track-step-text">
                            <strong>4. Validasi &amp; Penerbitan SPM (PPSPM)</strong>
                            <p>Pengujian kelayakan dokumen tagihan dan penerbitan SPM ke KPPN.</p>
                            <span class="track-timestamp" id="trackTime4">—</span>
                        </div>
                    </div>

                    <div class="track-step-row" id="trackNode5">
                        <div class="track-step-bullet" id="trackIcon5">5</div>
                        <div class="track-step-text">
                            <strong id="trackTitle5">5. Pencairan Dana oleh Bendahara</strong>
                            <p id="trackDesc5">Pembayaran tagihan setelah SP2D terbit dan pengarsipan kuitansi sah.</p>
                            <span class="track-timestamp" id="trackTime5">—</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="track-modal-footer">
                <button type="button" class="btn-formal-outline" onclick="closeTrackModal()">
                    <span>Tutup Pelacakan</span>
                </button>
            </div>

        </div>
    </div>

    <!-- =========================================================
         MODAL PANDUAN & VIDEO TUTORIAL SPJ (PUBLIK)
    ========================================================= -->
    <div class="panduan-modal-backdrop" id="panduanModalBackdrop" onclick="closePanduanModalOnBackdrop(event)" aria-hidden="true">
        <div class="panduan-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="panduanModalTitle">

            <!-- Modal Header -->
            <div class="tpl-modal-header">
                <div class="tpl-modal-header-info">
                    <div class="tpl-modal-badge">
                        <i class="bi bi-play-circle-fill"></i>
                        <span>Pusat Panduan &amp; Video Tutorial</span>
                    </div>
                    <h2 id="panduanModalTitle">Video Tutorial &amp; Panduan SPJ</h2>
                    <p>
                        Pelajari tata cara pengajuan, verifikasi kelengkapan, dan pencairan dana SPJ melalui panduan video interaktif dan berkas SOP resmi.
                    </p>
                </div>

                <div class="tpl-modal-header-actions">
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.video-tutorial.index') }}" class="btn-modal-admin" title="Buka panel admin untuk mengelola / mengubah video tutorial">
                            <i class="bi bi-gear-fill"></i>
                            <span>Kelola Video Tutorial</span>
                        </a>
                    @endif
                    <button type="button" class="tpl-modal-close" onclick="closePanduanModal()" aria-label="Tutup Modal Panduan">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body: Single Video Player Tutorial -->
            <div class="tpl-modal-body" style="padding: 24px;">
                @if($videoTutorial && ($videoTutorial->file_video || $videoTutorial->url_video))
                    <div class="panduan-single-video-wrapper">
                        <div class="panduan-theater-box" style="margin-bottom: 16px;">
                            <div class="panduan-player-frame" id="panduanPlayerFrame">
                                @if($videoTutorial->tipe_sumber === 'upload' && $videoTutorial->file_video)
                                    <video controls playsinline preload="metadata" id="panduanVideoPlayer" style="width: 100%; height: 100%;">
                                        <source src="{{ $videoTutorial->embed_url }}" type="video/mp4">
                                        Peramban Anda tidak mendukung pemutar video HTML5.
                                    </video>
                                @elseif($videoTutorial->embed_url)
                                    <iframe id="panduanIframePlayer" src="{{ $videoTutorial->embed_url }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @endif
                            </div>
                        </div>

                        <div class="panduan-single-video-meta">
                            <div class="panduan-meta-header">
                                <h3 class="panduan-single-title">{{ $videoTutorial->judul }}</h3>
                                @if($videoTutorial->durasi)
                                    <span class="panduan-duration-pill">
                                        <i class="bi bi-clock-history"></i> {{ $videoTutorial->durasi }}
                                    </span>
                                @endif
                            </div>
                            @if($videoTutorial->deskripsi)
                                <p class="panduan-single-desc">{{ $videoTutorial->deskripsi }}</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="panduan-empty-state">
                        <div class="panduan-empty-icon">
                            <i class="bi bi-camera-video-off"></i>
                        </div>
                        <h3 class="panduan-empty-title">Video Tutorial Belum Diunggah</h3>
                        <p class="panduan-empty-text">
                            Administrator belum mengunggah video tutorial panduan alur SPJ. Silakan pelajari tata cara pelaksanaan melalui dokumen Buku Panduan Role &amp; Flowchart SOP di bawah.
                        </p>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <div style="margin-top: 18px;">
                                <a href="{{ route('admin.video-tutorial.index') }}" class="btn-primary-cream">
                                    <i class="bi bi-cloud-arrow-up-fill"></i>
                                    <span>Unggah Video Tutorial Sekarang</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Modal Footer: Quick Links to PDF SOP -->
            <div class="panduan-pdf-strip">
                <div class="panduan-pdf-links">
                    <span style="font-size: 12px; font-weight: 800; color: var(--text-on-cream);">
                        <i class="bi bi-file-earmark-pdf-fill" style="color: #dc2626;"></i> DOKUMEN RESMI:
                    </span>
                    <a href="{{ route('panduan-role.preview') }}" target="_blank" class="panduan-pdf-btn">
                        <i class="bi bi-journal-text"></i>
                        <span>Buku Panduan Role &amp; Hak Akses</span>
                    </a>
                    <a href="{{ route('flowchart.preview') }}" target="_blank" class="panduan-pdf-btn">
                        <i class="bi bi-diagram-3-fill"></i>
                        <span>Flowchart SOP 5 Pintu SPJ</span>
                    </a>
                </div>
                <div>
                    <button type="button" class="btn-formal-outline" style="padding: 6px 16px; font-size: 12px;" onclick="closePanduanModal()">
                        <span>Tutup Panduan</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- =========================================================
         MODAL KATALOG FORMAT & TEMPLATE BERKAS SPJ (PUBLIK)
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
                    <h2 id="tplModalTitle">Format &amp; Template Berkas SPJ</h2>
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
                <!-- Skeleton Shimmer Grid for Templates -->
                <div id="tplSkeletonGrid" class="skeleton-tpl-grid" style="display: none;" aria-hidden="true">
                    @for($i = 0; $i < 6; $i++)
                    <div class="skeleton-tpl-card">
                        <div class="skel-tpl-top">
                            <div class="skel-shimmer-light skel-tpl-pill"></div>
                            <div class="skel-shimmer-light skel-tpl-format"></div>
                        </div>
                        <div class="skel-shimmer-light skel-tpl-title"></div>
                        <div class="skel-shimmer-light skel-tpl-desc"></div>
                        <div class="skel-shimmer-light skel-tpl-desc-short"></div>
                        <div class="skel-tpl-footer">
                            <div class="skel-shimmer-light skel-tpl-meta"></div>
                            <div class="skel-shimmer-light skel-tpl-btn"></div>
                        </div>
                    </div>
                    @endfor
                </div>

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
                                    <i class="bi bi-file-earmark-text-fill"></i> {{ strtoupper($ext) }}
                                @endif
                            </span>
                        </div>

                        <h4 class="tpl-item-title">{{ $tpl['nama'] }}</h4>
                        <p class="tpl-item-desc">{{ $tpl['keterangan'] ?? 'Format resmi BPS Kabupaten Kolaka Utara.' }}</p>

                        <div class="tpl-card-footer">
                            <span class="tpl-file-meta">
                                <i class="bi bi-paperclip"></i>
                                <span>{{ $tpl['file_size_formatted'] ?? 'Berkas Resmi' }}</span>
                            </span>
                            <a href="{{ $tpl['download_url'] ?? '#' }}" class="btn-download-tpl" download title="Unduh {{ $tpl['nama'] }}">
                                <i class="bi bi-download"></i>
                                <span>Unduh</span>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="tpl-empty-state">
                        <i class="bi bi-folder-x"></i>
                        <h4>Belum Ada Template Format</h4>
                        <p>Format dokumen SPJ akan segera diunggah oleh admin Subbagian Umum.</p>
                    </div>
                    @endforelse
                </div>

                <div class="tpl-empty-state" id="tplNoResults" style="display: none;">
                    <i class="bi bi-search"></i>
                    <h4>Berkas Tidak Ditemukan</h4>
                    <p>Tidak ada format berkas yang cocok dengan kata kunci pencarian Anda.</p>
                    <button type="button" class="btn-reset-filters" onclick="resetTemplateFilters()">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span>Tampilkan Semua Berkas</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Toast Notification for SPJ Copy -->
    <div class="copy-toast" id="spjCopyToast">
        <i class="bi bi-check-circle-fill"></i>
        <span id="spjCopyToastText">Nomor SPJ berhasil disalin!</span>
    </div>

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
                { id: 'alur', el: document.getElementById('alur') },
                { id: 'monitoring', el: document.getElementById('monitoring') }
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
               2. 3D FLOATING CARD SHUFFLE DECK ENGINE
            --------------------------------------------- */
            const deckWrapper = document.getElementById('heroDeckWrapper');
            const deckStage = document.getElementById('deckCardsStage');
            const deckDots = document.querySelectorAll('#deckDotsWrap .deck-dot');
            const btnShuffle = document.getElementById('btnDeckShuffle');
            const deckCards = deckStage ? Array.from(deckStage.querySelectorAll('.deck-card')) : [];

            let deckOrder = [0, 1, 2]; // index of card currently [front, middle, back]
            let deckShuffleTimer = null;
            let isDeckHovered = false;
            let isShuffling = false;

            const updateDeckClasses = () => {
                deckCards.forEach((card, idx) => {
                    card.classList.remove('card-pos-front', 'card-pos-middle', 'card-pos-back', 'card-shuffling-out');
                    const posIndex = deckOrder.indexOf(idx);
                    if (posIndex === 0) {
                        card.classList.add('card-pos-front');
                    } else if (posIndex === 1) {
                        card.classList.add('card-pos-middle');
                    } else if (posIndex === 2) {
                        card.classList.add('card-pos-back');
                    }
                });

                // Update dots
                const frontCardIdx = deckOrder[0];
                deckDots.forEach((dot, dIdx) => {
                    if (dIdx === frontCardIdx) {
                        dot.classList.add('is-active');
                    } else {
                        dot.classList.remove('is-active');
                    }
                });
            };

            const shuffleNextCard = () => {
                if (isShuffling || deckCards.length < 3) return;
                isShuffling = true;

                const frontIdx = deckOrder[0];
                const frontCard = deckCards[frontIdx];

                // Animate front card flying out smoothly
                if (frontCard) {
                    frontCard.classList.add('card-shuffling-out');
                }

                setTimeout(() => {
                    // Rotate the deck array: front goes to back
                    const oldFront = deckOrder.shift();
                    deckOrder.push(oldFront);

                    updateDeckClasses();

                    setTimeout(() => {
                        isShuffling = false;
                    }, 350);
                }, 380);
            };

            const jumpToCard = (targetCardIdx) => {
                if (isShuffling || deckOrder[0] === targetCardIdx) return;
                isShuffling = true;

                const frontCard = deckCards[deckOrder[0]];
                if (frontCard) {
                    frontCard.classList.add('card-shuffling-out');
                }

                setTimeout(() => {
                    // Reorder deck so targetCardIdx is at front
                    while (deckOrder[0] !== targetCardIdx) {
                        const shift = deckOrder.shift();
                        deckOrder.push(shift);
                    }
                    updateDeckClasses();

                    setTimeout(() => {
                        isShuffling = false;
                    }, 350);
                }, 350);
            };

            const startDeckAutoShuffle = () => {
                if (deckShuffleTimer) clearInterval(deckShuffleTimer);
                deckShuffleTimer = setInterval(() => {
                    if (!isDeckHovered) {
                        shuffleNextCard();
                    }
                }, 4500);
            };

            if (deckWrapper && deckStage) {
                // Interactive controls
                if (btnShuffle) {
                    btnShuffle.addEventListener('click', (e) => {
                        e.stopPropagation();
                        shuffleNextCard();
                        startDeckAutoShuffle();
                    });
                }

                deckStage.addEventListener('click', () => {
                    shuffleNextCard();
                    startDeckAutoShuffle();
                });

                deckDots.forEach(dot => {
                    dot.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const target = parseInt(dot.getAttribute('data-deck-target'), 10);
                        if (!isNaN(target)) {
                            jumpToCard(target);
                            startDeckAutoShuffle();
                        }
                    });
                });

                // Mouse hover pause
                deckWrapper.addEventListener('mouseenter', () => {
                    isDeckHovered = true;
                });

                deckWrapper.addEventListener('mouseleave', () => {
                    isDeckHovered = false;
                    deckStage.style.transform = 'rotateX(0deg) rotateY(0deg)';
                });

                // 3D Parallax Tilt
                deckWrapper.addEventListener('mousemove', (e) => {
                    const rect = deckWrapper.getBoundingClientRect();
                    const x = (e.clientX - rect.left) / rect.width - 0.5;
                    const y = (e.clientY - rect.top) / rect.height - 0.5;
                    const tiltX = -y * 8;
                    const tiltY = x * 8;
                    deckStage.style.transform = `rotateX(${tiltX.toFixed(2)}deg) rotateY(${tiltY.toFixed(2)}deg)`;
                });

                updateDeckClasses();
                startDeckAutoShuffle();
            }

            /* ---------------------------------------------
               2B. BIDIRECTIONAL SCROLL ENTRANCE REVEAL ENGINE
               (Pop up, slide kiri/kanan, slide up - ALWAYS ACTIVE UP & DOWN)
            --------------------------------------------- */
            document.documentElement.classList.add('reveal-engine-active');

            const revealTargets = document.querySelectorAll(
                '.reveal-pop, .reveal-slide-left, .reveal-slide-right, .reveal-slide-up'
            );

            if ('IntersectionObserver' in window) {
                const revealObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-revealed');
                        } else {
                            // Selalu aktif saat scroll ke atas maupun ke bawah:
                            // Menghapus kelas 'is-revealed' saat elemen keluar layar,
                            // sehingga saat di-scroll kembali (dari atas atau bawah)
                            // animasi entrance (pop up / slide) akan selalu aktif memukau.
                            entry.target.classList.remove('is-revealed');
                        }
                    });
                }, {
                    root: null,
                    rootMargin: '0px 0px -25px 0px',
                    threshold: 0.08
                });

                revealTargets.forEach(el => revealObserver.observe(el));
            } else {
                // Fallback untuk browser tanpa dukungan IntersectionObserver
                revealTargets.forEach(el => el.classList.add('is-revealed'));
            }

            if (window.location.hash === '#templates' || window.location.hash === '#template-spj') {
                setTimeout(() => openTemplateModal(), 300);
            }

            if (window.location.hash === '#panduan' || window.location.hash === '#video-tutorial') {
                setTimeout(() => openPanduanModal(), 300);
            }
        });

        /* =========================================================
           JAVASCRIPT MOBILE DRAWER NAVIGATION
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

        /* =========================================================
           JAVASCRIPT MODAL TEMPLATE BERKAS SPJ (PUBLIK)
        ========================================================= */
        let currentCatFilter = 'all';
        let tplSkeletonTimer = null;

        function showTplSkeleton() {
            const skeleton = document.getElementById('tplSkeletonGrid');
            const grid = document.getElementById('tplItemsGrid');
            const noResults = document.getElementById('tplNoResults');
            if (skeleton) skeleton.style.display = 'grid';
            if (grid) grid.style.display = 'none';
            if (noResults) noResults.style.display = 'none';
        }

        function hideTplSkeleton(hasResults = true) {
            const skeleton = document.getElementById('tplSkeletonGrid');
            const grid = document.getElementById('tplItemsGrid');
            const noResults = document.getElementById('tplNoResults');
            if (skeleton) skeleton.style.display = 'none';
            if (hasResults) {
                if (grid) {
                    grid.style.display = 'grid';
                    grid.classList.remove('skel-loaded-fade');
                    void grid.offsetWidth;
                    grid.classList.add('skel-loaded-fade');
                }
                if (noResults) noResults.style.display = 'none';
            } else {
                if (grid) grid.style.display = 'none';
                if (noResults) {
                    noResults.style.display = 'block';
                    noResults.classList.remove('skel-loaded-fade');
                    void noResults.offsetWidth;
                    noResults.classList.add('skel-loaded-fade');
                }
            }
        }

        /* ---------------------------------------------
           MODAL PANDUAN VIDEO TUTORIAL (SINGLE VIDEO)
        --------------------------------------------- */
        function openPanduanModal(event) {
            if (event) event.preventDefault();
            const backdrop = document.getElementById('panduanModalBackdrop');
            if (!backdrop) return;
            backdrop.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closePanduanModal() {
            const backdrop = document.getElementById('panduanModalBackdrop');
            if (!backdrop) return;
            backdrop.classList.remove('active');
            document.body.style.overflow = '';

            const videoEl = document.getElementById('panduanVideoPlayer');
            if (videoEl && typeof videoEl.pause === 'function') {
                videoEl.pause();
            }
            const iframeEl = document.getElementById('panduanIframePlayer');
            if (iframeEl) {
                const currentSrc = iframeEl.src;
                iframeEl.src = '';
                iframeEl.src = currentSrc;
            }
        }

        function closePanduanModalOnBackdrop(event) {
            if (event.target === document.getElementById('panduanModalBackdrop')) {
                closePanduanModal();
            }
        }

        function openTemplateModal(event) {
            if (event) event.preventDefault();
            const backdrop = document.getElementById('tplModalBackdrop');
            if (!backdrop) return;
            backdrop.classList.add('active');
            document.body.style.overflow = 'hidden';

            showTplSkeleton();
            setTimeout(() => {
                applyTemplateFilters();
                const searchBox = document.getElementById('tplSearchBox');
                if (searchBox) searchBox.focus();
            }, 220);
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

        function selectTemplateCategory(cat) {
            currentCatFilter = cat;
            document.querySelectorAll('.tpl-chip').forEach(chip => {
                chip.classList.toggle('active', chip.getAttribute('data-cat') === cat);
            });
            showTplSkeleton();
            if (tplSkeletonTimer) clearTimeout(tplSkeletonTimer);
            tplSkeletonTimer = setTimeout(() => {
                applyTemplateFilters();
            }, 180);
        }

        function filterTemplateCards() {
            showTplSkeleton();
            if (tplSkeletonTimer) clearTimeout(tplSkeletonTimer);
            tplSkeletonTimer = setTimeout(() => {
                applyTemplateFilters();
            }, 180);
        }

        function applyTemplateFilters() {
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

            hideTplSkeleton(visibleCount > 0 || cards.length === 0);
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
        ========================================================= */
        let currentStatusFilter = 'semua';
        let spjSkeletonTimer = null;

        function showSpjSkeleton() {
            const skeleton = document.getElementById('spjSkeletonStack');
            const tbody = document.getElementById('spjTableBody');
            const noResults = document.getElementById('spjNoFilterResults');
            if (skeleton) skeleton.style.display = 'flex';
            if (tbody) tbody.style.display = 'none';
            if (noResults) noResults.style.display = 'none';
        }

        function hideSpjSkeleton(hasResults = true) {
            const skeleton = document.getElementById('spjSkeletonStack');
            const tbody = document.getElementById('spjTableBody');
            const noResults = document.getElementById('spjNoFilterResults');
            if (skeleton) skeleton.style.display = 'none';
            if (hasResults) {
                if (tbody) {
                    tbody.style.display = 'flex';
                    tbody.classList.remove('skel-loaded-fade');
                    void tbody.offsetWidth;
                    tbody.classList.add('skel-loaded-fade');
                }
                if (noResults) noResults.style.display = 'none';
            } else {
                if (tbody) tbody.style.display = 'none';
                if (noResults) {
                    noResults.style.display = 'block';
                    noResults.classList.remove('skel-loaded-fade');
                    void noResults.offsetWidth;
                    noResults.classList.add('skel-loaded-fade');
                }
            }
        }

        function selectSpjStatus(status) {
            currentStatusFilter = status;
            document.querySelectorAll('#spjStatusChips .m-chip').forEach(chip => {
                chip.classList.toggle('active', chip.getAttribute('data-status') === status);
            });
            document.querySelectorAll('[data-stat-filter]').forEach(card => {
                card.classList.toggle('is-active-filter', card.getAttribute('data-stat-filter') === status);
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

        function filterSpjList(immediate = false) {
            if (immediate) {
                applySpjFilters();
                return;
            }

            showSpjSkeleton();
            if (spjSkeletonTimer) clearTimeout(spjSkeletonTimer);
            spjSkeletonTimer = setTimeout(() => {
                applySpjFilters();
            }, 240);
        }

        function applySpjFilters() {
            const tglQuery = (document.getElementById('filterTanggal')?.value || '').trim();
            const clearTglBtn = document.getElementById('clearFilterTanggal');
            if (clearTglBtn) clearTglBtn.style.display = tglQuery ? 'inline-block' : 'none';

            const sortVal = document.getElementById('spjSortSelect')?.value || 'terbaru';
            const tbody = document.getElementById('spjTableBody');
            const rows = Array.from(document.querySelectorAll('#spjTableBody .spj-row'));

            if (!tbody || rows.length === 0) {
                hideSpjSkeleton(false);
                return;
            }

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

            rows.forEach(row => {
                tbody.appendChild(row);

                const rowTglYmd = row.getAttribute('data-tanggal-ymd') || '';
                const rowStatus = row.getAttribute('data-status') || '';
                const rowGroup = row.getAttribute('data-status-group') || '';

                const matchTanggal = !tglQuery || rowTglYmd === tglQuery;

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

            const countLabel = document.getElementById('mFilteredCount');
            if (countLabel) countLabel.textContent = visibleCount.toString();

            const hasResults = (visibleCount > 0 || rows.length === 0);
            hideSpjSkeleton(hasResults);
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
            const kegiatan = row.querySelector('.m-kegiatan-sub')?.textContent || row.getAttribute('data-kegiatan');
            const pengaju = row.getAttribute('data-pengaju') || '—';
            const tanggalStr = row.getAttribute('data-tanggal-str') || '—';
            const nilaiStr = row.getAttribute('data-nilai-str') || '—';
            const badgeLabel = row.getAttribute('data-badge-label') || '';
            const badgeClass = row.getAttribute('data-badge-class') || '';
            const badgeIcon = row.getAttribute('data-badge-icon') || '';
            const stage = parseInt(row.getAttribute('data-stage') || '1', 10);
            const status = row.getAttribute('data-status') || '';
            const revisiNote = row.getAttribute('data-revisi-note') || '';

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
            const title5 = document.getElementById('trackTitle5');
            const desc5 = document.getElementById('trackDesc5');
            const time5 = document.getElementById('trackTime5');

            if (status === 'selesai') {
                if (title5) title5.textContent = '5. Pembayaran Selesai & Lunas';
                if (desc5) desc5.textContent = 'Pembayaran tagihan telah berhasil dicairkan dan ditransfer ke rekening penerima.';
                if (time5) time5.textContent = selesaiAt ? 'Lunas: ' + selesaiAt : 'Lunas';
            } else if (status === 'menunggu_otp_kpa') {
                if (title5) title5.textContent = '5. Otorisasi Pembayaran (Menunggu OTP KPA)';
                if (desc5) desc5.textContent = 'Transaksi telah diinput ke BRI QLOLA. Menunggu otorisasi kode OTP dari Kuasa Pengguna Anggaran (KPA).';
                if (time5) time5.textContent = 'Menunggu OTP KPA';
            } else if (status === 'proses_ls') {
                if (title5) title5.textContent = '5. Pembayaran LS (Kliring Bank)';
                if (desc5) desc5.textContent = 'SPM tagihan diproses via mekanisme LS. Menunggu kliring dan transfer langsung dari bank penyalur.';
                if (time5) time5.textContent = 'Kliring Bank LS';
            } else if (status === 'disetujui_ppspm') {
                if (title5) title5.textContent = '5. Pencairan Dana oleh Bendahara';
                if (desc5) desc5.textContent = 'SPM telah diterbitkan oleh PPSPM. Berkas berada di Bendahara untuk persiapan pencairan.';
                if (time5) time5.textContent = 'Siap Diproses Bendahara';
            } else {
                if (title5) title5.textContent = '5. Pencairan Dana';
                if (desc5) desc5.textContent = 'Pembayaran tagihan setelah SPM diterbitkan.';
                if (time5) time5.textContent = 'Menunggu';
            }

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

        // Efek transisi halus skeleton shimmer saat pertama kali halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            const spjCards = document.querySelectorAll('#spjTableBody .spj-row');
            if (spjCards.length > 0) {
                showSpjSkeleton();
                setTimeout(() => {
                    applySpjFilters();
                }, 280);
            }
        });
    </script>

</body>

</html>