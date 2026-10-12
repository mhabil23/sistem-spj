@extends('layouts.teknis')

@php
$title = 'Laporan SPJ';
$subtitle = 'Rekap dan laporan data SPJ.';
@endphp

@push('styles')
    @vite('resources/css/admin/laporan-spj.css')
@endpush

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
            action="{{ route('teknis.laporan.spj') }}"
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
                    href="{{ route('teknis.laporan.spj') }}"
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

            <div class="table-responsive">
                <table class="cetak-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 30px;">No</th>
                            <th rowspan="2" style="width: 200px;">Nama Kegiatan</th>
                            <th rowspan="2" style="width: 100px;">Jumlah Anggaran</th>
                            <th rowspan="2" style="width: 80px;">Tanggal Pembuatan Daftar</th>
                            <th colspan="14">Kelengkapan Administrasi</th>
                            <th rowspan="2" style="width: 80px;">Keterangan</th>
                        </tr>
                        <tr>
                            <th>Daftar Penerima</th>
                            <th>BAST</th>
                            <th>SK</th>
                            <th>KAK</th>
                            <th>Form Permintaan</th>
                            <th>SPK</th>
                            <th>Surat Tugas</th>
                            <th>Kesesuaian MRK dg SPK</th>
                            <th>CMS</th>
                            <th>Cek SBKS</th>
                            <th>Tgl Masuk SPJ</th>
                            <th>Tgl Periksa PPK</th>
                            <th>Paraf PPSPM</th>
                            <th>Paraf Bendahara</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            if(!function_exists('isChecked')){
                                function isChecked($spj, $keywords) {
                                    if (!$spj->dokumen_file) return false;
                                    $docs = json_decode($spj->dokumen_file, true);
                                    if (!is_array($docs)) return false;
                                    $keywordArr = is_array($keywords) ? $keywords : [$keywords];
                                    foreach($docs as $doc) {
                                        if ($doc['disiapkan']) {
                                            foreach($keywordArr as $kw) {
                                                if (stripos($doc['nama'], $kw) !== false) return true;
                                            }
                                        }
                                    }
                                    return false;
                                }
                            }
                        @endphp
                        @forelse($spjs as $index => $spj)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align: left;">{{ $spj->kegiatan }}</td>
                            <td style="text-align: right;">{{ number_format($spj->nilai, 0, ',', '.') }}</td>
                            <td>{!! $spj->tanggal ? \Carbon\Carbon::parse($spj->tanggal)->locale('id')->translatedFormat('d F Y') : '-' !!}</td>
                            
                            {{-- Checkmarks dinamis --}}
                            <td>{!! isChecked($spj, ['Daftar Penerima', 'Daftar Hadir', 'Daftar nama']) || $spj->kel_daftar_penerima ? '&radic;' : '' !!}</td>
                            <td>{!! isChecked($spj, ['BAST']) || $spj->kel_bast ? '&radic;' : '' !!}</td>
                            <td>{!! isChecked($spj, ['SK', 'Surat Keputusan']) || $spj->kel_sk ? '&radic;' : '' !!}</td>
                            <td>{!! isChecked($spj, ['KAK']) || $spj->kel_kak ? '&radic;' : '' !!}</td>
                            <td>{!! isChecked($spj, ['FP', 'Form Permintaan']) || $spj->kel_form_permintaan ? '&radic;' : '' !!}</td>
                            <td>{!! isChecked($spj, ['SPK', 'Perjanjian', 'Polis']) || $spj->kel_spk ? '&radic;' : '' !!}</td>
                            <td>{!! isChecked($spj, ['Surat Tugas', 'ST', 'Nota Pesanan']) || $spj->kel_surat_tugas ? '&radic;' : '' !!}</td>
                            <td>{!! isChecked($spj, ['Kesesuaian MRK']) || $spj->kel_kesesuaian_mrk ? '&radic;' : '' !!}</td>
                            <td>{!! isChecked($spj, ['CMS']) || $spj->kel_cms ? '&radic;' : '' !!}</td>
                            <td>{!! isChecked($spj, ['SBKS']) || $spj->kel_cek_sbks ? '&radic;' : '' !!}</td>
                            <td>{!! $spj->diajukan_at ? \Carbon\Carbon::parse($spj->diajukan_at)->locale('id')->translatedFormat('d F Y') : ($spj->created_at ? \Carbon\Carbon::parse($spj->created_at)->locale('id')->translatedFormat('d F Y') : '') !!}</td>
                            <td>{!! $spj->disetujui_ppk_at ? \Carbon\Carbon::parse($spj->disetujui_ppk_at)->locale('id')->translatedFormat('d F Y') : '' !!}</td>
                            <td>{!! $spj->disetujui_ppspm_at ? '&radic;' : '' !!}</td>
                            <td>{!! $spj->diselesaikan_at ? '&radic;' : '' !!}</td>
                            
                            <td></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="19" style="padding: 20px; font-style: italic;">Tidak ada data SPJ</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($spjs->count() > 0)
                    <tfoot>
                        <tr>
                            <th colspan="2" style="text-align: right; padding-right: 15px;">TOTAL</th>
                            <th style="text-align: right;">{{ number_format($totalNilai, 0, ',', '.') }}</th>
                            <th colspan="16"></th>
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