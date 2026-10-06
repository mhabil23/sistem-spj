<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen SPJ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #121312; /* Very dark, almost black */
            --bg-card: #1c1d1c; /* Slightly lighter for cards */
            --bg-card-hover: #262726;
            --accent: #ffb703; /* Yellow/Orange accent */
            --accent-hover: #fb8500;
            --text-main: #f8f9fa;
            --text-muted: #adb5bd;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* NAVBAR */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 5%;
            background: transparent;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo-icon {
            color: var(--accent);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none; /* Hide on mobile for simplicity */
            }
            .hero {
                grid-template-columns: 1fr !important;
                padding-top: 8rem !important;
            }
            .features-grid {
                grid-template-columns: 1fr !important;
            }
            .split-section {
                grid-template-columns: 1fr !important;
            }
            .footer-grid {
                grid-template-columns: 1fr !important;
            }
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--accent);
        }

        .nav-actions .btn {
            background-color: var(--accent);
            color: #000;
            font-weight: 700;
            padding: 0.75rem 1.75rem;
            border-radius: 2rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .nav-actions .btn:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }

        /* HERO SECTION */
        .hero {
            padding: 12rem 5% 6rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            min-height: 80vh;
        }

        .hero-content h1 {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            color: var(--text-main);
        }

        .hero-content h1 span {
            color: var(--accent);
        }

        .hero-content p {
            font-size: 1.1rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2.5rem;
            max-width: 90%;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            background-color: var(--accent);
            color: #000;
            font-weight: 700;
            padding: 1rem 2.5rem;
            border-radius: 2rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--text-main);
            font-weight: 600;
            padding: 1rem 2.5rem;
            border-radius: 2rem;
            text-decoration: none;
            border: 1px solid var(--text-muted);
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-outline:hover {
            border-color: var(--text-main);
            background-color: rgba(255,255,255,0.05);
        }

        .hero-image {
            width: 100%;
            height: 500px;
            border-radius: 2rem;
            object-fit: cover;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }

        /* SECTION: SERVICES / FEATURES */
        .section {
            padding: 6rem 5%;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-header p {
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .feature-card {
            background-color: var(--bg-card);
            border-radius: 1.5rem;
            overflow: hidden;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .feature-content {
            padding: 2rem;
            flex: 1;
        }

        .feature-content h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .feature-content p {
            color: var(--text-muted);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .feature-card.highlight {
            background-color: var(--accent);
            color: #000;
        }

        .feature-card.highlight .feature-content h3 {
            color: #000;
        }

        .feature-card.highlight .feature-content p {
            color: rgba(0,0,0,0.8);
        }

        .feature-card.highlight .icon-circle {
            width: 50px;
            height: 50px;
            background: #000;
            color: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        /* SPLIT SECTION */
        .split-section {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 4rem;
            align-items: center;
        }

        .split-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .split-content p {
            color: var(--text-muted);
            line-height: 1.8;
            margin-bottom: 2rem;
            font-size: 1.05rem;
        }

        .split-image img {
            width: 100%;
            border-radius: 1.5rem;
            object-fit: cover;
            height: 400px;
        }

        /* FOOTER */
        .footer {
            background-color: #0a0a0a;
            padding: 4rem 5% 2rem;
            margin-top: 4rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 4rem;
            margin-bottom: 4rem;
        }

        .footer-about p {
            color: var(--text-muted);
            line-height: 1.6;
            margin-top: 1rem;
            font-size: 0.9rem;
            max-width: 300px;
        }

        .footer-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-main);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--accent);
        }

        .footer-bottom {
            border-top: 1px solid #222;
            padding-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="/" class="logo">
            <svg class="logo-icon" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
            Sistem SPJ
        </a>
        <div class="nav-links">
            <a href="#">Beranda</a>
            <a href="#">Fitur</a>
            <a href="#">Alur Kerja</a>
            <a href="#">Kontak</a>
        </div>
        <div class="nav-actions">
            @auth
                @php
                    $role = auth()->user()->role;
                    $dashboardUrl = url('/' . $role . '/dashboard');
                @endphp
                <a href="{{ $dashboardUrl }}" class="btn">Dashboard Saya</a>
            @else
                <a href="{{ route('login') }}" class="btn">Masuk / Login</a>
            @endauth
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-content">
            <h1>Digitalisasi Tata Kelola <span>SPJ</span> Elegan</h1>
            <p>Tingkatkan efisiensi birokrasi dan transparansi keuangan dengan sistem digital yang mengotomatisasi pengajuan, verifikasi, dan pencairan dana secara real-time.</p>
            <div class="hero-buttons">
                @auth
                    <a href="{{ $dashboardUrl }}" class="btn-primary">Mulai Bekerja</a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">Mulai Sekarang</a>
                @endauth
                <a href="#" class="btn-outline">Pelajari Alur</a>
            </div>
        </div>
        <div class="hero-image-wrapper">
            <img src="https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&q=80&w=1000" alt="Office Management" class="hero-image">
        </div>
    </section>

    <!-- FEATURES (SERVICES) -->
    <section class="section">
        <div class="section-header">
            <h2>Keunggulan Tata Kelola Digital</h2>
            <p>Platform didesain khusus untuk memenuhi standar operasional administrasi SPJ yang ketat dengan pengalaman antarmuka yang sangat elegan dan responsif.</p>
        </div>
        
        <div class="features-grid">
            <!-- Card 1 -->
            <div class="feature-card">
                <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=800" alt="Tracking">
                <div class="feature-content">
                    <h3>Tracking Real-time</h3>
                    <p>Pantau posisi dan status berkas SPJ Anda secara langsung. Mulai dari pengajuan Staf Teknis, validasi Bagian Umum, hingga Bendahara.</p>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="feature-card">
                <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&q=80&w=800" alt="Archive">
                <div class="feature-content">
                    <h3>Arsip Digital Aman</h3>
                    <p>Semua dokumen pendukung dienkripsi dan disimpan secara terpusat di cloud. Sangat mudah dicari kapanpun saat pelaksanaan audit.</p>
                </div>
            </div>
            
            <!-- Card 3 (Highlight matching the screenshot's yellow card) -->
            <div class="feature-card highlight">
                <div class="feature-content" style="display: flex; flex-direction: column; justify-content: center; height: 100%;">
                    <div class="icon-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <h3 style="font-size: 2rem; margin-bottom: 1.5rem;">Akurasi Tinggi</h3>
                    <p style="font-size: 1.1rem; line-height: 1.7;">Meminimalisir kesalahan perhitungan nominal dan redaksional uraian. Sistem otomatis memastikan kelengkapan dokumen sesuai standar.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SPLIT SECTION (Will in your hom) -->
    <section class="section">
        <div class="split-section">
            <div class="split-content">
                <h2>Birokrasi yang <span style="color: var(--accent);">Transparan</span> & Cepat</h2>
                <p>Setiap perubahan status atau revisi berkas disertai dengan catatan komprehensif dari verifikator (Umum, PPK, atau Bendahara). Staf Teknis akan langsung menerima umpan balik untuk segera diperbaiki tanpa perlu bolak-balik tatap muka.</p>
                <p>Dengan adanya <strong>SLA (Service Level Agreement)</strong>, setiap antrean terpantau durasi prosesnya secara ketat, memastikan dana operasional kegiatan dapat dicairkan tepat waktu dan sesuai target.</p>
                <a href="#" class="btn-outline" style="margin-top: 1rem;">Lihat Panduan Sistem</a>
            </div>
            <div class="split-image">
                <img src="https://images.unsplash.com/photo-1573164713988-8665fc963095?auto=format&fit=crop&q=80&w=1000" alt="Transparent bureaucracy">
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-about">
                <a href="/" class="logo">
                    <svg class="logo-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                    Sistem SPJ
                </a>
                <p>Membawa inovasi ke dalam manajemen birokrasi pemerintahan dengan digitalisasi yang sangat aman, efisien, dan modern.</p>
            </div>
            <div>
                <h4 class="footer-title">Navigasi</h4>
                <ul class="footer-links">
                    <li><a href="#">Beranda</a></li>
                    <li><a href="#">Fitur Sistem</a></li>
                    <li><a href="#">Alur Verifikasi</a></li>
                    <li><a href="#">Bantuan (FAQ)</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Legal</h4>
                <ul class="footer-links">
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat Ketentuan</a></li>
                    <li><a href="#">SLA Standar</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Hubungi Kami</h4>
                <ul class="footer-links">
                    <li><a href="#">support@sistemspj.id</a></li>
                    <li><a href="#">+62 811 2233 4455</a></li>
                    <li><a href="#">Gedung Keuangan lt.4</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Sistem Manajemen SPJ. Hak Cipta Dilindungi.</p>
            <div style="display: flex; gap: 1rem;">
                <a href="#" style="color: var(--text-muted);"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
                <a href="#" style="color: var(--text-muted);"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg></a>
                <a href="#" style="color: var(--text-muted);"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
            </div>
        </div>
    </footer>
</body>
</html>
