<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi SPJ - {{ $spj->nomor_spj ?: 'SPJ #'.$spj->id }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm 10mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DejaVu Sans', Arial, "Helvetica Neue", Helvetica, sans-serif;
            background: #ffffff;
            color: #111827;
            font-size: 8pt;
            line-height: 1.35;
        }

        /* TOOLBAR KHUSUS BROWSER JIKA DIBUKA FORMAT HTML */
        .no-print {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            background: #0f172a;
            color: #ffffff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 99999;
            margin-bottom: 16px;
            font-family: Arial, sans-serif;
        }

        .btn-print-toolbar {
            background: #0284c7;
            color: #ffffff;
            border: none;
            padding: 7px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-close-toolbar {
            background: #475569;
            color: #ffffff;
            border: none;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
        }

        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; }
        }

        /* HEADER KOP LAPORAN */
        .header-section {
            text-align: center;
            margin-bottom: 12px;
        }

        .header-logo {
            height: 48px;
            margin-bottom: 4px;
        }

        .header-title {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin: 2px 0;
        }

        .header-subtitle {
            font-size: 9.5pt;
            color: #374151;
            margin: 0;
        }

        /* META BAR */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-bottom: 8px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
        }

        .meta-table td {
            padding: 3px 2px;
            vertical-align: middle;
        }

        /* TABLE CETAK REKAPITULASI */
        .cetak-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
            text-align: center;
            margin-bottom: 12px;
        }

        .cetak-table th, .cetak-table td {
            border: 1px solid #334155;
            padding: 4px 3px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .cetak-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
        }

        .cetak-table tbody tr:nth-child(even) {
            background-color: #fafbfc;
        }

        .check-mark {
            font-size: 10pt;
            font-weight: bold;
            color: #0f172a;
        }

        /* TANDA TANGAN */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            page-break-inside: avoid;
        }

        .ttd-table td {
            border: none;
            vertical-align: top;
        }

        .ttd-box {
            width: 250px;
            font-size: 8.5pt;
            line-height: 1.35;
            text-align: left;
        }

        .ttd-space {
            height: 44px;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
    </style>
</head>
<body>

    @if(request('format') === 'html')
        <div class="no-print">
            <div style="display: flex; align-items: center; gap: 8px;">
                <strong style="font-size: 13px;">📄 Pratinjau Lembar Checklist Berkas Fisik SPJ</strong>
                <span style="background: rgba(255,255,255,0.15); padding: 2px 8px; border-radius: 4px; font-size: 11px;">Tab Baru</span>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="window.print()" class="btn-print-toolbar">🖨️ Cetak Lembar Checklist</button>
                <button type="button" onclick="window.close()" class="btn-close-toolbar">✕ Tutup Tab</button>
            </div>
        </div>
    @endif

    @php
        $logoPath = public_path('images/logo-bps.png');
        $logoSrc = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : asset('images/logo-bps.png');

        $docs = $spj->dokumen_list;
        if (empty($docs) && !empty($spj->dokumen_file) && str_starts_with(trim($spj->dokumen_file), '[')) {
            $docs = json_decode($spj->dokumen_file, true) ?: [];
        }

        // Pemetaan label ringkas agar pas pada tabel landscape
        $cleanLabel = function($name) {
            $map = [
                'KAK' => 'KAK',
                'FP' => 'Form Permintaan',
                'Form Permintaan' => 'Form Permintaan',
                'kebutuhan barang' => 'Dokumen Kebutuhan',
                'Dokumen permintaan' => 'Dokumen Kebutuhan',
                'Surat Pesanan' => 'Surat Pesanan',
                'bukti pemesanan' => 'Surat Pesanan',
                'Surat Perjanjian' => 'Surat Perjanjian',
                'Surat Tugas' => 'Surat Tugas',
                'SPPD' => 'Surat Tugas',
                'Visum' => 'Surat Tugas',
                'SK KPA' => 'SK / SPK',
                'Surat Keputusan' => 'SK / SPK',
                'SPK' => 'SK / SPK',
                'Kontrak' => 'Kontrak',
                'SPMK' => 'SPMK',
                'Surat Perintah Mulai Kerja' => 'SPMK',
                'progres pekerjaan' => 'Progres Pekerjaan',
                'Undangan' => 'Undangan',
                'Daftar Hadir Lembur' => 'Hadir Lembur',
                'Perintah Lembur' => 'Surat Lembur',
                'Perhitungan Uang Lembur' => 'Hitung Lembur',
                'Perhitungan Lembur' => 'Hitung Lembur',
                'Daftar Hadir' => 'Daftar Hadir',
                'Daftar Penerima' => 'Daftar Penerima',
                'Daftar Gaji' => 'Daftar Gaji',
                'Rekapitulasi' => 'Rekapitulasi',
                'SPTJM' => 'SPTJM',
                'Notula' => 'Notula / Laporan',
                'Laporan' => 'Notula / Laporan',
                'Bukti pembelian' => 'Bukti Beli / Nota',
                'nota' => 'Bukti Beli / Nota',
                'kwitansi' => 'Bukti Beli / Nota',
                'Kuitansi' => 'Bukti Beli / Nota',
                'invoice' => 'Invoice / Faktur',
                'Invoice' => 'Invoice / Faktur',
                'Pemeriksaan Barang' => 'BA Pemeriksaan',
                'Pemeriksaan Pekerjaan' => 'BA Pemeriksaan',
                'Berita Acara Pemeriksaan' => 'BA Pemeriksaan',
                'BAST' => 'BAST',
                'Berita Acara Serah Terima' => 'BAST',
                'Faktur Pajak' => 'Bukti Pajak',
                'SSP' => 'Bukti Pajak',
                'PPh' => 'Bukti Pajak',
                'PPn' => 'Bukti Pajak',
                'Dokumentasi' => 'Dokumentasi',
                'Bukti transaksi' => 'Bukti Pembayaran',
                'Dokumen pembayaran' => 'Bukti Pembayaran',
                'Bukti pembayaran' => 'Bukti Pembayaran',
                'Screenshoot bukti transfer' => 'Bukti Transfer',
                'Kesesuaian MRK' => 'Kesesuaian MRK',
                'CMS' => 'CMS',
                'SBKS' => 'Cek SBKS'
            ];

            foreach ($map as $key => $lbl) {
                if (stripos($name, $key) !== false) {
                    return $lbl;
                }
            }

            return strlen($name) > 22 ? substr($name, 0, 20) . '...' : $name;
        };

        // Kumpulkan kolom dokumen dinamis
        $docColumns = [];
        $seen = [];
        if (!empty($docs)) {
            foreach ($docs as $d) {
                $rawName = $d['nama'] ?? 'Dokumen';
                $lbl = $cleanLabel($rawName);
                if (isset($seen[$lbl])) {
                    $seen[$lbl]++;
                    $lbl = $lbl . ' (' . $seen[$lbl] . ')';
                } else {
                    $seen[$lbl] = 1;
                }
                $docColumns[] = [
                    'label' => $lbl,
                    'full' => $rawName,
                    'checked' => !empty($d['disiapkan'])
                ];
            }
        } else {
            // Standar jika SPJ draf lama belum mengisi kelengkapan dokumen
            $defaultItems = ['KAK', 'Form Permintaan', 'Surat Tugas', 'Bukti Beli / Nota', 'BAST'];
            foreach ($defaultItems as $item) {
                $docColumns[] = ['label' => $item, 'full' => $item, 'checked' => true];
            }
        }

        $totalDocCols = count($docColumns);
        $totalKelengkapanColspan = $totalDocCols + 4;

        // Deteksi Cerdas Nama Belanja & Jenis Belanja
        $docNames = array_map(fn($d) => strtolower($d['nama'] ?? ''), $docs);
        $allDocText = implode(' ', $docNames);
        $kegiatanLower = strtolower($spj->kegiatan);

        $namaBelanja = 'Belanja Operasional';
        $jenisBelanja = $spj->seksi_label ?? ($spj->seksi ?: 'Kegiatan BPS');

        if (str_contains($kegiatanLower, 'gaji pns') || (str_contains($allDocText, 'gaji induk') && !str_contains($kegiatanLower, 'pppk'))) {
            $namaBelanja = 'Belanja Gaji PNS (5111)';
            $jenisBelanja = 'Belanja Gaji PNS';
        } elseif (str_contains($kegiatanLower, 'gaji pppk') || (str_contains($allDocText, 'gaji induk') && str_contains($kegiatanLower, 'pppk'))) {
            $namaBelanja = 'Belanja Gaji PPPK (5116)';
            $jenisBelanja = 'Belanja Gaji PPPK';
        } elseif (str_contains($kegiatanLower, 'tukin') || str_contains($kegiatanLower, 'tunjangan kinerja') || str_contains($allDocText, 'sptjm')) {
            if (str_contains($kegiatanLower, 'pppk')) {
                $namaBelanja = 'Belanja Tunjangan Kinerja PPPK (512414)';
                $jenisBelanja = 'Belanja Tunjangan Kinerja PPPK';
            } else {
                $namaBelanja = 'Belanja Tunjangan Kinerja PNS (512411)';
                $jenisBelanja = 'Belanja Tunjangan Kinerja PNS';
            }
        } elseif (str_contains($kegiatanLower, 'uang makan')) {
            if (str_contains($kegiatanLower, 'pppk')) {
                $namaBelanja = 'Belanja Uang Makan PPPK (511628)';
                $jenisBelanja = 'Belanja Uang Makan PPPK';
            } else {
                $namaBelanja = 'Belanja Uang Makan PNS (511129)';
                $jenisBelanja = 'Belanja Uang Makan PNS';
            }
        } elseif (str_contains($kegiatanLower, 'lembur') || str_contains($allDocText, 'hadir lembur') || str_contains($allDocText, 'perhitungan uang lembur')) {
            $namaBelanja = 'Belanja Lembur (512211)';
            $jenisBelanja = 'Belanja Lembur';
        } elseif (str_contains($kegiatanLower, 'modal') || str_contains($kegiatanLower, 'pengadaan') || str_contains($allDocText, 'pemeriksaan barang') || str_contains($allDocText, 'permintaan atau kebutuhan barang')) {
            $namaBelanja = 'Belanja Modal (53)';
            if (str_contains($allDocText, 'kartu kredit') || str_contains($allDocText, 'marketplace')) {
                $jenisBelanja = 'Pembelian Langsung dengan Kartu Kredit Pemerintah atau Marketplace';
            } elseif (str_contains($allDocText, 'surat perintah mulai kerja') || str_contains($allDocText, 'progres pekerjaan')) {
                $jenisBelanja = 'Pengadaan melalui Kontrak';
            } elseif (str_contains($allDocText, 'pembelian langsung oleh bendahara')) {
                $jenisBelanja = 'Pembelian Langsung oleh Bendahara Pengeluaran';
            } else {
                $jenisBelanja = 'Pengadaan Langsung melalui Penyedia';
            }
        } elseif (str_contains($kegiatanLower, 'perjalanan') || str_contains($allDocText, 'surat tugas') || str_contains($allDocText, 'visum') || str_contains($allDocText, 'sppd')) {
            $namaBelanja = 'Belanja Perjalanan Dinas Biasa (524111)';
            $jenisBelanja = 'Perjalanan Dinas Biasa';
        } elseif (str_contains($kegiatanLower, 'konsumsi') || str_contains($allDocText, 'konsumsi') || (str_contains($allDocText, 'daftar hadir') && str_contains($allDocText, 'notula'))) {
            $namaBelanja = 'Belanja Bahan (521211)';
            $jenisBelanja = 'Belanja Konsumsi';
        } elseif (str_contains($kegiatanLower, 'paket data') || str_contains($allDocText, 'paket data')) {
            $namaBelanja = 'Belanja Bahan (521211)';
            $jenisBelanja = 'Belanja Paket Data Komunikasi';
        } elseif (str_contains($kegiatanLower, 'honor') || str_contains($allDocText, '512213')) {
            $namaBelanja = 'Honor Output Kegiatan (512213)';
            $jenisBelanja = 'Honor Output Kegiatan';
        }

        $tanggalPenginputan = $spj->created_at ? $spj->created_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y');
        $tglMasukUmum = $spj->disetujui_umum_at ? \Carbon\Carbon::parse($spj->disetujui_umum_at)->translatedFormat('d F Y') : '-';
        $tglPeriksaPpk = $spj->disetujui_ppk_at ? \Carbon\Carbon::parse($spj->disetujui_ppk_at)->translatedFormat('d F Y') : '-';
    @endphp

    <!-- HEADER / KOP LAPORAN REKAPITULASI -->
    <div class="header-section">
        @if($logoSrc)
            <img src="{{ $logoSrc }}" alt="Logo BPS" class="header-logo">
        @endif
        <h2 class="header-title">LAPORAN REKAPITULASI SPJ</h2>
        <p class="header-subtitle">Badan Pusat Statistik Kabupaten Kolaka Utara</p>
    </div>

    <!-- META BAR INFORMASI SPJ -->
    <table class="meta-table">
        <tr>
            <td style="text-align: left;">
                No. SPJ: <strong>{{ $spj->nomor_spj ?: '—' }}</strong> |
                Periode Kegiatan: <strong>{{ $spj->periode_kegiatan_ringkas }}</strong> |
                Staf Pengaju / PIC: <strong>{{ $spj->user->name ?? 'Pengguna BPS' }}</strong> {{ $spj->no_wa ? '(' . $spj->no_wa . ')' : '' }}
            </td>
            <td style="text-align: right; white-space: nowrap;">
                Belanja: <strong>{{ $namaBelanja }} ({{ $jenisBelanja }})</strong>
            </td>
        </tr>
    </table>

    <!-- TABEL CETAK LAPORAN REKAPITULASI CHECKLIST FISIK SPJ -->
    <table class="cetak-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 25px;">No</th>
                <th rowspan="2" style="min-width: 160px; max-width: 260px;">Nama Kegiatan</th>
                <th rowspan="2" style="width: 85px; white-space: nowrap;">Jumlah Anggaran</th>
                <th rowspan="2" style="width: 75px;">Tanggal Penginputan SPJ</th>
                <th colspan="{{ $totalKelengkapanColspan }}">Kelengkapan Administrasi</th>
                <th rowspan="2" style="width: 75px;">Keterangan</th>
            </tr>
            <tr>
                @foreach($docColumns as $col)
                    <th style="min-width: 32px; font-size: 6.5pt;">{{ $col['label'] }}</th>
                @endforeach
                <th style="width: 55px; font-size: 6.5pt;">Tgl Masuk SPJ</th>
                <th style="width: 55px; font-size: 6.5pt;">Tgl Periksa PPK</th>
                <th style="width: 40px; font-size: 6.5pt;">Paraf PPSPM</th>
                <th style="width: 40px; font-size: 6.5pt;">Paraf Bendahara</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: bold;">1</td>
                <td style="text-align: left; padding: 4px 6px; font-size: 7.5pt;">
                    {{ $spj->kegiatan }}
                </td>
                <td style="text-align: right; white-space: nowrap; font-weight: bold; padding: 4px 5px;">
                    Rp. {{ number_format($spj->nilai, 0, ',', '.') }}
                </td>
                <td style="white-space: nowrap;">
                    {{ $tanggalPenginputan }}
                </td>
                @foreach($docColumns as $col)
                    <td>
                        @if($col['checked'])
                            <span class="check-mark">&radic;</span>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                @endforeach
                <td style="white-space: nowrap; font-size: 6.5pt;">
                    {{ $tglMasukUmum }}
                </td>
                <td style="white-space: nowrap; font-size: 6.5pt;">
                    {{ $tglPeriksaPpk }}
                </td>
                <td></td>
                <td></td>
                <td style="text-align: left; font-size: 6.5pt; padding: 3px 4px;">
                    {{ $spj->keterangan ?: '-' }}
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f8fafc;">
                <td colspan="2" style="text-align: right; padding-right: 12px; font-weight: bold;">TOTAL</td>
                <td style="text-align: right; white-space: nowrap; font-weight: bold; padding: 4px 5px;">
                    Rp. {{ number_format($spj->nilai, 0, ',', '.') }}
                </td>
                <td colspan="{{ $totalKelengkapanColspan + 2 }}"></td>
            </tr>
        </tfoot>
    </table>

    <!-- TANDA TANGAN PEJABAT PEMBUAT KOMITMEN -->
    <table class="ttd-table">
        <tr>
            <td style="width: 70%;"></td>
            <td style="width: 30%;">
                <div class="ttd-box">
                    <p style="margin: 0;">Lasusua, {{ now()->translatedFormat('d F Y') }}</p>
                    <p style="margin: 2px 0 0 0; font-weight: 600;">Pejabat Pembuat Komitmen</p>
                    <div class="ttd-space"></div>
                    <p class="ttd-name">Hidayatullah, S.E.</p>
                    <p style="margin: 1px 0 0 0; font-size: 7.5pt; color: #374151;">NIP. 198510292011011009</p>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
