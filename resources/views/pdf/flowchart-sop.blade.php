<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SOP & Flowchart Pengajuan SPJ - BPS Kabupaten Kolaka Utara</title>
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
            line-height: 1.35;
            color: #1e293b;
            background: #ffffff;
        }

        /* HEADER KOP */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #1e4253;
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
            font-size: 7.5pt;
            opacity: 0.9;
            margin-top: 2px;
        }

        /* SECTION HEADERS */
        .section-heading {
            font-size: 9pt;
            font-weight: bold;
            color: #1e4253;
            border-left: 3px solid #1e4253;
            padding-left: 6px;
            margin: 8px 0 6px 0;
            text-transform: uppercase;
        }

        /* FLOWCHART TABLE / SWIMLANES */
        .flowchart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .flowchart-table th, .flowchart-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            vertical-align: top;
            font-size: 7.5pt;
        }

        .flowchart-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            font-size: 7.5pt;
            text-transform: uppercase;
        }

        .role-badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-teknis { background-color: #e0f2fe; color: #0369a1; }
        .badge-umum { background-color: #dbeafe; color: #1d4ed8; }
        .badge-ppk { background-color: #fce7f3; color: #be185d; }
        .badge-ppspm { background-color: #ede9fe; color: #6d28d9; }
        .badge-bendahara { background-color: #fef3c7; color: #b45309; }
        .badge-selesai { background-color: #dcfce7; color: #15803d; }

        .status-pill {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 999px;
            font-size: 6pt;
            font-weight: bold;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            margin-top: 2px;
            white-space: nowrap;
        }

        .flow-step-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            padding: 3px 5px;
            margin-bottom: 2px;
            line-height: 1.3;
        }

        .flow-step-box.decision {
            background-color: #fffbeb;
            border-color: #fde68a;
        }

        .flow-step-box.action-green {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
        }

        .step-num {
            display: inline-block;
            width: 14px;
            height: 14px;
            background: #1e4253;
            color: #ffffff;
            border-radius: 50%;
            text-align: center;
            font-size: 6pt;
            line-height: 14px;
            font-weight: bold;
            margin-right: 3px;
        }

        .flow-arrow {
            text-align: center;
            font-size: 7.5pt;
            color: #0284c7;
            font-weight: bold;
            line-height: 1;
            margin: 1px 0;
        }

        /* MATRIKS TABEL */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            font-size: 7pt;
            line-height: 1.35;
        }

        .data-table th {
            background-color: #1e4253;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }

        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        /* ATURAN VALIDASI */
        .rule-card {
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            border-left: 3px solid #0284c7;
            padding: 5px 8px;
            margin-bottom: 5px;
            font-size: 7.5pt;
            line-height: 1.35;
        }

        .rule-card strong {
            color: #0f172a;
        }

        .page-break {
            page-break-after: always;
        }

        /* TANDA TANGAN */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            page-break-inside: avoid;
        }

        .ttd-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 8pt;
        }

        .ttd-space {
            height: 48px;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- ======================================================== -->
    <!-- HALAMAN 1: FLOWCHART LENGKAP 5 PINTU                      -->
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
        <h3>STANDAR OPERASIONAL PROSEDUR (SOP) & FLOWCHART</h3>
        <p>Alur Pengajuan, Verifikasi Administrasi, Pengujian Tagihan, dan Pencairan Dana SPJ</p>
    </div>

    <div class="section-heading">1. Diagram Alir Proses Penyelesaian SPJ (5 Pintu Meja)</div>

    <table class="flowchart-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 17%;">Pintu / Aktor</th>
                <th style="width: 44%;">Aktivitas & Alur Keputusan</th>
                <th style="width: 17%;">Status Sistem</th>
                <th style="width: 17%;">Notifikasi WA</th>
            </tr>
        </thead>
        <tbody>
            <!-- TAHAP 1: TEKNIS -->
            <tr>
                <td style="text-align: center; font-weight: bold;">1</td>
                <td>
                    <span class="role-badge badge-teknis">Staf Teknis</span><br>
                    <small style="color: #64748b;">Pengaju Kegiatan</small>
                </td>
                <td>
                    <div class="flow-step-box">
                        <span class="step-num">A</span> Input detail kegiatan, tanggal, nominal anggaran, nomor WhatsApp, serta <strong>unggah berkas lampiran digital</strong> (scan/foto/PDF).
                    </div>
                    <div class="flow-arrow">|</div>
                    <div class="flow-step-box">
                        <span class="step-num">B</span> Pilih <em>Nama & Jenis Belanja</em>, centang daftar berkas fisik awal yang telah disiapkan.
                    </div>
                    <div class="flow-arrow">|</div>
                    <div class="flow-step-box decision">
                        <strong>Validasi Pop-up Cetak:</strong> Sistem mewajibkan klik <u>Cetak Lembar Checklist Fisik</u> terlebih dahulu sebelum berkas dapat resmi diajukan.
                    </div>
                    <div class="flow-arrow">|</div>
                    <div class="flow-step-box action-green">
                        <span class="step-num">C</span> Submit pengajuan, serahkan berkas fisik + lembar checklist ke Meja Bagian Umum.
                    </div>
                </td>
                <td>
                    <span class="status-pill" style="background: #f1f5f9; color: #475569;">DRAFT</span><br>
                    <div class="flow-arrow" style="font-size: 6pt;">|</div>
                    <span class="status-pill" style="background: #e0f2fe; color: #0284c7;">DIAJUKAN</span>
                </td>
                <td>
                    <small>Pengaju dapat langsung kirim notifikasi konfirmasi WhatsApp ke staf Bagian Umum.</small>
                </td>
            </tr>

            <!-- TAHAP 2: UMUM -->
            <tr>
                <td style="text-align: center; font-weight: bold;">2</td>
                <td>
                    <span class="role-badge badge-umum">Bagian Umum</span><br>
                    <small style="color: #64748b;">Verifikasi Berkas</small>
                </td>
                <td>
                    <div class="flow-step-box">
                        <span class="step-num">A</span> Periksa kesesuaian fisik vs checklist (KAK, Form Permintaan, Bukti Beli, Undangan, Daftar Hadir, Notula, BAST, dll.).
                    </div>
                    <div class="flow-arrow">|</div>
                    <div class="flow-step-box decision">
                        <strong>Uji Kelengkapan Berkas:</strong><br>
                        &bull; <em>Kurang:</em> Beri catatan kendala & status <span style="color: #dc2626; font-weight: bold;">Revisi Umum</span>.<br>
                        &bull; <em>Lengkap:</em> Klik <strong>Setujui</strong>, teruskan dokumen fisik ke meja PPK.
                    </div>
                </td>
                <td>
                    <span class="status-pill" style="background: #fee2e2; color: #dc2626;">REVISI_UMUM</span><br>
                    <span style="font-size: 6pt; color: #64748b;">atau</span><br>
                    <span class="status-pill" style="background: #dcfce7; color: #166534;">DISETUJUI_UMUM</span>
                </td>
                <td>
                    <small>Otomatis kirim WhatsApp ke Pengaju (Informasi berkas disetujui / poin revisi).</small>
                </td>
            </tr>

            <!-- TAHAP 3: PPK -->
            <tr>
                <td style="text-align: center; font-weight: bold;">3</td>
                <td>
                    <span class="role-badge badge-ppk">PPK</span><br>
                    <small style="color: #64748b;">Komitmen Anggaran</small>
                </td>
                <td>
                    <div class="flow-step-box">
                        <span class="step-num">A</span> Pemeriksaan substansi output kegiatan, kewajaran biaya, dan kepatuhan anggaran.
                    </div>
                    <div class="flow-arrow">|</div>
                    <div class="flow-step-box decision">
                        <strong>Persetujuan PPK:</strong><br>
                        &bull; <em>Ada Koreksi:</em> Kembalikan berkas (<span style="color: #dc2626; font-weight: bold;">Revisi PPK</span>).<br>
                        &bull; <em>Disetujui:</em> Tanda tangani fisik kuitansi & klik setujui di sistem. Teruskan berkas ke meja PPSPM.
                    </div>
                </td>
                <td>
                    <span class="status-pill" style="background: #fee2e2; color: #dc2626;">REVISI_PPK</span><br>
                    <span style="font-size: 6pt; color: #64748b;">atau</span><br>
                    <span class="status-pill" style="background: #dcfce7; color: #166534;">DISETUJUI_PPK</span>
                </td>
                <td>
                    <small>Kirim pesan WA: Status persetujuan PPK & berkas diteruskan ke PPSPM.</small>
                </td>
            </tr>

            <!-- TAHAP 4: PPSPM -->
            <tr>
                <td style="text-align: center; font-weight: bold;">4</td>
                <td>
                    <span class="role-badge badge-ppspm">PPSPM</span><br>
                    <small style="color: #64748b;">Pengujian Tagihan</small>
                </td>
                <td>
                    <div class="flow-step-box">
                        <span class="step-num">A</span> Pengujian ketersediaan dana DIPA, kelayakan dokumen tagihan, & penerbitan SPM fisik.
                    </div>
                    <div class="flow-arrow">|</div>
                    <div class="flow-step-box decision">
                        <strong>Persetujuan PPSPM:</strong><br>
                        &bull; <em>Ada Koreksi:</em> Beri catatan kendala & status <span style="color: #dc2626; font-weight: bold;">Revisi PPSPM</span>.<br>
                        &bull; <em>Disetujui:</em> Terbitkan SPM & status <span style="color: #166534; font-weight: bold;">Disetujui PPSPM</span>, serahkan dokumen ke Bendahara Pengeluaran.
                    </div>
                </td>
                <td>
                    <span class="status-pill" style="background: #fee2e2; color: #dc2626;">REVISI_PPSPM</span><br>
                    <span style="font-size: 6pt; color: #64748b;">atau</span><br>
                    <span class="status-pill" style="background: #dcfce7; color: #166534;">DISETUJUI_PPSPM</span>
                </td>
                <td>
                    <small>Kirim pesan WA: SPM telah terbit, berkas siap diproses Bendahara.</small>
                </td>
            </tr>

            <!-- TAHAP 5: BENDAHARA -->
            <tr>
                <td style="text-align: center; font-weight: bold;">5</td>
                <td>
                    <span class="role-badge badge-bendahara">Bendahara</span><br>
                    <small style="color: #64748b;">Pencairan Dana</small>
                </td>
                <td>
                    <div class="flow-step-box">
                        <span class="step-num">A</span> Periksa & centang berkas perpajakan (PPh 21/22/23, PPN, Faktur, SSP/BPN/NTPN) serta kuitansi riil.
                    </div>
                    <div class="flow-arrow">|</div>
                    <div class="flow-step-box decision">
                        <strong>Eksekusi Pembayaran:</strong><br>
                        &bull; <em>Jalur 1: CMS BRI QLOLA</em> &rarr; Input Maker, status <span style="color: #0284c7; font-weight: bold;">Menunggu OTP QLOLA KPA</span>. Setelah KPA input OTP, konfirmasi selesai.<br>
                        &bull; <em>Jalur 2: Pembayaran LS (Bank)</em> &rarr; Input SP2D, status <span style="color: #ea580c; font-weight: bold;">Proses LS Bank</span> (kliring/transfer rekening).<br>
                        &bull; <em>Kendala Berkas/Rekening:</em> Kembalikan ke teknis (<span style="color: #dc2626; font-weight: bold;">Revisi Bendahara</span>).
                    </div>
                    <div class="flow-arrow">|</div>
                    <div class="flow-step-box action-green">
                        <span class="step-num">B</span> Input nomor resi / referensi bukti transfer bank & konfirmasi penyelesaian pencairan dana.
                    </div>
                </td>
                <td>
                    <span class="status-pill" style="background: #e0f2fe; color: #0284c7;">MENUNGGU_OTP_KPA</span><br>
                    <span class="status-pill" style="background: #ffedd5; color: #ea580c;">PROSES_LS</span><br>
                    <span class="status-pill" style="background: #fee2e2; color: #dc2626;">REVISI_BENDAHARA</span><br>
                    <div class="flow-arrow" style="font-size: 6pt;">|</div>
                    <span class="status-pill" style="background: #dcfce7; color: #166534; font-weight: 800;">SELESAI</span>
                </td>
                <td>
                    <small>Kirim pesan WA konfirmasi pencairan dana tuntas ke pengaju/rekanan.</small>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- ======================================================== -->
    <!-- HALAMAN 2: MATRIKS DOKUMEN & LEMBAR PENGESAHAN            -->
    <!-- ======================================================== -->
    <table class="kop-table" style="margin-bottom: 6px;">
        <tr>
            <td style="width: 45%;">
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" class="kop-logo" alt="Logo BPS" style="height: 30px; width: auto;">
                @endif
            </td>
            <td class="kop-text" style="width: 55%;">
                <h2 style="font-size: 8pt;">Lampiran: Matriks Dokumen Kelengkapan SPJ & Ketentuan Sistem</h2>
            </td>
        </tr>
    </table>

    <div class="section-heading">2. Matriks Dokumen Kelengkapan Fisik Berdasarkan Belanja</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Kategori Belanja (Akun)</th>
                <th style="width: 25%;">Jenis Belanja</th>
                <th style="width: 50%;">Dokumen Fisik yang Wajib Disiapkan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="3" style="font-weight: bold;">Belanja Bahan<br>(521211)</td>
                <td>Belanja Konsumsi</td>
                <td>KAK, Form Permintaan (dari BOS), Undangan, Daftar Hadir, Notula, Bukti Pembelian (Nota), BAST, PPh 22 (jika di atas Rp 2.000.000).</td>
            </tr>
            <tr>
                <td>Paket Data Komunikasi</td>
                <td>KAK, Form Permintaan (BOS), Nota Beli, Daftar Nama & No HP Penerima, Screenshot Bukti Transfer Paket Data/Pulsa.</td>
            </tr>
            <tr>
                <td>Perlengkapan Pelatihan</td>
                <td>KAK, Form Permintaan (BOS), Nota Beli, Daftar Alokasi Tanda Terima Barang, BAST, Faktur Pajak & PPN (transaksi > Rp 2.000.000).</td>
            </tr>
            <tr>
                <td><strong>Barang Persediaan Konsumsi (521811)</strong></td>
                <td>ATK CS / Percetakan</td>
                <td>KAK, Form Permintaan, Bukti Pembelian (&lt; 10 jt) / Kwitansi (&gt; 50 jt), BAST foto barang, Faktur Pajak & PPh 22.</td>
            </tr>
            <tr>
                <td rowspan="2"><strong>Jasa Profesi<br>(522151)</strong></td>
                <td>Honor Narasumber</td>
                <td>KAK, Form Permintaan, SK KPA, Undangan Moderator & Peserta, Jadwal Kegiatan, Daftar Hadir, CV, Bahan Paparan, Kuitansi, NPWP, Rekening, SSP PPh 21.</td>
            </tr>
            <tr>
                <td>Honor Moderator</td>
                <td>KAK, Form Permintaan, SK KPA, Undangan, Jadwal, Daftar Hadir, CV Moderator, Kuitansi, Salinan NPWP & Rekening, SSP PPh 21.</td>
            </tr>
            <tr>
                <td rowspan="2"><strong>Perjalanan Dinas Biasa & Dalam Kota<br>(524111 / 524113)</strong></td>
                <td>Luar Kota</td>
                <td>Form Permintaan, Surat Tugas, SPPD lengkap Visum tempat tujuan, Tiket/Kuitansi kendaraan, Bukti Hotel, Laporan Perjalanan & Foto GPS, Kuitansi PPK.</td>
            </tr>
            <tr>
                <td>Dalam Kota (s/d 8 jam & &gt; 8 jam)</td>
                <td>KAK, Form Permintaan, Surat Tugas, Visum tanda tangan pejabat tujuan, Kwitansi Transport / Daftar Pengeluaran Riil, Pernyataan Tanpa Kendaraan Dinas.</td>
            </tr>
            <tr>
                <td><strong>Honor Output Kegiatan (512213)</strong></td>
                <td>Petugas Mitra / Pengajar</td>
                <td>KAK, Form Permintaan, SK KPA rate SBKS, Surat Perjanjian Kerja / Kontrak Mitra, Daftar Rekap Kuitansi, BAPP/BAST, SSP PPh 21.</td>
            </tr>
        </tbody>
    </table>

    <div class="section-heading">3. Ketentuan Khusus & Standar Mutu Sistem SPJ</div>

    <div class="rule-card">
        <strong>1. Integritas Checklist Fisik vs Sistem:</strong> Pengajuan SPJ tidak dapat diproses sebelum lembar checklist resmi dicetak dan ditandatangani oleh Staf Pengaju/PIC kegiatan.
    </div>

    <div class="rule-card">
        <strong>2. Mekanisme Notifikasi WhatsApp Real-time:</strong> Setiap aksi penolakan (revisi) maupun persetujuan (approval) memicu pesan WhatsApp otomatis kepada nomor kontak pengaju, lengkap dengan rincian kegiatan dan catatan perbaikan.
    </div>

    <div class="rule-card">
        <strong>3. Transparansi & Jejak Audit (Audit Trail):</strong> Seluruh histori status, tanggal persetujuan per pintu, paraf digital, serta bukti transfer tersimpan permanen dan terangkum dalam <em>Laporan Rekapitulasi SPJ</em> yang dapat diunduh/dicetak sewaktu-waktu.
    </div>

    <!-- LEMBAR PENGESAHAN -->
    <table class="ttd-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Pejabat Pembuat Komitmen (PPK)</strong>
                <div class="ttd-space"></div>
                <span class="ttd-name">Hidayatullah</span><br>
                NIP. 19851029 201101 1 009
            </td>
            <td>
                Lasusua, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Disahkan Oleh,<br>
                <strong>Kepala BPS Kabupaten Kolaka Utara</strong>
                <div class="ttd-space"></div>
                <span class="ttd-name">...................................................</span><br>
                NIP. ...................................................
            </td>
        </tr>
    </table>

</body>
</html>
