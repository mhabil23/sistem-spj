@extends('layouts.admin')

@php
    $title = 'Manajemen Pengguna';
@endphp

@push('styles')
<style>
    /* =========================================================
       ADMIN - MANAJEMEN PENGGUNA (BPS DESIGN SYSTEM)
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

    .user-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        color: var(--dash-slate-800);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* FLASH ALERT BANNER */
    .user-alert-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        border-radius: var(--dash-radius-lg);
        font-size: 13.5px;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
        animation: fadeIn 0.3s ease;
    }

    .user-alert-banner.success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }

    .user-alert-banner.error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .user-alert-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-alert-banner i {
        font-size: 18px;
    }

    .btn-close-alert {
        background: transparent;
        border: none;
        cursor: pointer;
        color: inherit;
        opacity: 0.6;
        padding: 4px;
        display: inline-flex;
        border-radius: 4px;
        transition: opacity 0.2s;
        font-size: 14px;
    }

    .btn-close-alert:hover {
        opacity: 1;
    }

    /* 1. HEADER */
    .user-header-box {
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

    .user-header-title h2 {
        margin: 0 0 5px 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.02em;
    }

    .user-header-title p {
        margin: 0;
        color: var(--dash-slate-500);
        font-size: 13.5px;
    }

    .btn-create-user {
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

    .btn-create-user:hover {
        background: linear-gradient(135deg, var(--bps-orange-dark) 0%, #c2410c 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(243, 112, 33, 0.45);
        color: #ffffff;
    }

    /* 2. STATS CARDS */
    .user-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .user-card-kpi {
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

    .user-card-kpi::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
    }

    .user-card-kpi:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -4px rgba(8, 32, 82, 0.08);
        border-color: #cbd5e1;
    }

    .user-card-kpi.blue::before { background: linear-gradient(90deg, var(--bps-navy), var(--bps-blue)); }
    .user-card-kpi.blue .kpi-icon { background: var(--bps-blue-light); color: var(--bps-blue); }
    .user-card-kpi.blue .kpi-pill { background: #eff6ff; color: var(--bps-blue); border: 1px solid #bfdbfe; }

    .user-card-kpi.green::before { background: linear-gradient(90deg, var(--bps-green-dark), #34d399); }
    .user-card-kpi.green .kpi-icon { background: var(--bps-green-light); color: var(--bps-green); }
    .user-card-kpi.green .kpi-pill { background: var(--bps-green-light); color: var(--bps-green-dark); border: 1px solid #a7f3d0; }

    .user-card-kpi.orange::before { background: linear-gradient(90deg, var(--bps-orange), #fb923c); }
    .user-card-kpi.orange .kpi-icon { background: var(--bps-orange-light); color: var(--bps-orange); }
    .user-card-kpi.orange .kpi-pill { background: var(--bps-orange-light); color: var(--bps-orange-dark); border: 1px solid #fed7aa; }

    .user-card-kpi.navy::before { background: linear-gradient(90deg, var(--bps-navy), #1e3a8a); }
    .user-card-kpi.navy .kpi-icon { background: #f0f7ff; color: var(--bps-navy); }
    .user-card-kpi.navy .kpi-pill { background: #f0f7ff; color: var(--bps-navy); border: 1px solid #cbd5e1; }

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
    .user-main-panel {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
        overflow: hidden;
    }

    .user-toolbar-wrap {
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

    /* 4. USER TABLE */
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
        font-size: 11.5px;
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

    /* User Cell Styling */
    .user-profile-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 800;
        flex-shrink: 0;
        border: 2px solid #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .user-avatar-circle.admin { background: #eff6ff; color: var(--bps-blue); }
    .user-avatar-circle.teknis { background: var(--bps-green-light); color: var(--bps-green); }
    .user-avatar-circle.umum { background: var(--bps-orange-light); color: var(--bps-orange-dark); }
    .user-avatar-circle.ppk { background: #e0e7ff; color: #3730a3; }
    .user-avatar-circle.ppspm { background: #f0f9ff; color: #0369a1; }
    .user-avatar-circle.bendahara { background: #fdf2f8; color: #db2777; }

    .user-name-title {
        font-weight: 700;
        color: var(--bps-navy);
        font-size: 13.5px;
        display: block;
    }

    .user-id-sub {
        font-size: 11px;
        color: var(--dash-slate-500);
        font-family: monospace;
        display: inline-block;
        margin-top: 2px;
        background: #f1f5f9;
        padding: 1px 6px;
        border-radius: 4px;
    }

    .email-link {
        color: var(--dash-slate-700);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        transition: color 0.15s ease;
    }

    .email-link:hover {
        color: var(--bps-blue);
        text-decoration: underline;
    }

    .badge-wa {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 8px;
        border-radius: 4px;
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
        font-size: 11.5px;
        font-weight: 600;
        margin-top: 4px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .badge-wa:hover {
        background: #dcfce7;
        color: #166534;
        transform: translateY(-1px);
    }

    /* Role Badges */
    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .badge-role.admin { background: #eff6ff; color: var(--bps-blue); border: 1px solid #bfdbfe; }
    .badge-role.teknis { background: var(--bps-green-light); color: var(--bps-green-dark); border: 1px solid #a7f3d0; }
    .badge-role.umum { background: var(--bps-orange-light); color: var(--bps-orange-dark); border: 1px solid #fed7aa; }
    .badge-role.ppk { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
    .badge-role.ppspm { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
    .badge-role.bendahara { background: #fdf2f8; color: #db2777; border: 1px solid #fbcfe8; }

    /* Status Badges */
    .badge-user-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-user-status.active {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .badge-user-status.inactive {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    .action-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

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

    .btn-action-cell.edit:hover {
        background: var(--bps-blue);
        color: #ffffff;
        border-color: var(--bps-blue);
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(2, 75, 134, 0.25);
    }

    .btn-action-cell.delete:hover {
        background: var(--bps-danger);
        color: #ffffff;
        border-color: var(--bps-danger);
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.25);
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
        .user-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .user-stats-grid {
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
<div class="user-page-wrapper">

    {{-- ALERT FLASH MESSAGES --}}
    @if(session('success'))
        <div class="user-alert-banner success">
            <div class="user-alert-content">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close-alert" onclick="this.parentElement.remove()" title="Tutup Notifikasi">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="user-alert-banner error">
            <div class="user-alert-content">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close-alert" onclick="this.parentElement.remove()" title="Tutup Notifikasi">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- 1. HEADER --}}
    <div class="user-header-box">
        <div class="user-header-title">
            <h2>
                <i class="bi bi-people-fill" style="color: var(--bps-blue);"></i>
                Manajemen Pengguna & Hak Akses
            </h2>
            <p>Kelola akun pegawai, pembagian hak akses (role), kontak WhatsApp, dan status keaktifan user dalam sistem SPJ.</p>
        </div>

        <a href="{{ route('admin.pengguna.create') }}" class="btn-create-user">
            <i class="bi bi-person-plus-fill"></i> Tambah Pengguna Baru
        </a>
    </div>

    {{-- 2. STATISTIK KPI CARDS (BPS COLOR PALETTE) --}}
    <div class="user-stats-grid">
        {{-- Card 1: Total Pengguna (BPS Blue) --}}
        <div class="user-card-kpi blue">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Total Pengguna</span>
                    <div class="kpi-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ $users->count() }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Seluruh Akun</span>
                <span class="kpi-pill">{{ $users->count() }} terdaftar</span>
            </div>
        </div>

        {{-- Card 2: Pengguna Aktif (BPS Green) --}}
        <div class="user-card-kpi green">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Pengguna Aktif</span>
                    <div class="kpi-icon">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ $users->where('status', 'aktif')->count() }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Status Keaktifan</span>
                <span class="kpi-pill">{{ $users->count() > 0 ? round(($users->where('status', 'aktif')->count() / $users->count()) * 100) : 0 }}% aktif</span>
            </div>
        </div>

        {{-- Card 3: Nonaktif / Dibatasi (BPS Orange) --}}
        <div class="user-card-kpi orange">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Nonaktif</span>
                    <div class="kpi-icon">
                        <i class="bi bi-person-slash"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ $users->where('status', 'nonaktif')->count() }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Akun Dibatasi</span>
                <span class="kpi-pill">{{ $users->where('status', 'nonaktif')->count() }} akun</span>
            </div>
        </div>

        {{-- Card 4: Jenis Role (BPS Navy) --}}
        <div class="user-card-kpi navy">
            <div>
                <div class="kpi-top-row">
                    <span class="kpi-title">Distribusi Role</span>
                    <div class="kpi-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
                <div class="kpi-number">{{ $users->pluck('role')->unique()->count() }}</div>
            </div>
            <div class="kpi-bottom-row">
                <span>Variasi Hak Akses</span>
                <span class="kpi-pill">{{ $users->pluck('role')->unique()->count() }} role aktif</span>
            </div>
        </div>
    </div>

    {{-- 3. MAIN TABLE PANEL --}}
    <div class="user-main-panel">

        {{-- TOOLBAR --}}
        <div class="user-toolbar-wrap">
            <div class="toolbar-info">
                <h3>
                    <i class="bi bi-table" style="color: var(--bps-blue);"></i>
                    Daftar Pengguna Sistem
                </h3>
                <p>Seluruh akun pegawai yang berwenang mengakses alur SPJ.</p>
            </div>

            <div class="toolbar-controls">
                {{-- Search Box --}}
                <div class="search-input-wrap">
                    <i class="bi bi-search search-icon"></i>
                    <input
                        type="text"
                        id="user_search"
                        class="search-input-field"
                        placeholder="Cari nama, email, atau ID pengguna..."
                        onkeyup="filterUsers()">
                </div>

                {{-- Role Filter --}}
                <select id="role_filter" class="filter-dropdown" onchange="filterUsers()">
                    <option value="">Semua Role</option>
                    <option value="admin">Administrator</option>
                    <option value="teknis">Staf Teknis</option>
                    <option value="umum">Bagian Umum</option>
                    <option value="ppk">PPK</option>
                    <option value="ppspm">PPSPM</option>
                    <option value="bendahara">Bendahara</option>
                </select>

                {{-- Status Filter --}}
                <select id="status_filter" class="filter-dropdown" onchange="filterUsers()">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>

                {{-- Reset Filter --}}
                <button type="button" class="btn-reset-filter" onclick="resetUserFilter()" title="Reset pencarian dan filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>
        </div>

        {{-- TABLE CONTAINER --}}
        <div class="table-container">
            <table class="modern-data-table" id="user_table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th><i class="bi bi-person me-1"></i> Pengguna & ID</th>
                        <th><i class="bi bi-envelope me-1"></i> Kontak & WhatsApp</th>
                        <th><i class="bi bi-shield me-1"></i> Role / Hak Akses</th>
                        <th><i class="bi bi-activity me-1"></i> Status</th>
                        <th><i class="bi bi-clock me-1"></i> Terakhir Diperbarui</th>
                        <th style="text-align: center; width: 90px;"><i class="bi bi-gear me-1"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody id="user_table_body">
                    @forelse($users as $index => $user)
                    <tr class="user-row"
                        data-name="{{ strtolower($user->name) }}"
                        data-email="{{ strtolower($user->email) }}"
                        data-id="usr-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}"
                        data-role="{{ strtolower($user->role) }}"
                        data-status="{{ strtolower($user->status) }}">
                        
                        {{-- NO --}}
                        <td style="text-align: center; font-weight: 800; color: var(--dash-slate-400);">
                            {{ $index + 1 }}
                        </td>

                        {{-- PENGGUNA & ID --}}
                        <td>
                            <div class="user-profile-cell">
                                <div class="user-avatar-circle {{ $user->role }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong class="user-name-title">{{ $user->name }}</strong>
                                    <span class="user-id-sub">USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- EMAIL & WHATSAPP --}}
                        <td>
                            <a href="mailto:{{ $user->email }}" class="email-link">
                                <i class="bi bi-envelope-fill" style="color: var(--dash-slate-400); font-size: 12px;"></i>
                                {{ $user->email }}
                            </a>
                            @if($user->no_wa)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $user->no_wa)) }}" target="_blank" class="badge-wa" title="Hubungi pegawai via WhatsApp">
                                    <i class="bi bi-whatsapp"></i> {{ $user->no_wa }}
                                </a>
                            @endif
                        </td>

                        {{-- ROLE --}}
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge-role admin">
                                    <i class="bi bi-shield-shaded"></i> Administrator
                                </span>
                            @elseif($user->role === 'teknis')
                                <span class="badge-role teknis">
                                    <i class="bi bi-laptop"></i> Staf Teknis
                                </span>
                            @elseif($user->role === 'umum')
                                <span class="badge-role umum">
                                    <i class="bi bi-clipboard2-check"></i> Bagian Umum
                                </span>
                            @elseif($user->role === 'ppk')
                                <span class="badge-role ppk">
                                    <i class="bi bi-file-earmark-person"></i> PPK
                                </span>
                            @elseif($user->role === 'ppspm')
                                <span class="badge-role ppspm">
                                    <i class="bi bi-patch-check"></i> PPSPM
                                </span>
                            @elseif($user->role === 'bendahara')
                                <span class="badge-role bendahara">
                                    <i class="bi bi-cash-stack"></i> Bendahara
                                </span>
                            @else
                                <span class="badge-role admin">
                                    <i class="bi bi-person"></i> {{ ucfirst($user->role) }}
                                </span>
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @if($user->status === 'aktif')
                                <span class="badge-user-status active">
                                    <i class="bi bi-check-circle-fill"></i> Aktif
                                </span>
                            @else
                                <span class="badge-user-status inactive">
                                    <i class="bi bi-dash-circle-fill"></i> Nonaktif
                                </span>
                            @endif
                        </td>

                        {{-- TERAKHIR DIPERBARUI --}}
                        <td>
                            <span style="font-weight: 500; color: var(--dash-slate-600); font-size: 12.5px;">
                                <i class="bi bi-clock-history" style="font-size: 11px; margin-right: 4px; color: var(--dash-slate-400);"></i>
                                {{ $user->updated_at ? $user->updated_at->format('d M Y, H:i') : '-' }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td style="text-align: center;">
                            <div class="action-group" style="justify-content: center;">
                                <a href="{{ route('admin.pengguna.edit', $user->id) }}" class="btn-action-cell edit" title="Edit Akun Pengguna">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna {{ $user->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-cell delete" title="Hapus Pengguna">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 45px 20px; color: var(--dash-slate-400);">
                            <i class="bi bi-people" style="font-size: 38px; display: block; margin-bottom: 10px; color: var(--dash-slate-400);"></i>
                            Belum ada akun pengguna yang terdaftar.
                        </td>
                    </tr>
                    @endforelse

                    {{-- Row saat filter pencarian tidak menemukan hasil --}}
                    <tr id="no_match_user_row" style="display: none;">
                        <td colspan="7" style="text-align: center; padding: 45px 20px; color: var(--dash-slate-400);">
                            <i class="bi bi-search" style="font-size: 34px; display: block; margin-bottom: 10px; color: var(--dash-slate-400);"></i>
                            Tidak ada akun pengguna yang cocok dengan filter atau kata kunci pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- TABLE FOOTER COUNTER --}}
        <div class="table-bottom-bar">
            <span>
                Menampilkan <strong id="showing_user_count" style="color: var(--bps-navy);">{{ $users->count() }}</strong> dari <strong>{{ $users->count() }}</strong> total pengguna terdaftar
            </span>
            <span style="font-size: 11.5px; color: var(--dash-slate-400);">
                <i class="bi bi-shield-check"></i> Sistem Informasi SPJ &bull; BPS Kolaka Utara
            </span>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function filterUsers() {
        const query = document.getElementById('user_search').value.toLowerCase().trim();
        const roleVal = document.getElementById('role_filter').value.toLowerCase().trim();
        const statusVal = document.getElementById('status_filter').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.user-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowName = row.getAttribute('data-name') || '';
            const rowEmail = row.getAttribute('data-email') || '';
            const rowId = row.getAttribute('data-id') || '';
            const rowRole = row.getAttribute('data-role') || '';
            const rowStatus = row.getAttribute('data-status') || '';

            const matchesQuery = !query || 
                rowName.includes(query) || 
                rowEmail.includes(query) || 
                rowId.includes(query);

            const matchesRole = !roleVal || rowRole === roleVal;
            const matchesStatus = !statusVal || rowStatus === statusVal;

            if (matchesQuery && matchesRole && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update Counter
        const countSpan = document.getElementById('showing_user_count');
        if (countSpan) countSpan.textContent = visibleCount;

        // No match notification
        const noMatchRow = document.getElementById('no_match_user_row');
        if (noMatchRow) {
            noMatchRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }
    }

    function resetUserFilter() {
        document.getElementById('user_search').value = '';
        document.getElementById('role_filter').value = '';
        document.getElementById('status_filter').value = '';
        filterUsers();
    }
</script>
@endpush

@endsection