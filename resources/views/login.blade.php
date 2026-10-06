<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Sistem SPJ</title>

    @vite([
    'resources/css/login.css',
    'resources/js/app.js'
    ])
</head>

<body>

    <main class="login-page">

        <!-- BAGIAN KIRI -->
        <section class="login-info">

            <div class="info-content">

                <a href="/" class="brand">
                    <div class="brand-logo">
                        SPJ
                    </div>

                    <div>
                        <strong>Sistem SPJ</strong>
                        <span>Pengelolaan Surat Pertanggungjawaban</span>
                    </div>
                </a>


                <div class="info-main">

                    <span class="info-badge">
                        SISTEM PENGELOLAAN SPJ
                    </span>

                    <h1>
                        Kelola proses SPJ
                        <span>lebih mudah.</span>
                    </h1>

                    <p>
                        Satu sistem untuk mengelola pengajuan,
                        pemeriksaan, persetujuan, pembayaran,
                        hingga pengarsipan SPJ.
                    </p>


                    <div class="workflow-mini">

                        <div class="workflow-item active">
                            <div class="workflow-icon">✓</div>

                            <div>
                                <strong>Teknis</strong>
                                <small>Pengajuan SPJ</small>
                            </div>
                        </div>


                        <div class="workflow-line"></div>


                        <div class="workflow-item active">
                            <div class="workflow-icon">✓</div>

                            <div>
                                <strong>Umum</strong>
                                <small>Pemeriksaan</small>
                            </div>
                        </div>


                        <div class="workflow-line"></div>


                        <div class="workflow-item">
                            <div class="workflow-icon">3</div>

                            <div>
                                <strong>PPK</strong>
                                <small>Persetujuan</small>
                            </div>
                        </div>


                        <div class="workflow-line"></div>


                        <div class="workflow-item">
                            <div class="workflow-icon">4</div>

                            <div>
                                <strong>Bendahara</strong>
                                <small>Pembayaran</small>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="info-footer">
                    <span>© {{ date('Y') }} Sistem SPJ</span>
                    <span>•</span>
                    <span>Terintegrasi & Terstruktur</span>
                </div>

            </div>

        </section>


        <!-- BAGIAN KANAN -->
        <section class="login-section">

            <div class="login-card">

                <div class="mobile-brand">
                    <div class="brand-logo">
                        SPJ
                    </div>
                </div>


                <div class="login-header">

                    <span class="login-label">
                        SELAMAT DATANG
                    </span>

                    <h2>
                        Masuk ke Sistem
                    </h2>

                    <p>
                        Silakan masuk menggunakan akun Anda
                        untuk melanjutkan.
                    </p>

                </div>


                @if ($errors->any())
                    <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-size: 0.875rem;">
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">

                    @csrf

                    <!-- EMAIL -->
                    <div class="form-group">

                        <label for="email">
                            Email
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon">
                                @
                            </span>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Masukkan email"
                                autocomplete="email"
                                required>

                        </div>

                    </div>


                    <!-- PASSWORD -->
                    <div class="form-group">

                        <div class="label-row">

                            <label for="password">
                                Password
                            </label>

                            <a href="#">
                                Lupa password?
                            </a>

                        </div>


                        <div class="input-wrapper">

                            <span class="input-icon">
                                ●
                            </span>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required>

                            <button
                                type="button"
                                class="toggle-password"
                                onclick="togglePassword()">
                                Lihat
                            </button>

                        </div>

                    </div>


                    <!-- REMEMBER -->
                    <div class="remember">

                        <label>
                            <input
                                type="checkbox"
                                name="remember">

                            <span>
                                Ingat saya
                            </span>
                        </label>

                    </div>


                    <!-- BUTTON -->
                    <button
                        type="submit"
                        class="login-submit">
                        Masuk ke Sistem
                        <span>→</span>
                    </button>

                </form>


                <div class="login-note">

                    <span class="security-icon">
                        🔒
                    </span>

                    <p>
                        Pastikan Anda menggunakan akun yang
                        telah terdaftar pada sistem.
                    </p>

                </div>

            </div>

        </section>

    </main>


    <script>
        function togglePassword() {

            const password =
                document.getElementById('password');

            const button =
                document.querySelector('.toggle-password');

            if (password.type === 'password') {

                password.type = 'text';
                button.textContent = 'Sembunyikan';

            } else {

                password.type = 'password';
                button.textContent = 'Lihat';

            }
        }
    </script>

</body>

</html>