<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lupa Kata Sandi | Sistem Informasi Penyelesaian Tagihan - BPS</title>

    <x-favicon />

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    @vite([
        'resources/css/login.css',
        'resources/js/app.js'
    ])
</head>

<body>

    <main class="login-page">

        <!-- ================= BAGIAN KIRI: BRANDING & ALUR SISTEM ================= -->
        <section class="login-info">
            <div class="info-bg-mesh" aria-hidden="true"></div>
            <div class="info-glow-orb glow-top" aria-hidden="true"></div>
            <div class="info-glow-orb glow-bottom" aria-hidden="true"></div>

            <div class="info-content">

                <!-- Header Brand Link -->
                <a href="{{ url('/') }}" class="brand-link" title="Kembali ke Beranda">
                    <div class="brand-logo-box">
                        <img src="{{ asset('images/logo-bps-app.png') }}" alt="Logo BPS">
                    </div>
                    <div class="brand-text">
                        <strong>SISTEM INFORMASI PENYELESAIAN TAGIHAN</strong>
                        <span>Badan Pusat Statistik</span>
                    </div>
                </a>

                <!-- Main Highlight Content -->
                <div class="info-main">
                    <span class="info-badge">
                        <i class="bi bi-shield-lock"></i> PEMULIHAN KEAMANAN AKUN
                    </span>

                    <h1>
                        Lupa Kata Sandi?
                        <span>Reset Mudah Lewat Email.</span>
                    </h1>

                    <p style="color: #cbd5e1; font-size: 14.5px; line-height: 1.6; margin-top: 12px; max-width: 440px;">
                        Jangan khawatir jika Anda lupa kata sandi akun Admin atau Staf. Masukkan email terdaftar Anda dan kami akan mengirimkan tautan verifikasi aman untuk membuat kata sandi baru.
                    </p>

                    <!-- Card Info Pemulihan -->
                    <div style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 18px 22px; margin-top: 24px; max-width: 440px; backdrop-filter: blur(8px);">
                        <div style="display: flex; gap: 14px; align-items: flex-start;">
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(56, 189, 248, 0.2); color: #38bdf8; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                <i class="bi bi-envelope-check"></i>
                            </div>
                            <div>
                                <strong style="color: #ffffff; font-size: 13.5px; display: block; margin-bottom: 4px;">Pengiriman Tautan Instan</strong>
                                <span style="color: #94a3b8; font-size: 12px; line-height: 1.5; display: block;">Tautan reset akan dikirimkan langsung ke inbox email resmi Anda dan berlaku selama 60 menit demi keamanan data.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Informasi -->
                <div class="info-footer">
                    <span><i class="bi bi-buildings"></i> BPS Kolaka Utara</span>
                    <span>•</span>
                    <span>© {{ date('Y') }} Sistem SPJ Terintegrasi</span>
                </div>

            </div>
        </section>


        <!-- ================= BAGIAN KANAN: FORM LUPA PASSWORD ================= -->
        <section class="login-section">

            <div class="login-card-container">

                <!-- Header Brand khusus Mobile View -->
                <div class="mobile-brand-box">
                    <a href="{{ url('/') }}" class="mobile-brand-link">
                        <img src="{{ asset('images/logo-bps-app.png') }}" alt="Logo BPS">
                        <div>
                            <strong>SISTEM INFORMASI SPJ</strong>
                            <span>Badan Pusat Statistik</span>
                        </div>
                    </a>
                </div>

                <!-- Card Form -->
                <div class="login-card">

                    <div class="card-header">
                        <div class="header-badge">
                            <i class="bi bi-key"></i> LUPA KATA SANDI
                        </div>
                        <h2>Reset Kata Sandi</h2>
                        <p>Masukkan alamat email akun Anda. Kami akan mengirimkan tautan reset kata sandi ke email tersebut.</p>
                    </div>

                    <!-- Notifikasi Sukses / Link Terkirim -->
                    @if (session('status'))
                        <div class="alert-box-success">
                            <i class="bi bi-check-circle-fill alert-icon-success"></i>
                            <div class="alert-content">
                                <strong>Tautan Berhasil Dikirim:</strong>
                                <span>{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Notifikasi Error Validasi -->
                    @if (isset($errors) && $errors->any())
                        <div class="alert-box-danger">
                            <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                            <div class="alert-content">
                                <strong>Permintaan Gagal:</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Form Lupa Password -->
                    <form action="{{ route('password.email') }}" method="POST" id="forgotPasswordForm">
                        @csrf

                        <!-- Field Email -->
                        <div class="form-group">
                            <label for="email">Alamat Email Terdaftar</label>
                            <div class="input-container">
                                <span class="input-icon">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="contoh: admin@example.com"
                                    autocomplete="email"
                                    required
                                    autofocus>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn-submit-login">
                            <span>Kirim Link Reset Kata Sandi</span>
                            <i class="bi bi-send-fill"></i>
                        </button>

                    </form>

                    <!-- Tombol Kembali ke Login & Beranda -->
                    <div class="back-home-wrap" style="display: flex; flex-direction: column; gap: 8px; align-items: center; margin-top: 22px;">
                        <a href="{{ route('login') }}" class="link-back-home" style="color: #0284c7; font-weight: 600;">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali ke Halaman Masuk (Login)</span>
                        </a>
                        <a href="{{ url('/') }}" class="link-back-home" style="font-size: 12px; color: #94a3b8;">
                            <span>Ke Halaman Beranda Utama</span>
                        </a>
                    </div>

                    <!-- Catatan Keamanan Bawah -->
                    <div class="security-card-note">
                        <i class="bi bi-shield-check"></i>
                        <span>Proses pemulihan dilindungi enkripsi token SHA-256 sekali pakai.</span>
                    </div>

                </div>

            </div>

        </section>

    </main>

</body>
</html>

