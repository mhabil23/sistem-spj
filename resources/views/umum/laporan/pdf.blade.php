<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi SPJ - BPS Kabupaten Kolaka Utara</title>
    <style>
        @page {
            margin: 12mm 15mm;
            size: A4 landscape;
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
            width: 130px;
        }

        .kop-text {
            text-align: right;
        }

        .kop-text h1 {
            font-size: 12pt;
            font-weight: bold;
            color: #1e4253;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .kop-text h2 {
            font-size: 10pt;
            font-weight: 600;
            color: #334155;
            margin-bottom: 1px;
        }

        .kop-text p {
            font-size: 7.5pt;
            color: #64748b;
        }

        /* TITLE BANNER */
        .doc-title-box {
            background-color: #1e4253;
            color: #ffffff;
            text-align: center;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 12px;
        }

        .doc-title-box h3 {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .doc-title-box p {
            font-size: 8pt;
            margin-top: 3px;
            opacity: 0.95;
        }

        /* SUMMARY KPI BOX */
        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }

        .summary-box td {
            padding: 6px 10px;
            font-size: 8pt;
            vertical-align: top;
        }

        .summary-title {
            font-weight: bold;
            color: #1e4253;
        }

        /* TABLE DATA */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 7px;
            font-size: 8pt;
        }

        .report-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 7.5pt;
        }

        .report-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .report-table tfoot td {
            background-color: #f1f5f9;
            font-weight: bold;
            font-size: 8.5pt;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }

        /* BADGES IN PDF */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 7pt;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }

        .badge-selesai { background: #dcfce7; color: #166534; }
        .badge-diajukan { background: #dbeafe; color: #1e40af; }
        .badge-umum { background: #e0f2fe; color: #0369a1; }
        .badge-ppk { background: #f3e8ff; color: #6b21a8; }
        .badge-ppspm { background: #fef3c7; color: #92400e; }
        .badge-revisi { background: #fee2e2; color: #991b1b; }

        /* TANDA TANGAN SECTION */
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
            font-size: 8.5pt;
        }

        .ttd-space {
            height: 55px;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd-nip {
            font-size: 7.5pt;
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
            <td style="width: 35%;">
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
            <td class="kop-text" style="width: 65%;">
                <h1>Badan Pusat Statistik</h1>
                <h2>Kabupaten Kolaka Utara</h2>
                <p>Jl. Trans Sulawesi Kompleks Perkantoran Pemda Kolaka Utara, Lasusua</p>
                <p>Email: bps7408@bps.go.id | Website: kolakautarakab.bps.go.id</p>
            </td>
        </tr>
    </table>

    <!-- ======================================================== -->
    <!-- JUDUL DOKUMEN LAPORAN                                    -->
    <!-- ======================================================== -->
    <div class="doc-title-box">
        <h3>Laporan Rekapitulasi Surat Pertanggungjawaban (SPJ)</h3>
        <p>{{ $periodeLabel }} &bull; Tanggal Cetak: {{ $tanggalCetak }}</p>
    </div>

    <!-- ======================================================== -->
    <!-- SUMMARY BOX                                              -->
    <!-- ======================================================== -->
    <table class="summary-box">
        <tr>
            <td style="width: 25%;">
                <div class="summary-title">Total Berkas SPJ:</div>
                <div style="font-size: 10pt; font-weight: bold;">{{ $stats['totalSpj'] }} Berkas</div>
            </td>
            <td style="width: 30%;">
                <div class="summary-title">Total Nilai Anggaran:</div>
                <div style="font-size: 10pt; font-weight: bold; color: #1e4253;">
                    Rp {{ number_format($stats['totalNilai'], 0, ',', '.') }}
                </div>
            </td>
            <td style="width: 25%;">
                <div class="summary-title">Telah Cair (Selesai):</div>
                <div style="font-weight: bold; color: #166534;">
                    Rp {{ number_format($stats['totalNilaiSelesai'], 0, ',', '.') }} ({{ $stats['totalSelesai'] }} Berkas)
                </div>
            </td>
            <td style="width: 20%;">
                <div class="summary-title">Proses & Revisi:</div>
                <div>{{ $stats['totalDiproses'] }} Proses | {{ $stats['totalRevisi'] }} Revisi</div>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="border-top: 1px dashed #cbd5e1; font-size: 7.5pt; color: #475569;">
                <em>Terbilang: {{ $terbilangTotal }}</em>
            </td>
        </tr>
    </table>

    <!-- ======================================================== -->
    <!-- TABEL DATA SPJ                                           -->
    <!-- ======================================================== -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 90px;">No. SPJ</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 110px;">Pengaju (Teknis)</th>
                <th>Nama Kegiatan</th>
                <th style="width: 90px;">Nilai (Rp)</th>
                <th style="width: 85px;">Status</th>
                <th style="width: 100px;">Keterangan / Tgl Cair</th>
            </tr>
        </thead>
        <tbody>
            @forelse($spjs as $index => $spj)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-mono" style="font-size: 7.5pt;">
                    <strong>{{ $spj->nomor_spj ?: 'SPJ-'.$spj->id }}</strong>
                    @if($spj->nomor_spm)
                        <div style="color: #0369a1; font-size: 6.5pt;">SPM: {{ $spj->nomor_spm }}</div>
                    @endif
                </td>
                <td class="text-center" style="font-size: 7.5pt;">
                    {{ $spj->tanggal ? $spj->tanggal->format('d/m/Y') : '-' }}
                </td>
                <td>
                    <div style="font-weight: bold;">{{ $spj->user->name ?? 'Teknis' }}</div>
                    <div style="font-size: 6.5pt; color: #64748b;">{{ $spj->user->email ?? '-' }}</div>
                </td>
                <td>{{ $spj->kegiatan }}</td>
                <td class="text-right font-mono" style="font-size: 7.5pt; font-weight: bold;">
                    {{ number_format($spj->nilai, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    @if($spj->status === 'selesai')
                        <span class="badge badge-selesai">Selesai (Cair)</span>
                    @elseif($spj->status === 'disetujui_ppspm')
                        <span class="badge badge-ppspm">Di Bendahara</span>
                    @elseif($spj->status === 'disetujui_ppk')
                        <span class="badge badge-ppk">Di PPSPM</span>
                    @elseif($spj->status === 'disetujui_umum')
                        <span class="badge badge-umum">Di PPK</span>
                    @elseif(str_contains($spj->status, 'revisi'))
                        <span class="badge badge-revisi">Revisi</span>
                    @else
                        <span class="badge badge-diajukan">Diajukan</span>
                    @endif
                </td>
                <td style="font-size: 7pt;">
                    @if($spj->diselesaikan_at)
                        <span style="color: #166534; font-weight: bold;">Cair: {{ $spj->diselesaikan_at->format('d/m/Y') }}</span>
                    @elseif($spj->catatan_revisi && str_contains($spj->status, 'revisi'))
                        <span style="color: #991b1b;">{{ \Illuminate\Support\Str::limit($spj->catatan_revisi, 35) }}</span>
                    @else
                        Update: {{ $spj->updated_at->format('d/m/Y') }}
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 16px; color: #64748b;">
                    Tidak ada data SPJ untuk periode laporan ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($spjs->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="5" class="text-right" style="text-transform: uppercase;">
                    Total Keseluruhan ({{ $spjs->count() }} Berkas SPJ):
                </td>
                <td class="text-right font-mono" style="font-weight: bold;">
                    Rp {{ number_format($stats['totalNilai'], 0, ',', '.') }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- ======================================================== -->
    <!-- TANDA TANGAN SECTION                                     -->
    <!-- ======================================================== -->
    <table class="ttd-table">
        <tr>
            <td>
                <p>Mengetahui,</p>
                <p><strong>Kepala Badan Pusat Statistik<br>Kabupaten Kolaka Utara</strong></p>
                <div class="ttd-space"></div>
                <p class="ttd-name">( ............................................................ )</p>
                <p class="ttd-nip">NIP. ......................................................</p>
            </td>
            <td>
                <p>Lasusua, {{ $tanggalCetak }}</p>
                <p><strong>Pengadministrasi / Verifikator Umum<br>Subbagian Umum</strong></p>
                <div class="ttd-space"></div>
                <p class="ttd-name">( {{ auth()->user()->name ?? 'Petugas Verifikasi Umum' }} )</p>
                <p class="ttd-nip">NIP. {{ auth()->user()->nip ?? '......................................................' }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
