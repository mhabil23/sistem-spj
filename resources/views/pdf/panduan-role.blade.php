<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panduan Hak Akses & Tugas Role - Sistem SPJ BPS Kolaka Utara</title>
    <style>
        @page {
            margin: 10mm 12mm;
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
            line-height: 1.4;
            color: #1e293b;
            background: #ffffff;
        }

        /* HEADER KOP */
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
            width: 155px;
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
            padding: 7px 12px;
            border-radius: 4px;
            margin-bottom: 9px;
        }

        .doc-title-box h3 {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doc-title-box p {
            font-size: 7.5pt;
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
            margin: 9px 0 6px 0;
            text-transform: uppercase;
        }

        .role-title-box {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #0284c7;
            padding: 6px 10px;
            border-radius: 4px;
            margin-top: 8px;
            margin-bottom: 6px;
        }

        .role-title-box.teknis { border-left-color: #0284c7; background: #f0f9ff; }
        .role-title-box.umum { border-left-color: #2563eb; background: #eff6ff; }
        .role-title-box.bendahara { border-left-color: #d97706; background: #fffbeb; }
        .role-title-box.admin { border-left-color: #dc2626; background: #fef2f2; }
        .role-title-box.pejabat { border-left-color: #7c3aed; background: #f5f3ff; }

        .role-title-box h4 {
            font-size: 9pt;
            font-weight: bold;
            color: #0f172a;
        }

        .role-title-box p {
            font-size: 7.2pt;
            color: #475569;
            margin-top: 1px;
        }

        /* TABLES */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            vertical-align: top;
            font-size: 7.5pt;
        }

        .data-table th {
            background-color: #f8fafc;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            font-size: 7.2pt;
            text-transform: uppercase;
        }

        .sub-table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0 6px 0;
        }

        .sub-table td {
            padding: 3px 5px;
            font-size: 7.5pt;
            vertical-align: top;
            border-bottom: 1px dashed #e2e8f0;
        }

        .sub-table td.col-label {
            width: 135px;
            font-weight: bold;
            color: #334155;
        }

        /* BADGES */
        .badge {
            display: inline-block;
            padding: 1.5px 5px;
            border-radius: 3px;
            font-size: 6.8pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-teknis { background-color: #e0f2fe; color: #0369a1; }
        .badge-umum { background-color: #dbeafe; color: #1d4ed8; }
        .badge-bendahara { background-color: #fef3c7; color: #b45309; }
        .badge-admin { background-color: #fee2e2; color: #b91c1c; }
        .badge-pejabat { background-color: #ede9fe; color: #6d28d9; }
        .badge-yes { background-color: #dcfce7; color: #15803d; font-weight: bold; }
        .badge-no { background-color: #fee2e2; color: #991b1b; }

        /* LISTS */
        .task-list {
            list-style: none;
            padding-left: 0;
            margin: 3px 0 5px 0;
        }

        .task-list li {
            position: relative;
            padding-left: 14px;
            margin-bottom: 3px;
            font-size: 7.5pt;
            line-height: 1.35;
        }

        .task-list li::before {
            content: "•";
            position: absolute;
            left: 3px;
            color: #1e4253;
            font-size: 9pt;
            font-weight: bold;
        }

        .highlight-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 6px 8px;
            margin: 5px 0;
            font-size: 7.3pt;
        }

        .alert-box {
            border-radius: 4px;
            padding: 5px 8px;
            margin: 4px 0 6px 0;
            font-size: 7.2pt;
            line-height: 1.35;
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

        .alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .page-break {
            page-break-after: always;
        }

        /* SIGNATURE */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .ttd-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 7.8pt;
        }

        .ttd-space {
            height: 44px;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
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
        <h3>MATRIKS HAK AKSES, WEWENANG & TUGAS (TUPOKSI) SETIAP ROLE</h3>
        <p>Sistem Informasi Pertanggungjawaban Keuangan (SPJ) Digital BPS Kabupaten Kolaka Utara</p>
    </div>

    <div class="section-heading">I. Gambaran Umum & Alur Koordinasi Antar Peran</div>
    <p style="text-align: justify; margin-bottom: 6px; font-size: 7.5pt;">
        Sistem Informasi SPJ BPS Kabupaten Kolaka Utara dirancang untuk memastikan tata kelola administrasi pertanggungjawaban belanja negara yang akuntabel, transparan, dan terstandarisasi. Setiap pengguna memiliki peran (*role*) spesifik dengan wewenang yang terpisah (*separation of duties*). Alur pengajuan berjalan melalui 5 tahapan utama:
    </p>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 14%;">Role / Aktor</th>
                <th style="width: 18%;">Fokus Utama</th>
                <th style="width: 48%;">Tugas Pokok dalam Sistem</th>
                <th style="width: 20%;">Output Dokumen</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="badge badge-teknis">Staf Teknis</span></td>
                <td><strong>Pengaju Kegiatan</strong></td>
                <td>Menyusun rincian kegiatan, mencentang kelengkapan berkas fisik, wajib mencetak checklist, mengunggah lampiran digital, dan menyerahkan berkas fisik ke Subbag Umum.</td>
                <td>Draf SPJ, Checklist Fisik, Lampiran Bukti</td>
            </tr>
            <tr>
                <td><span class="badge badge-umum">Subbag Umum</span></td>
                <td><strong>Verifikasi Administrasi</strong></td>
                <td>Menerima fisik, konfirmasi penerimaan di sistem, menguji keabsahan formal kuitansi/nota/daftar hadir, memberikan status setuju/revisi, dan mencetak lembar verifikasi.</td>
                <td>Lembar Checklist Verifikasi Umum (PDF)</td>
            </tr>
            <tr>
                <td><span class="badge badge-pejabat">PPK & PPSPM</span></td>
                <td><strong>Pengujian & Otorisasi</strong></td>
                <td>PPK mengesahkan komitmen belanja negara. PPSPM menguji keabsahan tagihan serta menerbitkan Surat Perintah Membayar (SPM).</td>
                <td>Lembar Pengesahan PPK & Nomor SPM</td>
            </tr>
            <tr>
                <td><span class="badge badge-bendahara">Bendahara</span></td>
                <td><strong>Pajak & Pencairan</strong></td>
                <td>Menghitung & memotong pajak (PPh/PPN), memverifikasi kuitansi akhir, mencairkan dana via perbankan (BRI QLOLA/CMS/LS), mencatat bukti transfer, dan menyelesaikan SPJ.</td>
                <td>Bukti Transfer Bank & Rekap Cair (CSV/PDF)</td>
            </tr>
            <tr>
                <td><span class="badge badge-admin">Administrator</span></td>
                <td><strong>Pengelola & Pengawas</strong></td>
                <td>Mengelola akun pengguna, mereset password melalui email, mengelola template berkas, dan memegang hak eksklusif menghapus SPJ jika ada duplikasi/kesalahan fatal.</td>
                <td>Manajemen Akun, Template, Audit Trail</td>
            </tr>
        </tbody>
    </table>

    <div class="section-heading">II. Rincian Hak & Tugas: ROLE 1 - TIM TEKNIS (Pengaju SPJ)</div>
    <div class="role-title-box teknis">
        <h4>1. STAF TEKNIS / PENANGGUNG JAWAB KEGIATAN (Subject Matter - SM)</h4>
        <p>Aktor yang bertugas menyelenggarakan kegiatan teknis sensus/survei dan menyusun dokumen pertanggungjawabannya.</p>
    </div>

    <table class="sub-table">
        <tr>
            <td class="col-label">Tugas Pokok (Ngapain Saja?):</td>
            <td>
                <ul class="task-list">
                    <li><strong>Membuat Pengajuan SPJ Baru:</strong> Mengisi nomor SPJ (otomatis sistem), memilih Seksi Teknis (Sosial, Produksi, Distribusi, Neraca, IPDS), menginput nilai anggaran, uraian kegiatan, tanggal pelaksanaan (atau rentang tanggal kegiatan), dan nomor WhatsApp PIC.</li>
                    <li><strong>Menentukan Kategori & Jenis Belanja:</strong> Memilih klasifikasi belanja anggaran (Belanja Bahan, Honor output kegiatan, Perjalanan Dinas, Paket Data, dll.) yang otomatis menghasilkan daftar dokumen fisik wajib.</li>
                    <li><strong>Mempersiapkan Berkas Fisik:</strong> Memeriksa dan mencentang berkas fisik yang telah disiapkan (KAK, Undangan, Daftar Hadir, Notula, Bukti Pembelian/Nota/Kuitansi bertandatangan, BAST).</li>
                    <li><strong>Wajib Mencetak Checklist Fisik:</strong> Sesuai SOP sistem, staf teknis <u>wajib menekan tombol Print</u> dan mencetak lembar checklist berkas SPJ terlebih dahulu sebelum tombol pengajuan aktif. Berkas cetak dilampirkan bersama dokumen fisik.</li>
                    <li><strong>Mengunggah Lampiran Digital:</strong> Mengunggah berkas scan/foto bukti transaksi (PDF, JPG, PNG) ke dalam sistem untuk mempermudah pengecekan daring.</li>
                    <li><strong>Menyerahkan Fisik ke Subbag Umum:</strong> Segera membawa seluruh dokumen fisik SPJ beserta lembar checklist cetak ke meja Subbagian Umum.</li>
                    <li><strong>Memperbaiki Berkas Revisi:</strong> Memantau notifikasi dan segera melakukan perbaikan dokumen apabila terdapat catatan pengembalian dari Subbagian Umum atau Bendahara.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label">Hak Akses Sistem (Fitur yang Bisa Digunakan):</td>
            <td>
                <ul class="task-list">
                    <li>Membuat dan menyimpan Draf SPJ sewaktu-waktu.</li>
                    <li>Mengubah data dan berkas SPJ selama statusnya masih <em>Draft</em> atau dalam masa <em>Revisi</em>.</li>
                    <li>Mengakses fitur cetak lembar checklist verifikasi mandiri (langsung print tab baru).</li>
                    <li>Mencetak laporan pertanggungjawaban kegiatan teknis ke format PDF.</li>
                    <li>Mengelola profil akun pribadi (foto profil, nomor WhatsApp aktif, seksi asal, dan kata sandi).</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label" style="color: #b91c1c;">Batasan & Larangan Sistem:</td>
            <td>
                <div class="alert-box alert-danger">
                    <strong>PENTING - LARANGAN TEKNIS:</strong>
                    <ul class="task-list" style="margin-top: 2px;">
                        <li><strong>DILARANG / TIDAK BISA MENGHAPUS SPJ:</strong> Tombol aksi hapus pada akun Teknis telah ditiadakan dan rute backend dikunci (403 Forbidden). Penghapusan SPJ hanya dapat dilakukan oleh Administrator sistem.</li>
                        <li><strong>Tidak Dapat Mengubah SPJ yang Sedang Berjalan:</strong> Saat SPJ berstatus <em>Diajukan</em>, <em>Disetujui Umum</em>, <em>Disetujui PPK</em>, atau <em>Proses Bendahara</em>, form pengeditan dikunci otomatis untuk menjaga integritas data.</li>
                        <li><strong>Tidak Berwenang Mengubah Bagian Pajak:</strong> Checkbox kelengkapan yang menjadi porsi Bendahara (misal PPh 22) dinonaktifkan dari form teknis dan diberi tanda "Dilengkapi Bendahara".</li>
                    </ul>
                </div>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ======================================================== -->
    <!-- HALAMAN 2: ROLE UMUM & ROLE BENDAHARA                    -->
    <!-- ======================================================== -->
    <div class="section-heading">III. Rincian Hak & Tugas: ROLE 2 - SUBBAGIAN UMUM</div>
    <div class="role-title-box umum">
        <h4>2. SUBBAGIAN UMUM (Verifikator Berkas Administrasi Awal)</h4>
        <p>Pintu verifikasi pertama yang bertugas memastikan kelengkapan fisik, kesesuaian format, dan validitas administrasi.</p>
    </div>

    <table class="sub-table">
        <tr>
            <td class="col-label">Tugas Pokok (Ngapain Saja?):</td>
            <td>
                <ul class="task-list">
                    <li><strong>Menerima Berkas Fisik:</strong> Menerima berkas fisik yang diserahkan staf teknis dan melakukan tombol klik <em>"Konfirmasi Terima Berkas Fisik"</em> di sistem (mencatat tanggal & jam penerimaan fisik).</li>
                    <li><strong>Memulai Proses Pemeriksaan:</strong> Mengaktifkan status pemeriksaan (mencatat waktu dimulainya penelaahan berkas guna penghitungan SLA pemeriksaan).</li>
                    <li><strong>Menguji Kelengkapan & Keabsahan Dokumen:</strong>
                        <ul style="padding-left: 14px; margin-top: 2px;">
                            <li>Memeriksa keberadaan kuitansi bertanda tangan basah dan cap stempel basah penyedia/toko.</li>
                            <li>Mencocokkan nota belanja/bukti transaksi dengan rincian anggaran yang diinput di sistem.</li>
                            <li>Memeriksa KAK, surat tugas/undangan, daftar hadir peserta, dan notula hasil kegiatan.</li>
                            <li>Memeriksa BAST untuk belanja barang persediaan atau jasa.</li>
                        </ul>
                    </li>
                    <li><strong>Menetapkan Keputusan Verifikasi:</strong>
                        <ul style="padding-left: 14px; margin-top: 2px;">
                            <li><strong>Setujui & Teruskan:</strong> Jika dokumen lengkap dan valid, mengubah status menjadi <em>Disetujui Umum</em> dan meneruskan berkas fisik ke PPK / Bendahara.</li>
                            <li><strong>Tolak / Kembalikan (Revisi Umum):</strong> Jika berkas tidak lengkap/keliru, wajib mencantumkan catatan perbaikan spesifik. Sistem otomatis mengirimkan notifikasi WhatsApp ke PIC Teknis.</li>
                        </ul>
                    </li>
                    <li><strong>Mencetak Lembar Verifikasi:</strong> Mencetak <em>Lembar Checklist Verifikasi SPJ (PDF)</em> resmi yang memuat hasil pemeriksaan dan tanda tangan verifikator untuk disatukan dalam bundel fisik.</li>
                    <li><strong>Monitoring Dashboard & Laporan:</strong> Memantau antrean berkas masuk, statistik pengajuan staf teknis, serta mencetak rekapitulasi laporan SPJ berkala.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label">Hak Akses Sistem (Fitur yang Bisa Digunakan):</td>
            <td>
                <ul class="task-list">
                    <li>Melihat seluruh pengajuan SPJ dari seluruh staf teknis yang berstatus diajukan atau selesai.</li>
                    <li>Melakukan konfirmasi penerimaan fisik dan pengesahan status verifikasi dokumen.</li>
                    <li>Memberikan catatan revisi dan memicu pengiriman pesan WhatsApp koreksi.</li>
                    <li>Mencetak checklist verifikasi PDF dan laporan rekapitulasi SPJ (filter bulan/tahun/rentang tanggal).</li>
                    <li>Melihat grafik tren pengajuan serta ranking pengajuan staf teknis riil.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label" style="color: #b91c1c;">Batasan & Larangan Sistem:</td>
            <td>
                <div class="alert-box alert-danger">
                    <strong>PENTING - LARANGAN BAGIAN UMUM:</strong>
                    <ul class="task-list" style="margin-top: 2px;">
                        <li><strong>DILARANG / TIDAK BISA MENGHAPUS SPJ:</strong> Tombol hapus pada halaman Riwayat Umum telah dihilangkan dan backend dikunci (403 Forbidden). Arsip SPJ tidak boleh dihapus sepihak oleh verifikator umum.</li>
                        <li><strong>Tidak Dapat Mengubah Anggaran Kegiatan:</strong> Subbagian Umum tidak berhak mengubah nominal atau uraian kegiatan teknis secara sepihak; perubahan data harus melalui mekanisme revisi oleh teknis.</li>
                        <li><strong>Tidak Berwenang Mencairkan Dana:</strong> Hak pencairan kas dan perhitungan pemotongan pajak berada di bawah kewenangan Bendahara Pengeluaran.</li>
                    </ul>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-heading" style="margin-top: 10px;">IV. Rincian Hak & Tugas: ROLE 3 - BENDAHARA PENGELUARAN</div>
    <div class="role-title-box bendahara">
        <h4>3. BENDAHARA PENGELUARAN (Verifikator Keuangan, Pajak & Eksekusi Pencairan)</h4>
        <p>Pintu akhir verifikasi keuangan negara yang berwenang menguji keabsahan perpajakan dan melakukan pencairan dana.</p>
    </div>

    <table class="sub-table">
        <tr>
            <td class="col-label">Tugas Pokok (Ngapain Saja?):</td>
            <td>
                <ul class="task-list">
                    <li><strong>Menerima Berkas dari PPK/Umum:</strong> Menerima berkas SPJ yang telah diverifikasi administratif oleh Subbag Umum dan disetujui Pejabat Pembuat Komitmen (PPK).</li>
                    <li><strong>Verifikasi Kepatuhan Perpajakan & Keuangan:</strong>
                        <ul style="padding-left: 14px; margin-top: 2px;">
                            <li>Menghitung kewajiban pemotongan pajak: PPh 21 (honorarium), PPh 22 (belanja barang di atas Rp 2 juta), PPh 23 (sewa/jasa), dan PPN sesuai peraturan perpajakan.</li>
                            <li>Memeriksa kelengkapan faktur pajak dan bukti NPWP/identitas rekanan/penerima.</li>
                            <li>Memeriksa keabsahan kuitansi bermeterai cukup (Rp 10.000 untuk transaksi di atas Rp 5 juta).</li>
                        </ul>
                    </li>
                    <li><strong>Pengambilan Keputusan Keuangan:</strong>
                        <ul style="padding-left: 14px; margin-top: 2px;">
                            <li><strong>Setujui / Loloskan:</strong> Menyetujui SPJ untuk diproses pembayaran melalui BRI QLOLA / CMS Perbankan / LS Kas Negara.</li>
                            <li><strong>Kembalikan (Revisi Bendahara):</strong> Memberikan catatan revisi keuangan kepada teknis jika terdapat selisih hitung, bukti potong pajak belum lengkap, atau nomor rekening penerima salah.</li>
                        </ul>
                    </li>
                    <li><strong>Eksekusi Pencairan & Bukti Transfer:</strong>
                        <ul style="padding-left: 14px; margin-top: 2px;">
                            <li>Melakukan transfer dana melalui CMS BRI / QLOLA ke rekening rekanan atau staf pelaksana.</li>
                            <li>Menginput nomor referensi transfer bank / nomor transaksi QLOLA ke dalam sistem.</li>
                            <li>Menyelesaikan status SPJ menjadi <strong>Selesai (Cair)</strong>.</li>
                        </ul>
                    </li>
                    <li><strong>Pelaporan & Rekapitulasi:</strong> Mengekspor riwayat pencairan ke format spreadsheet CSV dan laporan rekapitulasi pencairan PDF bertandatangan resmi.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label">Hak Akses Sistem (Fitur yang Bisa Digunakan):</td>
            <td>
                <ul class="task-list">
                    <li>Mengakses Kotak Masuk SPJ Bendahara dan Meja Verifikasi Berkas Keuangan.</li>
                    <li>Mengelola checklist kelengkapan berkas khusus porsi bendahara (PPh 22, Faktur Pajak, Bukti Bayar).</li>
                    <li>Fitur Verifikasi Massal (Bulk Verification) untuk pengesahan SPJ bertahap dalam satu batch.</li>
                    <li>Form Konfirmasi Pencairan Dana dan penyimpanan nomor referensi bukti transfer bank.</li>
                    <li>Ekspor data riwayat SPJ terbayar ke file CSV dan berkas cetak PDF.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label" style="color: #b91c1c;">Batasan & Larangan Sistem:</td>
            <td>
                <div class="alert-box alert-danger">
                    <strong>PENTING - LARANGAN BENDAHARA:</strong>
                    <ul class="task-list" style="margin-top: 2px;">
                        <li><strong>DILARANG / TIDAK BISA MENGHAPUS SPJ:</strong> Tombol hapus pada Riwayat Bendahara telah ditiadakan dan rute backend dikunci (403 Forbidden). Seluruh transaksi yang masuk meja bendahara tercatat permanen dalam jejak audit keuangan.</li>
                        <li><strong>Tidak Dapat Mengubah Substansi Kegiatan Teknis:</strong> Bendahara tidak berwenang mengubah uraian substansi kegiatan lapangan yang telah disahkan penanggung jawab kegiatan.</li>
                    </ul>
                </div>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ======================================================== -->
    <!-- HALAMAN 3: ADMIN, PPK/PPSPM & FEATURE MATRIX             -->
    <!-- ======================================================== -->
    <div class="section-heading">V. Rincian Hak & Tugas: ROLE 4 - ADMINISTRATOR SISTEM</div>
    <div class="role-title-box admin">
        <h4>4. ADMINISTRATOR SISTEM (Pengelola Utama & Hak Istimewa Pengawasan)</h4>
        <p>Aktor pengendali sistem informasi yang mengelola keamanan akun, master data, dan satu-satunya pemegang hak hapus.</p>
    </div>

    <table class="sub-table">
        <tr>
            <td class="col-label">Tugas Pokok (Ngapain Saja?):</td>
            <td>
                <ul class="task-list">
                    <li><strong>Manajemen Pengguna (User Management):</strong> Mendaftarkan akun baru, memperbarui profil pegawai, menetapkan hak akses (*role* Teknis, Umum, Bendahara, PPK, PPSPM, Admin), dan mengaitkan staf teknis ke Seksi resmi (Sosial, Produksi, Distribusi, Neraca, IPDS).</li>
                    <li><strong>Pemulihan & Keamanan Akun:</strong> Mereset kata sandi pengguna yang lupa password melalui pengiriman tautan token resmi ke email dinas/sungguhan pengguna, serta mengaktifkan/menonaktifkan akun yang mutasi.</li>
                    <li><strong>Pengelolaan Template Berkas:</strong> Mengunggah, memperbarui, dan mempublikasikan file template format berkas acuan (KAK, BAST, SPK, Daftar Hadir) yang dapat diunduh oleh seluruh pegawai.</li>
                    <li><strong>Pengawasan Jejak Audit (*Audit Trail*):</strong> Memantau riwayat log aktivitas dan perubahan status setiap dokumen SPJ dari awal hingga selesai.</li>
                    <li><strong>Hak Eksklusif Hapus Data SPJ:</strong> <u>Administrator adalah satu-satunya role</u> yang memiliki tombol dan wewenang menghapus data SPJ dari database jika terdapat duplikasi data fatal atau berkas uji coba.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label">Hak Akses Eksklusif:</td>
            <td>
                <span class="badge badge-admin">Akses Penuh Seluruh Modul</span>
                <span class="badge badge-admin">Kelola Master Data User</span>
                <span class="badge badge-admin">Kelola Template Dokumen</span>
                <span class="badge badge-admin">Satu-satunya Role Pemilik Hak Hapus (Delete SPJ)</span>
            </td>
        </tr>
    </table>

    <div class="section-heading" style="margin-top: 8px;">VI. Rincian Hak & Tugas: ROLE 5 - PEJABAT PERBENDAHARAAN (PPK & PPSPM)</div>
    <div class="role-title-box pejabat">
        <h4>5. PEJABAT PEMBUAT KOMITMEN (PPK) & PEJABAT PENANDATANGAN SPM (PPSPM)</h4>
        <p>Pejabat pengelola perbendaharaan negara yang berwenang mengesahkan ikatan belanja dan menerbitkan surat perintah bayar.</p>
    </div>

    <table class="sub-table">
        <tr>
            <td class="col-label">PPK (Pejabat Pembuat Komitmen):</td>
            <td>
                <ul class="task-list">
                    <li>Menerima berkas SPJ yang telah diverifikasi lengkap oleh Subbagian Umum.</li>
                    <li>Menguji kesesuaian ikatan belanja dengan alokasi DIPA / POK satuan kerja.</li>
                    <li>Memberikan persetujuan pengesahan (*Disetujui PPK*) atau pengembalian catatan revisi komitmen.</li>
                    <li>Menandatangani lembar kuitansi / surat bukti komitmen negara sebelum diteruskan ke PPSPM.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="col-label">PPSPM (Persetujuan Akhir SPM):</td>
            <td>
                <ul class="task-list">
                    <li>Menguji kelengkapan dokumen tagihan negara yang telah disahkan PPK.</li>
                    <li>Menerbitkan nomor Surat Perintah Membayar (SPM) resmi satuan kerja.</li>
                    <li>Mengubah status menjadi *Disetujui PPSPM* dan meneruskan dokumen ke meja Bendahara untuk pembayaran.</li>
                </ul>
            </td>
        </tr>
    </table>

    <div class="section-heading" style="margin-top: 10px;">VII. Matriks Perbandingan Hak Akses Fitur (Feature Matrix)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 36%; text-align: left;">Fitur / Kapabilitas Sistem</th>
                <th style="width: 12%;">Teknis</th>
                <th style="width: 13%;">Umum</th>
                <th style="width: 13%;">Bendahara</th>
                <th style="width: 13%;">PPK / PPSPM</th>
                <th style="width: 13%;">Admin</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Membuat Pengajuan SPJ Baru</td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Edit Draf & Perbaikan Berkas Revisi</td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Cetak Lembar Checklist Berkas Pengajuan</td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Wajib</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Unggah File Scan / Lampiran Digital</td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Konfirmasi Terima Berkas Fisik & Mulai Periksa</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Verifikasi Administrasi & Cetak Lembar Umum</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Kirim Catatan Revisi & Pemicu Notifikasi WA</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Pengesahan Komitmen Belanja (PPK)</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya (PPK)</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Penerbitan Surat Perintah Membayar (SPM)</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya (PPSPM)</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Verifikasi Pajak & Checklist Keuangan</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Pencairan Dana (BRI QLOLA / CMS) & Input Bukti Transfer</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Ekspor Laporan & Arsip (CSV / PDF)</td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya (Kegiatan)</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya (SPJ Umum)</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya (Cair)</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya (Semua)</span></td>
            </tr>
            <tr style="background-color: #fef2f2;">
                <td><strong>Hapus Data SPJ (Delete Action)</strong></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; DIKUNCI</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; DIKUNCI</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; DIKUNCI</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; DIKUNCI</span></td>
                <td style="text-align: center;"><span class="badge badge-yes" style="background:#dc2626; color:#fff;">&radic; KHUSUS ADMIN</span></td>
            </tr>
            <tr>
                <td>Kelola Pengguna, Role & Reset Password</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Tidak</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya</span></td>
            </tr>
            <tr>
                <td>Kelola Template Berkas SPJ</td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Unduh Saja</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Unduh Saja</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Unduh Saja</span></td>
                <td style="text-align: center;"><span class="badge badge-no">&times; Unduh Saja</span></td>
                <td style="text-align: center;"><span class="badge badge-yes">&radic; Ya (CRUD)</span></td>
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- ======================================================== -->
    <!-- HALAMAN 4: ATURAN VALIDASI KHUSUS & LEMBAR PENGESAHAN    -->
    <!-- ======================================================== -->
    <div class="section-heading">VIII. Ketentuan & Aturan Validasi Khusus Sistem</div>

    <div class="highlight-box">
        <strong style="color: #0369a1; font-size: 8pt;">1. Validasi Wajib Print Sebelum Pengajuan (Sisi Teknis):</strong>
        <p style="margin-top: 2px;">
            Sistem menerapkan proteksi tombol pengajuan. Ketika staf teknis memilih opsi <em>"Ajukan Sekarang"</em>, tombol simpan berada dalam kondisi abu-abu (*disabled*). Staf teknis diwajibkan menekan tombol <strong>"Print"</strong> terlebih dahulu. Setelah jendela pratinjau cetak terbuka, tombol submit akan berubah warna menjadi hijau aktif (*ready to submit*). Berkas cetak tersebut wajib dilampirkan bersama fisik SPJ.
        </p>
    </div>

    <div class="highlight-box">
        <strong style="color: #b91c1c; font-size: 8pt;">2. Kebijakan Keamanan: Pembatasan Hak Hapus SPJ (Hanya Admin):</strong>
        <p style="margin-top: 2px;">
            Untuk menjamin integritas rekam jejak audit (*audit trail*) dan mencegah kehilangan berkas pertanggungjawaban keuangan negara, <strong>role Teknis, Umum, dan Bendahara sama sekali tidak memiliki tombol hapus SPJ</strong>. Endpoint delete di tingkat controller backend diproteksi dengan penolakan <em>403 Forbidden</em>. Hanya akun bertingkat Administrator yang dapat menghapus dokumen jika terdapat insiden data dobel atau salah input fatal.
        </p>
    </div>

    <div class="highlight-box">
        <strong style="color: #d97706; font-size: 8pt;">3. Proteksi Checkbox Perpajakan & Keuangan:</strong>
        <p style="margin-top: 2px;">
            Pada form perbaikan/pengeditan teknis, berkas yang merupakan kewenangan bendahara (misal PPh 22) tidak menampilkan kotak centang (*checkbox*) dan diberi label khusus <em>"Dilengkapi Bendahara"</em>. Tombol "Centang Semua" pada sisi teknis hanya mencentang berkas fisik wajib SM, sedangkan backend mengunci status berkas bendahara agar tidak dapat dimanipulasi oleh staf teknis.
        </p>
    </div>

    <div class="highlight-box">
        <strong style="color: #15803d; font-size: 8pt;">4. Integrasi 5 Seksi Teknis BPS & WhatsApp Notification:</strong>
        <p style="margin-top: 2px;">
            Setiap pengajuan SPJ terikat pada salah satu dari 5 Seksi Teknis BPS (Sosial, Produksi, Distribusi, Neraca, IPDS). Sistem mengintegrasikan nomor WhatsApp PIC pengaju dengan layanan notifikasi otomatis. Ketika berkas fisik dikonfirmasi tiba, disetujui, atau jika terdapat catatan revisi, sistem secara langsung memicu pesan WhatsApp kepada pengaju agar koreksi dapat dilakukan seketika tanpa hambatan komunikasi.
        </p>
    </div>

    <div class="highlight-box">
        <strong style="color: #475569; font-size: 8pt;">5. Pengelolaan Akun & Reset Kata Sandi Resmi via Email:</strong>
        <p style="margin-top: 2px;">
            Fitur lupa kata sandi (*forgot password*) terintegrasi langsung dengan mailer SMTP resmi. Administrator dan seluruh pengguna dapat mereset kata sandi melalui pengiriman token terenkripsi ke alamat email yang terdaftar di database, menjaga kedaulatan akun tanpa perlu mengubah konfigurasi server secara manual.
        </p>
    </div>

    <!-- TANDA TANGAN PENGESAHAN -->
    <table class="ttd-table">
        <tr>
            <td>
                Mengetahui / Mengesahkan,<br>
                <strong>Pejabat Pembuat Komitmen (PPK)</strong><br>
                BPS Kabupaten Kolaka Utara
                <div class="ttd-space"></div>
                <span class="ttd-name">NURHAYATI, S.Si., M.Si.</span><br>
                <span>NIP. 19820315 200604 2 004</span>
            </td>
            <td>
                Lasusua, {{ now()->locale('id')->translatedFormat('d F Y') }}<br>
                Menyetujui,<br>
                <strong>Kepala BPS Kabupaten Kolaka Utara</strong>
                <div class="ttd-space"></div>
                <span class="ttd-name">IR. H. MUHAMMAD SYARIF, M.Si.</span><br>
                <span>NIP. 19680512 199403 1 002</span>
            </td>
        </tr>
    </table>

</body>
</html>
