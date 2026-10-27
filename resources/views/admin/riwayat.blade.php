@extends('layouts.admin')

@php
    $title = 'Riwayat Proses Dokumen';
@endphp

@push('styles')
<style>
    /* =========================================================
       ADMIN - RIWAYAT PROSES (BPS DESIGN SYSTEM)
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

    .history-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        color: var(--dash-slate-800);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* 1. HEADER */
    .history-header-box {
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

    .history-header-title h2 {
        margin: 0 0 5px 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.02em;
    }

    .history-header-title p {
        margin: 0;
        color: var(--dash-slate-500);
        font-size: 13.5px;
    }

    .btn-header-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: var(--dash-radius-md);
        color: var(--bps-navy);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-header-action:hover {
        background: var(--bps-blue-subtle);
        border-color: var(--bps-blue);
        color: var(--bps-blue);
    }

    /* 2. STATS CARDS */
    .history-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .history-card-kpi {
        background: #ffffff;
        border-radius: var(--dash-radius-lg);
        padding: 18px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .history-card-kpi::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
    }

    .history-card-kpi:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -4px rgba(8, 32, 82, 0.08);
        border-color: #cbd5e1;
    }

    .history-card-kpi.blue::before { background: linear-gradient(90deg, var(--bps-navy), var(--bps-blue)); }
    .history-card-kpi.blue .kpi-icon { background: var(--bps-blue-light); color: var(--bps-blue); }
    .history-card-kpi.blue .kpi-pill { background: #eff6ff; color: var(--bps-blue); border: 1px solid #bfdbfe; }

    .history-card-kpi.sky::before { background: linear-gradient(90deg, #0284c7, #38bdf8); }
    .history-card-kpi.sky .kpi-icon { background: #f0f9ff; color: #0284c7; }
    .history-card-kpi.sky .kpi-pill { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }

    .history-card-kpi.green::before { background: linear-gradient(90deg, var(--bps-green-dark), #34d399); }
    .history-card-kpi.green .kpi-icon { background: var(--bps-green-light); color: var(--bps-green); }
    .history-card-kpi.green .kpi-pill { background: var(--bps-green-light); color: var(--bps-green-dark); border: 1px solid #a7f3d0; }

    .history-card-kpi.orange::before { background: linear-gradient(90deg, var(--bps-orange), #fb923c); }
    .history-card-kpi.orange .kpi-icon { background: var(--bps-orange-light); color: var(--bps-orange); }
    .history-card-kpi.orange .kpi-pill { background: var(--bps-orange-light); color: var(--bps-orange-dark); border: 1px solid #fed7aa; }

    .kpi-top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .kpi-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--dash-slate-600);
    }

    .kpi-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--dash-radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .kpi-number {
        font-size: 30px;
        font-weight: 800;
        color: var(--bps-navy);
        line-height: 1;
        margin: 0 0 12px 0;
        letter-spacing: -0.02em;
    }

    .kpi-bottom-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
        font-size: 12px;
        color: var(--dash-slate-500);
    }

    .kpi-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 11px;
    }

    /* 3. TABLE PANEL */
    .history-main-panel {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
        overflow: hidden;
    }

    .history-toolbar-wrap {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        background: #ffffff;
    }

    .toolbar-info h3 {
        margin: 0 0 3px 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .toolbar-info p {
        margin: 0;
        font-size: 12.5px;
        color: var(--dash-slate-500);
    }

    .toolbar-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-input-wrap {
        position: relative;
        min-width: 270px;
    }

    .search-input-wrap i.search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--dash-slate-400);
        font-size: 14px;
        pointer-events: none;
    }

    .search-input-field {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border-radius: var(--dash-radius-md);
        border: 1px solid #cbd5e1;
        font-size: 13px;
        color: var(--dash-slate-800);
        background: #f8fafc;
        outline: none;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .search-input-field:focus {
        background: #ffffff;
        border-color: var(--bps-blue);
        box-shadow: 0 0 0 3px rgba(2, 75, 134, 0.12);
    }

    .filter-dropdown {
        padding: 9px 12px;
        border-radius: var(--dash-radius-md);
        border: 1px solid #cbd5e1;
        font-size: 13px;
        color: var(--dash-slate-700);
        background: #f8fafc;
        outline: none;
        cursor: pointer;
        font-family: inherit;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .filter-dropdown:focus {
        background: #ffffff;
        border-color: var(--bps-blue);
        box-shadow: 0 0 0 3px rgba(2, 75, 134, 0.12);
    }

    .btn-reset-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 14px;
        border-radius: var(--dash-radius-md);
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: var(--dash-slate-600);
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-reset-filter:hover {
        background: #f1f5f9;
        color: var(--bps-navy);
        border-color: #cbd5e1;
    }

    /* 4. MODERN TABLE */
    .table-container {
        overflow-x: auto;
        width: 100%;
    }

    .modern-history-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        white-space: nowrap;
    }

    .modern-history-table th {
        padding: 13px 18px;
        background: #f8fafc;
        color: var(--dash-slate-600);
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
    }

    .modern-history-table td {
        padding: 15px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: var(--dash-slate-700);
        vertical-align: middle;
    }

    .modern-history-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .modern-history-table tbody tr:hover {
        background-color: var(--bps-blue-subtle);
    }

    .modern-history-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Document Cell */
    .document-cell {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .doc-badge-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .doc-number-badge {
        font-family: monospace;
        font-weight: 800;
        font-size: 12px;
        color: var(--bps-blue);
        background: var(--bps-blue-light);
        padding: 2px 7px;
        border-radius: 4px;
        border: 1px solid #bfdbfe;
    }

    .doc-kegiatan-text {
        font-weight: 700;
        color: var(--bps-navy);
        font-size: 13.5px;
        max-width: 280px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .doc-nilai-text {
        font-size: 11.5px;
        color: var(--bps-green-dark);
        font-weight: 700;
    }

    /* Step / Activity Badge */
    .step-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .step-pill.diajukan { background: #eff6ff; color: var(--bps-blue); border: 1px solid #bfdbfe; }
    .step-pill.umum { background: var(--bps-orange-light); color: var(--bps-orange-dark); border: 1px solid #fed7aa; }
    .step-pill.ppk { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
    .step-pill.ppspm { background: var(--bps-green-light); color: var(--bps-green-dark); border: 1px solid #a7f3d0; }
    .step-pill.revisi { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    .step-keterangan {
        font-size: 12px;
        color: var(--dash-slate-500);
        max-width: 320px;
        white-space: normal;
        line-height: 1.4;
    }

    /* User Cell */
    .history-user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .history-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--bps-blue-light);
        color: var(--bps-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        flex-shrink: 0;
        border: 1px solid #bfdbfe;
    }

    .history-user-name {
        font-weight: 700;
        color: var(--dash-slate-800);
        font-size: 13px;
        display: block;
    }

    .history-user-role {
        font-size: 11px;
        color: var(--dash-slate-500);
    }

    /* Status Pill */
    .status-badge-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .status-badge-item.completed {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .status-badge-item.processing {
        background: #eff6ff;
        color: #0284c7;
        border: 1px solid #bae6fd;
    }

    .status-badge-item.warning {
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #fed7aa;
    }

    .status-badge-item.returned {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    /* Action Button */
    .btn-action-cell {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f5f9;
        color: var(--bps-navy);
        border: 1px solid #e2e8f0;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 14px;
    }

    .btn-action-cell.view:hover {
        background: var(--bps-blue);
        color: #ffffff;
        border-color: var(--bps-blue);
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(2, 75, 134, 0.25);
    }

    .table-bottom-bar {
        padding: 14px 24px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12.5px;
        color: var(--dash-slate-500);
        background: #ffffff;
    }

    /* RESPONSIVE */
    @media (max-width: 1200px) {
        .history-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .history-stats-grid {
            grid-template-columns: 1fr;
        }
        .toolbar-controls {
            width: 100%;
        }
        .search-input-wrap {
            width: 100%;
            min-width: 100%;
        }
        .filter-dropdown {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="history-page-wrapper">

    {{-- 1. HEADER --}}
    <div class="history-header-box">
        <div class="history-header-title">
            <h2>
                <i class="bi bi-clock-history" style="color: var(--bps-blue);"></i>
                Riwayat Proses &amp; Jejak Audit SPJ
            </h2>
            <p>Pantau seluruh jejak aktivitas verifikasi, persetujuan tahapan, dan status proses dokumen SPJ secara transparan.</p>
        </div>

        <a href="{{ route('admin.spj.index') }}" class="btn-header-action">
            <i class="bi bi-folder2-open"></i> Kelola Data SPJ
        </a>
    </div>

    {{-- 2. STATISTIK KPI CARDS (BPS COLOR PALETTE) --}}
    <div class="history-stats-grid">
        {{-- Card 1: Total Riwayat --}}
        <div class="history-card-kpi blue">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Total Jejak Audit</span>
                    <div class="kpi-icon">
                        <i class="bi bi-journal-check"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ $totalRiwayat }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Aktivitas Tercatat</span>
                <span class="kpi-pill">{{ $totalRiwayat }} riwayat</span>
            </div>
        </div>

        {{-- Card 2: Sedang Diproses --}}
        <div class="history-card-kpi sky">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Tahapan Berjalan</span>
                    <div class="kpi-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ $diproses }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Proses Berjalan</span>
                <span class="kpi-pill">{{ $diproses }} berkas aktif</span>
            </div>
        </div>

        {{-- Card 3: Selesai / Disetujui PPSPM --}}
        <div class="history-card-kpi green">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Disetujui PPSPM (Selesai)</span>
                    <div class="kpi-icon">
                        <i class="bi bi-check2-all"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ $selesai }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Verifikasi Selesai</span>
                <span class="kpi-pill">{{ $selesai }} tuntas</span>
            </div>
        </div>

        {{-- Card 4: Dikembalikan / Revisi --}}
        <div class="history-card-kpi orange">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Dikembalikan (Revisi)</span>
                    <div class="kpi-icon">
                        <i class="bi bi-arrow-return-left"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ $dikembalikan }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Perlu Perbaikan</span>
                <span class="kpi-pill">{{ $dikembalikan }} berkas</span>
            </div>
        </div>
    </div>

    {{-- 3. MAIN TABLE PANEL --}}
    <div class="history-main-panel">

        {{-- TOOLBAR --}}
        <div class="history-toolbar-wrap">
            <div class="toolbar-info">
                <h3>
                    <i class="bi bi-list-task" style="color: var(--bps-blue);"></i>
                    Daftar Linimasa Proses SPJ
                </h3>
                <p>Urutan kronologis setiap perubahan status dan tahapan SPJ.</p>
            </div>

            <div class="toolbar-controls">
                {{-- Search Box --}}
                <div class="search-input-wrap">
                    <i class="bi bi-search search-icon"></i>
                    <input
                        type="text"
                        id="history_search"
                        class="search-input-field"
                        placeholder="Cari nomor SPJ, kegiatan, atau nama..."
                        onkeyup="filterHistories()">
                </div>

                {{-- Status Filter --}}
                <select id="status_filter" class="filter-dropdown" onchange="filterHistories()">
                    <option value="">Semua Status</option>
                    <option value="diajukan">Diajukan</option>
                    <option value="disetujui_umum">Disetujui Bagian Umum</option>
                    <option value="disetujui_ppk">Disetujui PPK</option>
                    <option value="disetujui_ppspm">Disetujui PPSPM</option>
                    <option value="menunggu_otp_kpa">Menunggu OTP QLOLA KPA</option>
                    <option value="selesai">Selesai (Pembayaran)</option>
                    <option value="revisi">Dikembalikan / Revisi</option>
                </select>

                {{-- Reset Filter --}}
                <button type="button" class="btn-reset-filter" onclick="resetHistoryFilter()" title="Reset pencarian dan filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>
        </div>

        {{-- TABLE CONTAINER --}}
        <div class="table-container">
            <table class="modern-history-table" id="history_table">
                <thead>
                    <tr>
                        <th style="width: 45px; text-align: center;">No</th>
                        <th><i class="bi bi-file-earmark-text me-1"></i> Dokumen SPJ</th>
                        <th><i class="bi bi-activity me-1"></i> Aktivitas &amp; Tahapan</th>
                        <th><i class="bi bi-person me-1"></i> Pengguna / Staf</th>
                        <th><i class="bi bi-check-circle me-1"></i> Status Alur</th>
                        <th><i class="bi bi-calendar-event me-1"></i> Waktu Pencatatan</th>
                        <th style="text-align: center; width: 80px;"><i class="bi bi-gear me-1"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody id="history_table_body">
                    @forelse($riwayat as $index => $item)
                    @php
                        $spj = $item->spj;
                        $statusNormalized = strtolower($item->status_baru ?? '');
                        $isRevisi = str_starts_with($statusNormalized, 'revisi');
                    @endphp
                    <tr class="history-row"
                        data-nomor="{{ strtolower($spj?->nomor_spj ?? '') }}"
                        data-kegiatan="{{ strtolower($spj?->kegiatan ?? '') }}"
                        data-aksi="{{ strtolower($item->aksi ?? '') }}"
                        data-user="{{ strtolower($item->user?->name ?? $spj?->user?->name ?? '') }}"
                        data-status="{{ $isRevisi ? 'revisi' : $statusNormalized }}">
                        
                        {{-- NO --}}
                        <td style="text-align: center; font-weight: 800; color: var(--dash-slate-400);">
                            {{ $index + 1 }}
                        </td>

                        {{-- DOKUMEN --}}
                        <td>
                            <div class="document-cell">
                                <div class="doc-badge-row">
                                    <span class="doc-number-badge">
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                        {{ $spj?->nomor_spj ?? 'SPJ-#' . $item->spj_id }}
                                    </span>
                                </div>
                                <span class="doc-kegiatan-text" title="{{ $spj?->kegiatan ?? 'Tanpa Keterangan' }}">
                                    {{ $spj?->kegiatan ?? 'Dokumen SPJ #' . $item->spj_id }}
                                </span>
                                @if($spj?->nilai)
                                    <span class="doc-nilai-text">
                                        Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- AKTIVITAS & TAHAPAN --}}
                        <td>
                            <div>
                                @if($item->status_baru === 'diajukan')
                                    <span class="step-pill diajukan">
                                        <i class="bi bi-send-fill"></i> Pengajuan Berkas
                                    </span>
                                @elseif($item->status_baru === 'disetujui_umum')
                                    <span class="step-pill umum">
                                        <i class="bi bi-clipboard2-check-fill"></i> Verifikasi Bagian Umum
                                    </span>
                                @elseif($item->status_baru === 'disetujui_ppk')
                                    <span class="step-pill ppk">
                                        <i class="bi bi-file-earmark-person-fill"></i> Persetujuan PPK
                                    </span>
                                @elseif($item->status_baru === 'menunggu_otp_kpa')
                                    <span class="step-pill ppk" style="background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe;">
                                        <i class="bi bi-key-fill"></i> Menunggu OTP QLOLA KPA
                                    </span>
                                @elseif($item->status_baru === 'selesai')
                                    <span class="step-pill ppspm">
                                        <i class="bi bi-patch-check-fill"></i> Selesai (Pembayaran Selesai)
                                    </span>
                                @elseif($item->status_baru === 'disetujui_ppspm')
                                    <span class="step-pill ppspm">
                                        <i class="bi bi-patch-check-fill"></i> Disetujui PPSPM (Penerbitan SPM)
                                    </span>
                                @elseif($isRevisi)
                                    <span class="step-pill revisi">
                                        <i class="bi bi-arrow-return-left"></i> Pengembalian Berkas
                                    </span>
                                @else
                                    <span class="step-pill diajukan">
                                        <i class="bi bi-check-circle"></i> {{ $item->aksi }}
                                    </span>
                                @endif

                                <div class="step-keterangan">
                                    {{ $item->keterangan ?: 'Aktivitas proses dokumen SPJ pada alur verifikasi sistem.' }}
                                </div>
                            </div>
                        </td>

                        {{-- PENGGUNA --}}
                        <td>
                            <div class="history-user-cell">
                                <div class="history-avatar">
                                    {{ strtoupper(substr($item->user?->name ?? $spj?->user?->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="history-user-name">
                                        {{ $item->user?->name ?? $spj?->user?->name ?? 'Petugas Sistem' }}
                                    </span>
                                    <span class="history-user-role">
                                        {{ ucfirst($item->user?->role ?? $spj?->user?->role ?? 'Pegawai') }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @if($item->status_baru === 'selesai')
                                <span class="status-badge-item completed">
                                    <i class="bi bi-check-circle-fill"></i> Selesai
                                </span>
                            @elseif($item->status_baru === 'menunggu_otp_kpa')
                                <span class="status-badge-item processing" style="background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe;">
                                    <i class="bi bi-key-fill"></i> Menunggu OTP KPA
                                </span>
                            @elseif($item->status_baru === 'disetujui_ppspm')
                                <span class="status-badge-item completed">
                                    <i class="bi bi-patch-check-fill"></i> Disetujui PPSPM
                                </span>
                            @elseif($item->status_baru === 'disetujui_ppk')
                                <span class="status-badge-item processing">
                                    <i class="bi bi-hourglass-split"></i> Disetujui PPK
                                </span>
                            @elseif($item->status_baru === 'disetujui_umum')
                                <span class="status-badge-item processing">
                                    <i class="bi bi-hourglass-split"></i> Disetujui Umum
                                </span>
                            @elseif($item->status_baru === 'diajukan')
                                <span class="status-badge-item warning">
                                    <i class="bi bi-clock"></i> Diajukan
                                </span>
                            @elseif($isRevisi)
                                <span class="status-badge-item returned">
                                    <i class="bi bi-exclamation-circle-fill"></i> Revisi
                                </span>
                            @else
                                <span class="status-badge-item processing">
                                    <i class="bi bi-circle-fill" style="font-size: 8px;"></i> {{ ucfirst(str_replace('_', ' ', $item->status_baru)) }}
                                </span>
                            @endif
                        </td>

                        {{-- WAKTU --}}
                        <td>
                            <div style="font-size: 13px; color: var(--dash-slate-700); font-weight: 600;">
                                <i class="bi bi-calendar3" style="font-size: 11px; margin-right: 4px; color: var(--dash-slate-400);"></i>
                                {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                            </div>
                            <div style="font-size: 11.5px; color: var(--dash-slate-400); margin-top: 2px;">
                                <i class="bi bi-clock-history" style="font-size: 10px; margin-right: 3px;"></i>
                                {{ $item->created_at ? $item->created_at->format('H:i') : '-' }} WIB
                            </div>
                        </td>

                        {{-- AKSI --}}
                        <td style="text-align: center;">
                            @if($spj)
                                <a href="{{ route('admin.spj.show', $spj->id) }}" class="btn-action-cell view" title="Lihat Detail SPJ">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            @else
                                <span style="color: var(--dash-slate-400); font-size: 12px;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 45px 20px; color: var(--dash-slate-400);">
                            <i class="bi bi-clock-history" style="font-size: 38px; display: block; margin-bottom: 10px; color: var(--dash-slate-400);"></i>
                            Belum ada riwayat proses SPJ yang tercatat di sistem.
                        </td>
                    </tr>
                    @endforelse

                    {{-- Row saat pencarian tidak cocok --}}
                    <tr id="no_match_history_row" style="display: none;">
                        <td colspan="7" style="text-align: center; padding: 45px 20px; color: var(--dash-slate-400);">
                            <i class="bi bi-search" style="font-size: 34px; display: block; margin-bottom: 10px; color: var(--dash-slate-400);"></i>
                            Tidak ada riwayat proses yang cocok dengan filter atau kata kunci pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- TABLE FOOTER COUNTER --}}
        <div class="table-bottom-bar">
            <span>
                Menampilkan <strong id="showing_history_count" style="color: var(--bps-navy);">{{ $riwayat->count() }}</strong> dari <strong>{{ $totalRiwayat }}</strong> total riwayat proses
            </span>
            <span style="font-size: 11.5px; color: var(--dash-slate-400);">
                <i class="bi bi-shield-check"></i> Sistem Informasi SPJ &bull; BPS Kolaka Utara
            </span>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function filterHistories() {
        const query = document.getElementById('history_search').value.toLowerCase().trim();
        const statusVal = document.getElementById('status_filter').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.history-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowNomor = row.getAttribute('data-nomor') || '';
            const rowKegiatan = row.getAttribute('data-kegiatan') || '';
            const rowAksi = row.getAttribute('data-aksi') || '';
            const rowUser = row.getAttribute('data-user') || '';
            const rowStatus = row.getAttribute('data-status') || '';

            const matchesQuery = !query || 
                rowNomor.includes(query) || 
                rowKegiatan.includes(query) || 
                rowAksi.includes(query) || 
                rowUser.includes(query);

            const matchesStatus = !statusVal || rowStatus === statusVal;

            if (matchesQuery && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update Counter
        const countSpan = document.getElementById('showing_history_count');
        if (countSpan) countSpan.textContent = visibleCount;

        // No match notification
        const noMatchRow = document.getElementById('no_match_history_row');
        if (noMatchRow) {
            noMatchRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }
    }

    function resetHistoryFilter() {
        document.getElementById('history_search').value = '';
        document.getElementById('status_filter').value = '';
        filterHistories();
    }
</script>
@endpush

@endsection