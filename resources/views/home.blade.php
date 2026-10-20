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

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="brand">
            <div class="brand-logo">
                @if(file_exists(public_path('images/logo-bps-app.png')))
                    <img src="{{ asset('images/logo-bps-app.png') }}" alt="Logo BPS">
                @else
                    BPS
                @endif
            </div>

            <div>
                <h1>SISTEM INFORMASI PENYELESAIAN TAGIHAN</h1>
                <span>Badan Pusat Statistik</span>
            </div>
        </div>

        <!-- Right Side Nav (Links + Login Button matching Reference) -->
        <div class="navbar-right">
            <nav class="nav-links">
                <a href="#beranda" class="nav-link active">Beranda</a>
                <a href="#alur" class="nav-link">Alur</a>
                <a href="#panduan" class="nav-link">Panduan</a>
            </nav>

            <div class="nav-actions">
                @auth
                    @php
                        $role = auth()->user()->role;
                        $dashboardUrl = url('/' . $role . '/dashboard');
                    @endphp
                    <a href="{{ $dashboardUrl }}" class="login-btn dashboard-badge">
                        Dashboard ({{ strtoupper($role) }})
                    </a>
                @else
                    <a href="{{ route('login') }}" class="login-btn">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </header>


    <!-- HERO SECTION WITH HIGH-TECH GRAPHICS & GLASSMORPHISM -->
    <!-- MAIN SEAMLESS CANVAS (Unified background atmosphere spanning Hero and Workflow) -->
    <main class="page-canvas">

        <!-- Unified background layer that runs seamlessly across both sections -->
        <div class="unified-bg-canvas" aria-hidden="true">
            <!-- Continuous grid mesh -->
            <div class="bg-grid-mesh"></div>

            <!-- Ambient glowing orbs (smooth continuous glow) -->
            <div class="glow-orb glow-cyan-hero"></div>
            <div class="glow-orb glow-orange-hero"></div>
            <div class="glow-orb glow-cyan-wf"></div>
            <div class="glow-orb glow-orange-wf"></div>
            <div class="glow-orb glow-cyan-ft"></div>
            <div class="glow-orb glow-orange-ft"></div>

            <!-- Financial Chart silhouette in Hero background -->
            <svg class="bg-chart-lines" viewBox="0 0 1000 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g opacity="0.14">
                    <rect x="420" y="300" width="14" height="150" rx="3" fill="#0284c7"/>
                    <rect x="442" y="250" width="14" height="200" rx="3" fill="#38bdf8"/>
                    <rect x="464" y="210" width="14" height="240" rx="3" fill="#0284c7"/>
                    <rect x="486" y="270" width="14" height="180" rx="3" fill="#38bdf8"/>
                    <rect x="508" y="180" width="14" height="270" rx="3" fill="#0284c7"/>
                    <rect x="530" y="150" width="14" height="300" rx="3" fill="#38bdf8"/>
                    <rect x="552" y="200" width="14" height="250" rx="3" fill="#0284c7"/>
                    <rect x="574" y="130" width="14" height="320" rx="3" fill="#38bdf8"/>
                    <rect x="596" y="190" width="14" height="260" rx="3" fill="#0284c7"/>
                    <rect x="618" y="240" width="14" height="210" rx="3" fill="#38bdf8"/>
                </g>
                <path d="M380 340 Q 480 300 520 200 T 600 140 T 700 240 T 820 120" stroke="rgba(56, 189, 248, 0.35)" stroke-width="2" stroke-dasharray="4 4" fill="none"/>
            </svg>

            <!-- Crystal Facets in Workflow (Left) -->
            <svg class="wf-facets" viewBox="0 0 500 450" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="facetGrad1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.32"/>
                        <stop offset="100%" stop-color="#818cf8" stop-opacity="0.12"/>
                    </linearGradient>
                    <linearGradient id="facetGrad2" x1="0%" y1="100%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#c084fc" stop-opacity="0.22"/>
                        <stop offset="100%" stop-color="#38bdf8" stop-opacity="0.08"/>
                    </linearGradient>
                    <linearGradient id="facetGrad3" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#ffffff" stop-opacity="0.45"/>
                        <stop offset="100%" stop-color="#38bdf8" stop-opacity="0.18"/>
                    </linearGradient>
                </defs>
                <polygon points="0,50 180,120 70,240" fill="url(#facetGrad1)"/>
                <polygon points="70,240 180,120 220,280" fill="url(#facetGrad2)"/>
                <polygon points="0,50 70,240 0,320" fill="url(#facetGrad3)"/>
                <polygon points="180,120 320,80 220,280" fill="url(#facetGrad1)" opacity="0.6"/>
                <polygon points="220,280 320,80 340,240" fill="url(#facetGrad2)" opacity="0.4"/>
                <line x1="0" y1="50" x2="180" y2="120" stroke="rgba(255,255,255,0.7)" stroke-width="1.5"/>
                <line x1="180" y1="120" x2="70" y2="240" stroke="rgba(255,255,255,0.6)" stroke-width="1.2"/>
                <line x1="70" y1="240" x2="220" y2="280" stroke="rgba(255,255,255,0.6)" stroke-width="1.2"/>
                <line x1="180" y1="120" x2="220" y2="280" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                <line x1="180" y1="120" x2="320" y2="80" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
            </svg>

            <!-- Dot Grid in Workflow & Features -->
            <div class="workflow-dot-grid wf-dots-tr"></div>
            <div class="workflow-dot-grid wf-dots-bl"></div>
            <div class="workflow-dot-grid wf-dots-ft"></div>

            <!-- Flowing Wave Ribbons across both sections -->
            <svg class="bg-wave-ribbons" viewBox="0 0 1440 900" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="ribbonGrad1" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#38bdf8" stop-opacity="0"/>
                        <stop offset="35%" stop-color="#38bdf8" stop-opacity="0.55"/>
                        <stop offset="70%" stop-color="#2563eb" stop-opacity="0.45"/>
                        <stop offset="100%" stop-color="#f97316" stop-opacity="0.35"/>
                    </linearGradient>
                    <linearGradient id="ribbonGrad2" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#0284c7" stop-opacity="0"/>
                        <stop offset="45%" stop-color="#38bdf8" stop-opacity="0.6"/>
                        <stop offset="100%" stop-color="#fb923c" stop-opacity="0.45"/>
                    </linearGradient>
                </defs>
                <path d="M 320 380 C 470 340, 580 240, 730 280 C 880 320, 1030 200, 1180 220 C 1280 230, 1370 160, 1440 140" stroke="url(#ribbonGrad1)" stroke-width="1.6"/>
                <path d="M 350 390 C 500 350, 600 260, 750 290 C 900 320, 1050 210, 1200 240 C 1290 250, 1380 180, 1440 160" stroke="url(#ribbonGrad2)" stroke-width="1.4"/>
                <path d="M 380 400 C 530 360, 620 280, 770 300 C 920 320, 1070 220, 1220 260 C 1300 270, 1390 200, 1440 180" stroke="url(#ribbonGrad1)" stroke-width="1.2"/>
                <path d="M 280 420 C 480 380, 640 480, 850 440 C 1060 400, 1240 280, 1440 310" stroke="url(#ribbonGrad2)" stroke-width="1.2"/>
                <path d="M 120 480 C 340 430, 560 540, 780 500 C 1000 460, 1200 350, 1440 370" stroke="url(#ribbonGrad1)" stroke-width="1"/>
            </svg>

            <!-- Luminous sparkles -->
            <div class="particle-sparkle sparkle-1"></div>
            <div class="particle-sparkle sparkle-2"></div>
            <div class="particle-sparkle sparkle-3"></div>
            <div class="particle-sparkle sparkle-4"></div>
            <div class="particle-sparkle sparkle-5"></div>
            <div class="particle-sparkle wf-sp-1"></div>
            <div class="particle-sparkle wf-sp-2"></div>
            <div class="particle-sparkle wf-sp-3"></div>
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

            <!-- Right Hero Visual (Glassmorphic Neumorphic Card with 3D Tilt & Live Stepper) -->
            <div class="hero-card-wrapper" id="heroCardWrapper">

                <!-- Floating Micro-Badges -->
                <div class="floating-stat-badge float-top-left" id="floatBadge1">
                    <div class="float-icon mint-glow">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                    <div>
                        <strong>100% Tervalidasi</strong>
                        <small>Standar KPPN &amp; BPS</small>
                    </div>
                </div>


                <!-- Interactive 3D Glass Card -->
                <div class="hero-glass-card" id="heroInteractiveCard">

                    <!-- Header -->
                    <div class="glass-card-header">
                        <div>
                            <span class="card-status-label">
                                <span class="live-indicator-dot"></span>
                                LIVE STATUS SPJ
                            </span>
                            <div class="card-spj-id" id="cardSpjCode">SPJ/2026/04/012</div>
                        </div>

                        <div class="live-status-pill status-diproses" id="cardStatusBadge">
                            <span class="badge-icon-warn">!</span>
                            <span id="cardStatusText">Diproses Umum</span>
                        </div>
                    </div>

                    <!-- Stepper Progress Bar (5 Steps) -->
                    <div class="stepper-container" id="heroStepper">

                        <div class="stepper-step step-done" data-step="0" title="Klik untuk simulasi tahap Teknis">
                            <div class="step-circle">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <span class="step-label">Teknis</span>
                        </div>

                        <div class="step-line line-glow" data-line="0"></div>

                        <div class="stepper-step step-active-now" data-step="1" title="Klik untuk simulasi tahap Subbagian Umum">
                            <div class="step-circle">2</div>
                            <span class="step-label">Umum</span>
                        </div>

                        <div class="step-line" data-line="1"></div>

                        <div class="stepper-step" data-step="2" title="Klik untuk simulasi tahap PPK">
                            <div class="step-circle step-pending">3</div>
                            <span class="step-label">PPK</span>
                        </div>

                        <div class="step-line" data-line="2"></div>

                        <div class="stepper-step" data-step="3" title="Klik untuk simulasi tahap PPSPM">
                            <div class="step-circle step-pending">4</div>
                            <span class="step-label">PPSPM</span>
                        </div>

                        <div class="step-line" data-line="3"></div>

                        <div class="stepper-step" data-step="4" title="Klik untuk simulasi tahap Bendahara">
                            <div class="step-circle step-pending">5</div>
                            <span class="step-label">Bendahara</span>
                        </div>

                    </div>

                    <!-- Live Stage Detail Note Box -->
                    <div class="live-detail-box" id="simDetailBox">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2px;">
                            <span style="font-size:10.5px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.4px;" id="simStageRole">Posisi: Subbagian Umum</span>
                            <span style="font-size:10.5px; font-weight:700; color:#0284c7;" id="simStageSla">Estimasi: 1 - 2 Hari</span>
                        </div>
                        <div class="live-detail-note">
                            <i class="bi bi-info-circle-fill"></i>
                            <span id="simStageDesc">Memeriksa kelengkapan administrasi berkas kuitansi, daftar hadir, dan rincian riil kegiatan.</span>
                        </div>
                    </div>

                    <!-- Bottom Info Cards -->
                    <div class="glass-card-info-grid">

                        <div class="info-pill-card">
                            <div class="info-pill-text">
                                <small>Kegiatan</small>
                                <strong id="cardKegiatanText">Survei Pertanian 2026</strong>
                            </div>
                            <div class="info-pill-icon icon-blue">
                                <i class="bi bi-briefcase-fill" style="font-size:15px;"></i>
                            </div>
                        </div>

                        <div class="info-pill-card">
                            <div class="info-pill-text">
                                <small>Nilai SPJ</small>
                                <strong id="cardNominalText">Rp 4.750.000</strong>
                            </div>
                            <div class="info-pill-icon icon-orange">
                                <i class="bi bi-cash-stack" style="font-size:16px;"></i>
                            </div>
                        </div>

                    </div>

                    <!-- Simulation Controls Bar -->
                    <div class="live-controls-bar">
                        <div class="live-cycle-tag">
                            <i class="bi bi-arrow-repeat"></i>
                            <span id="simLiveTag">Simulasi Otomatis Aktif</span>
                        </div>
                        <button type="button" class="live-playback-btn" id="btnToggleSim" title="Jeda atau lanjutkan simulasi siklus SPJ">
                            <i class="bi bi-pause-fill" id="simPlayPauseIcon"></i>
                            <span id="simPlayPauseText">Jeda</span>
                        </button>
                    </div>

                </div>

            </div>

        </section>

        <!-- ALUR 5 TAHAPAN LENGKAP -->
        <section class="workflow" id="alur">

            <div class="section-title">
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
                <div class="flow-card blue">
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
                <div class="flow-card green">
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
                <div class="flow-card orange">
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
                <div class="flow-card purple">
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
                <div class="flow-card emerald">
                    <div class="number">05</div>
                    <div class="icon">
                        <i class="bi bi-cash-coin" style="font-size: 20px;"></i>
                    </div>
                    <h3>Bendahara</h3>
                    <p>Melakukan pembayaran tagihan SPJ dan pengarsipan bukti sah.</p>
                </div>

            </div>

            <!-- DECISION & SOP DOWNLOAD AREA -->
            <div class="decision-area">
                <div class="decision-box">
                    <span class="decision-check-circle">
                        <i class="bi bi-check-lg" style="font-size: 14px;"></i>
                    </span>
                    <strong class="decision-label">Memenuhi Syarat?</strong>
                </div>

                <div class="decision-text">
                    Jika tidak memenuhi syarat, SPJ dikembalikan kepada Teknis untuk diperbaiki.
                </div>

                <a href="{{ route('flowchart.preview') }}" target="_blank" class="btn-download-sop" title="Buka & Unduh Flowchart SOP SPJ Resmi BPS Kolaka Utara">
                    <i class="bi bi-file-earmark-pdf-fill" style="color: #ef4444; font-size: 16px;"></i>
                    <span>Lihat Dokumen SOP Lengkap</span>
                </a>
            </div>

        </section>


        <!-- FEATURES -->
        <section class="features" id="panduan">

            <div class="section-title">
                <span class="section-label">FITUR SISTEM</span>

                <h2 class="section-heading">
                    Pengelolaan SPJ Dalam Satu Sistem
                </h2>
            </div>


            <div class="feature-grid">

                <div class="feature">
                    <div class="feature-icon feat-blue">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h3>Pengajuan SPJ</h3>
                    <p>
                        Pengajuan SPJ dilakukan secara
                        terstruktur melalui sistem.
                    </p>
                </div>

                <div class="feature">
                    <div class="feature-icon feat-green">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3>Pemeriksaan</h3>
                    <p>
                        Setiap dokumen dapat diperiksa
                        sebelum diteruskan.
                    </p>
                </div>

                <div class="feature">
                    <div class="feature-icon feat-amber">
                        <i class="bi bi-bell"></i>
                    </div>
                    <h3>Status Real-time</h3>
                    <p>
                        Pantau posisi SPJ dari pengajuan
                        hingga selesai.
                    </p>
                </div>

                <div class="feature">
                    <div class="feature-icon feat-purple">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <h3>Arsip Digital</h3>
                    <p>
                        Dokumen dan riwayat SPJ tersimpan
                        secara terstruktur.
                    </p>
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
            const floatBadge1 = document.getElementById('floatBadge1');
            const floatBadge2 = document.getElementById('floatBadge2');

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

                    if (floatBadge1) {
                        floatBadge1.style.transform = `translate3d(${(-x * 14).toFixed(1)}px, ${(-y * 14).toFixed(1)}px, 20px)`;
                    }
                    if (floatBadge2) {
                        floatBadge2.style.transform = `translate3d(${(-x * 18).toFixed(1)}px, ${(-y * 18).toFixed(1)}px, 25px)`;
                    }
                });

                cardWrapper.addEventListener('mouseleave', () => {
                    isHovering = false;
                    glassCard.style.transition = 'transform 0.5s ease-out';
                    glassCard.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';

                    if (floatBadge1) {
                        floatBadge1.style.transform = '';
                    }
                    if (floatBadge2) {
                        floatBadge2.style.transform = '';
                    }
                });
            }

            /* ---------------------------------------------
               3. 5-STAGE LIVE INTERACTIVE STEPPER SIMULATION
            --------------------------------------------- */
            const stages = [
                {
                    name: 'Teknis',
                    role: 'Posisi: Tim Teknis Kegiatan',
                    statusClass: 'status-ready',
                    statusText: 'Pengajuan Dibuat',
                    statusIcon: 'bi bi-file-earmark-arrow-up',
                    sla: 'Estimasi: Hari H',
                    desc: 'Menginput rincian SPJ, melampirkan berkas bukti sah, dan menyerahkan ke Subbagian Umum.',
                    kegiatan: 'Survei Pertanian 2026',
                    nominal: 'Rp 4.750.000',
                    wa: 'Notifikasi: SPJ berhasil diajukan',
                    code: 'SPJ/2026/04/012'
                },
                {
                    name: 'Umum',
                    role: 'Posisi: Subbagian Umum',
                    statusClass: 'status-diproses',
                    statusText: 'Verifikasi Berkas',
                    statusIcon: 'bi bi-search',
                    sla: 'Estimasi: 1 - 2 Hari',
                    desc: 'Memeriksa kelengkapan administrasi kuitansi, daftar hadir, dan rincian riil kegiatan.',
                    kegiatan: 'Survei Pertanian 2026',
                    nominal: 'Rp 4.750.000',
                    wa: 'Notifikasi: Sedang diverifikasi Umum',
                    code: 'SPJ/2026/04/012'
                },
                {
                    name: 'PPK',
                    role: 'Posisi: Pejabat Pembuat Komitmen (PPK)',
                    statusClass: 'status-ppk',
                    statusText: 'Disetujui PPK',
                    statusIcon: 'bi bi-patch-check',
                    sla: 'Estimasi: 1 - 2 Hari',
                    desc: 'Pengujian kesesuaian anggaran DIPA/POK, substansi hasil kegiatan, dan persetujuan SPJ.',
                    kegiatan: 'Sensus Ekonomi Lanjutan',
                    nominal: 'Rp 6.200.000',
                    wa: 'Notifikasi: PPK menyetujui SPJ',
                    code: 'SPJ/2026/04/015'
                },
                {
                    name: 'PPSPM',
                    role: 'Posisi: Pejabat Penandatangan SPM (PPSPM)',
                    statusClass: 'status-ppspm',
                    statusText: 'Penerbitan SPM',
                    statusIcon: 'bi bi-award',
                    sla: 'Estimasi: 1 - 2 Hari',
                    desc: 'Pengujian dokumen tagihan, validasi ketersediaan dana, dan penerbitan SPM ke KPPN.',
                    kegiatan: 'Survei Angkatan Kerja',
                    nominal: 'Rp 8.450.000',
                    wa: 'Notifikasi: SPM diterbitkan ke KPPN',
                    code: 'SPJ/2026/04/018'
                },
                {
                    name: 'Bendahara',
                    role: 'Posisi: Bendahara Pengeluaran',
                    statusClass: 'status-selesai',
                    statusText: 'Selesai & Lunas',
                    statusIcon: 'bi bi-check-circle-fill',
                    sla: 'Estimasi: 1 Hari Kerja',
                    desc: 'Pencairan dana SP2D, pembayaran ke pelaksana kegiatan, dan pengarsipan bukti setor sah.',
                    kegiatan: 'Survei Sosial Ekonomi (Susenas)',
                    nominal: 'Rp 12.300.000',
                    wa: 'Notifikasi: Pembayaran SPJ Selesai!',
                    code: 'SPJ/2026/04/020'
                }
            ];

            let currentStep = 1; // start on 'Umum'
            let isAutoPlaying = true;
            let simInterval = null;

            const elSpjCode = document.getElementById('cardSpjCode');
            const elStatusBadge = document.getElementById('cardStatusBadge');
            const elStatusText = document.getElementById('cardStatusText');
            const elStageRole = document.getElementById('simStageRole');
            const elStageSla = document.getElementById('simStageSla');
            const elStageDesc = document.getElementById('simStageDesc');
            const elKegiatanText = document.getElementById('cardKegiatanText');
            const elNominalText = document.getElementById('cardNominalText');
            const elWaNote = document.getElementById('heroWaNote');
            const btnToggleSim = document.getElementById('btnToggleSim');
            const iconPlayPause = document.getElementById('simPlayPauseIcon');
            const textPlayPause = document.getElementById('simPlayPauseText');
            const elLiveTag = document.getElementById('simLiveTag');

            const stepperEl = document.getElementById('heroStepper');
            const stepItems = stepperEl ? stepperEl.querySelectorAll('.stepper-step') : [];
            const stepLines = stepperEl ? stepperEl.querySelectorAll('.step-line') : [];

            const renderStep = (idx) => {
                const s = stages[idx];
                if (!s) return;

                // Update text & info
                if (elSpjCode) elSpjCode.textContent = s.code;
                if (elStageRole) elStageRole.textContent = s.role;
                if (elStageSla) elStageSla.textContent = s.sla;
                if (elStageDesc) elStageDesc.textContent = s.desc;
                if (elKegiatanText) elKegiatanText.textContent = s.kegiatan;
                if (elNominalText) elNominalText.textContent = s.nominal;
                if (elWaNote) elWaNote.textContent = s.wa;

                // Update dynamic badge
                if (elStatusBadge) {
                    elStatusBadge.className = `live-status-pill ${s.statusClass}`;
                    elStatusBadge.innerHTML = `<i class="${s.statusIcon}"></i> <span>${s.statusText}</span>`;
                }

                // Update stepper items
                stepItems.forEach((stepNode, i) => {
                    const circle = stepNode.querySelector('.step-circle');
                    stepNode.classList.remove('step-done', 'step-active-now');

                    if (i < idx) {
                        stepNode.classList.add('step-done');
                        if (circle) {
                            circle.className = 'step-circle';
                            circle.innerHTML = '<i class="bi bi-check-lg"></i>';
                        }
                    } else if (i === idx) {
                        stepNode.classList.add('step-active-now');
                        if (circle) {
                            circle.className = 'step-circle step-glow';
                            circle.innerHTML = (i + 1).toString();
                        }
                    } else {
                        if (circle) {
                            circle.className = 'step-circle step-pending';
                            circle.textContent = (i + 1).toString();
                        }
                    }
                });

                // Update line glow
                stepLines.forEach((lineNode, i) => {
                    if (i < idx) {
                        lineNode.classList.add('line-glow');
                    } else {
                        lineNode.classList.remove('line-glow');
                    }
                });
            };

            const startSimulation = () => {
                if (simInterval) clearInterval(simInterval);
                simInterval = setInterval(() => {
                    if (!isAutoPlaying) return;
                    currentStep = (currentStep + 1) % stages.length;
                    renderStep(currentStep);
                }, 3500);
            };

            // Click on step dots to jump
            stepItems.forEach((stepNode) => {
                stepNode.addEventListener('click', () => {
                    const stepIdx = parseInt(stepNode.getAttribute('data-step'), 10);
                    if (!isNaN(stepIdx)) {
                        currentStep = stepIdx;
                        renderStep(currentStep);
                        // Reset timer so it gives 3.5s from click
                        startSimulation();
                    }
                });
            });

            // Toggle play / pause button
            if (btnToggleSim) {
                btnToggleSim.addEventListener('click', () => {
                    isAutoPlaying = !isAutoPlaying;
                    if (isAutoPlaying) {
                        iconPlayPause.className = 'bi bi-pause-fill';
                        textPlayPause.textContent = 'Jeda';
                        if (elLiveTag) elLiveTag.textContent = 'Simulasi Otomatis Aktif';
                    } else {
                        iconPlayPause.className = 'bi bi-play-fill';
                        textPlayPause.textContent = 'Lanjut';
                        if (elLiveTag) elLiveTag.textContent = 'Simulasi Dijeda';
                    }
                });
            }

            // Pause auto play temporarily while user hovers card to inspect details
            if (glassCard) {
                glassCard.addEventListener('mouseenter', () => {
                    if (simInterval) clearInterval(simInterval);
                });

                glassCard.addEventListener('mouseleave', () => {
                    if (isAutoPlaying) startSimulation();
                });
            }

            // Initialize on start
            renderStep(currentStep);
            startSimulation();
        });
    </script>
</body>

</html>