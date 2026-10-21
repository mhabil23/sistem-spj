<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Sistem Informasi Penyelesaian Tagihan - BPS</title>

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
                        <i class="bi bi-shield-check"></i> PORTAL RESMI PENGELOLAAN SPJ
                    </span>

                    <h1>
                        Kelola Proses SPJ
                        <span>Lebih Cepat & Terstruktur.</span>
                    </h1>

                    <!-- Animasi Lucu & Interaktif: Si Berkas SPJ & Alur Cepat -->
                    <div class="spj-cute-showcase">
                        <!-- Balon Percakapan Lucu -->
                        <div class="cute-speech-bubble">
                            <span class="bubble-sparkle">✨</span>
                            <span class="bubble-text">Hai! Berkas SPJ siap diproses sampai cair!</span>
                            <span class="bubble-tail"></span>
                        </div>

                        <!-- Panggung Animasi Karakter & Elemen SPJ -->
                        <div class="cute-stage">
                            <!-- Satelit 1: Nota & Kwitansi Mini yang Melayang -->
                            <div class="cute-orbit-item item-nota" title="Nota & Bukti Transaksi">
                                <svg width="34" height="42" viewBox="0 0 34 42" fill="none">
                                    <rect x="2" y="2" width="30" height="38" rx="4" fill="#ffffff" stroke="#38bdf8" stroke-width="2"/>
                                    <line x1="7" y1="9" x2="22" y2="9" stroke="#0284c7" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="7" y1="15" x2="27" y2="15" stroke="#cbd5e1" stroke-width="1.8" stroke-linecap="round"/>
                                    <line x1="7" y1="20" x2="25" y2="20" stroke="#cbd5e1" stroke-width="1.8" stroke-linecap="round"/>
                                    <line x1="7" y1="25" x2="19" y2="25" stroke="#cbd5e1" stroke-width="1.8" stroke-linecap="round"/>
                                    <circle cx="23" cy="31" r="5" fill="#fef08a"/>
                                    <text x="23" y="34" font-size="8" font-weight="bold" fill="#ca8a04" text-anchor="middle">Rp</text>
                                </svg>
                            </div>

                            <!-- Karakter Utama: Si Berkas SPJ Ceria -->
                            <div class="cute-mascot" id="cuteMascot" title="Si Berkas SPJ">
                                <svg width="90" height="106" viewBox="0 0 96 114" fill="none" class="mascot-svg">
                                    <!-- Bayangan di Lantai -->
                                    <ellipse cx="48" cy="110" rx="32" ry="4" fill="rgba(0,0,0,0.22)" class="mascot-shadow" />

                                    <!-- Kumpulan Tubuh Dokumen Berkas -->
                                    <g class="mascot-body-group">
                                        <!-- Kertas Putih Utama -->
                                        <path d="M16 8C16 4.68629 18.6863 2 22 2H66L84 20V100C84 103.314 81.3137 106 78 106H22C18.6863 106 16 103.314 16 100V8Z" fill="#FFFFFF" stroke="#38bdf8" stroke-width="2.5" />
                                        
                                        <!-- Lipatan Sudut Kertas (Dog-ear) -->
                                        <path d="M66 2V18C66 19.1046 66.8954 20 68 20H84L66 2Z" fill="#e0f2fe" stroke="#38bdf8" stroke-width="2" stroke-linejoin="round" />

                                        <!-- Header Dokumen Mini -->
                                        <rect x="25" y="14" width="30" height="4" rx="2" fill="#0284c7" />
                                        <rect x="25" y="21" width="20" height="3" rx="1.5" fill="#94a3b8" />

                                        <!-- Pipi Merona Pink Menggemaskan -->
                                        <ellipse cx="33" cy="58" rx="5" ry="3.5" fill="#fca5a5" opacity="0.85" class="blush" />
                                        <ellipse cx="67" cy="58" rx="5" ry="3.5" fill="#fca5a5" opacity="0.85" class="blush" />

                                        <!-- Mata Kartun Berbinar & Berkedip -->
                                        <g class="mascot-eye eye-left">
                                            <ellipse cx="36" cy="48" rx="5.5" ry="6.5" fill="#0f172a" />
                                            <circle cx="34" cy="46" r="2.2" fill="#FFFFFF" />
                                            <circle cx="38" cy="50" r="1.1" fill="#FFFFFF" />
                                        </g>
                                        <g class="mascot-eye eye-right">
                                            <ellipse cx="64" cy="48" rx="5.5" ry="6.5" fill="#0f172a" />
                                            <circle cx="62" cy="46" r="2.2" fill="#FFFFFF" />
                                            <circle cx="66" cy="50" r="1.1" fill="#FFFFFF" />
                                        </g>

                                        <!-- Senyum Bahagia -->
                                        <path d="M44 58C44 63 56 63 56 58" stroke="#0f172a" stroke-width="2.2" stroke-linecap="round" fill="#f43f5e" class="mascot-smile" />

                                        <!-- Dasi Biru Resmi BPS -->
                                        <path d="M47 67L44 71L48 83L52 83L56 71L53 67Z" fill="#0284c7" stroke="#0369a1" stroke-width="1.2" stroke-linejoin="round" />
                                        <polygon points="46,65 54,65 52,68 48,68" fill="#0369a1" />

                                        <!-- Cap Stempel SPJ ACC di Badan Kertas -->
                                        <g class="body-stamp">
                                            <rect x="24" y="86" width="50" height="13" rx="3" fill="#ecfdf5" stroke="#10b981" stroke-width="1.5" stroke-dasharray="2 1" />
                                            <text x="49" y="95.5" font-size="7.5" font-weight="800" fill="#059669" text-anchor="middle" letter-spacing="0.8">SPJ ACC ✓</text>
                                        </g>

                                        <!-- Tangan Kartun Mungil yang Melambai -->
                                        <ellipse cx="14" cy="66" rx="4.5" ry="6.5" fill="#ffffff" stroke="#38bdf8" stroke-width="2" class="mascot-hand hand-left" />
                                        <ellipse cx="86" cy="66" rx="4.5" ry="6.5" fill="#ffffff" stroke="#38bdf8" stroke-width="2" class="mascot-hand hand-right" />
                                    </g>
                                </svg>
                            </div>

                            <!-- Satelit 2: Stempel Hijau ACC yang Melompat -->
                            <div class="cute-orbit-item item-stamp" title="Stempel Disetujui">
                                <div class="stamp-badge">
                                    <i class="bi bi-patch-check-fill"></i>
                                    <span>ACC</span>
                                </div>
                            </div>

                            <!-- Satelit 3: Koin / Dana Berputar Ceria -->
                            <div class="cute-orbit-item item-coin" title="Pencairan Dana">
                                <div class="coin-disc">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                            </div>

                            <!-- Bintang & Kilauan Mengambang -->
                            <div class="sparkle sparkle-1">✨</div>
                            <div class="sparkle sparkle-2">⭐</div>
                            <div class="sparkle sparkle-3">✨</div>
                        </div>

                        <!-- Mini Status Alur Ceria -->
                        <div class="cute-capsule-bar">
                            <span class="capsule-chip"><i class="bi bi-file-earmark-check"></i> Siap</span>
                            <i class="bi bi-chevron-right capsule-arrow"></i>
                            <span class="capsule-chip"><i class="bi bi-search"></i> Diverifikasi</span>
                            <i class="bi bi-chevron-right capsule-arrow"></i>
                            <span class="capsule-chip"><i class="bi bi-patch-check"></i> ACC PPK</span>
                            <i class="bi bi-chevron-right capsule-arrow"></i>
                            <span class="capsule-chip success"><i class="bi bi-wallet2"></i> Cair! 🎉</span>
                        </div>
                    </div>

                    <!-- Workflow Alur Mini -->
                    <div class="workflow-stepper">
                        <div class="workflow-step active">
                            <div class="step-num"><i class="bi bi-file-earmark-text"></i></div>
                            <div class="step-desc">
                                <strong>1. Tim Teknis</strong>
                                <small>Pengajuan SPJ & Dokumen</small>
                            </div>
                        </div>

                        <div class="step-connector"></div>

                        <div class="workflow-step">
                            <div class="step-num"><i class="bi bi-clipboard-check"></i></div>
                            <div class="step-desc">
                                <strong>2. Bagian Umum</strong>
                                <small>Verifikasi Administrasi</small>
                            </div>
                        </div>

                        <div class="step-connector"></div>

                        <div class="workflow-step">
                            <div class="step-num"><i class="bi bi-check2-circle"></i></div>
                            <div class="step-desc">
                                <strong>3. PPK & PPSPM</strong>
                                <small>Persetujuan & Disposisi</small>
                            </div>
                        </div>

                        <div class="step-connector"></div>

                        <div class="workflow-step">
                            <div class="step-num"><i class="bi bi-cash-stack"></i></div>
                            <div class="step-desc">
                                <strong>4. Bendahara</strong>
                                <small>Pembayaran & Bukti Transfer</small>
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


        <!-- ================= BAGIAN KANAN: FORM LOGIN ================= -->
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
                            <i class="bi bi-box-arrow-in-right"></i> MASUK KE AKUN
                        </div>
                        <h2>Selamat Datang</h2>
                        <p>Silakan masukkan email dan kata sandi yang terdaftar untuk mengakses panel sistem.</p>
                    </div>

                    <!-- Notifikasi Error Validasi -->
                    @if (isset($errors) && $errors->any())
                        <div class="alert-box-danger">
                            <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                            <div class="alert-content">
                                <strong>Gagal Masuk:</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Form Login -->
                    <form action="{{ route('login') }}" method="POST" id="loginForm">
                        @csrf

                        <!-- Field Email -->
                        <div class="form-group">
                            <label for="email">Alamat Email / ID Pengguna</label>
                            <div class="input-container">
                                <span class="input-icon">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="contoh: teknis@example.com"
                                    autocomplete="email"
                                    required
                                    autofocus>
                            </div>
                        </div>

                        <!-- Field Password -->
                        <div class="form-group">
                            <div class="label-wrapper">
                                <label for="password">Kata Sandi</label>
                            </div>
                            <div class="input-container">
                                <span class="input-icon">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Masukkan kata sandi akun Anda"
                                    autocomplete="current-password"
                                    required>
                                <button
                                    type="button"
                                    class="btn-toggle-password"
                                    onclick="togglePassword()"
                                    title="Lihat / Sembunyikan Kata Sandi"
                                    aria-label="Toggle password visibility">
                                    <i class="bi bi-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Opsi Remember & Kembali -->
                        <div class="form-options">
                            <label class="remember-checkbox">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span>Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn-submit-login">
                            <span>Masuk ke Sistem</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>

                    </form>

                    <!-- Tombol Kembali ke Beranda -->
                    <div class="back-home-wrap">
                        <a href="{{ url('/') }}" class="link-back-home">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali ke Halaman Beranda</span>
                        </a>
                    </div>

                    <!-- Catatan Keamanan Bawah -->
                    <div class="security-card-note">
                        <i class="bi bi-shield-lock-fill"></i>
                        <p>Koneksi terenkripsi aman. Pastikan Anda menjaga kerahasiaan kata sandi akun SPJ Anda.</p>
                    </div>

                </div>

            </div>

        </section>

    </main>

    <!-- JavaScript Toggle Password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            }
        }
    </script>

</body>

</html>