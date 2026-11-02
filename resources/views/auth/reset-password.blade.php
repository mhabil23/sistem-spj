<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Buat Kata Sandi Baru | Sistem Informasi Penyelesaian Tagihan - BPS</title>

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
                        <i class="bi bi-shield-check"></i> PEMULIHAN KEAMANAN AKUN
                    </span>

                    <h1>
                        Langkah Terakhir!
                        <span>Buat Kata Sandi Baru Anda.</span>
                    </h1>

                    <p style="color: #cbd5e1; font-size: 14.5px; line-height: 1.6; margin-top: 12px; max-width: 440px;">
                        Gunakan kombinasi kata sandi yang aman dan mudah Anda ingat agar dapat login kembali dengan lancar.
                    </p>

                    <!-- Tips Keamanan -->
                    <div style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 18px 22px; margin-top: 24px; max-width: 440px; backdrop-filter: blur(8px);">
                        <strong style="color: #ffffff; font-size: 13.5px; display: block; margin-bottom: 8px;">
                            <i class="bi bi-lightbulb" style="color: #facc15;"></i> Panduan Kata Sandi Kuat:
                        </strong>
                        <ul style="color: #cbd5e1; font-size: 12px; line-height: 1.6; margin: 0; padding-left: 18px;">
                            <li>Minimal 6 karakter atau lebih</li>
                            <li>Kombinasikan huruf besar, huruf kecil, dan angka</li>
                            <li>Jangan gunakan kata sandi yang sama dengan akun lain</li>
                        </ul>
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


        <!-- ================= BAGIAN KANAN: FORM BUAT PASSWORD BARU ================= -->
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
                            <i class="bi bi-shield-lock"></i> KATA SANDI BARU
                        </div>
                        <h2>Perbarui Kata Sandi</h2>
                        <p>Masukkan kata sandi baru untuk akun Anda, lalu konfirmasikan sekali lagi.</p>
                    </div>

                    <!-- Notifikasi Error Validasi -->
                    @if (isset($errors) && $errors->any())
                        <div class="alert-box-danger">
                            <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                            <div class="alert-content">
                                <strong>Pembaruan Gagal:</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Form Reset Password -->
                    <form action="{{ route('password.update') }}" method="POST" id="resetPasswordForm">
                        @csrf

                        <!-- Token Hidden Field -->
                        <input type="hidden" name="token" value="{{ $token }}">

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
                                    value="{{ old('email', $email) }}"
                                    placeholder="contoh: admin@example.com"
                                    autocomplete="email"
                                    required>
                            </div>
                        </div>

                        <!-- Field Password Baru -->
                        <div class="form-group">
                            <label for="password">Kata Sandi Baru</label>
                            <div class="input-container">
                                <span class="input-icon">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Masukkan kata sandi baru (min. 6 karakter)"
                                    autocomplete="new-password"
                                    required
                                    autofocus>
                                <button
                                    type="button"
                                    class="btn-toggle-password"
                                    onclick="togglePasswordVisibility('password', 'passwordIcon')"
                                    title="Lihat / Sembunyikan Kata Sandi"
                                    aria-label="Toggle password visibility">
                                    <i class="bi bi-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Field Konfirmasi Password Baru -->
                        <div class="form-group">
                            <label for="password_confirmation">Ulangi Kata Sandi Baru</label>
                            <div class="input-container">
                                <span class="input-icon">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Ketik ulang kata sandi baru di atas"
                                    autocomplete="new-password"
                                    required>
                                <button
                                    type="button"
                                    class="btn-toggle-password"
                                    onclick="togglePasswordVisibility('password_confirmation', 'confirmIcon')"
                                    title="Lihat / Sembunyikan Kata Sandi"
                                    aria-label="Toggle password visibility">
                                    <i class="bi bi-eye" id="confirmIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn-submit-login">
                            <span>Simpan & Perbarui Kata Sandi</span>
                            <i class="bi bi-check2-circle"></i>
                        </button>

                    </form>

                    <!-- Tombol Kembali ke Login -->
                    <div class="back-home-wrap">
                        <a href="{{ route('login') }}" class="link-back-home">
                            <i class="bi bi-arrow-left"></i>
                            <span>Batal & Kembali ke Halaman Masuk</span>
                        </a>
                    </div>

                    <!-- Catatan Keamanan Bawah -->
                    <div class="security-card-note">
                        <i class="bi bi-shield-check"></i>
                        <span>Kata sandi Anda akan langsung dienkripsi menggunakan algoritma Bcrypt yang aman.</span>
                    </div>

                </div>

            </div>

        </section>

    </main>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>

</body>
</html>

