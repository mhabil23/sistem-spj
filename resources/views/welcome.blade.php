<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen SPJ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }
        .navbar {
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0369a1;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-links a {
            text-decoration: none;
            color: #0369a1;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .nav-links a.login-btn {
            background-color: #0284c7;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.4);
        }
        .nav-links a.login-btn:hover {
            background-color: #0369a1;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px -1px rgba(2, 132, 199, 0.5);
        }
        .nav-links a.dash-btn {
            background-color: #10b981;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.4);
        }
        .nav-links a.dash-btn:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px -1px rgba(16, 185, 129, 0.5);
        }
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
        }
        .hero h1 {
            font-size: 3.5rem;
            color: #0f172a;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        .hero p {
            font-size: 1.25rem;
            color: #475569;
            max-width: 600px;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }
        .hero-img {
            max-width: 100%;
            height: auto;
            width: 600px;
            margin-top: 2rem;
        }
        .features {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 3rem;
        }
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: left;
        }
        .feature-card h3 {
            color: #0f172a;
            margin-top: 0;
        }
        .feature-card p {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            Sistem SPJ
        </a>
        <div class="nav-links">
            @auth
                @php
                    $role = auth()->user()->role;
                    $dashboardUrl = url('/' . $role . '/dashboard');
                @endphp
                <a href="{{ $dashboardUrl }}" class="dash-btn">Masuk ke Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="login-btn">Log in</a>
            @endauth
        </div>
    </nav>

    <main class="hero">
        <h1>Digitalisasi Tata Kelola<br><span style="color: #0284c7;">Surat Pertanggungjawaban</span></h1>
        <p>Sistem manajemen SPJ terpadu yang mempermudah alur pengajuan, verifikasi, dan pencairan dana mulai dari Staf Teknis hingga Bendahara.</p>
        
        <div class="features">
            <div class="feature-card">
                <h3>🚀 Cepat & Efisien</h3>
                <p>Alur birokrasi dipersingkat melalui verifikasi digital berjenjang yang aman dan transparan.</p>
            </div>
            <div class="feature-card">
                <h3>🔒 Aman & Terekam</h3>
                <p>Semua riwayat pengajuan, persetujuan, dan catatan revisi tersimpan rapi dan dapat dipertanggungjawabkan.</p>
            </div>
            <div class="feature-card">
                <h3>📊 Laporan Terpusat</h3>
                <p>Ekspor laporan dalam format PDF dan Excel untuk keperluan audit dan arsip dengan satu kali klik.</p>
            </div>
        </div>
    </main>
</body>
</html>
