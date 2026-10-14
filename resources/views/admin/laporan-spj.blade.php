@extends('layouts.admin')

@php
$title = 'Laporan SPJ';
$subtitle = 'Rekap dan laporan data SPJ.';
@endphp

@section('content')

<div class="laporan-page">

    {{-- HEADER --}}
    <div class="page-header">

        <div>
            <h2>Laporan SPJ</h2>

            <p>
                @if(request('tanggal_mulai') || request('tanggal_selesai'))
                    Periode: <strong>{{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') : 'Awal' }}</strong> 
                    s/d 
                    <strong>{{ request('tanggal_selesai') ? \Carbon\Carbon::parse(request('tanggal_selesai'))->format('d/m/Y') : 'Akhir' }}</strong>
                    @if(request('status'))
                        | Status: <strong>{{ ucfirst(request('status')) }}</strong>
                    @endif
                @else
                    Rekapitulasi seluruh data SPJ dalam sistem.
                @endif
            </p>
        </div>

        <button
            type="button"
            class="btn-print"
            onclick="window.print()">

            <i class="bi bi-printer"></i>

            Cetak Laporan

        </button>

    </div>


    {{-- FILTER --}}
    <div class="laporan-filter">

        <form
            action="{{ route('admin.laporan.spj') }}"
            method="GET">

            <div class="filter-group">

                <label>
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    name="tanggal_mulai"
                    value="{{ request('tanggal_mulai') }}">

            </div>


            <div class="filter-group">

                <label>
                    Tanggal Selesai
                </label>

                <input
                    type="date"
                    name="tanggal_selesai"
                    value="{{ request('tanggal_selesai') }}">

            </div>


            <div class="filter-group">

                <label>
                    Status
                </label>

                <select name="status">

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="diajukan"
                        {{ request('status') == 'diajukan' ? 'selected' : '' }}>
                        Diajukan
                    </option>

                    <option
                        value="diproses"
                        {{ request('status') == 'diproses' ? 'selected' : '' }}>
                        Diproses
                    </option>

                    <option
                        value="selesai"
                        {{ request('status') == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                    <option
                        value="dikembalikan"
                        {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>
                        Dikembalikan
                    </option>

                    <option
                        value="draft"
                        {{ request('status') == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                </select>

            </div>


            <div class="filter-buttons">

                <button type="submit" class="btn-filter">
                    Terapkan
                </button>

                <a
                    href="{{ route('admin.laporan.spj') }}"
                    class="btn-reset">

                    Reset

                </a>

            </div>

        </form>

    </div>


    {{-- RINGKASAN --}}
    <div class="laporan-stats">

        <div class="laporan-stat-card">

            <div class="laporan-stat-icon blue">
                <i class="bi bi-file-earmark-text"></i>
            </div>

            <div>

                <span>Total SPJ</span>

                <strong>
                    {{ $totalSpj }}
                </strong>

                <small>
                    Data laporan
                </small>

            </div>

        </div>


        <div class="laporan-stat-card">

            <div class="laporan-stat-icon orange">
                <i class="bi bi-send"></i>
            </div>

            <div>

                <span>Diajukan</span>

                <strong>
                    {{ $totalDiajukan }}
                </strong>

                <small>
                    Menunggu proses
                </small>

            </div>

        </div>


        <div class="laporan-stat-card">

            <div class="laporan-stat-icon purple">
                <i class="bi bi-arrow-repeat"></i>
            </div>

            <div>

                <span>Diproses</span>

                <strong>
                    {{ $totalDiproses }}
                </strong>

                <small>
                    Sedang diperiksa
                </small>

            </div>

        </div>


        <div class="laporan-stat-card">

            <div class="laporan-stat-icon green">
                <i class="bi bi-check-circle"></i>
            </div>

            <div>

                <span>Selesai</span>

                <strong>
                    {{ $totalSelesai }}
                </strong>

                <small>
                    SPJ selesai
                </small>

            </div>

        </div>


        <div class="laporan-stat-card">

            <div class="laporan-stat-icon red">
                <i class="bi bi-arrow-return-left"></i>
            </div>

            <div>

                <span>Dikembalikan</span>

                <strong>
                    {{ $totalDikembalikan }}
                </strong>

                <small>
                    Perlu diperbaiki
                </small>

            </div>

        </div>

    </div>

    {{-- TABEL --}}
    <div class="laporan-panel">

        <div class="panel-header">

            <div>

                <h3>
                    Daftar Laporan SPJ
                </h3>

                <p>
                    Data SPJ berdasarkan filter yang dipilih.
                </p>

            </div>

            <span class="jumlah-data">

                {{ $totalSpj }} data

            </span>

        </div>


        <div class="table-wrapper">

            <style>
                .table-responsive { overflow-x: auto; }
                .cetak-table { width: 100%; border-collapse: collapse; font-size: 11px; text-align: center; margin-bottom: 30px; }
                .cetak-table th, .cetak-table td { border: 1px solid #333; padding: 5px; }
                .cetak-table th { background-color: #f5f5f5; font-weight: bold; text-align: center; }
                .ttd-container { display: flex; justify-content: flex-end; padding-right: 50px; text-align: left; font-size: 12px; margin-top: 40px; }
                
                @media print {
                    @page { size: landscape; margin: 10mm; }
                    .page-header, .laporan-filter, .laporan-stats, .panel-header, .topbar, .sidebar { display: none !important; }
                    .laporan-panel { box-shadow: none; border: none; padding: 0; }
                    .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; }
                    body { background: #fff; }
                    .print-only-logo { display: block !important; }
                    
                    /* Penyesuaian khusus agar tabel muat di kertas A4 Landscape */
                    .table-responsive { overflow: visible !important; width: 100% !important; }
                    .cetak-table { font-size: 7.5px !important; margin-bottom: 15px !important; table-layout: auto !important; }
                    .cetak-table th, .cetak-table td { padding: 3px 2px !important; white-space: normal !important; word-wrap: break-word !important; }
                    
                    /* Lebar minimum untuk kolom aksi/tanda tangan agar tetap rapi */
                    .cetak-table th { min-width: 25px; }
                }
            </style>

            <div class="print-only-logo" style="display: none; margin-bottom: 20px; text-align: center;">
                <img src="{{ asset('images/logo-bps.png') }}" alt="Logo BPS" style="height: 50px;">
                <h2 style="font-size: 16px; margin: 10px 0 5px;">LAPORAN REKAPITULASI SPJ</h2>
                <p style="font-size: 12px; margin: 0;">
                    @if(request('tanggal_mulai') || request('tanggal_selesai'))
                        Periode: {{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') : 'Awal' }} s/d {{ request('tanggal_selesai') ? \Carbon\Carbon::parse(request('tanggal_selesai'))->format('d/m/Y') : 'Akhir' }}
                        {{ request('status') ? ' | Status: ' . ucfirst(request('status')) : '' }}
                    @else
                        Seluruh Data SPJ
                    @endif
                </p>
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

                if (!function_exists('isDocChecked')) {
                    function isDocChecked($spj, $def) {
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

                // Hanya tampilkan kolom dokumen yang tercentang di setidaknya 1 SPJ yang sedang ditampilkan
                $activeDocColumns = [];
                foreach ($masterDocDefs as $key => $def) {
                    $hasAny = false;
                    foreach ($spjs as $spj) {
                        if (isDocChecked($spj, $def)) {
                            $hasAny = true;
                            break;
                        }
                    }
                    if ($hasAny) {
                        $activeDocColumns[$key] = $def;
                    }
                }

                // Jika data kosong atau belum ada yang diceklis sama sekali, sediakan kolom standar minimal
                if (empty($activeDocColumns)) {
                    $activeDocColumns = [
                        'kak' => $masterDocDefs['kak'],
                        'fp' => $masterDocDefs['fp'],
                        'daftar_hadir' => $masterDocDefs['daftar_hadir'],
                        'bast' => $masterDocDefs['bast'],
                    ];
                }

                $totalDocCols = count($activeDocColumns);
                $totalKelengkapanColspan = $totalDocCols + 4; // Ditambah 4 kolom: Tgl Masuk, Tgl Periksa, Paraf PPSPM, Paraf Bendahara
                $totalTableColumns = 4 + $totalKelengkapanColspan + 1; // 4 kolom awal + kelengkapan + 1 keterangan
            @endphp

            <div class="table-responsive">
                <table class="cetak-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 30px;">No</th>
                            <th rowspan="2" style="width: 200px;">Nama Kegiatan</th>
                            <th rowspan="2" style="width: 100px;">Jumlah Anggaran</th>
                            <th rowspan="2" style="width: 80px;">Tanggal Pembuatan Daftar</th>
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
                            <td style="text-align: left;">{{ $spj->kegiatan }}</td>
                            <td style="text-align: right; white-space: nowrap;">Rp. {{ number_format($spj->nilai, 0, ',', '.') }}</td>
                            <td>{!! $spj->tanggal ? \Carbon\Carbon::parse($spj->tanggal)->locale('id')->translatedFormat('d F Y') : '-' !!}</td>
                            
                            {{-- Checkmarks dinamis untuk kolom yang aktif --}}
                            @foreach($activeDocColumns as $colDef)
                                <td>{!! isDocChecked($spj, $colDef) ? '&radic;' : '' !!}</td>
                            @endforeach

                            <td>{!! $spj->diajukan_at ? \Carbon\Carbon::parse($spj->diajukan_at)->locale('id')->translatedFormat('d F Y') : ($spj->created_at ? \Carbon\Carbon::parse($spj->created_at)->locale('id')->translatedFormat('d F Y') : '') !!}</td>
                            <td>{!! $spj->disetujui_ppk_at ? \Carbon\Carbon::parse($spj->disetujui_ppk_at)->locale('id')->translatedFormat('d F Y') : '' !!}</td>
                            <td>{!! $spj->disetujui_ppspm_at ? '&radic;' : '' !!}</td>
                            <td>{!! $spj->diselesaikan_at ? '&radic;' : '' !!}</td>
                            
                            <td></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $totalTableColumns }}" style="padding: 20px; font-style: italic;">Tidak ada data SPJ</td>
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

            <div class="ttd-container">
                <div>
                    <p style="margin: 0;">setor ke PPSPM :</p>
                    <p style="margin: 0;">Lasusua, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                    <p style="margin: 0;">Pejabat Pembuat Komitmen</p>
                    <br><br><br><br>
                    <p style="margin: 0; text-decoration: underline; font-weight: bold;">Hidayatullah</p>
                    <p style="margin: 0;">NIP.198510292011011009</p>
                </div>
            </div>

        </div>

    </div>

</div>


@endsection