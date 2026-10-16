<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistem Informasi Penyelesaian Tagihan | Badan Pusat Statistik</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
                <a href="#" class="nav-link active">Beranda</a>
                <a href="#tentang" class="nav-link">Tentang</a>
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
        <section class="hero">

            <!-- Left Hero Content -->
            <div class="hero-content">

                <span class="hero-badge">
                    SISTEM PENGELOLAAN SPJ
                </span>

                <h1 class="hero-main-title">
                    Kelola SPJ Cerdas <br>
                    <span class="gradient-text">&amp; Transparan</span>
                </h1>

                <p class="hero-description">
                    Sistem untuk mengelola pengajuan, pemeriksaan, persetujuan,
                    pembayaran, hingga pengarsipan Surat Pertanggungjawaban secara
                    terintegrasi di BPS Kabupaten Kolaka Utara.
                </p>

                <div class="hero-buttons">
                    <a href="{{ auth()->check() ? url('/' . auth()->user()->role . '/dashboard') : route('login') }}" class="btn-primary">
                        <span>Mulai Pengajuan</span>
                        <svg class="btn-arrow-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>

                    <a href="#alur" class="btn-secondary">
                        Lihat Alur
                    </a>
                </div>

            </div>

            <!-- Right Hero Visual (Glassmorphic Neumorphic Card matching image) -->
            <div class="hero-card-wrapper">

                <div class="hero-glass-card">

                    <!-- Header -->
                    <div class="glass-card-header">
                        <div>
                            <span class="card-status-label">STATUS SPJ</span>
                            <h3 class="card-spj-id">SPJ-2026-001</h3>
                        </div>

                        <div class="badge-status-diproses">
                            <span class="badge-icon-warn">!</span>
                            <span>Diproses</span>
                        </div>
                    </div>

                    <!-- Stepper Progress Bar -->
                    <div class="stepper-container">

                        <div class="stepper-step step-done">
                            <div class="step-circle">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <span class="step-label">Teknis</span>
                        </div>

                        <div class="step-line line-glow"></div>

                        <div class="stepper-step step-active-now">
                            <div class="step-circle step-glow">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <span class="step-label">Umum</span>
                        </div>

                        <div class="step-line"></div>

                        <div class="stepper-step">
                            <div class="step-circle step-pending">3</div>
                            <span class="step-label">PPK</span>
                        </div>

                        <div class="step-line"></div>

                        <div class="stepper-step">
                            <div class="step-circle step-pending">4</div>
                            <span class="step-label">Bendahara</span>
                        </div>

                    </div>

                    <!-- Bottom Info Cards -->
                    <div class="glass-card-info-grid">

                        <div class="info-pill-card">
                            <div class="info-pill-text">
                                <small>Kegiatan</small>
                                <strong>Perjalanan Dinas</strong>
                            </div>
                            <div class="info-pill-icon icon-blue">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M4 10l8-6 8 6"/>
                                </svg>
                            </div>
                        </div>

                        <div class="info-pill-card">
                            <div class="info-pill-text">
                                <small>Nilai SPJ</small>
                                <strong>Rp 5.000.000</strong>
                            </div>
                            <div class="info-pill-icon icon-orange">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="6" width="20" height="12" rx="2"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M6 12h.01M18 12h.01"/>
                                </svg>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </section>

        <!-- ALUR -->
        <section class="workflow" id="alur">

            <div class="section-title">
                <span class="section-label">ALUR PROSES</span>

                <h2 class="section-heading">
                    Alur Pengajuan SPJ
                </h2>

                <p class="section-desc">
                    Setiap SPJ diproses melalui tahapan pemeriksaan sebelum pembayaran dan pengarsipan.
                </p>
            </div>

            <div class="flow-container">

                <!-- TEKNIS -->
                <div class="flow-card blue">
                    <div class="number">01</div>
                    <div class="icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                    </div>
                    <h3>Teknis</h3>
                    <p>Mengajukan dan menyerahkan dokumen SPJ.</p>
                </div>

                <div class="arrow">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>

                <!-- SUBBAGIAN UMUM -->
                <div class="flow-card green">
                    <div class="number">02</div>
                    <div class="icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            <line x1="9" y1="12" x2="15" y2="12"></line>
                            <line x1="9" y1="16" x2="15" y2="16"></line>
                        </svg>
                    </div>
                    <h3>Subbagian Umum</h3>
                    <p>Memeriksa kelengkapan dokumen SPJ.</p>
                </div>

                <div class="arrow">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>

                <!-- PPK -->
                <div class="flow-card yellow">
                    <div class="number">03</div>
                    <div class="icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <h3>PPK</h3>
                    <p>Melakukan pemeriksaan dan persetujuan SPJ.</p>
                </div>

                <div class="arrow">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>

                <!-- BENDAHARA -->
                <div class="flow-card purple">
                    <div class="number">04</div>
                    <div class="icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9c0-2.5 2.5-4 6-4s6 1.5 6 4c0 1.5-1 3-2.5 4.5 1.5 1.5 2.5 3 2.5 4.5 0 2.5-2.5 4-6 4s-6-1.5-6-4c0-1.5 1-3 2.5-4.5C7 12 6 10.5 6 9z"></path>
                            <circle cx="12" cy="10" r="1.5" fill="#7c3aed"></circle>
                        </svg>
                    </div>
                    <h3>Bendahara</h3>
                    <p>Melakukan pembayaran dan pengarsipan.</p>
                </div>

            </div>

            <!-- DECISION -->
            <div class="decision-area">
                <div class="decision-box">
                    <span class="decision-check-circle">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </span>
                    <strong class="decision-label">Memenuhi Syarat?</strong>
                </div>

                <div class="decision-text">
                    Jika tidak memenuhi syarat, SPJ dikembalikan kepada Teknis untuk diperbaiki.
                </div>
            </div>

        </section>


        <!-- FEATURES -->
        <section class="features">

            <div class="section-title">
                <span class="section-label">FITUR SISTEM</span>

                <h2 class="section-heading">
                    Pengelolaan SPJ Dalam Satu Sistem
                </h2>
            </div>


            <div class="feature-grid">

                <div class="feature">
                    <div class="feature-icon">📑</div>
                    <h3>Pengajuan SPJ</h3>
                    <p>
                        Pengajuan SPJ dilakukan secara
                        terstruktur melalui sistem.
                    </p>
                </div>

                <div class="feature">
                    <div class="feature-icon">🔍</div>
                    <h3>Pemeriksaan</h3>
                    <p>
                        Setiap dokumen dapat diperiksa
                        sebelum diteruskan.
                    </p>
                </div>

                <div class="feature">
                    <div class="feature-icon">🔔</div>
                    <h3>Status Real-time</h3>
                    <p>
                        Pantau posisi SPJ dari pengajuan
                        hingga selesai.
                    </p>
                </div>

                <div class="feature">
                    <div class="feature-icon">🗂️</div>
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

</body>

</html>