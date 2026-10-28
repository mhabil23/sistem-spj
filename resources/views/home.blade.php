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
                <a href="#templates" class="nav-link" onclick="openTemplateModal(event)">Template SPJ</a>
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

            <!-- Right Hero Visual (Glassmorphic Neumorphic Card with 3D Tilt & Stepper) -->
            <div class="hero-card-wrapper" id="heroCardWrapper">

                <!-- Interactive 3D Glass Card -->
                <div class="hero-glass-card" id="heroInteractiveCard">

                    <!-- Header -->
                    <div class="glass-card-header">
                        <span class="card-status-label">
                            <span class="live-indicator-dot"></span>
                            LIVE STATUS SPJ
                        </span>

                        <div class="live-status-pill status-ready smooth-crossfade" id="cardStatusBadge">
                            <i class="bi bi-file-earmark-arrow-up"></i>
                            <span id="cardStatusText">Pengajuan Dibuat</span>
                        </div>
                    </div>

                    <!-- Stepper Progress Bar (5 Steps) -->
                    <div class="stepper-container" id="heroStepper">

                        <div class="stepper-step step-active-now" data-step="0" title="Tahap 1: Teknis">
                            <div class="step-circle step-glow">1</div>
                            <span class="step-label">Teknis</span>
                        </div>

                        <div class="step-line" data-line="0"></div>

                        <div class="stepper-step" data-step="1" title="Tahap 2: Subbagian Umum">
                            <div class="step-circle step-pending">2</div>
                            <span class="step-label">Umum</span>
                        </div>

                        <div class="step-line" data-line="1"></div>

                        <div class="stepper-step" data-step="2" title="Tahap 3: PPK">
                            <div class="step-circle step-pending">3</div>
                            <span class="step-label">PPK</span>
                        </div>

                        <div class="step-line" data-line="2"></div>

                        <div class="stepper-step" data-step="3" title="Tahap 4: PPSPM">
                            <div class="step-circle step-pending">4</div>
                            <span class="step-label">PPSPM</span>
                        </div>

                        <div class="step-line" data-line="3"></div>

                        <div class="stepper-step" data-step="4" title="Tahap 5: Bendahara">
                            <div class="step-circle step-pending">5</div>
                            <span class="step-label">Bendahara</span>
                        </div>

                    </div>

                    <!-- Live Stage Detail Note Box -->
                    <div class="live-detail-box smooth-crossfade" id="simDetailBox">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2px;">
                            <span style="font-size:10.5px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.4px;" id="simStageRole">Posisi: Tim Teknis Kegiatan</span>
                            <span style="font-size:10.5px; font-weight:700; color:#0284c7;" id="simStageSla">Estimasi: Hari H</span>
                        </div>
                        <div class="live-detail-note">
                            <i class="bi bi-info-circle-fill"></i>
                            <span id="simStageDesc">Menginput rincian SPJ, melampirkan berkas bukti sah, dan menyerahkan ke Subbagian Umum.</span>
                        </div>
                    </div>

                    <!-- Bottom Info Cards -->
                    <div class="glass-card-info-grid">

                        <div class="info-pill-card">
                            <div class="info-pill-text">
                                <small>Kegiatan</small>
                                <strong id="cardKegiatanText" class="smooth-crossfade">Survei Pertanian 2026</strong>
                            </div>
                            <div class="info-pill-icon icon-blue">
                                <i class="bi bi-briefcase-fill" style="font-size:15px;"></i>
                            </div>
                        </div>

                        <div class="info-pill-card">
                            <div class="info-pill-text">
                                <small>Nilai SPJ</small>
                                <strong id="cardNominalText" class="smooth-crossfade">Rp 4.750.000</strong>
                            </div>
                            <div class="info-pill-icon icon-orange">
                                <i class="bi bi-cash-stack" style="font-size:16px;"></i>
                            </div>
                        </div>

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

                <div class="feature feature-link feature-tpl-trigger" onclick="openTemplateModal()" id="cardTemplateSpj" role="button" tabindex="0" title="Buka & Unduh Katalog Template Berkas SPJ">
                    <div class="feature-icon feat-rose">
                        <i class="bi bi-file-earmark-arrow-down"></i>
                    </div>
                    <div class="feature-title-row">
                        <h3>Template SPJ</h3>
                        <span class="feature-ext-badge"><i class="bi bi-download"></i></span>
                    </div>
                    <p>
                        Format dan template berkas
                        kelengkapan SPJ siap diunduh.
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
               3. 5-STAGE INTERACTIVE STEPPER
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
                    wa: 'Notifikasi: SPJ berhasil diajukan'
                },
                {
                    name: 'Umum',
                    role: 'Posisi: Subbagian Umum',
                    statusClass: 'status-diproses',
                    statusText: 'Verifikasi Berkas',
                    statusIcon: 'bi bi-search',
                    sla: 'Estimasi: 1 - 2 Hari',
                    desc: 'Memeriksa kelengkapan administrasi kuitansi, daftar hadir, dan rincian riil kegiatan.',
                    kegiatan: 'Survei Industri Manufaktur',
                    nominal: 'Rp 5.850.000',
                    wa: 'Notifikasi: Sedang diverifikasi Umum'
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
                    wa: 'Notifikasi: PPK menyetujui SPJ'
                },
                {
                    name: 'PPSPM',
                    role: 'Posisi: Pejabat Penandatangan SPM (PPSPM)',
                    statusClass: 'status-ppspm',
                    statusText: 'Penerbitan SPM',
                    statusIcon: 'bi bi-award',
                    sla: 'Estimasi: 1 - 2 Hari',
                    desc: 'Pengujian dokumen tagihan, validasi ketersediaan dana, dan penerbitan SPM ke KPPN.',
                    kegiatan: 'Survei Angkatan Kerja (Sakernas)',
                    nominal: 'Rp 8.450.000',
                    wa: 'Notifikasi: SPM diterbitkan ke KPPN'
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
                    wa: 'Notifikasi: Pembayaran SPJ Selesai!'
                }
            ];

            let currentStep = 0; // start on 'Teknis'
            let autoStepInterval = null;
            let transitionTimeout = null;

            const elStatusBadge = document.getElementById('cardStatusBadge');
            const elStageRole = document.getElementById('simStageRole');
            const elStageSla = document.getElementById('simStageSla');
            const elStageDesc = document.getElementById('simStageDesc');
            const elKegiatanText = document.getElementById('cardKegiatanText');
            const elNominalText = document.getElementById('cardNominalText');
            const elWaNote = document.getElementById('heroWaNote');
            const elDetailBox = document.getElementById('simDetailBox');

            const stepperEl = document.getElementById('heroStepper');
            const stepItems = stepperEl ? stepperEl.querySelectorAll('.stepper-step') : [];
            const stepLines = stepperEl ? stepperEl.querySelectorAll('.step-line') : [];

            const renderStep = (idx, immediate = false) => {
                const s = stages[idx];
                if (!s) return;

                // 1. Instantly update stepper circles and animated progress lines
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

                // Fluid line glow animation
                stepLines.forEach((lineNode, i) => {
                    if (i < idx) {
                        lineNode.classList.add('line-glow');
                    } else {
                        lineNode.classList.remove('line-glow');
                    }
                });

                const applyContent = () => {
                    if (elStageRole) elStageRole.textContent = s.role;
                    if (elStageSla) elStageSla.textContent = s.sla;
                    if (elStageDesc) elStageDesc.textContent = s.desc;
                    if (elKegiatanText) elKegiatanText.textContent = s.kegiatan;
                    if (elNominalText) elNominalText.textContent = s.nominal;
                    if (elWaNote) elWaNote.textContent = s.wa;

                    if (elStatusBadge) {
                        elStatusBadge.className = `live-status-pill ${s.statusClass} smooth-crossfade`;
                        elStatusBadge.innerHTML = `<i class="${s.statusIcon}"></i> <span>${s.statusText}</span>`;
                    }
                };

                if (immediate) {
                    applyContent();
                    return;
                }

                // 2. Ultra-smooth crossfade for text, badges, and card values
                if (transitionTimeout) clearTimeout(transitionTimeout);

                const fadeTargets = [elStatusBadge, elDetailBox, elKegiatanText, elNominalText].filter(Boolean);

                // Fade out softly
                fadeTargets.forEach(el => el.classList.add('fading-out'));

                transitionTimeout = setTimeout(() => {
                    applyContent();

                    fadeTargets.forEach(el => {
                        el.classList.remove('fading-out');
                        el.classList.add('prep-in');
                    });

                    // Force browser reflow to register starting position
                    void (elDetailBox ? elDetailBox.offsetWidth : 0);

                    requestAnimationFrame(() => {
                        fadeTargets.forEach(el => el.classList.remove('prep-in'));
                    });
                }, 180);
            };

            const startAutoStep = () => {
                if (autoStepInterval) clearInterval(autoStepInterval);
                autoStepInterval = setInterval(() => {
                    currentStep = (currentStep + 1) % stages.length;
                    renderStep(currentStep);
                }, 3200);
            };

            // Pause auto progression when hovered to inspect details
            if (glassCard) {
                glassCard.addEventListener('mouseenter', () => {
                    if (autoStepInterval) clearInterval(autoStepInterval);
                });

                glassCard.addEventListener('mouseleave', () => {
                    startAutoStep();
                });
            }

            // Click on step dots to inspect stage and continue auto progression
            stepItems.forEach((stepNode) => {
                stepNode.addEventListener('click', () => {
                    const stepIdx = parseInt(stepNode.getAttribute('data-step'), 10);
                    if (!isNaN(stepIdx)) {
                        currentStep = stepIdx;
                        renderStep(currentStep);
                        startAutoStep();
                    }
                });
            });

            // Initialize on start immediately without flash
            renderStep(currentStep, true);
            startAutoStep();

            // Auto-open modal if hash is #templates or #template-spj
            if (window.location.hash === '#templates' || window.location.hash === '#template-spj') {
                setTimeout(() => openTemplateModal(), 300);
            }
        });

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
                        <h4>Belum ada format template yang diunggah</h4>
                        <p>Administrator belum menambahkan template format SPJ ke sistem.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Empty Search Filter State -->
                <div class="tpl-empty-box" id="tplNoResults" style="display: none;">
                    <i class="bi bi-search"></i>
                    <h4>Tidak ditemukan template yang cocok</h4>
                    <p>Coba kata kunci lain atau pilih tab kategori 'Semua Format'.</p>
                    <button type="button" class="btn-reset-filter" onclick="resetTemplateFilters()">Tampilkan Semua Format</button>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="tpl-modal-footer">
                <div class="tpl-footer-note">
                    <i class="bi bi-shield-check"></i>
                    <span>Template resmi Badan Pusat Statistik Kabupaten Kolaka Utara &bull; Siap digunakan untuk pelaporan SPJ APBN.</span>
                </div>
                <button type="button" class="btn-close-modal" onclick="closeTemplateModal()">Tutup</button>
            </div>

        </div>
    </div>
</body>

</html>