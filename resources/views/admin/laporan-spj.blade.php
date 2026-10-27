@extends('layouts.admin')

@php
    $title = 'Laporan SPJ';
@endphp

@push('styles')
<style>
    /* =========================================================
       ADMIN - LAPORAN SPJ (BPS DESIGN SYSTEM)
    ========================================================= */
    :root {
        --bps-navy: #082052;
        --bps-navy-dark: #051538;
        --bps-blue: #024b86;
        --bps-blue-light: #e0f2fe;
        --bps-blue-subtle: #f0f7ff;
        
        --bps-orange: #f37021;
        --bps-orange-dark: #ea580c;
        --bps-orange-light: #fff7ed;

        --bps-green: #16a34a;
        --bps-green-dark: #15803d;
        --bps-green-light: #ecfdf5;

        --bps-danger: #dc2626;
        --bps-danger-light: #fef2f2;

        --dash-slate-900: #082052;
        --dash-slate-800: #1e293b;
        --dash-slate-700: #334155;
        --dash-slate-600: #475569;
        --dash-slate-500: #64748b;
        --dash-slate-400: #94a3b8;
        --dash-slate-200: #e2e8f0;
        --dash-slate-100: #f1f5f9;
        --dash-slate-50: #f8fafc;

        --dash-radius-xl: 16px;
        --dash-radius-lg: 12px;
        --dash-radius-md: 8px;
    }

    .laporan-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        color: var(--dash-slate-800);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* 1. HEADER */
    .laporan-header-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        background: #ffffff;
        padding: 22px 26px;
        border-radius: var(--dash-radius-xl);
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
    }

    .laporan-header-title h2 {
        margin: 0 0 5px 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.02em;
    }

    .laporan-header-title p {
        margin: 0;
        color: var(--dash-slate-500);
        font-size: 13.5px;
    }

    .btn-print-modern {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 22px;
        background: linear-gradient(135deg, var(--bps-blue) 0%, #1e3a8a 100%);
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        border-radius: var(--dash-radius-md);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(2, 75, 134, 0.35);
        transition: all 0.2s ease;
    }

    .btn-print-modern:hover {
        background: linear-gradient(135deg, #023866 0%, #172554 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(2, 75, 134, 0.45);
        color: #ffffff;
    }

    /* 2. FILTER BOX */
    .laporan-filter-card {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        padding: 20px 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
    }

    .laporan-filter-form {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 16px;
    }

    .filter-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
        min-width: 180px;
    }

    .filter-field label {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--dash-slate-700);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-field label i {
        color: var(--bps-blue);
        font-size: 13px;
    }

    .filter-input-control {
        width: 100%;
        padding: 9px 12px;
        border-radius: var(--dash-radius-md);
        border: 1px solid #cbd5e1;
        font-size: 13px;
        color: var(--dash-slate-800);
        background: #f8fafc;
        outline: none;
        transition: all 0.2s ease;
        font-family: inherit;
        box-sizing: border-box;
    }

    .filter-input-control:focus {
        background: #ffffff;
        border-color: var(--bps-blue);
        box-shadow: 0 0 0 3px rgba(2, 75, 134, 0.12);
    }

    .filter-action-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-apply-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        background: var(--bps-blue);
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        border-radius: var(--dash-radius-md);
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-apply-filter:hover {
        background: #023866;
    }

    .btn-reset-filter-url {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 15px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: var(--dash-slate-600);
        font-size: 13px;
        font-weight: 600;
        border-radius: var(--dash-radius-md);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-reset-filter-url:hover {
        background: #f1f5f9;
        color: var(--bps-navy);
        border-color: #94a3b8;
    }

    /* 3. STATS CARDS (5 COLUMNS) */
    .laporan-stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
    }

    .laporan-kpi-card {
        background: #ffffff;
        border-radius: var(--dash-radius-lg);
        padding: 16px 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .laporan-kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
    }

    .laporan-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -4px rgba(8, 32, 82, 0.08);
        border-color: #cbd5e1;
    }

    .laporan-kpi-card.blue::before { background: linear-gradient(90deg, var(--bps-navy), var(--bps-blue)); }
    .laporan-kpi-card.blue .kpi-icon { background: var(--bps-blue-light); color: var(--bps-blue); }

    .laporan-kpi-card.orange::before { background: linear-gradient(90deg, var(--bps-orange), #fb923c); }
    .laporan-kpi-card.orange .kpi-icon { background: var(--bps-orange-light); color: var(--bps-orange); }

    .laporan-kpi-card.sky::before { background: linear-gradient(90deg, #0284c7, #38bdf8); }
    .laporan-kpi-card.sky .kpi-icon { background: #f0f9ff; color: #0284c7; }

    .laporan-kpi-card.green::before { background: linear-gradient(90deg, var(--bps-green-dark), #34d399); }
    .laporan-kpi-card.green .kpi-icon { background: var(--bps-green-light); color: var(--bps-green); }

    .laporan-kpi-card.red::before { background: linear-gradient(90deg, var(--bps-danger), #f87171); }
    .laporan-kpi-card.red .kpi-icon { background: var(--bps-danger-light); color: var(--bps-danger); }

    .laporan-kpi-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .laporan-kpi-title {
        font-size: 12px;
        font-weight: 700;
        color: var(--dash-slate-600);
    }

    .laporan-kpi-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--dash-radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .laporan-kpi-number {
        font-size: 26px;
        font-weight: 800;
        color: var(--bps-navy);
        line-height: 1;
        margin: 0 0 8px 0;
        letter-spacing: -0.02em;
    }

    .laporan-kpi-sub {
        font-size: 11px;
        color: var(--dash-slate-500);
        border-top: 1px dashed #e2e8f0;
        padding-top: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* 4. MAIN REPORT PANEL */
    .laporan-panel {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
        overflow: hidden;
    }

    .panel-header {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }

    .panel-header h3 {
        margin: 0 0 3px 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .panel-header p {
        margin: 0;
        font-size: 12.5px;
        color: var(--dash-slate-500);
    }

    .jumlah-data {
        background: var(--bps-blue-light);
        color: var(--bps-blue);
        padding: 4px 12px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 12px;
        border: 1px solid #bfdbfe;
    }

    .table-wrapper {
        padding: 20px 24px;
    }

    .table-responsive { 
        overflow-x: auto; 
        width: 100%;
    }

    /* PRESERVED PRINT & TABLE STYLES */
    .cetak-table { 
        width: 100%; 
        border-collapse: collapse; 
        font-size: 11.5px; 
        text-align: center; 
        margin-bottom: 30px; 
    }

    .cetak-table th, .cetak-table td { 
        border: 1px solid #cbd5e1; 
        padding: 7px 6px; 
    }

    .cetak-table th { 
        background-color: #f8fafc; 
        font-weight: 800; 
        color: var(--dash-slate-700); 
        text-align: center; 
    }

    .cetak-table tbody tr:hover {
        background-color: var(--bps-blue-subtle);
    }

    .ttd-container { 
        display: flex; 
        justify-content: flex-end; 
        padding-right: 50px; 
        text-align: left; 
        font-size: 12px; 
        margin-top: 40px; 
    }

    /* PRINT SPECIFIC (UNTOUCHED AND PRESERVED EXACTLY AS REQUIRED) */
    @media print {
        @page { size: landscape; margin: 10mm; }
        .page-header, 
        .laporan-header-box, 
        .laporan-filter, 
        .laporan-filter-card, 
        .laporan-stats, 
        .laporan-stats-grid, 
        .panel-header, 
        .topbar, 
        .sidebar, 
        .btn-print-modern,
        .btn-header-action,
        .no-print { 
            display: none !important; 
        }
        .laporan-panel { 
            box-shadow: none !important; 
            border: none !important; 
            padding: 0 !important; 
            background: transparent !important; 
        }
        .table-wrapper {
            padding: 0 !important;
        }
        .main-content { 
            margin-left: 0 !important; 
            padding: 0 !important; 
            width: 100% !important; 
            max-width: 100% !important; 
        }
        body { 
            background: #fff !important; 
        }
        .print-only-logo { 
            display: block !important; 
        }
        
        .table-responsive { 
            overflow: visible !important; 
            width: 100% !important; 
        }
        .cetak-table { 
            font-size: 7.5px !important; 
            margin-bottom: 15px !important; 
            table-layout: auto !important; 
            width: 100% !important;
            border-collapse: collapse !important;
        }
        .cetak-table th, .cetak-table td { 
            padding: 3px 2px !important; 
            white-space: normal !important; 
            word-wrap: break-word !important; 
            border: 1px solid #333 !important; 
        }
        .cetak-table th { 
            min-width: 25px; 
            background-color: #f5f5f5 !important; 
        }
        .ttd-container { 
            display: flex !important; 
            justify-content: flex-end !important; 
            padding-right: 50px !important; 
            text-align: left !important; 
            font-size: 12px !important; 
            margin-top: 40px !important; 
        }
    }

    /* RESPONSIVE ON SCREEN */
    @media (max-width: 1200px) {
        .laporan-stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .laporan-stats-grid {
            grid-template-columns: 1fr;
        }
        .laporan-filter-form {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-field {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="laporan-page-wrapper">

    {{-- 1. HEADER --}}
    <div class="laporan-header-box">
        <div class="laporan-header-title">
            <h2>
                <i class="bi bi-file-earmark-bar-graph-fill" style="color: var(--bps-blue);"></i>
                Laporan &amp; Rekapitulasi SPJ
            </h2>
            <p>
                @if(request('search') || request('nama_spj'))
                    Pencarian SPJ: <strong>"{{ request('search') ?? request('nama_spj') }}"</strong>
                @endif
                @if(request('tanggal_mulai') || request('tanggal_selesai'))
                    {{ (request('search') || request('nama_spj')) ? '&bull;' : '' }} Periode: <strong>{{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') : 'Awal' }}</strong> 
                    s/d 
                    <strong>{{ request('tanggal_selesai') ? \Carbon\Carbon::parse(request('tanggal_selesai'))->format('d/m/Y') : 'Akhir' }}</strong>
                @endif
                @if(request('status'))
                    &bull; Status: <strong>{{ ucfirst(str_replace('_', ' ', request('status'))) }}</strong>
                @endif
                @if(!request('search') && !request('nama_spj') && !request('tanggal_mulai') && !request('tanggal_selesai') && !request('status'))
                    Rekapitulasi matriks kelengkapan berkas fisik dan status verifikasi SPJ.
                @endif
            </p>
        </div>

        <button
            type="button"
            class="btn-print-modern"
            onclick="window.print()">
            <i class="bi bi-printer-fill"></i> Cetak Laporan Fisik
        </button>
    </div>

    {{-- 2. FILTER CARD --}}
    <div class="laporan-filter-card">
        <form
            action="{{ route('admin.laporan.spj') }}"
            method="GET"
            class="laporan-filter-form">

            {{-- Cari Nama SPJ / Kegiatan --}}
            <div class="filter-field" style="min-width: 220px; flex: 1.5;">
                <label>
                    <i class="bi bi-search"></i> Cari Nama SPJ / Kegiatan
                </label>
                <input
                    type="text"
                    name="search"
                    class="filter-input-control"
                    placeholder="Masukkan nama kegiatan atau nomor SPJ..."
                    value="{{ request('search') ?? request('nama_spj') }}">
            </div>

            {{-- Tanggal Mulai --}}
            <div class="filter-field">
                <label>
                    <i class="bi bi-calendar-event"></i> Tanggal Mulai
                </label>
                <input
                    type="date"
                    name="tanggal_mulai"
                    class="filter-input-control"
                    value="{{ request('tanggal_mulai') }}">
            </div>

            {{-- Tanggal Selesai --}}
            <div class="filter-field">
                <label>
                    <i class="bi bi-calendar-check"></i> Tanggal Selesai
                </label>
                <input
                    type="date"
                    name="tanggal_selesai"
                    class="filter-input-control"
                    value="{{ request('tanggal_selesai') }}">
            </div>

            {{-- Status Filter --}}
            <div class="filter-field">
                <label>
                    <i class="bi bi-funnel"></i> Status Proses
                </label>
                <select name="status" class="filter-input-control">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses (Umum / PPK)</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai (Disetujui PPSPM)</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan (Revisi)</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            {{-- Filter Buttons --}}
            <div class="filter-action-group">
                <button type="submit" class="btn-apply-filter">
                    <i class="bi bi-filter"></i> Terapkan
                </button>

                <a
                    href="{{ route('admin.laporan.spj') }}"
                    class="btn-reset-filter-url"
                    title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>

        </form>
    </div>

    {{-- 3. STATISTIK KPI CARDS (BPS COLOR PALETTE) --}}
    <div class="laporan-stats-grid">
        {{-- Card 1: Total SPJ --}}
        <div class="laporan-kpi-card blue">
            <div>
                <div class="laporan-kpi-top">
                    <span class="laporan-kpi-title">Total SPJ</span>
                    <div class="laporan-kpi-icon kpi-icon">
                        <i class="bi bi-files"></i>
                    </div>
                </div>
                <div class="laporan-kpi-number">{{ $totalSpj }}</div>
            </div>
            <div class="laporan-kpi-sub">
                <span>Nilai Total</span>
                <strong style="color: var(--bps-navy);">Rp {{ number_format($totalNilai, 0, ',', '.') }}</strong>
            </div>
        </div>

        {{-- Card 2: Diajukan --}}
        <div class="laporan-kpi-card orange">
            <div>
                <div class="laporan-kpi-top">
                    <span class="laporan-kpi-title">Diajukan</span>
                    <div class="laporan-kpi-icon kpi-icon">
                        <i class="bi bi-send-fill"></i>
                    </div>
                </div>
                <div class="laporan-kpi-number">{{ $totalDiajukan }}</div>
            </div>
            <div class="laporan-kpi-sub">
                <span>Menunggu Verifikasi</span>
                <span>{{ $totalDiajukan }} berkas</span>
            </div>
        </div>

        {{-- Card 3: Diproses --}}
        <div class="laporan-kpi-card sky">
            <div>
                <div class="laporan-kpi-top">
                    <span class="laporan-kpi-title">Dalam Proses</span>
                    <div class="laporan-kpi-icon kpi-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="laporan-kpi-number">{{ $totalDiproses }}</div>
            </div>
            <div class="laporan-kpi-sub">
                <span>Umum &amp; PPK</span>
                <span>{{ $totalDiproses }} berkas</span>
            </div>
        </div>

        {{-- Card 4: Selesai --}}
        <div class="laporan-kpi-card green">
            <div>
                <div class="laporan-kpi-top">
                    <span class="laporan-kpi-title">Selesai (PPSPM)</span>
                    <div class="laporan-kpi-icon kpi-icon">
                        <i class="bi bi-check2-all"></i>
                    </div>
                </div>
                <div class="laporan-kpi-number">{{ $totalSelesai }}</div>
            </div>
            <div class="laporan-kpi-sub">
                <span>SPM Diterbitkan</span>
                <span>{{ $totalSelesai }} berkas</span>
            </div>
        </div>

        {{-- Card 5: Dikembalikan --}}
        <div class="laporan-kpi-card red">
            <div>
                <div class="laporan-kpi-top">
                    <span class="laporan-kpi-title">Dikembalikan</span>
                    <div class="laporan-kpi-icon kpi-icon">
                        <i class="bi bi-arrow-return-left"></i>
                    </div>
                </div>
                <div class="laporan-kpi-number">{{ $totalDikembalikan }}</div>
            </div>
            <div class="laporan-kpi-sub">
                <span>Perlu Perbaikan</span>
                <span>{{ $totalDikembalikan }} berkas</span>
            </div>
        </div>
    </div>

    {{-- 4. TABEL LAPORAN (PRESERVED TABLE & PRINT STRUCTURE) --}}
    <div class="laporan-panel">

        <div class="panel-header">
            <div>
                <h3>
                    <i class="bi bi-table" style="color: var(--bps-blue);"></i>
                    Daftar Matriks Kelengkapan Administrasi SPJ
                </h3>
                <p>Rekapitulasi ceklist berkas fisik dan pengesahan pejabat penandatangan.</p>
            </div>

            <span class="jumlah-data">
                {{ $totalSpj }} data terpilih
            </span>
        </div>

        <div class="table-wrapper">

            {{-- HEADER LOGO KHUSUS PRINT CETAK --}}
            <div class="print-only-logo" style="display: none; margin-bottom: 20px; text-align: center;">
                <img src="{{ asset('images/logo-bps.png') }}" alt="Logo BPS" style="height: 50px;">
                <h2 style="font-size: 16px; margin: 10px 0 5px;">LAPORAN REKAPITULASI SPJ</h2>
                @if(request('tanggal_mulai') || request('tanggal_selesai') || request('status'))
                    <p style="font-size: 12px; margin: 0;">
                        @if(request('tanggal_mulai') || request('tanggal_selesai'))
                            Periode: {{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') : 'Awal' }} s/d {{ request('tanggal_selesai') ? \Carbon\Carbon::parse(request('tanggal_selesai'))->format('d/m/Y') : 'Akhir' }}
                        @endif
                        @if(request('status'))
                            {{ (request('tanggal_mulai') || request('tanggal_selesai')) ? ' | ' : '' }}Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                        @endif
                    </p>
                @endif
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
                            <th rowspan="2" style="width: 32px;">No</th>
                            <th rowspan="2" style="width: 220px;">Nama Kegiatan</th>
                            <th rowspan="2" style="width: 110px;">Jumlah Anggaran</th>
                            <th rowspan="2" style="width: 90px;">Tanggal Penginputan</th>
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
                            <td style="text-align: left; font-weight: 600;">{{ $spj->kegiatan }}</td>
                            <td style="text-align: right; white-space: nowrap; font-weight: 700; color: var(--bps-navy);">Rp. {{ number_format($spj->nilai, 0, ',', '.') }}</td>
                            <td>{!! $spj->created_at ? $spj->created_at->locale('id')->translatedFormat('d F Y') : '-' !!}</td>
                            
                            {{-- Checkmarks dinamis untuk kolom yang aktif --}}
                            @foreach($activeDocColumns as $colDef)
                                <td style="font-weight: bold; color: var(--bps-green-dark);">{!! isDocChecked($spj, $colDef) ? '&radic;' : '' !!}</td>
                            @endforeach

                            <td>{!! $spj->disetujui_umum_at ? \Carbon\Carbon::parse($spj->disetujui_umum_at)->locale('id')->translatedFormat('d F Y') : '-' !!}</td>
                            <td>{!! $spj->disetujui_ppk_at ? \Carbon\Carbon::parse($spj->disetujui_ppk_at)->locale('id')->translatedFormat('d F Y') : '' !!}</td>
                            <td style="font-weight: bold; color: var(--bps-green-dark);">{!! $spj->disetujui_ppspm_at ? '&radic;' : '' !!}</td>
                            <td style="font-weight: bold; color: var(--bps-green-dark);">{!! $spj->diselesaikan_at ? '&radic;' : '' !!}</td>
                            
                            <td></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $totalTableColumns }}" style="padding: 30px; font-style: italic; color: var(--dash-slate-400);">Tidak ada data SPJ yang sesuai dengan filter pencarian</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($spjs->count() > 0)
                    <tfoot>
                        <tr>
                            <th colspan="2" style="text-align: right; padding-right: 15px; font-weight: 800;">TOTAL</th>
                            <th style="text-align: right; white-space: nowrap; font-weight: 800; color: var(--bps-navy);">Rp. {{ number_format($totalNilai, 0, ',', '.') }}</th>
                            <th colspan="{{ $totalTableColumns - 3 }}"></th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            {{-- BLOK TANDA TANGAN CETAK (PRESERVED EXACTLY AS REQUIRED) --}}
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