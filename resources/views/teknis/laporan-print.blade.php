<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi SPJ - BPS Kabupaten Kolaka Utara</title>
    <style>
        @page {
            size: landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            background: #ffffff;
            color: #111827;
            margin: 0;
            padding: 0;
            font-size: 10px;
        }

        /* TOOLBAR KHUSUS BROWSER (DISEMBUNYIKAN SAAT DICETAK) */
        .print-toolbar {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            background: #0f172a;
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 99999;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .print-toolbar-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .print-toolbar-title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .print-toolbar-badge {
            background: rgba(255, 255, 255, 0.15);
            color: #e2e8f0;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }

        .print-toolbar-actions {
            display: flex;
            gap: 10px;
        }

        .btn-toolbar-print {
            background: #0284c7;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }

        .btn-toolbar-print:hover {
            background: #0369a1;
        }

        .btn-toolbar-close {
            background: #475569;
            color: #ffffff;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-toolbar-close:hover {
            background: #334155;
        }

        /* CONTAINER LAPORAN CETAK */
        .print-content {
            padding: 20px 25px;
            max-width: 100%;
            margin: 0 auto;
        }

        .print-header {
            text-align: center;
            margin-bottom: 16px;
        }

        .print-header img {
            height: 50px;
            margin-bottom: 6px;
        }

        .print-header h2 {
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 4px 0 2px 0;
            color: #0f172a;
        }

        .print-header p {
            font-size: 11px;
            color: #475569;
            margin: 0;
        }

        .meta-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            margin-bottom: 12px;
            padding: 6px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        .table-responsive {
            overflow: visible;
            width: 100%;
        }

        .cetak-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            text-align: center;
            margin-bottom: 20px;
            table-layout: auto;
        }

        .cetak-table th, .cetak-table td {
            border: 1px solid #334155;
            padding: 4px 3px;
            word-wrap: break-word;
        }

        .cetak-table th {
            background-color: #f1f5f9;
            font-weight: 700;
            color: #0f172a;
            min-width: 25px;
        }

        .ttd-container {
            display: flex;
            justify-content: flex-end;
            padding-right: 50px;
            text-align: left;
            font-size: 11px;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .print-content {
                padding: 0 !important;
            }

            body {
                background: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .cetak-table {
                font-size: 7.5pt !important;
            }

            .cetak-table th {
                background-color: #f1f5f9 !important;
            }
        }
    </style>
</head>
<body>

    <!-- TOOLBAR DI ATAS TAB BARU -->
    <div class="print-toolbar no-print">
        <div class="print-toolbar-info">
            <span class="print-toolbar-title">📄 Pratinjau Laporan Rekapitulasi SPJ</span>
            <span class="print-toolbar-badge">Tab Cetak Terpisah</span>
        </div>
        <div class="print-toolbar-actions">
            <button type="button" onclick="window.print()" class="btn-toolbar-print">
                🖨️ Cetak Laporan
            </button>
            <button type="button" onclick="window.close()" class="btn-toolbar-close">
                ✕ Tutup Tab
            </button>
        </div>
    </div>

    <!-- KONTEN UTAMA CETAK -->
    <div class="print-content">

        <!-- KOP BPS -->
        <div class="print-header">
            <img src="{{ asset('images/logo-bps.png') }}" alt="Logo BPS" onerror="this.style.display='none'">
            <h2>LAPORAN REKAPITULASI SPJ</h2>
            <p>Badan Pusat Statistik Kabupaten Kolaka Utara</p>
        </div>

        <!-- INFO PERIODE & PENGGUNA -->
        <div class="meta-bar">
            <div>
                @if(request('tanggal_mulai') || request('tanggal_selesai'))
                    Periode: <strong>{{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))->translatedFormat('d F Y') : 'Awal' }}</strong> s/d <strong>{{ request('tanggal_selesai') ? \Carbon\Carbon::parse(request('tanggal_selesai'))->translatedFormat('d F Y') : 'Akhir' }}</strong>
                @else
                    Periode: <strong>Semua Periode Data</strong>
                @endif
                @if(request('status'))
                    &bull; Status: <strong>{{ ucfirst(str_replace('_', ' ', request('status'))) }}</strong>
                @endif
            </div>
            <div>
                Staf Pengaju: <strong>{{ $user->name ?? 'Staf Teknis' }}</strong> ({{ $user->email ?? '-' }}) &bull; Total: <strong>{{ $totalSpj }} SPJ</strong>
            </div>
        </div>

        @php
            $masterDocDefs = [
                'kak' => [
                    'label' => 'KAK',
                    'keywords' => ['KAK'],
                    'legacy' => ['kel_kak']
                ],
                'fp' => [
                    'label' => 'Form Permintaan',
                    'keywords' => ['FP', 'Form Permintaan'],
                    'legacy' => ['kel_form_permintaan']
                ],
                'surat_tugas' => [
                    'label' => 'Surat Tugas',
                    'keywords' => ['Surat Tugas', 'SPPD', 'Visum'],
                    'legacy' => ['kel_surat_tugas']
                ],
                'sk_spk' => [
                    'label' => 'SK / SPK',
                    'keywords' => ['SK KPA', 'Surat Keputusan', 'Surat kontrak', 'SPK', 'Polis', 'Perjanjian'],
                    'legacy' => ['kel_sk', 'kel_spk']
                ],
                'undangan' => [
                    'label' => 'Undangan',
                    'keywords' => ['Undangan', 'Surat Undangan'],
                    'legacy' => []
                ],
                'daftar_hadir' => [
                    'label' => 'Daftar Hadir / Penerima',
                    'keywords' => ['Daftar Hadir', 'Daftar Penerima', 'Daftar nama', 'Daftar Alokasi', 'Daftar rekap kuitansi'],
                    'legacy' => ['kel_daftar_penerima']
                ],
                'notula' => [
                    'label' => 'Notula / Laporan',
                    'keywords' => ['Notula', 'Notulensi', 'Laporan tim', 'Laporan pengajar', 'Laporan pelaksanaan', 'Laporan perjalanan dinas'],
                    'legacy' => []
                ],
                'bukti_beli' => [
                    'label' => 'Bukti Beli / Nota',
                    'keywords' => ['Bukti pembelian', 'Bukti Pembelian', 'nota', 'kwitansi', 'Kuitansi', 'invoice', 'Invoice', 'Tagihan', 'Tiket', 'Kwitansi Transpor', 'Daftar Pengeluaran riil'],
                    'legacy' => []
                ],
                'bast' => [
                    'label' => 'BAST',
                    'keywords' => ['BAST', 'BAPP'],
                    'legacy' => ['kel_bast']
                ],
                'pajak' => [
                    'label' => 'Bukti Bayar / Pajak',
                    'keywords' => ['Faktur Pajak', 'SSP', 'PPh', 'PPn', 'Screenshoot bukti transfer'],
                    'legacy' => []
                ],
                'mrk' => [
                    'label' => 'Kesesuaian MRK',
                    'keywords' => ['Kesesuaian MRK'],
                    'legacy' => ['kel_kesesuaian_mrk']
                ],
                'cms' => [
                    'label' => 'CMS',
                    'keywords' => ['CMS'],
                    'legacy' => ['kel_cms']
                ],
                'sbks' => [
                    'label' => 'Cek SBKS',
                    'keywords' => ['SBKS'],
                    'legacy' => ['kel_cek_sbks']
                ],
            ];

            if (!function_exists('isDocCheckedPrint')) {
                function isDocCheckedPrint($spj, $def) {
                    if (!empty($def['legacy'])) {
                        foreach ($def['legacy'] as $col) {
                            if (!empty($spj->$col)) return true;
                        }
                    }
                    if ($spj->dokumen_file) {
                        $docs = json_decode($spj->dokumen_file, true);
                        if (is_array($docs)) {
                            foreach ($docs as $doc) {
                                if (!empty($doc['disiapkan'])) {
                                    $docName = $doc['nama'] ?? '';
                                    foreach ($def['keywords'] as $kw) {
                                        if (stripos($docName, $kw) !== false) {
                                            return true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    return false;
                }
            }

            $activeDocColumns = [];
            foreach ($masterDocDefs as $key => $def) {
                $hasAny = false;
                foreach ($spjs as $spj) {
                    if (isDocCheckedPrint($spj, $def)) {
                        $hasAny = true;
                        break;
                    }
                }
                if ($hasAny) {
                    $activeDocColumns[$key] = $def;
                }
            }

            if (empty($activeDocColumns)) {
                $activeDocColumns = [
                    'kak' => $masterDocDefs['kak'],
                    'fp' => $masterDocDefs['fp'],
                    'daftar_hadir' => $masterDocDefs['daftar_hadir'],
                    'bast' => $masterDocDefs['bast'],
                ];
            }

            $totalDocCols = count($activeDocColumns);
            $totalKelengkapanColspan = $totalDocCols + 4;
            $totalTableColumns = 4 + $totalKelengkapanColspan + 1;
        @endphp

        <div class="table-responsive">
            <table class="cetak-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 30px;">No</th>
                        <th rowspan="2" style="width: 220px;">Nama Kegiatan</th>
                        <th rowspan="2" style="width: 100px;">Jumlah Anggaran</th>
                        <th rowspan="2" style="width: 85px;">Tanggal SPJ</th>
                        <th colspan="{{ $totalKelengkapanColspan }}">Kelengkapan Administrasi</th>
                        <th rowspan="2" style="width: 80px;">Keterangan</th>
                    </tr>
                    <tr>
                        @foreach($activeDocColumns as $colDef)
                            <th>{{ $colDef['label'] }}</th>
                        @endforeach
                        <th>Tgl Masuk SPJ</th>
                        <th>Tgl Periksa PPK</th>
                        <th>Paraf PPSPM</th>
                        <th>Paraf Bendahara</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spjs as $index => $spj)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="text-align: left; padding: 4px 6px;">{{ $spj->kegiatan }}</td>
                        <td style="text-align: right; white-space: nowrap; font-weight: 600;">Rp. {{ number_format($spj->nilai, 0, ',', '.') }}</td>
                        <td>{!! $spj->tanggal ? $spj->tanggal->translatedFormat('d F Y') : '-' !!}</td>
                        
                        @foreach($activeDocColumns as $colDef)
                            <td>{!! isDocCheckedPrint($spj, $colDef) ? '&radic;' : '' !!}</td>
                        @endforeach

                        <td>{!! $spj->disetujui_umum_at ? \Carbon\Carbon::parse($spj->disetujui_umum_at)->translatedFormat('d/m/Y') : '-' !!}</td>
                        <td>{!! $spj->disetujui_ppk_at ? \Carbon\Carbon::parse($spj->disetujui_ppk_at)->translatedFormat('d/m/Y') : '-' !!}</td>
                        <td>{!! $spj->disetujui_ppspm_at ? '&radic;' : '' !!}</td>
                        <td>{!! $spj->diselesaikan_at ? '&radic;' : '' !!}</td>
                        
                        <td style="text-align: left; font-size: 7.5pt;">{{ $spj->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $totalTableColumns }}" style="padding: 24px; font-style: italic; color: #64748b;">
                            Tidak ada data SPJ untuk filter yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($spjs->count() > 0)
                <tfoot>
                    <tr>
                        <th colspan="2" style="text-align: right; padding-right: 15px;">TOTAL</th>
                        <th style="text-align: right; white-space: nowrap;">Rp. {{ number_format($totalNilai, 0, ',', '.') }}</th>
                        <th colspan="{{ $totalTableColumns - 3 }}"></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- TANDA TANGAN -->
        <div class="ttd-container">
            <div>
                <p style="margin: 0;">Lasusua, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p style="margin: 0; font-weight: 600;">Staf Pengaju / Bagian Teknis</p>
                <br><br><br><br>
                <p style="margin: 0; text-decoration: underline; font-weight: bold;">{{ $user->name ?? 'Pengguna BPS' }}</p>
                <p style="margin: 0; color: #475569;">NIP. {{ $user->nip ?? '........................' }}</p>
            </div>
        </div>

    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.focus();
                window.print();
            }, 350);
        });
    </script>
</body>
</html>

