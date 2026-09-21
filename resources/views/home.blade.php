<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistem SPJ</title>

    @vite(['resources/css/home.css'])
</head>

<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="brand">
            <div class="brand-logo">SPJ</div>

            <div>
                <h1>Sistem SPJ</h1>
                <span>Pengelolaan Surat Pertanggungjawaban</span>
            </div>
        </div>

        <a href="#" class="login-btn">
            Login
        </a>
    </header>


    <!-- HERO -->
    <main>

        <section class="hero">

            <div class="hero-content">

                <span class="badge">
                    SISTEM PENGELOLAAN SPJ
                </span>

                <h2>
                    Proses SPJ Lebih
                    <span>Terstruktur & Transparan</span>
                </h2>

                <p>
                    Sistem untuk mengelola pengajuan, pemeriksaan,
                    persetujuan, pembayaran, hingga pengarsipan
                    Surat Pertanggungjawaban secara terintegrasi.
                </p>

                <div class="hero-buttons">
                    <a href="#" class="btn-primary">
                        Mulai Pengajuan
                    </a>

                    <a href="#alur" class="btn-secondary">
                        Lihat Alur
                    </a>
                </div>

            </div>

            <div class="hero-card">

                <div class="card-header">
                    <div>
                        <small>STATUS SPJ</small>
                        <h3>SPJ-2026-001</h3>
                    </div>

                    <span class="status">
                        Diproses
                    </span>
                </div>

                <div class="mini-progress">

                    <div class="progress-item active">
                        <div class="circle">✓</div>
                        <span>Teknis</span>
                    </div>

                    <div class="line active"></div>

                    <div class="progress-item active">
                        <div class="circle">✓</div>
                        <span>Umum</span>
                    </div>

                    <div class="line"></div>

                    <div class="progress-item">
                        <div class="circle">3</div>
                        <span>PPK</span>
                    </div>

                    <div class="line"></div>

                    <div class="progress-item">
                        <div class="circle">4</div>
                        <span>Bendahara</span>
                    </div>

                </div>

                <div class="card-info">
                    <div>
                        <small>Kegiatan</small>
                        <strong>Perjalanan Dinas</strong>
                    </div>

                    <div>
                        <small>Nilai SPJ</small>
                        <strong>Rp 5.000.000</strong>
                    </div>
                </div>

            </div>

        </section>


        <!-- ALUR -->
        <section class="workflow" id="alur">

            <div class="section-title">
                <span>ALUR PROSES</span>

                <h2>
                    Alur Pengajuan SPJ
                </h2>

                <p>
                    Setiap SPJ diproses melalui tahapan pemeriksaan
                    sebelum pembayaran dan pengarsipan.
                </p>
            </div>


            <div class="flow-container">

                <!-- TEKNIS -->
                <div class="flow-card blue">

                    <div class="number">01</div>

                    <div class="icon">📄</div>

                    <h3>Teknis</h3>

                    <p>
                        Mengajukan dan menyerahkan
                        dokumen SPJ.
                    </p>

                </div>


                <div class="arrow">
                    →
                </div>


                <!-- UMUM -->
                <div class="flow-card green">

                    <div class="number">02</div>

                    <div class="icon">📋</div>

                    <h3>Subbagian Umum</h3>

                    <p>
                        Memeriksa kelengkapan
                        dokumen SPJ.
                    </p>

                </div>


                <div class="arrow">
                    →
                </div>


                <!-- PPK -->
                <div class="flow-card yellow">

                    <div class="number">03</div>

                    <div class="icon">✓</div>

                    <h3>PPK</h3>

                    <p>
                        Melakukan pemeriksaan
                        dan persetujuan SPJ.
                    </p>

                </div>


                <div class="arrow">
                    →
                </div>


                <!-- BENDAHARA -->
                <div class="flow-card purple">

                    <div class="number">04</div>

                    <div class="icon">💰</div>

                    <h3>Bendahara</h3>

                    <p>
                        Melakukan pembayaran
                        dan pengarsipan.
                    </p>

                </div>

            </div>


            <!-- DECISION -->
            <div class="decision-area">

                <div class="decision">
                    <span>✓</span>
                    <strong>Memenuhi Syarat?</strong>
                </div>

                <div class="decision-text">
                    Jika tidak memenuhi syarat,
                    SPJ dikembalikan kepada Teknis
                    untuk diperbaiki.
                </div>

            </div>

        </section>


        <!-- FEATURES -->
        <section class="features">

            <div class="section-title">
                <span>FITUR SISTEM</span>

                <h2>
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
            <strong>Sistem SPJ</strong>

            <p>
                Sistem Pengelolaan Surat Pertanggungjawaban
            </p>
        </div>

        <span>
            © {{ date('Y') }} Sistem SPJ
        </span>

    </footer>

</body>

</html>