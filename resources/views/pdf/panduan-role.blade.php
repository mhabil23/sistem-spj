<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panduan Hak Akses & Tugas Role - Sistem SPJ Digital BPS Kolaka Utara</title>
    <x-favicon />
    <style>
        @page {
            margin: 10mm 12mm 12mm 12mm;
            size: A4 portrait;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            line-height: 1.38;
            color: #1e293b;
            background: #ffffff;
        }

        /* HEADER KOP BPS */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2.5px solid #1e4253;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo {
            width: 150px;
        }

        .kop-text {
            text-align: right;
        }

        .kop-text h1 {
            font-size: 11pt;
            font-weight: bold;
            color: #1e4253;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .kop-text h2 {
            font-size: 8.5pt;
            font-weight: 600;
            color: #475569;
            margin-bottom: 1px;
        }

        .kop-text p {
            font-size: 7pt;
            color: #64748b;
        }

        /* TITLE BANNER */
        .doc-title-box {
            background-color: #1e4253;
            color: #ffffff;
            text-align: center;
            padding: 6px 10px;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .doc-title-box h3 {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doc-title-box p {
            font-size: 7.2pt;
            opacity: 0.92;
            margin-top: 2px;
        }

        /* SECTION HEADINGS */
        .section-heading {
            font-size: 8.5pt;
            font-weight: bold;
            color: #1e4253;
            border-left: 3.5px solid #1e4253;
            padding-left: 6px;
            margin: 8px 0 5px 0;
            text-transform: uppercase;
        }

        .role-title-box {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #0284c7;
            padding: 5px 9px;
            border-radius: 4px;
            margin-top: 6px;
            margin-bottom: 5px;
        }

        .role-title-box.teknis { border-left-color: #0284c7; background: #f0f9ff; }
        .role-title-box.umum { border-left-color: #2563eb; background: #eff6ff; }
        .role-title-box.bendahara { border-left-color: #d97706; background: #fffbeb; }
        .role-title-box.admin { border-left-color: #dc2626; background: #fef2f2; }

        .role-title-box h4 {
            font-size: 8.8pt;
            font-weight: bold;
            color: #0f172a;
        }

        .role-title-box p {
            font-size: 7pt;
            color: #475569;
            margin-top: 1px;
        }

        /* TABLES */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 7px;
        }

        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 3.5px 5.5px;
            vertical-align: top;
            font-size: 7.3pt;
        }

        .data-table th {
            background-color: #f8fafc;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            font-size: 7pt;
            text-transform: uppercase;
        }

        .sub-table {
            width: 100%;
            border-collapse: collapse;
            margin: 3px 0 5px 0;
        }

        .sub-table td {
            padding: 3px 5px;
            font-size: 7.3pt;
            vertical-align: top;
            border-bottom: 1px dashed #e2e8f0;
        }

        .sub-table td.col-label {
            width: 130px;
            font-weight: bold;
            color: #334155;
        }

        /* BADGES */
        .badge {
            display: inline-block;
            padding: 1px 4.5px;
            border-radius: 3px;
            font-size: 6.6pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-teknis { background-color: #e0f2fe; color: #0369a1; }
        .badge-umum { background-color: #dbeafe; color: #1d4ed8; }
        .badge-bendahara { background-color: #fef3c7; color: #b45309; }
        .badge-admin { background-color: #fee2e2; color: #b91c1c; }
        .badge-yes { background-color: #dcfce7; color: #15803d; font-weight: bold; }
        .badge-no { background-color: #fee2e2; color: #991b1b; }

        /* LISTS */
        .task-list {
            list-style: none;
            padding-left: 0;
            margin: 2px 0 4px 0;
        }

        .task-list li {
            position: relative;
            padding-left: 13px;
            margin-bottom: 2.5px;
            font-size: 7.3pt;
            line-height: 1.34;
        }

        .task-list li::before {
            content: "•";
            position: absolute;
            left: 2px;
            color: #1e4253;
            font-size: 8.5pt;
            font-weight: bold;
        }

        .step-num {
            display: inline-block;
            background: #1e4253;
            color: #ffffff;
            width: 14px;
            height: 14px;
            line-height: 14px;
            text-align: center;
            border-radius: 50%;
            font-size: 6.5pt;
            font-weight: bold;
            margin-right: 3px;
        }

        .highlight-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px 7px;
            margin: 4px 0;
            font-size: 7.2pt;
        }

        .alert-box {
            border-radius: 4px;
            padding: 4px 7px;
            margin: 3px 0 5px 0;
            font-size: 7pt;
            line-height: 1.32;
        }

        .alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-warning {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
        }

        .alert-info {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
        }

        .page-break {
            page-break-after: always;
        }

        /* FOOTER KEDINASAN RESMI */
        .official-footer {
            margin-top: 14px;
            padding-top: 6px;
            border-top: 1.5px solid #cbd5e1;
            text-align: center;
            font-size: 6.8pt;
            color: #64748b;
        }

        .official-footer strong {
            color: #1e4253;
        }
    </style>
</head>
<body>

    <!-- ======================================================== -->
    <!-- HALAMAN 1: KOP, RINGKASAN & ROLE TEKNIS                  -->
    <!-- ======================================================== -->
    <table class="kop-table">
        <tr>
            <td style="width: 45%;">
                @php
                    $logoPath = public_path('images/logo-bps.png');
                    $logoSrc = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
                @endphp
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" class="kop-logo" alt="Logo BPS">
                @else
                    <strong style="font-size: 11pt; color: #1e4253;">BADAN PUSAT STATISTIK</strong>
                @endif
            </td>
            <td class="kop-text" style="width: 55%;">
                <h1>Badan Pusat Statistik</h1>
                <h2>Kabupaten Kolaka Utara</h2>
                <p>Jl. Trans Sulawesi Kompleks Perkantoran Pemda Kolaka Utara, Lasusua</p>
                <p>Email: bps7408@bps.go.id | Website: kolakautarakab.bps.go.id</p>
            </td>
        </tr>
    </table>

    <div class="doc-title-box">
        <h3>PANDUAN HAK AKSES, WEWENANG & TUGAS ROLE SISTEM SPJ DIGITAL</h3>
        <p>Pedoman Operasional Antarmuka & Tanggung Jawab Pengguna - BPS Kabupaten Kolaka Utara</p>
    </div>

    <div class="section-heading">I. Gambaran Ringkas Alur Kerja Sistem (Workflow SPJ)</div>
    <p style="text-align: justify; margin-bottom: 5px; font-size: 7.3pt;">
        Sistem Informasi SPJ BPS Kabupaten Kolaka Utara membagi wewenang kerja pengguna ke dalam 4 peran utama secara terstruktur (*separation of duties*). Setiap berkas pertanggungjawaban keuangan diproses secara bertahap dari pengusul hingga pencairan dana dengan rincian peran sebagai berikut:
    </p>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 16%;">Role Pengguna</th>
                <th style="width: 20%;">Fokus di Web</th>
                <th style="width: 44%;">Inti Tugas Operasional</th>
                <th style="width: 20%;">Keluaran (Output)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="badge badge-teknis">1. Staf Teknis</span></td>
                <td><strong>Pengajuan & Berkas</strong></td>
                <td>Mengisi data kegiatan, checklist berkas fisik, wajib cetak lembar SPJ sebelum ajukan, upload scan bukti, dan menyerahkan berkas fisik.</td>
                <td>Draf SPJ, Lembar Cetak SPJ, Lampiran Bukti</td>
            </tr>
            <tr>
                <td><span class="badge badge-umum">2. Subbag Umum</span></td>
                <td><strong>Verifikasi Administrasi</strong></td>
                <td>Konfirmasi terima fisik, periksa SLA & keabsahan formal bukti belanja, setujui/revisi (notifikasi WA), dan cetak lembar verifikasi.</td>
                <td>Lembar Checklist Verifikasi Umum (PDF)</td>
            </tr>
            <tr>
                <td><span class="badge badge-bendahara">3. Bendahara</span></td>
                <td><strong>Pajak & Pencairan</strong></td>
                <td>Verifikasi bukti keuangan, hitung pajak (PPh/PPN), eksekusi transfer via bank/BRI QLOLA, input resi transfer, dan ubah status Cair.</td>
                <td>Bukti Transfer Bank & Rekap Cair (CSV/PDF)</td>
            </tr>
            <tr>
                <td><span class="badge badge-admin">4. Administrator</span></td>
                <td><strong>Pengelola & Hak Hapus</strong></td>
                <td>Kelola akun pengguna, reset sandi via email resmi, kelola template berkas, dan memegang hak tunggal menghapus SPJ.</td>
                <td>Master Data User, Template & Audit Trail</td>
            </tr>
        </tbody>
    </table>

    <div class="section-heading">II. Rincian Hak & Tugas: ROLE 1 - STAF TEKNIS (PENGUSUL KEGIATAN)</div>
    <div class="role-title-box teknis">
        <h4>1. STAF TEKNIS KEGIATAN (Subject Matter - SM)</h4>
        <p>Aktor pelaksana kegiatan lapangan/survei yang menyusun dan mengajukan pertanggungjawaban belanja operasional.</p>
    </div>

    <table class="sub-table">
        <tr>
            <td class="col-label">Tugas Pokok di Web (Ngapain Saja?):</td>
            <td>
                <ul class="task-list">
                    <li><span class="step-num">1</span><strong>Membuat Pengajuan SPJ:</strong> Membuka menu <em>Pengajuan SPJ</em>, sistem otomatis men-generate Nomor SPJ. Pengaju memilih Seksi Teknis (Sosial, Produksi, Distribusi, Neraca, atau IPDS), menginput nominal anggaran, uraian belanja, rentang tanggal pelaksanaan kegiatan (tanggal mulai s.d. selesai), serta nomor WhatsApp aktif PIC.</li>
                    <li><span class="step-num">2</span><strong>Memilih Checklist Berkas Fisik:</strong> Mencentang dokumen fisik yang telah disiapkan (KAK, Undangan, Notula, BAST, Form Permintaan, Daftar Hadir, Bukti Pembelian/Nota toko).</li>
                    <li><span class="step-num">3</span><strong>Kewajiban Cetak Lembar SPJ (Wajib Print):</strong> Sebelum pengajuan dapat dikirim, sistem mengunci tombol kirim (*disabled*). Staf teknis <u>wajib menekan tombol Print</u> terlebih dahulu. Setelah jendela pratinjau cetak terbuka, tombol submit akan aktif dan berubah warna menjadi hijau. Lembar cetak ini wajib dilampirkan bersama dokumen fisik.</li>
                    <li><span class="step-num">4</span><strong>Unggah Lampiran Digital:</strong> Melalui menu <em>Kelengkapan Dokumen</em>, staf teknis mengunggah scan/foto bukti dukung asli (format PDF, JPG, PNG) agar verifikator dapat menelaah berkas secara daring.</li>
                    <li><span class="step-num">5</span><strong>Menyerahkan Berkas Fisik:</strong> Membawa bundel berkas fisik yang telah dibubuhi tanda tangan dan stempel basah beserta lembar checklist cetak ke loket Subbagian Umum.</li>
                    <li><span class="step-num">6</span><strong>Tindak Lanjut Revisi:</strong> Memantau riwayat pengajuan. Jika berkas dikembalikan (status <em>Revisi Umum</em> atau <em>Revisi Bendahara</em>), segera membuka form edit, membaca catatan revisi, melengkapi kekurangan, dan mengajukan kembali.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label">Menu & Fitur yang Diakses:</td>
            <td>
                <ul class="task-list">
                    <li><strong>Dashboard:</strong> Ringkasan statistik SPJ pengaju (Total Diajukan, Disetujui, Revisi, Selesai Cair).</li>
                    <li><strong>Pengajuan SPJ:</strong> Pembuatan draf baru, simpan draf sementara, dan submit pengajuan resmi.</li>
                    <li><strong>Kelengkapan Dokumen:</strong> Manajemen unggah berkas digital pendukung per SPJ.</li>
                    <li><strong>Riwayat SPJ:</strong> Pelacakan posisi SPJ, catatan revisi verifikator, dan cetak ulang lembar pengajuan.</li>
                    <li><strong>Laporan SPJ:</strong> Ekspor dan cetak rekapitulasi daftar kegiatan yang telah dipertanggungjawabkan.</li>
                    <li><strong>Profil Akun:</strong> Memperbarui data diri, nomor WhatsApp, foto profil, dan kata sandi akun.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label" style="color: #b91c1c;">Batasan & Larangan Sistem:</td>
            <td>
                <div class="alert-box alert-danger">
                    <strong>KETENTUAN KETAT ROLE TEKNIS:</strong>
                    <ul class="task-list" style="margin-top: 2px;">
                        <li><strong>DILARANG & TIDAK BISA HAPUS SPJ:</strong> Tombol aksi hapus ditiadakan dari akun teknis dan rute backend diproteksi (403 Forbidden). Penghapusan data SPJ hanya dapat dilakukan oleh Administrator.</li>
                        <li><strong>Form Terkunci Saat Sedang Diverifikasi:</strong> Ketika berkas berstatus <em>Diajukan</em>, <em>Diproses Umum</em>, atau <em>Proses Bendahara</em>, data tidak dapat diubah oleh staf teknis.</li>
                        <li><strong>Checkbox Pajak Bendahara Terproteksi:</strong> Opsi berkas perpajakan khusus bendahara (misal PPh 22) dinonaktifkan dari form teknis dan diberi label "Dilengkapi Bendahara".</li>
                    </ul>
                </div>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ======================================================== -->
    <!-- HALAMAN 2: ROLE SUBBAG UMUM & ROLE BENDAHARA             -->
    <!-- ======================================================== -->
    <div class="section-heading">III. Rincian Hak & Tugas: ROLE 2 - SUBBAGIAN UMUM (VERIFIKATOR ADMINISTRASI)</div>
    <div class="role-title-box umum">
        <h4>2. SUBBAGIAN UMUM (Pintu Verifikasi Berkas Administrasi & Fisik)</h4>
        <p>Aktor yang bertugas menguji keabsahan formal, kelengkapan berkas fisik asli, dan kepatuhan standar administrasi BPS.</p>
    </div>

    <table class="sub-table">
        <tr>
            <td class="col-label">Tugas Pokok di Web (Ngapain Saja?):</td>
            <td>
                <ul class="task-list">
                    <li><span class="step-num">1</span><strong>Menerima Berkas Fisik:</strong> Menerima berkas fisik yang diserahkan staf teknis di loket Subbag Umum, lalu menekan tombol <strong>"Konfirmasi Terima Berkas Fisik"</strong> di web (sistem mencatat waktu penerimaan fisik).</li>
                    <li><span class="step-num">2</span><strong>Memulai Pemeriksaan (SLA):</strong> Menekan tombol <strong>"Mulai Periksa"</strong> untuk mengaktifkan penghitungan durasi layanan verifikasi (SLA) secara otomatis.</li>
                    <li><span class="step-num">3</span><strong>Pemeriksaan Formal & Dokumen Pendukung:</strong>
                        <ul style="padding-left: 14px; margin-top: 2px;">
                            <li>Memastikan keberadaan kuitansi/nota belanja dengan cap stempel basah dan tanda tangan asli rekanan.</li>
                            <li>Mencocokkan kesesuaian nilai kuitansi fisik dengan nominal yang tertera pada sistem.</li>
                            <li>Memeriksa kelengkapan administrasi: KAK, surat undangan, daftar hadir peserta, notula hasil, dan BAST.</li>
                        </ul>
                    </li>
                    <li><span class="step-num">4</span><strong>Menetapkan Keputusan Verifikasi:</strong>
                        <ul style="padding-left: 14px; margin-top: 2px;">
                            <li><strong>Setujui:</strong> Jika dokumen lengkap dan sah, status diubah menjadi <em>Disetujui Umum</em> dan berkas diteruskan ke Bendahara Pengeluaran.</li>
                            <li><strong>Revisi (Kembalikan):</strong> Jika terdapat kekurangan, verifikator menginput catatan perbaikan detail. Sistem secara otomatis memicu pesan WhatsApp ke nomor pengaju teknis terkait.</li>
                        </ul>
                    </li>
                    <li><span class="step-num">5</span><strong>Cetak Lembar Verifikasi:</strong> Mengklik tombol <strong>"Cetak Lembar Verifikasi SPJ (PDF)"</strong> untuk dicetak dan disatukan ke dalam bundel fisik sebagai bukti uji formalitas.</li>
                    <li><span class="step-num">6</span><strong>Monitoring Antrean & Pelaporan:</strong> Memantau antrean berkas masuk, tracking status SPJ per seksi teknis, dan mengunduh laporan rekapitulasi SPJ.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label">Menu & Fitur yang Diakses:</td>
            <td>
                <ul class="task-list">
                    <li><strong>Dashboard Umum:</strong> Monitoring antrean SPJ, status SLA verifikasi, grafik pengajuan 5 Seksi Teknis, dan top pengaju.</li>
                    <li><strong>Daftar SPJ / Verifikasi:</strong> Manajemen aksi terima fisik, mulai telaah, setujui berkas, tolak/revisi, dan cetak checklist.</li>
                    <li><strong>Riwayat SPJ:</strong> Arsip pencarian seluruh SPJ yang telah selesai diverifikasi atau diteruskan.</li>
                    <li><strong>Laporan SPJ:</strong> Ekspor dan rekapitulasi seluruh pengajuan SPJ satker dengan filter periode/seksi.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label" style="color: #b91c1c;">Batasan & Larangan Sistem:</td>
            <td>
                <div class="alert-box alert-danger">
                    <strong>KETENTUAN KETAT ROLE SUBBAG UMUM:</strong>
                    <ul class="task-list" style="margin-top: 2px;">
                        <li><strong>DILARANG & TIDAK BISA HAPUS SPJ:</strong> Tombol hapus ditiadakan dari seluruh halaman Subbag Umum dan backend diproteksi (403 Forbidden).</li>
                        <li><strong>Tidak Berwenang Mengubah Nominal:</strong> Subbag Umum tidak dapat mengedit nominal anggaran kegiatan teknis; jika terdapat ketidaksesuaian nilai, harus dikembalikan melalui mekanisme revisi.</li>
                        <li><strong>Tidak Berwenang Mencairkan Kas:</strong> Eksekusi pembayaran dan bukti potong pajak mutlak kewenangan Bendahara.</li>
                    </ul>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-heading" style="margin-top: 8px;">IV. Rincian Hak & Tugas: ROLE 3 - BENDAHARA PENGELUARAN (VERIFIKATOR KEUANGAN & KAS)</div>
    <div class="role-title-box bendahara">
        <h4>3. BENDAHARA PENGELUARAN (Pintu Verifikasi Keuangan, Perpajakan & Pencairan Dana)</h4>
        <p>Aktor yang berwenang menguji keabsahan bukti pengeluaran riil, perhitungan pajak negara, dan eksekusi transfer pembayaran.</p>
    </div>

    <table class="sub-table">
        <tr>
            <td class="col-label">Tugas Pokok di Web (Ngapain Saja?):</td>
            <td>
                <ul class="task-list">
                    <li><span class="step-num">1</span><strong>Menerima Berkas Lolos Verifikasi:</strong> Membuka antrean SPJ yang telah dinyatakan lengkap dan disetujui oleh Subbagian Umum.</li>
                    <li><span class="step-num">2</span><strong>Verifikasi Kepatuhan Perpajakan & Bukti Bayar:</strong>
                        <ul style="padding-left: 14px; margin-top: 2px;">
                            <li>Menghitung dan memverifikasi potongan pajak: PPh Pasal 21 (honorarium), PPh Pasal 22 (belanja barang di atas Rp 2 juta), PPh Pasal 23 (sewa/jasa), dan PPN sesuai ketentuan berlaku.</li>
                            <li>Memeriksa kelengkapan NPWP/identitas rekanan dan faktur pajak pendukung.</li>
                            <li>Memeriksa meterai tempel resmi (Rp 10.000) pada transaksi kuitansi di atas Rp 5.000.000,-.</li>
                        </ul>
                    </li>
                    <li><span class="step-num">3</span><strong>Persetujuan Keuangan / Revisi:</strong>
                        <ul style="padding-left: 14px; margin-top: 2px;">
                            <li><strong>Setujui Pembayaran:</strong> Menyetujui SPJ untuk segera dieksekusi pembayarannya.</li>
                            <li><strong>Revisi Bendahara:</strong> Mengembalikan berkas jika ditemukan selisih nominal hitung kuitansi atau bukti pajak belum lengkap.</li>
                        </ul>
                    </li>
                    <li><span class="step-num">4</span><strong>Eksekusi Transfer & Input Bukti Pembayaran:</strong>
                        <ul style="padding-left: 14px; margin-top: 2px;">
                            <li>Melakukan transfer dana riil via perbankan (CMS BRI / BRI QLOLA / Kas Negara).</li>
                            <li>Menginput nomor referensi transfer bank / nomor transaksi QLOLA ke form pembayaran web.</li>
                            <li>Mengunggah file resi/bukti transfer bank dan mengesahkan status SPJ menjadi <strong>Selesai (Cair)</strong>.</li>
                        </ul>
                    </li>
                    <li><span class="step-num">5</span><strong>Ekspor Laporan & Pencatatan Kas:</strong> Mengunduh rekapitulasi data SPJ terbayar ke file spreadsheet CSV (Excel) dan mencetak bukti pembayaran resmi PDF.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label">Menu & Fitur yang Diakses:</td>
            <td>
                <ul class="task-list">
                    <li><strong>Dashboard Bendahara:</strong> Ringkasan SPJ siap bayar, total realisasi anggaran kas, dan rekap SPJ selesai cair.</li>
                    <li><strong>Kotak Masuk / Meja Verifikasi:</strong> Pengujian kepatuhan pajak, checklist keuangan khusus bendahara, dan persetujuan bayar.</li>
                    <li><strong>Konfirmasi Pencairan:</strong> Input nomor referensi transaksi bank, unggah struk/resi transfer, dan submit status Cair.</li>
                    <li><strong>Riwayat & Laporan:</strong> Ekspor data pencairan ke CSV/Excel dan cetak bukti kuitansi pertanggungjawaban kas.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label" style="color: #b91c1c;">Batasan & Larangan Sistem:</td>
            <td>
                <div class="alert-box alert-danger">
                    <strong>KETENTUAN KETAT ROLE BENDAHARA:</strong>
                    <ul class="task-list" style="margin-top: 2px;">
                        <li><strong>DILARANG & TIDAK BISA HAPUS SPJ:</strong> Tombol hapus ditiadakan dari akun Bendahara dan rute backend dikunci (403 Forbidden). Transaksi yang telah masuk buku kas tercatat permanen dalam jejak audit keuangan.</li>
                        <li><strong>Tidak Berwenang Mengubah Substansi Kegiatan:</strong> Bendahara tidak berhak mengubah materi teknis kegiatan sensus/survei.</li>
                    </ul>
                </div>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ======================================================== -->
    <!-- HALAMAN 3: ADMIN, MATRIKS FITUR & ATURAN VALIDASI        -->
    <!-- ======================================================== -->
    <div class="section-heading">V. Rincian Hak & Tugas: ROLE 4 - ADMINISTRATOR SISTEM</div>
    <div class="role-title-box admin">
        <h4>4. ADMINISTRATOR SISTEM (Pengelola Akun, Master Data & Hak Istimewa Hapus)</h4>
        <p>Aktor pengendali sistem informasi yang mengelola keamanan akses, template berkas, dan satu-satunya pemegang hak hapus SPJ.</p>
    </div>

    <table class="sub-table">
        <tr>
            <td class="col-label">Tugas Pokok di Web (Ngapain Saja?):</td>
            <td>
                <ul class="task-list">
                    <li><span class="step-num">1</span><strong>Manajemen Pengguna (User Management):</strong> Mendaftarkan akun pegawai baru, mengedit identitas, mengatur peranan (*role: Teknis, Umum, Bendahara, Admin*), dan menugaskan staf teknis ke 5 Seksi resmi (Sosial, Produksi, Distribusi, Neraca, IPDS).</li>
                    <li><span class="step-num">2</span><strong>Reset Kata Sandi via Email Resmi:</strong> Menangani pegawai yang lupa kata sandi dengan mengirimkan tautan reset token terenkripsi langsung ke email pengguna melalui mailer SMTP resmi.</li>
                    <li><span class="step-num">3</span><strong>Pengelolaan Template Berkas:</strong> Mengunggah (*upload*), memperbarui, dan menghapus berkas format acuan resmi (format KAK, BAST, SPK, Daftar Hadir) yang dapat diunduh bebas oleh seluruh staf di halaman depan/dashboard.</li>
                    <li><span class="step-num">4</span><strong>Pengawasan Audit Trail:</strong> Memantau riwayat log seluruh dokumen SPJ dari draf hingga selesai cair guna memastikan integritas transaksi.</li>
                    <li><span class="step-num">5</span><strong>Hak Tunggal Hapus Data SPJ:</strong> <u>Administrator adalah satu-satunya role</u> yang memiliki tombol dan wewenang menghapus data SPJ dari sistem jika terjadi duplikasi berkas fatal atau data uji coba.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label">Hak Istimewa:</td>
            <td>
                <span class="badge badge-admin">Akses Penuh Seluruh Modul</span>
                <span class="badge badge-admin">Kelola Akun Pegawai</span>
                <span class="badge badge-admin">Kelola Template Dokumen</span>
                <span class="badge badge-admin" style="background: #b91c1c; color: #fff;">Satu-satunya Pemilik Hak Hapus SPJ</span>
            </td>
        </tr>
    </table>

    <div class="section-heading" style="margin-top: 8px;">VI. Matriks Perbandingan Hak Akses Fitur Antarmuka Web</div>
    <p style="margin-bottom: 4px; font-size: 7.2pt;">Tabel komparasi hak akses menu dan aksi operasional antar role pengguna pada aplikasi SPJ:</p>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 44%; text-align: left;">Fitur & Aksi di Website SPJ</th>
                <th style="width: 14%;">Staf Teknis</th>
                <th style="width: 14%;">Subbag Umum</th>
                <th style="width: 14%;">Bendahara</th>
                <th style="width: 14%;">Admin</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Membuat Pengajuan SPJ Baru & Draf</td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Edit Draf & Perbaikan Berkas Status Revisi</td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Cetak Lembar Checklist Pengajuan (Wajib Print)</td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Wajib</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Unggah File Lampiran Digital (PDF/Gambar)</td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Konfirmasi Terima Fisik & Mulai Periksa SLA</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Verifikasi Administrasi & Cetak Lembar Umum</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Kirim Catatan Revisi & Pemicu Notifikasi WhatsApp</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Verifikasi Pajak & Checklist Porsi Bendahara</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Input Nomor Transaksi Bank & Upload Bukti Cair</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Ekspor Laporan (CSV / PDF)</td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Kegiatan</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; SPJ Umum</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Kas Cair</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Semua</span></td>
            </tr>
            <tr style="background-color: #fef2f2;">
                <td><strong>Hapus Data SPJ (Delete Action)</strong></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; DIKUNCI</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; DIKUNCI</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; DIKUNCI</span></td>
                <td style="text-align: center;"><span class="badge badge-yes" style="background:#dc2626; color:#fff;">&radic; KHUSUS ADMIN</span></td>
            </tr>
            <tr>
                <td>Kelola Pengguna, Role & Reset Password Email</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Kelola Template Berkas SPJ Kedinasan</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Unduh Saja</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Unduh Saja</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Unduh Saja</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya (CRUD)</span></td>
            </tr>
        </tbody>
    </table>

    <div class="section-heading" style="margin-top: 8px;">VII. Ketentuan & Kebijakan Validasi Khusus Sistem</div>
    <div class="highlight-box">
        <strong style="color: #0369a1; font-size: 7.6pt;">1. Validasi Wajib Cetak Checklist Sebelum Pengajuan (Sisi Teknis):</strong>
        <p style="margin-top: 2px;">
            Sistem memberlakukan proteksi tombol pengajuan. Tombol submit awal berada dalam kondisi abu-abu (*disabled*). Staf teknis diwajibkan menekan tombol <strong>"Print"</strong> terlebih dahulu. Setelah jendela pratinjau cetak terbuka, tombol submit akan berubah warna menjadi hijau aktif (*ready to submit*). Berkas cetak tersebut wajib dilampirkan bersama dokumen fisik SPJ ke Subbag Umum.
        </p>
    </div>

    <div class="highlight-box">
        <strong style="color: #b91c1c; font-size: 7.6pt;">2. Keamanan Jejak Audit: Larangan Hapus SPJ (Khusus Administrator):</strong>
        <p style="margin-top: 2px;">
            Guna mencegah hilangnya rekam jejak keuangan negara, <strong>role Teknis, Umum, dan Bendahara tidak memiliki tombol hapus SPJ</strong>. Rute penghapusan backend dikunci dengan status <em>403 Forbidden</em>. Hanya akun Administrator yang dapat menghapus dokumen apabila terdapat insiden duplikasi atau kesalahan data fatal.
        </p>
    </div>

    <div class="highlight-box">
        <strong style="color: #d97706; font-size: 7.6pt;">3. Proteksi Checkbox Perpajakan & Keuangan:</strong>
        <p style="margin-top: 2px;">
            Pada form perbaikan/edit teknis, berkas yang merupakan kewenangan bendahara (misal PPh 22) tidak menampilkan kotak centang dan diberi label khusus <em>"Dilengkapi Bendahara"</em>. Tombol "Centang Semua" pada sisi teknis hanya mencentang berkas fisik wajib SM, sedangkan backend mengunci status berkas bendahara agar tidak dimanipulasi oleh pengaju teknis.
        </p>
    </div>

    <div class="highlight-box">
        <strong style="color: #15803d; font-size: 7.6pt;">4. Integrasi 5 Seksi Teknis & Notifikasi WhatsApp Otomatis:</strong>
        <p style="margin-top: 2px;">
            Setiap pengajuan SPJ terikat pada salah satu dari 5 Seksi Teknis BPS (Sosial, Produksi, Distribusi, Neraca, IPDS). Sistem mengintegrasikan nomor WhatsApp PIC pengaju dengan notifikasi otomatis. Ketika berkas fisik dikonfirmasi tiba, disetujui, atau jika terdapat catatan revisi dari Subbag Umum/Bendahara, sistem langsung mengirim pesan WhatsApp ke pengaju untuk segera ditindaklanjuti.
        </p>
    </div>

    <!-- FOOTER KEDINASAN RESMI (TANPA TTD) -->
    <div class="official-footer">
        <strong>SISTEM INFORMASI PERTANGGUNGJAWABAN KEUANGAN (SPJ) DIGITAL</strong><br>
        Badan Pusat Statistik Kabupaten Kolaka Utara &bull; Dokumen Panduan Standar Operasional Pengguna Sistem Web<br>
        <em>Dicetak dan diarsipkan secara digital pada {{ now()->locale('id')->translatedFormat('l, d F Y') }}</em>
    </div>

</body>
</html>
