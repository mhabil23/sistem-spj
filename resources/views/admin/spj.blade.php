@extends('layouts.admin')

@php
    $title = 'Data SPJ';
@endphp

@push('styles')
<style>
    /* =========================================================
       ADMIN - DATA SPJ (BPS THEME & MODERN DATA TABLE)
    ========================================================= */
    :root {
        --bps-navy: #082052;
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
        --dash-slate-200: #e2e8f0;
        --dash-slate-100: #f1f5f9;
        --dash-slate-50: #f8fafc;

        --dash-radius-xl: 16px;
        --dash-radius-lg: 12px;
        --dash-radius-md: 8px;
    }

    .spj-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        color: var(--dash-slate-800);
    }

    /* 1. HEADER */
    .spj-header-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        background: #ffffff;
        padding: 20px 24px;
        border-radius: var(--dash-radius-xl);
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
    }

    .spj-header-title h2 {
        margin: 0 0 4px 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .spj-header-title p {
        margin: 0;
        color: var(--dash-slate-500);
        font-size: 13px;
    }

    .btn-create-spj {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        background: linear-gradient(135deg, var(--bps-orange) 0%, var(--bps-orange-dark) 100%);
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        border-radius: var(--dash-radius-md);
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(243, 112, 33, 0.35);
        transition: all 0.2s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-create-spj:hover {
        background: linear-gradient(135deg, var(--bps-orange-dark) 0%, #c2410c 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(243, 112, 33, 0.45);
        color: #ffffff;
    }

    /* 2. STATS CARDS */
    .spj-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .spj-card-kpi {
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

    .spj-card-kpi::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
    }

    .spj-card-kpi:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -4px rgba(8, 32, 82, 0.08);
        border-color: #cbd5e1;
    }

    .spj-card-kpi.blue::before { background: linear-gradient(90deg, var(--bps-navy), var(--bps-blue)); }
    .spj-card-kpi.blue .kpi-icon { background: var(--bps-blue-light); color: var(--bps-blue); }
    .spj-card-kpi.blue .kpi-pill { background: #eff6ff; color: var(--bps-blue); border: 1px solid #bfdbfe; }

    .spj-card-kpi.orange::before { background: linear-gradient(90deg, var(--bps-orange), #fb923c); }
    .spj-card-kpi.orange .kpi-icon { background: var(--bps-orange-light); color: var(--bps-orange); }
    .spj-card-kpi.orange .kpi-pill { background: var(--bps-orange-light); color: var(--bps-orange-dark); border: 1px solid #fed7aa; }

    .spj-card-kpi.green::before { background: linear-gradient(90deg, var(--bps-green-dark), #34d399); }
    .spj-card-kpi.green .kpi-icon { background: var(--bps-green-light); color: var(--bps-green); }
    .spj-card-kpi.green .kpi-pill { background: var(--bps-green-light); color: var(--bps-green-dark); border: 1px solid #a7f3d0; }

    .spj-card-kpi.red::before { background: linear-gradient(90deg, var(--bps-danger), #f87171); }
    .spj-card-kpi.red .kpi-icon { background: var(--bps-danger-light); color: var(--bps-danger); }
    .spj-card-kpi.red .kpi-pill { background: var(--bps-danger-light); color: var(--bps-danger); border: 1px solid #fecaca; }

    .kpi-top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .kpi-title {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--dash-slate-600);
    }

    .kpi-icon {
        width: 38px;
        height: 38px;
        border-radius: var(--dash-radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .kpi-number {
        font-size: 28px;
        font-weight: 800;
        color: var(--bps-navy);
        line-height: 1;
        margin: 0 0 10px 0;
    }

    .kpi-bottom-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
        font-size: 11.5px;
        color: var(--dash-slate-500);
    }

    .kpi-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 10.5px;
    }

    /* 3. TABLE PANEL */
    .spj-main-panel {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
        overflow: hidden;
    }

    .spj-toolbar-wrap {
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
        margin: 0 0 2px 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .toolbar-info p {
        margin: 0;
        font-size: 12px;
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
        min-width: 260px;
    }

    .search-input-wrap i {
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
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .filter-dropdown:focus {
        background: #ffffff;
        border-color: var(--bps-blue);
    }

    .btn-reset-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 12px;
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
    }

    /* 4. TABLE */
    .table-container {
        overflow-x: auto;
        width: 100%;
    }

    .modern-data-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        white-space: nowrap;
    }

    .modern-data-table th {
        padding: 13px 18px;
        background: #f8fafc;
        color: var(--dash-slate-600);
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
    }

    .modern-data-table td {
        padding: 15px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: var(--dash-slate-700);
        vertical-align: middle;
    }

    .modern-data-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .modern-data-table tbody tr:hover {
        background-color: var(--bps-blue-subtle);
    }

    .modern-data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .uraian-title {
        font-weight: 700;
        color: var(--bps-navy);
        display: block;
        max-width: 320px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .uraian-title:hover {
        color: var(--bps-orange);
    }

    .nomor-spj-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        color: var(--dash-slate-500);
        margin-top: 3px;
    }

    .nomor-spj-badge {
        font-family: monospace;
        font-size: 10.5px;
        background: #f1f5f9;
        padding: 1px 6px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        color: var(--dash-slate-700);
    }

    .pic-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: var(--dash-slate-700);
        font-weight: 600;
    }

    .pic-avatar {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--bps-blue-light);
        color: var(--bps-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
    }

    .nilai-amount {
        font-weight: 800;
        color: var(--bps-navy);
        font-size: 13.5px;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-status.diajukan { background: var(--bps-blue-light); color: var(--bps-blue); border: 1px solid #bfdbfe; }
    .badge-status.umum { background: var(--bps-orange-light); color: var(--bps-orange-dark); border: 1px solid #fed7aa; }
    .badge-status.ppk { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
    .badge-status.selesai { background: var(--bps-green-light); color: var(--bps-green-dark); border: 1px solid #a7f3d0; }
    .badge-status.revisi { background: var(--bps-danger-light); color: var(--bps-danger); border: 1px solid #fecaca; }
    .badge-status.draft { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action-cell {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f5f9;
        color: var(--bps-navy);
        border: none;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 14px;
    }

    .btn-action-cell.edit:hover {
        background: var(--bps-blue);
        color: #ffffff;
    }

    .btn-action-cell.delete:hover {
        background: var(--bps-danger);
        color: #ffffff;
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
        .spj-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .spj-stats-grid {
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
<div class="spj-page-wrapper">

    {{-- 1. HEADER --}}
    <div class="spj-header-box">
        <div class="spj-header-title">
            <h2>
                <i class="bi bi-folder2-open" style="color: var(--bps-blue);"></i>
                Manajemen Data SPJ
            </h2>
            <p>Kelola, pantau kelengkapan berkas, dan verifikasi seluruh pengajuan tagihan SPJ BPS Kolaka Utara.</p>
        </div>

        <a href="{{ route('admin.spj.create') }}" class="btn-create-spj">
            <i class="bi bi-plus-circle-fill"></i> Tambah SPJ Baru
        </a>
    </div>

    {{-- 2. STATISTIK KPI CARDS (BPS COLOR PALETTE) --}}
    <div class="spj-stats-grid">
        {{-- Card 1: Total SPJ --}}
        <div class="spj-card-kpi blue">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Total Berkas SPJ</span>
                    <div class="kpi-icon">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ number_format($totalSpj) }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Total Pengajuan</span>
                <span class="kpi-pill">Rp {{ number_format($totalNilaiSpj, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Card 2: Menunggu Proses --}}
        <div class="spj-card-kpi orange">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Sedang Diproses</span>
                    <div class="kpi-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ number_format($menungguProses) }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Alur Verifikasi</span>
                <span class="kpi-pill">{{ $totalSpj > 0 ? round(($menungguProses / $totalSpj) * 100) : 0 }}% berkas aktif</span>
            </div>
        </div>

        {{-- Card 3: Selesai / Disetujui PPSPM --}}
        <div class="spj-card-kpi green">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Disetujui (Selesai)</span>
                    <div class="kpi-icon">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ number_format($selesai) }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Total Selesai</span>
                <span class="kpi-pill">Rp {{ number_format($totalNilaiSelesai, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Card 4: Perlu Revisi --}}
        <div class="spj-card-kpi red">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Perlu Revisi</span>
                    <div class="kpi-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ number_format($dikembalikan) }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Perbaikan Berkas</span>
                <span class="kpi-pill">{{ $dikembalikan }} berkas</span>
            </div>
        </div>
    </div>

    {{-- 3. MAIN TABLE PANEL --}}
    <div class="spj-main-panel">

        {{-- TOOLBAR --}}
        <div class="spj-toolbar-wrap">
            <div class="toolbar-info">
                <h3>
                    <i class="bi bi-table" style="color: var(--bps-blue);"></i>
                    Daftar Seluruh SPJ
                </h3>
                <p>Data lengkap pengajuan tagihan SPJ dengan pencarian instan.</p>
            </div>

            <div class="toolbar-controls">
                {{-- Search Box --}}
                <div class="search-input-wrap">
                    <i class="bi bi-search"></i>
                    <input
                        type="text"
                        id="table_search"
                        class="search-input-field"
                        placeholder="Cari uraian, nomor SPJ, atau PIC..."
                        onkeyup="filterTable()">
                </div>

                {{-- Status Filter --}}
                <select id="status_filter" class="filter-dropdown" onchange="filterTable()">
                    <option value="">Semua Status</option>
                    <option value="diproses">Sedang Diproses (Aktif)</option>
                    <option value="diajukan">Pengajuan Teknis</option>
                    <option value="disetujui_umum">Verifikasi Bagian Umum</option>
                    <option value="disetujui_ppk">Persetujuan PPK</option>
                    <option value="disetujui_ppspm">Disetujui PPSPM</option>
                    <option value="menunggu_otp_kpa">Menunggu OTP QLOLA KPA</option>
                    <option value="selesai">Selesai</option>
                    <option value="revisi">Perlu Revisi</option>
                    <option value="draft">Draft</option>
                </select>

                {{-- Reset Filter --}}
                <button type="button" class="btn-reset-filter" onclick="resetFilter()" title="Reset pencarian">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>
        </div>

        {{-- TABLE CONTAINER --}}
        <div class="table-container">
            <table class="modern-data-table" id="spj_table">
                <thead>
                    <tr>
                        <th style="width: 45px; text-align: center;">No</th>
                        <th>Uraian Kegiatan & Nomor SPJ</th>
                        <th>Staf Pengaju (PIC)</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Nilai Anggaran</th>
                        <th>Status Posisi</th>
                        <th style="text-align: center; width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table_body">
                    @forelse($spjs as $index => $spj)
                    <tr class="spj-row"
                        data-status="{{ strtolower($spj->status) }}"
                        data-kegiatan="{{ strtolower($spj->kegiatan) }}"
                        data-nomor="{{ strtolower($spj->nomor_spj ?? '') }}"
                        data-pic="{{ strtolower($spj->user->name ?? '') }}"
                        data-nilai="{{ $spj->nilai }}">
                        
                        {{-- NO --}}
                        <td style="text-align: center; font-weight: 800; color: var(--dash-slate-400);">
                            {{ $index + 1 }}
                        </td>

                        {{-- URAIAN & NOMOR SPJ --}}
                        <td>
                            <a href="{{ route('admin.spj.edit', $spj->id) }}" class="uraian-title" title="{{ $spj->kegiatan }}">
                                {{ $spj->kegiatan }}
                            </a>
                            <div class="nomor-spj-tag">
                                @if($spj->nomor_spj)
                                    <span class="nomor-spj-badge">{{ $spj->nomor_spj }}</span>
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">(Belum ada nomor SPJ)</span>
                                @endif
                            </div>
                        </td>

                        {{-- STAF PENGAJU (PIC) --}}
                        <td>
                            <div class="pic-badge">
                                <div class="pic-avatar">
                                    {{ strtoupper(substr($spj->user->name ?? 'T', 0, 1)) }}
                                </div>
                                <span>{{ $spj->user->name ?? 'Staf Teknis' }}</span>
                            </div>
                        </td>

                        {{-- TANGGAL --}}
                        <td>
                            <span style="font-weight: 600; color: var(--dash-slate-700);">
                                <i class="bi bi-calendar3" style="font-size: 11px; margin-right: 3px; color: var(--dash-slate-400);"></i>
                                {{ $spj->tanggal ? \Carbon\Carbon::parse($spj->tanggal)->format('d M Y') : $spj->created_at->format('d M Y') }}
                            </span>
                        </td>

                        {{-- NILAI ANGGARAN --}}
                        <td>
                            <span class="nilai-amount">
                                Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- STATUS POSISI --}}
                        <td>
                            @if($spj->status === 'diajukan')
                                <span class="badge-status diajukan">
                                    <i class="bi bi-clock"></i> Pengajuan Teknis
                                </span>
                            @elseif($spj->status === 'disetujui_umum')
                                <span class="badge-status umum">
                                    <i class="bi bi-search"></i> Verifikasi Umum
                                </span>
                            @elseif($spj->status === 'disetujui_ppk')
                                <span class="badge-status ppk">
                                    <i class="bi bi-shield-check"></i> Persetujuan PPK
                                </span>
                            @elseif($spj->status === 'disetujui_ppspm')
                                <span class="badge-status selesai">
                                    <i class="bi bi-patch-check-fill"></i> Disetujui PPSPM
                                </span>
                            @elseif($spj->status === 'menunggu_otp_kpa')
                                <span class="badge-status ppk" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">
                                    <i class="bi bi-key-fill"></i> Menunggu OTP KPA
                                </span>
                            @elseif($spj->status === 'selesai')
                                <span class="badge-status selesai">
                                    <i class="bi bi-check-circle-fill"></i> Selesai
                                </span>
                            @elseif(str_contains($spj->status, 'revisi') || $spj->status === 'dikembalikan')
                                <span class="badge-status revisi">
                                    <i class="bi bi-arrow-repeat"></i> Perlu Revisi
                                </span>
                            @else
                                <span class="badge-status draft">
                                    <i class="bi bi-file-earmark"></i> {{ ucfirst($spj->status) }}
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td style="text-align: center;">
                            <div class="action-group" style="justify-content: center;">
                                <a href="{{ route('admin.spj.edit', $spj->id) }}" class="btn-action-cell edit" title="Edit / Detail SPJ">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.spj.destroy', $spj->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas SPJ ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-cell delete" title="Hapus SPJ">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="empty_row">
                        <td colspan="7" style="text-align: center; padding: 40px 20px; color: var(--dash-slate-400);">
                            <i class="bi bi-inbox" style="font-size: 36px; display: block; margin-bottom: 8px;"></i>
                            Belum ada berkas SPJ yang terdaftar dalam sistem.
                        </td>
                    </tr>
                    @endforelse
                    
                    {{-- Row saat filter pencarian tidak menemukan hasil --}}
                    <tr id="no_match_row" style="display: none;">
                        <td colspan="7" style="text-align: center; padding: 40px 20px; color: var(--dash-slate-400);">
                            <i class="bi bi-search" style="font-size: 32px; display: block; margin-bottom: 8px; color: var(--dash-slate-400);"></i>
                            Tidak ada berkas SPJ yang cocok dengan filter atau kata kunci pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- TABLE FOOTER COUNTER --}}
        <div class="table-bottom-bar">
            <span>
                Menampilkan <strong id="showing_count" style="color: var(--bps-navy);">{{ $spjs->count() }}</strong> dari <strong>{{ $spjs->count() }}</strong> total berkas SPJ
            </span>
            <span style="font-size: 11.5px; color: var(--dash-slate-400);">
                <i class="bi bi-shield-check"></i> Sistem Informasi SPJ &bull; BPS Kolaka Utara
            </span>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function filterTable() {
        const query = document.getElementById('table_search').value.toLowerCase().trim();
        const statusVal = document.getElementById('status_filter').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.spj-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowKegiatan = row.getAttribute('data-kegiatan') || '';
            const rowNomor = row.getAttribute('data-nomor') || '';
            const rowPic = row.getAttribute('data-pic') || '';
            const rowStatus = row.getAttribute('data-status') || '';

            const matchesQuery = !query || 
                rowKegiatan.includes(query) || 
                rowNomor.includes(query) || 
                rowPic.includes(query);

            let matchesStatus = true;
            if (statusVal) {
                if (statusVal === 'revisi' || statusVal === 'dikembalikan') {
                    matchesStatus = rowStatus.includes('revisi') || rowStatus === 'dikembalikan';
                } else if (statusVal === 'selesai') {
                    matchesStatus = rowStatus === 'selesai';
                } else if (statusVal === 'disetujui_ppspm') {
                    matchesStatus = rowStatus === 'disetujui_ppspm';
                } else if (statusVal === 'menunggu_otp_kpa') {
                    matchesStatus = rowStatus === 'menunggu_otp_kpa';
                } else if (statusVal === 'diproses') {
                    matchesStatus = ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'diproses'].includes(rowStatus);
                } else {
                    matchesStatus = rowStatus === statusVal;
                }
            }

            if (matchesQuery && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update Counter
        const countSpan = document.getElementById('showing_count');
        if (countSpan) countSpan.textContent = visibleCount;

        // No match notification
        const noMatchRow = document.getElementById('no_match_row');
        if (noMatchRow) {
            noMatchRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }
    }

    function resetFilter() {
        document.getElementById('table_search').value = '';
        document.getElementById('status_filter').value = '';
        filterTable();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const statusParam = urlParams.get('status');
        if (statusParam) {
            const select = document.getElementById('status_filter');
            if (select) {
                select.value = statusParam;
                filterTable();
            }
        }
    });
</script>
@endpush

@endsection