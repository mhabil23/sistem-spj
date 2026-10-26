<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Kendali & Checklist Verifikasi SPJ - #{{ $spj->id }}</title>
    <style>
        @page {
            margin: 12mm 15mm;
            size: A4 portrait;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            line-height: 1.35;
            color: #1e293b;
            background: #ffffff;
        }

        /* HEADER KOP BPS */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2.5px solid #1e4253;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo {
            width: 135px;
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
            font-size: 9pt;
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
            margin-bottom: 12px;
        }

        .doc-title-box h3 {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .doc-title-box p {
            font-size: 7.5pt;
            margin-top: 2px;
            opacity: 0.9;
        }

        /* INFO META TABLE */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }

        .info-table td {
            padding: 5px 8px;
            font-size: 8pt;
            vertical-align: top;
        }

        .info-label {
            width: 140px;
            font-weight: bold;
            color: #334155;
        }

        .info-sep {
            width: 10px;
            text-align: center;
        }

        .info-val {
            color: #0f172a;
        }

        /* CHECKLIST TABLE */
        .section-heading {
            font-size: 8.5pt;
            font-weight: bold;
            color: #1e4253;
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .checklist-table th,
        .checklist-table td {
            border: 1px solid #94a3b8;
            padding: 5px 6px;
            font-size: 7.5pt;
            line-height: 1.3;
        }

        .checklist-table th {
            background-color: #e2edf5;
            color: #1e4253;
            font-weight: bold;
            text-align: center;
        }

        .box-check {
            width: 14px;
            height: 14px;
            border: 1px solid #475569;
            margin: auto;
            text-align: center;
            line-height: 14px;
            font-weight: bold;
            font-size: 9pt;
        }

        .box-check.checked {
            background-color: #dcfce7;
            color: #15803d;
            border-color: #16a34a;
        }

        /* KESIMPULAN & CATATAN */
        .catatan-box {
            border: 1px solid #94a3b8;
            border-radius: 4px;
            padding: 8px 10px;
            margin-bottom: 14px;
            background: #fafafa;
            min-height: 52px;
        }

        .catatan-title {
            font-weight: bold;
            font-size: 8pt;
            color: #1e4253;
            margin-bottom: 4px;
        }

        .catatan-content {
            font-size: 7.5pt;
            color: #334155;
            line-height: 1.4;
        }

        .rekomendasi-row {
            margin-top: 6px;
            padding-top: 6px;
            border-top: 1px dashed #cbd5e1;
            font-size: 8pt;
        }

        /* TANDA TANGAN SECTION */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: avoid;
        }

        .ttd-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 8pt;
        }

        .ttd-space {
            height: 50px;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd-nip {
            font-size: 7pt;
            color: #64748b;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <!-- ======================================================== -->
    <!-- KOP SURAT BPS KABUPATEN KOLAKA UTARA                     -->
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

    <!-- ======================================================== -->
    <!-- JUDUL DOKUMEN                                            -->
    <!-- ======================================================== -->
    <div class="doc-title-box">
        <h3>LEMBAR KENDALI & CHECKLIST VERIFIKASI DOKUMEN SPJ</h3>
        <p>SUBBAGIAN UMUM / MEJA VERIFIKATOR PENYELESAIAN TAGIHAN</p>
    </div>

    <!-- ======================================================== -->
    <!-- INFORMASI SPJ                                            -->
    <!-- ======================================================== -->
    <table class="info-table">
        <tr>
            <td class="info-label">No. Registrasi / SPJ</td>
            <td class="info-sep">:</td>
            <td class="info-val"><strong>{{ $spj->nomor_spj ?: 'SPJ/BPS7408/' . str_pad($spj->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
            <td class="info-label">Tanggal Pengajuan</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ ($spj->diajukan_at ? \Carbon\Carbon::parse($spj->diajukan_at) : $spj->created_at)->format('d F Y, H:i') }} WITA</td>
        </tr>
        <tr>
            <td class="info-label">Staf Pengaju (PIC)</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $spj->user->name ?? 'Staf Teknis' }} ({{ $spj->user->seksi ?? 'Seksi Teknis' }})</td>
            <td class="info-label">Nomor WhatsApp PIC</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $spj->no_wa ?: ($spj->user->no_wa ?? '-') }}</td>
        </tr>
        <tr>
            <td class="info-label">Nama Kegiatan</td>
            <td class="info-sep">:</td>
            <td class="info-val" colspan="4"><strong>{{ $spj->kegiatan }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Nilai Tagihan SPJ</td>
            <td class="info-sep">:</td>
            <td class="info-val" colspan="4">
                <strong style="color: #047857; font-size: 9.5pt;">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <!-- ======================================================== -->
    <!-- TABEL CHECKLIST VERIFIKASI DOKUMEN                       -->
    <!-- ======================================================== -->
    <div class="section-heading">I. DAFTAR PEMERIKSAAN KELENGKAPAN BERKAS (SOP)</div>

    @php
        $decodedDocs = [];
        if ($spj->dokumen_file) {
            $parsed = json_decode($spj->dokumen_file, true);
            if (is_array($parsed)) {
                $decodedDocs = $parsed;
            }
        }

        // Default standard 10 SOP list jika belum ada di json
        $defaultSopItems = [
            ['nama' => 'Kerangka Acuan Kerja (KAK) / Rencana Anggaran Biaya', 'disiapkan' => true],
            ['nama' => 'Formulir Permintaan Belanja (FP dari BOS / Sistem)', 'disiapkan' => true],
            ['nama' => 'Kuitansi asli bermaterai cukup (sesuai ketentuan nilai belanja > Rp5 Juta)', 'disiapkan' => true],
            ['nama' => 'Nota / Faktur / Invoice asli bertanda tangan rekanan & berstempel basah', 'disiapkan' => true],
            ['nama' => 'Berita Acara Serah Terima (BAST) Pekerjaan / Bukti Pembelian', 'disiapkan' => true],
            ['nama' => 'Surat Tugas & Daftar Hadir Peserta / Petugas / Visum', 'disiapkan' => true],
            ['nama' => 'Laporan pelaksanaan kegiatan disertai foto dokumentasi kegiatan (GPS)', 'disiapkan' => true],
            ['nama' => 'Surat Keputusan (SK) Tim / Pokja (jika relevan honor kegiatan)', 'disiapkan' => false],
            ['nama' => 'Perhitungan & Bukti Potong Pajak (PPh Pasal 21 / 22 / 23 / PPN)', 'disiapkan' => false],
            ['nama' => 'Kesesuaian jumlah nominal kuitansi dengan alokasi DIPA / Pagu', 'disiapkan' => true],
        ];

        $itemsToShow = count($decodedDocs) > 0 ? $decodedDocs : $defaultSopItems;
    @endphp

    <table class="checklist-table">
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>
                <th>Rincian Dokumen / Item Pemeriksaan SOP</th>
                <th style="width: 70px;">Ada & Sesuai</th>
                <th style="width: 70px;">Tidak Ada</th>
                <th style="width: 140px;">Keterangan / Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itemsToShow as $idx => $item)
                @php
                    $isReady = !empty($item['disiapkan']);
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>{{ $item['nama'] ?? 'Dokumen' }}</td>
                    <td style="text-align: center;">
                        <div class="box-check {{ $isReady ? 'checked' : '' }}">
                            {{ $isReady ? '✔' : '' }}
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <div class="box-check {{ !$isReady ? 'checked' : '' }}">
                            {{ !$isReady ? '✔' : '' }}
                        </div>
                    </td>
                    <td style="font-size: 7pt; color: #475569;">
                        @if($isReady)
                            Lengkap fisik
                        @else
                            Bagian Bendahara / PPK
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ======================================================== -->
    <!-- KESIMPULAN & CATATAN PEMERIKSA                           -->
    <!-- ======================================================== -->
    <div class="section-heading">II. CATATAN & KESIMPULAN HASIL VERIFIKASI</div>
    <div class="catatan-box">
        <div class="catatan-title">Catatan Verifikator Subbagian Umum:</div>
        <div class="catatan-content">
            {{ $spj->catatan_internal ?: ($spj->catatan_revisi ?: 'Dokumen telah diperiksa dan disesuaikan dengan ketentuan Standar Operasional Prosedur (SOP) penyelesaian tagihan SPJ di BPS Kabupaten Kolaka Utara.') }}
        </div>
        <div class="rekomendasi-row">
            <strong>Kesimpulan:</strong> &nbsp;
            @if(in_array($spj->status, ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'selesai']))
                [ <strong>✔</strong> ] <strong>MEMENUHI SYARAT (Lengkap & Benar, Diteruskan ke PPK)</strong> &nbsp;&nbsp;&nbsp;&nbsp; [ &nbsp; ] PERLU REVISI
            @elseif(str_contains($spj->status, 'revisi'))
                [ &nbsp; ] MEMENUHI SYARAT &nbsp;&nbsp;&nbsp;&nbsp; [ <strong>✔</strong> ] <strong>PERLU PERBAIKAN / DIKEMBALIKAN KE TEKNIS</strong>
            @else
                [ <strong>✔</strong> ] Dalam Proses Verifikasi Subbagian Umum
            @endif
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TANDA TANGAN SECTION                                     -->
    <!-- ======================================================== -->
    <table class="ttd-table">
        <tr>
            <td>
                <p>Mengetahui,</p>
                <p><strong>Pejabat Pembuat Komitmen (PPK)</strong></p>
                <div class="ttd-space"></div>
                <p class="ttd-name">( ............................................................ )</p>
                <p class="ttd-nip">NIP. ......................................................</p>
            </td>
            <td>
                <p>Lasusua, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                <p><strong>Petugas Verifikasi Subbag Umum</strong></p>
                <div class="ttd-space"></div>
                <p class="ttd-name">( {{ auth()->user()->name ?? 'Petugas Verifikasi Umum' }} )</p>
                <p class="ttd-nip">NIP. {{ auth()->user()->nip ?? '......................................................' }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
