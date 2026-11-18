@extends('layouts.admin')

@php
    $title = 'Syarat Dokumen SPJ';
@endphp

@section('content')

@push('styles')
<style>
    :root {
        --bps-navy: #082052;
        --bps-blue: #024b86;
        --bps-blue-light: #e0f2fe;
        --bps-orange: #f37021;
        --bps-orange-light: #fff7ed;
        --bps-green: #10b981;
        --bps-green-light: #ecfdf5;
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

    .doc-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        color: var(--dash-slate-800);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* HEADER HERO */
    .doc-header-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        background: #ffffff;
        padding: 22px 26px;
        border-radius: var(--dash-radius-xl);
        border: 1px solid var(--dash-slate-200);
        box-shadow: 0 2px 6px rgba(8, 32, 82, 0.04);
        position: relative;
        overflow: hidden;
    }

    .doc-header-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(180deg, var(--bps-orange) 0%, var(--bps-blue) 100%);
    }

    .doc-header-title h2 {
        margin: 0 0 4px 0;
        font-size: 21px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .doc-header-title p {
        margin: 0;
        color: var(--dash-slate-500);
        font-size: 13.5px;
        max-width: 720px;
        line-height: 1.45;
    }

    .btn-add-doc {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--bps-blue) 0%, #0369a1 100%);
        color: #ffffff;
        border: none;
        padding: 11px 20px;
        border-radius: var(--dash-radius-md);
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(2, 75, 134, 0.25);
    }

    .btn-add-doc:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(2, 75, 134, 0.35);
    }

    /* STATS GRID */
    .doc-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
    }

    .doc-stat-card {
        background: #ffffff;
        padding: 16px 20px;
        border-radius: var(--dash-radius-lg);
        border: 1px solid var(--dash-slate-200);
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .doc-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .doc-stat-icon.blue { background: #e0f2fe; color: #0284c7; }
    .doc-stat-icon.green { background: #dcfce7; color: #16a34a; }
    .doc-stat-icon.orange { background: #ffedd5; color: #ea580c; }
    .doc-stat-icon.purple { background: #f3e8ff; color: #7e22ce; }

    .doc-stat-val {
        font-size: 20px;
        font-weight: 800;
        color: var(--bps-navy);
        line-height: 1.1;
    }

    .doc-stat-lbl {
        font-size: 12px;
        font-weight: 600;
        color: var(--dash-slate-500);
        margin-top: 2px;
    }

    /* FILTER BAR */
    .doc-filter-panel {
        background: #ffffff;
        border-radius: var(--dash-radius-lg);
        border: 1px solid var(--dash-slate-200);
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .filter-grid-doc {
        display: grid;
        grid-template-columns: 1.4fr 1.4fr 1.1fr 1.1fr 1.6fr auto;
        gap: 12px;
        align-items: flex-end;
    }

    @media (max-width: 1200px) {
        .filter-grid-doc {
            grid-template-columns: 1fr 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .filter-grid-doc {
            grid-template-columns: 1fr;
        }
    }

    .filter-label-doc {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: var(--dash-slate-600);
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-control-doc {
        width: 100%;
        height: 38px;
        border-radius: var(--dash-radius-md);
        border: 1px solid var(--dash-slate-200);
        padding: 0 12px;
        font-size: 12.5px;
        color: var(--dash-slate-800);
        background: var(--dash-slate-50);
        outline: none;
        transition: all 0.2s ease;
    }

    .form-control-doc:focus {
        border-color: var(--bps-blue);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(2, 75, 134, 0.12);
    }

    /* VIEW MODE SWITCHER BAR */
    .view-control-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 4px;
    }

    .view-mode-switcher {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        border: 1px solid var(--dash-slate-200);
        padding: 4px;
        border-radius: 10px;
        gap: 4px;
    }

    .btn-view-mode {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 15px;
        border-radius: 7px;
        border: none;
        background: transparent;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--dash-slate-600);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-view-mode:hover {
        color: var(--bps-blue);
    }

    .btn-view-mode.active {
        background: #ffffff;
        color: var(--bps-blue);
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    }

    /* TOMBOL PERUNTUKAN INTERAKTIF & DROPDOWN */
    .peruntukan-dropdown-wrapper {
        position: relative;
        display: inline-block;
    }

    .btn-peruntukan-toggle {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        white-space: nowrap;
        user-select: none;
    }

    .btn-peruntukan-toggle.semua {
        background: #ecfdf5;
        color: #047857;
        border-color: #a7f3d0;
    }
    .btn-peruntukan-toggle.semua:hover {
        background: #d1fae5;
    }

    .btn-peruntukan-toggle.umum {
        background: #f5f3ff;
        color: #6d28d9;
        border-color: #ddd6fe;
    }
    .btn-peruntukan-toggle.umum:hover {
        background: #ede9fe;
    }

    .btn-peruntukan-toggle.teknis {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }
    .btn-peruntukan-toggle.teknis:hover {
        background: #dbeafe;
    }

    .peruntukan-menu-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        min-width: 285px;
        background: #ffffff;
        border: 1px solid var(--dash-slate-200);
        border-radius: 12px;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.16);
        z-index: 1050;
        padding: 8px;
        animation: fadeInMenu 0.15s ease-out;
        text-align: left;
    }

    .peruntukan-menu-dropdown.show {
        display: block;
    }

    @keyframes fadeInMenu {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dropdown-header-title {
        font-size: 11px;
        font-weight: 800;
        color: var(--bps-navy);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 4px 8px 2px 8px;
    }

    .dropdown-header-desc {
        font-size: 10.5px;
        color: var(--dash-slate-500);
        padding: 0 8px 6px 8px;
        line-height: 1.35;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 6px;
    }

    .dropdown-item-peruntukan {
        width: 100%;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid transparent;
        background: transparent;
        text-align: left;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .dropdown-item-peruntukan:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }

    .dropdown-item-peruntukan.active {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .dropdown-item-peruntukan strong {
        display: block;
        font-size: 12px;
        color: var(--dash-slate-800);
    }

    .dropdown-item-peruntukan small {
        display: block;
        font-size: 10.5px;
        color: var(--dash-slate-500);
        margin-top: 2px;
        line-height: 1.3;
    }

    /* 4A. TAMPILAN KARTU KELOMPOK (CARD VIEW) */
    .belanja-groups-wrapper {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .belanja-group-card {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        border: 1px solid var(--dash-slate-200);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(8, 32, 82, 0.04);
        transition: border-color 0.2s ease;
    }

    .belanja-group-card:hover {
        border-color: #cbd5e1;
    }

    .belanja-group-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--dash-slate-200);
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .belanja-header-left {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .badge-akun-kode {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        letter-spacing: 0.2px;
    }

    .badge-akun-kode.akun-51 {
        background: #dbeafe;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .badge-akun-kode.akun-52 {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .badge-akun-kode.akun-53 {
        background: #f3e8ff;
        color: #7e22ce;
        border: 1px solid #e9d5ff;
    }

    .jenis-belanja-title {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: var(--bps-navy);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-doc-count {
        background: #ffffff;
        color: var(--dash-slate-600);
        border: 1px solid var(--dash-slate-200);
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
    }

    .btn-quick-add {
        background: #ffffff;
        color: var(--bps-blue);
        border: 1.5px solid #bae6fd;
        padding: 6px 14px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-quick-add:hover {
        background: #0284c7;
        color: #ffffff;
        border-color: #0284c7;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
    }

    .doc-table-inner {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .doc-table-inner th {
        background: #ffffff;
        padding: 10px 16px;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--dash-slate-500);
        border-bottom: 1px solid var(--dash-slate-200);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .doc-table-inner td {
        padding: 11px 16px;
        font-size: 13px;
        color: var(--dash-slate-700);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .doc-table-inner tr:last-child td {
        border-bottom: none;
    }

    .doc-table-inner tr:hover td {
        background: #f8fafc;
    }

    .badge-assignee {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 9px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .badge-assignee.sm {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .badge-assignee.umum {
        background: #f5f3ff;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
    }

    .badge-assignee.teknis {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .badge-assignee.bendahara {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .badge-assignee.ppk {
        background: #ede9fe;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
    }

    .btn-action-icon {
        background: transparent;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .btn-action-icon.edit { color: #0284c7; }
    .btn-action-icon.edit:hover { background: #e0f2fe; }
    .btn-action-icon.delete { color: #dc2626; }
    .btn-action-icon.delete:hover { background: #fee2e2; }

    /* 4B. TAMPILAN TABEL EXCEL RINGKAS (PERSIS BENTUK SEPERTI GAMBAR DARI USER) */
    .excel-table-container {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        border: 1.5px solid #334155;
        box-shadow: 0 4px 14px rgba(8, 32, 82, 0.06);
        overflow-x: auto;
    }

    .excel-doc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
        color: #0f172a;
    }

    .excel-doc-table th {
        background: #d1fae5;
        color: #064e3b;
        font-weight: 800;
        font-size: 12px;
        padding: 10px 14px;
        border: 1px solid #334155;
        text-align: center;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }

    .excel-doc-table th.th-nama-belanja {
        background: #bbf7d0;
    }

    .excel-doc-table th.th-jenis-belanja {
        background: #bbf7d0;
    }

    .excel-doc-table th.th-dokumen {
        background: #cbf3bb;
    }

    .excel-doc-table th.th-penanggungjawab {
        background: #bbf7d0;
    }

    .excel-doc-table th.th-peruntukan {
        background: #d1fae5;
    }

    .excel-doc-table th.th-aksi {
        background: #cbf3bb;
    }

    .excel-doc-table td {
        border: 1px solid #334155;
        padding: 7px 12px;
        vertical-align: middle;
        background: #ffffff;
    }

    .excel-doc-table tr:hover td {
        background-color: #f8fafc;
    }

    .excel-cell-nama-belanja {
        font-weight: 800;
        color: var(--bps-navy);
        background: #ffffff !important;
        font-size: 13px;
        vertical-align: middle !important;
    }

    .excel-cell-jenis-belanja {
        font-weight: 700;
        color: #1e293b;
        background: #ffffff !important;
        vertical-align: middle !important;
    }

    .excel-cell-dokumen {
        font-weight: 500;
        color: #0f172a;
        background: #ffffff;
    }

    .excel-cell-assignee {
        text-align: center;
        font-weight: 700;
        white-space: nowrap;
    }

    .excel-cell-peruntukan {
        text-align: center;
        vertical-align: middle !important;
        background: #f8fafc !important;
    }

    .excel-cell-aksi {
        text-align: center;
        white-space: nowrap;
    }

    /* MODAL */
    .modal-backdrop-custom {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(3px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .modal-box-custom {
        background: #ffffff;
        width: 100%;
        max-width: 680px;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        animation: modalScaleIn 0.2s ease-out;
    }

    @keyframes modalScaleIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .modal-header-custom {
        background: var(--bps-navy);
        color: #ffffff;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header-custom h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-close-modal {
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 20px;
        cursor: pointer;
        opacity: 0.8;
    }

    .btn-close-modal:hover { opacity: 1; }

    .modal-body-custom {
        padding: 22px;
        max-height: 75vh;
        overflow-y: auto;
    }

    .form-group-modal {
        margin-bottom: 16px;
    }

    .form-label-modal {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--dash-slate-700);
        margin-bottom: 6px;
    }

    .form-help-modal {
        font-size: 11px;
        color: var(--dash-slate-500);
        margin-top: 4px;
    }

    .doc-input-rows-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 8px;
    }

    .doc-input-row {
        display: grid;
        grid-template-columns: 1fr 180px 75px 36px;
        gap: 8px;
        align-items: center;
        background: #f8fafc;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid var(--dash-slate-200);
        animation: fadeInRow 0.2s ease-out;
    }

    @keyframes fadeInRow {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-remove-row {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 16px;
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }

    .btn-remove-row:hover:not(:disabled) {
        color: #dc2626;
        background: #fee2e2;
    }

    .btn-remove-row:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .btn-add-more-row {
        background: #e0f2fe;
        color: #0369a1;
        border: 1.5px dashed #7dd3fc;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
        transition: all 0.2s ease;
        margin-top: 6px;
    }

    .btn-add-more-row:hover {
        background: #0284c7;
        color: #ffffff;
        border-style: solid;
        border-color: #0284c7;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
    }

    .modal-footer-custom {
        padding: 14px 22px;
        background: #f8fafc;
        border-top: 1px solid var(--dash-slate-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .btn-cancel-modal {
        background: #ffffff;
        border: 1px solid var(--dash-slate-200);
        color: var(--dash-slate-600);
        padding: 8px 16px;
        border-radius: var(--dash-radius-md);
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
    }

    .btn-save-modal {
        background: var(--bps-blue);
        color: #ffffff;
        border: none;
        padding: 8px 20px;
        border-radius: var(--dash-radius-md);
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(2, 75, 134, 0.25);
    }

    .btn-save-modal:hover {
        background: #0369a1;
    }
</style>
@endpush

<div class="doc-page-wrapper">

    <!-- 1. HEADER HERO -->
    <div class="doc-header-box">
        <div class="doc-header-title">
            <h2>
                <i class="bi bi-card-checklist" style="color: var(--bps-orange);"></i>
                Kelola Persyaratan Dokumen SPJ
            </h2>
            <p>
                Konfigurasi daftar checklist dokumen wajib untuk setiap <strong>Nama Belanja</strong> dan <strong>Jenis Belanja</strong>. Setiap penambahan atau perubahan dokumen di sini otomatis sinkron ke formulir pengajuan SPJ Staf Teknis dan Subbag Umum.
            </p>
        </div>
        <div>
            <button type="button" class="btn-add-doc" onclick="openAddDocModal()">
                <i class="bi bi-plus-circle-fill"></i>
                Tambah Dokumen / Jenis Belanja Baru
            </button>
        </div>
    </div>

    <!-- 2. STATS CARDS -->
    <div class="doc-stats-grid">
        <div class="doc-stat-card">
            <div class="doc-stat-icon purple">
                <i class="bi bi-tags-fill"></i>
            </div>
            <div>
                <div class="doc-stat-val">{{ $totalJenisBelanja }}</div>
                <div class="doc-stat-lbl">Jenis Belanja Terdaftar</div>
            </div>
        </div>
        <div class="doc-stat-card">
            <div class="doc-stat-icon blue">
                <i class="bi bi-file-earmark-check"></i>
            </div>
            <div>
                <div class="doc-stat-val">{{ $totalDokumen }}</div>
                <div class="doc-stat-lbl">Total Dokumen Persyaratan</div>
            </div>
        </div>
        <div class="doc-stat-card">
            <div class="doc-stat-icon green">
                <i class="bi bi-person-badge"></i>
            </div>
            <div>
                <div class="doc-stat-val">{{ $totalSm }}</div>
                <div class="doc-stat-lbl">Wajib Disiapkan Teknis / Umum</div>
            </div>
        </div>
        <div class="doc-stat-card">
            <div class="doc-stat-icon orange">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <div class="doc-stat-val">{{ $totalBendahara }}</div>
                <div class="doc-stat-lbl">Dilengkapi Bendahara / PPK</div>
            </div>
        </div>
    </div>

    <!-- 3. FILTER BAR -->
    <div class="doc-filter-panel">
        <form action="{{ route('admin.syarat-dokumen.index') }}" method="GET" class="filter-grid-doc">
            <div>
                <label class="filter-label-doc">Kategori / Nama Belanja</label>
                <select name="nama_belanja" class="form-control-doc" onchange="this.form.submit()">
                    <option value="">-- Semua Nama Belanja --</option>
                    @foreach($daftarNamaBelanja as $nb)
                        <option value="{{ $nb }}" {{ request('nama_belanja') === $nb ? 'selected' : '' }}>
                            {{ $nb }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="filter-label-doc">Jenis Belanja</label>
                <select name="jenis_belanja" class="form-control-doc" onchange="this.form.submit()">
                    <option value="">-- Semua Jenis Belanja --</option>
                    @foreach($daftarJenisBelanja as $jb)
                        <option value="{{ $jb }}" {{ request('jenis_belanja') === $jb ? 'selected' : '' }}>
                            {{ $jb }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="filter-label-doc">Pihak / Assignee</label>
                <select name="assignee" class="form-control-doc" onchange="this.form.submit()">
                    <option value="">-- Semua Pihak --</option>
                    <option value="SM" {{ request('assignee') === 'SM' ? 'selected' : '' }}>SM (Teknis/Umum)</option>
                    <option value="Bendahara" {{ request('assignee') === 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                    <option value="PPK" {{ request('assignee') === 'PPK' ? 'selected' : '' }}>PPK</option>
                </select>
            </div>
            <div>
                <label class="filter-label-doc">Peruntukan</label>
                <select name="peruntukan" class="form-control-doc" onchange="this.form.submit()">
                    <option value="">-- Semua Peruntukan --</option>
                    <option value="semua" {{ request('peruntukan') === 'semua' ? 'selected' : '' }}>Dua-duanya (Teknis &amp; Umum)</option>
                    <option value="umum" {{ request('peruntukan') === 'umum' ? 'selected' : '' }}>Khusus Subbag Umum</option>
                    <option value="teknis" {{ request('peruntukan') === 'teknis' ? 'selected' : '' }}>Khusus Staf Teknis</option>
                </select>
            </div>
            <div>
                <label class="filter-label-doc">Cari Dokumen / Belanja</label>
                <input type="text" name="search" class="form-control-doc" placeholder="Ketik kata kunci pencarian..." value="{{ request('search') }}">
            </div>
            <div>
                <button type="submit" class="btn-save-modal" style="height: 38px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- VIEW CONTROL & TOGGLE BAR (OPSI TAMPILAN KARTU vs TABEL EXCEL) -->
    <div class="view-control-bar">
        <div style="font-size: 13.5px; font-weight: 700; color: var(--dash-slate-700); display: flex; align-items: center; gap: 8px;">
            <span>Daftar Dokumen Persyaratan</span>
            <span style="font-size: 11px; font-weight: 600; color: var(--dash-slate-500); background: #f1f5f9; padding: 2px 8px; border-radius: 9999px;">
                {{ $groupedDokumens->count() }} Jenis Belanja Ditemukan
            </span>
        </div>

        <div class="view-mode-switcher">
            <button type="button" class="btn-view-mode active" id="btnViewCards" onclick="switchViewMode('cards')" title="Tampilan Kartu Kelompok Modern">
                <i class="bi bi-grid-fill"></i> Tampilan Kartu
            </button>
            <button type="button" class="btn-view-mode" id="btnViewTable" onclick="switchViewMode('table')" title="Tampilan Tabel Rinci / Excel">
                <i class="bi bi-table"></i> Tampilan Tabel Excel
            </button>
        </div>
    </div>

    <!-- 4A. TAMPILAN KARTU KELOMPOK (BENTUK BEGITU) -->
    <div id="containerViewCards" class="belanja-groups-wrapper">
        @forelse($groupedDokumens as $groupKey => $docs)
            @php
                $first = $docs->first();
                $namaBelanja = $first->nama_belanja;
                $jenisBelanja = $first->jenis_belanja;
                $peruntukanVal = $first->peruntukan ?: \App\Models\SyaratDokumenSpj::determinePeruntukanByKode($namaBelanja);
                $groupKeyId = md5($namaBelanja . '|||' . $jenisBelanja);

                $akunKode = '52';
                if (str_contains($namaBelanja, '51')) $akunKode = '51';
                elseif (str_contains($namaBelanja, '53')) $akunKode = '53';
            @endphp
            <div class="belanja-group-card">
                <div class="belanja-group-header">
                    <div class="belanja-header-left">
                        <span class="badge-akun-kode akun-{{ $akunKode }}">{{ $namaBelanja }}</span>
                        <h3 class="jenis-belanja-title">{{ $jenisBelanja }}</h3>
                        
                        <!-- TOMBOL PERUNTUKAN INTERAKTIF -->
                        <div class="peruntukan-dropdown-wrapper" data-group-wrapper="{{ $groupKeyId }}">
                            <button type="button" class="btn-peruntukan-toggle {{ $peruntukanVal }}" data-group-btn="{{ $groupKeyId }}" onclick="togglePeruntukanDropdown(event, this)" title="Klik untuk ubah peruntukan semua berkas sekaligus (kecuali Bendahara)">
                                <span class="peruntukan-current-label">
                                    @if($peruntukanVal === 'umum')
                                        <i class="bi bi-building"></i> Khusus Umum
                                    @elseif($peruntukanVal === 'teknis')
                                        <i class="bi bi-laptop"></i> Khusus Teknis
                                    @else
                                        <i class="bi bi-people-fill"></i> Dua-duanya
                                    @endif
                                </span>
                                <i class="bi bi-chevron-down" style="font-size: 10px; margin-left: 3px;"></i>
                            </button>
                            <div class="peruntukan-menu-dropdown">
                                <div class="dropdown-header-title">Ubah Peruntukan Belanja</div>
                                <div class="dropdown-header-desc">Semua berkas otomatis mengikuti, kecuali berkas yang disiapkan Bendahara.</div>
                                <button type="button" class="dropdown-item-peruntukan {{ $peruntukanVal === 'semua' ? 'active' : '' }}" onclick="changeGroupPeruntukan('{{ addslashes($namaBelanja) }}', '{{ addslashes($jenisBelanja) }}', 'semua', this, '{{ $groupKeyId }}')">
                                    <i class="bi bi-people-fill" style="color: #059669; font-size: 16px;"></i>
                                    <div>
                                        <strong>Dua-duanya (Teknis &amp; Umum)</strong>
                                        <small>Berlaku untuk Tim Teknis dan Subbag Umum</small>
                                    </div>
                                </button>
                                <button type="button" class="dropdown-item-peruntukan {{ $peruntukanVal === 'teknis' ? 'active' : '' }}" onclick="changeGroupPeruntukan('{{ addslashes($namaBelanja) }}', '{{ addslashes($jenisBelanja) }}', 'teknis', this, '{{ $groupKeyId }}')">
                                    <i class="bi bi-laptop" style="color: #2563eb; font-size: 16px;"></i>
                                    <div>
                                        <strong>Khusus Staf Teknis</strong>
                                        <small>Hanya tampil pada formulir Tim Teknis</small>
                                    </div>
                                </button>
                                <button type="button" class="dropdown-item-peruntukan {{ $peruntukanVal === 'umum' ? 'active' : '' }}" onclick="changeGroupPeruntukan('{{ addslashes($namaBelanja) }}', '{{ addslashes($jenisBelanja) }}', 'umum', this, '{{ $groupKeyId }}')">
                                    <i class="bi bi-building" style="color: #7c3aed; font-size: 16px;"></i>
                                    <div>
                                        <strong>Khusus Subbag Umum</strong>
                                        <small>Hanya tampil pada formulir Subbag Umum</small>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <span class="badge-doc-count"><i class="bi bi-file-earmark-text"></i> {{ $docs->count() }} Dokumen</span>
                    </div>
                    <div>
                        <button type="button" class="btn-quick-add" onclick="openAddDocForGroup('{{ addslashes($namaBelanja) }}', '{{ addslashes($jenisBelanja) }}', '{{ $peruntukanVal }}')">
                            <i class="bi bi-plus-lg"></i> Tambah Dokumen (+)
                        </button>
                    </div>
                </div>

                <div class="belanja-group-body">
                    <table class="doc-table-inner">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">No</th>
                                <th>Nama Dokumen Persyaratan</th>
                                <th style="width: 240px; text-align: center;">Pihak Penyiap (Assignee)</th>
                                <th style="width: 80px; text-align: center;">Urutan</th>
                                <th style="width: 100px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($docs as $dIndex => $doc)
                            @php
                                $docPeruntukan = $doc->peruntukan ?: $peruntukanVal;
                            @endphp
                            <tr class="doc-row-{{ $groupKeyId }}" data-doc-id="{{ $doc->id }}" data-is-bendahara="{{ $doc->assignee === 'Bendahara' ? '1' : '0' }}">
                                <td style="text-align: center; color: var(--dash-slate-500); font-weight: 600;">
                                    {{ $dIndex + 1 }}
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">
                                        {{ $doc->nama_dokumen }}
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    @if($doc->assignee === 'Bendahara')
                                        <span class="badge-assignee bendahara" title="Diverifikasi dan dilengkapi oleh Bendahara Pengeluaran">
                                            <i class="bi bi-cash-coin"></i> Dilengkapi Bendahara
                                        </span>
                                    @elseif($doc->assignee === 'PPK')
                                        <span class="badge-assignee ppk" title="Diverifikasi dan dilengkapi oleh PPK">
                                            <i class="bi bi-shield-check"></i> Dilengkapi PPK
                                        </span>
                                    @else
                                        @if($docPeruntukan === 'umum')
                                            <span class="badge-assignee umum badge-assignee-dynamic" data-doc-id="{{ $doc->id }}" title="Wajib disediakan oleh Subbagian Umum">
                                                <i class="bi bi-building"></i> Disiapkan Subbag Umum
                                            </span>
                                        @elseif($docPeruntukan === 'teknis')
                                            <span class="badge-assignee teknis badge-assignee-dynamic" data-doc-id="{{ $doc->id }}" title="Wajib disediakan oleh Staf Teknis (SM)">
                                                <i class="bi bi-laptop"></i> Disiapkan Staf Teknis (SM)
                                            </span>
                                        @else
                                            <span class="badge-assignee sm badge-assignee-dynamic" data-doc-id="{{ $doc->id }}" title="Wajib disediakan oleh pengaju kegiatan (Teknis / Umum)">
                                                <i class="bi bi-person-check-fill"></i> Disiapkan Teknis / Umum (SM)
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td style="text-align: center; font-weight: 700; color: var(--dash-slate-600); font-size: 12px;">
                                    {{ $doc->urutan }}
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <button type="button" class="btn-action-icon edit" title="Edit Dokumen" onclick="openEditDocModalById({{ $doc->id }})">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <form action="{{ route('admin.syarat-dokumen.destroy', $doc->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen persyaratan: \'{{ addslashes($doc->nama_dokumen) }}\'?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-icon delete" title="Hapus Dokumen">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div style="background: #ffffff; border-radius: var(--dash-radius-xl); border: 1px solid var(--dash-slate-200); padding: 48px 16px; text-align: center; color: var(--dash-slate-500);">
                <i class="bi bi-inbox" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.4;"></i>
                <h4 style="margin: 0 0 6px 0; color: var(--bps-navy); font-size: 16px;">Tidak ada dokumen persyaratan ditemukan</h4>
                <p style="margin: 0; font-size: 13px;">Silakan sesuaikan kata kunci filter atau klik tombol "Tambah Dokumen / Jenis Belanja Baru" di atas.</p>
            </div>
        @endforelse
    </div>

    <!-- 4B. TAMPILAN TABEL EXCEL RINGKAS (PERSIS BENTUK SEPERTI GAMBAR DARI USER) -->
    <div id="containerViewTable" class="excel-table-container" style="display: none;">
        <table class="excel-doc-table">
            <thead>
                <tr>
                    <th class="th-nama-belanja" style="width: 22%;">Nama Belanja</th>
                    <th class="th-jenis-belanja" style="width: 20%;">Jenis Belanja</th>
                    <th class="th-dokumen">Dokumen Wajib</th>
                    <th class="th-penanggungjawab" style="width: 14%;">Penanggungjawab</th>
                    <th class="th-peruntukan" style="width: 17%;">Peruntukan</th>
                    <th class="th-aksi" style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groupedByNamaBelanja as $namaBelanja => $jenisBelanjaGroup)
                    @php
                        $totalRowsNama = $jenisBelanjaGroup->sum(fn($docs) => $docs->count());
                        $isFirstNamaRow = true;

                        $akunKode = '52';
                        if (str_contains($namaBelanja, '51')) $akunKode = '51';
                        elseif (str_contains($namaBelanja, '53')) $akunKode = '53';
                    @endphp

                    @foreach($jenisBelanjaGroup as $jenisBelanja => $docs)
                        @php
                            $totalRowsJenis = $docs->count();
                            $isFirstJenisRow = true;
                            $firstDoc = $docs->first();
                            $peruntukanVal = $firstDoc->peruntukan ?: \App\Models\SyaratDokumenSpj::determinePeruntukanByKode($namaBelanja);
                            $groupKeyId = md5($namaBelanja . '|||' . $jenisBelanja);
                        @endphp

                        @foreach($docs as $doc)
                            @php
                                $docPeruntukan = $doc->peruntukan ?: $peruntukanVal;
                            @endphp
                            <tr class="doc-row-table-{{ $groupKeyId }}" data-doc-id="{{ $doc->id }}" data-is-bendahara="{{ $doc->assignee === 'Bendahara' ? '1' : '0' }}">
                                @if($isFirstNamaRow)
                                    <td rowspan="{{ $totalRowsNama }}" class="excel-cell-nama-belanja">
                                        <div style="display: flex; flex-direction: column; gap: 6px;">
                                            <span class="badge-akun-kode akun-{{ $akunKode }}" style="align-self: flex-start;">
                                                {{ $namaBelanja }}
                                            </span>
                                            <span style="font-size: 11px; color: var(--dash-slate-500);">
                                                {{ $jenisBelanjaGroup->count() }} Jenis Belanja ({{ $totalRowsNama }} Dokumen)
                                            </span>
                                        </div>
                                    </td>
                                    @php $isFirstNamaRow = false; @endphp
                                @endif

                                @if($isFirstJenisRow)
                                    <td rowspan="{{ $totalRowsJenis }}" class="excel-cell-jenis-belanja">
                                        <div style="display: flex; flex-direction: column; gap: 8px;">
                                            <strong style="color: #0f172a; font-size: 13.5px;">{{ $jenisBelanja }}</strong>
                                            <button type="button" class="btn-quick-add" style="align-self: flex-start; padding: 4px 10px; font-size: 11px;" onclick="openAddDocForGroup('{{ addslashes($namaBelanja) }}', '{{ addslashes($jenisBelanja) }}', '{{ $peruntukanVal }}')">
                                                <i class="bi bi-plus-lg"></i> + Dokumen
                                            </button>
                                        </div>
                                    </td>
                                @endif

                                <td class="excel-cell-dokumen">
                                    <span style="font-weight: 600; color: #1e293b;">{{ $doc->nama_dokumen }}</span>
                                    <span style="font-size: 10.5px; color: var(--dash-slate-500); margin-left: 6px;">(Urutan #{{ $doc->urutan }})</span>
                                </td>

                                <td class="excel-cell-assignee">
                                    @if($doc->assignee === 'Bendahara')
                                        <span class="badge-assignee bendahara" style="font-size: 11px;">
                                            <i class="bi bi-cash-coin"></i> Bendahara
                                        </span>
                                    @elseif($doc->assignee === 'PPK')
                                        <span class="badge-assignee ppk" style="font-size: 11px;">
                                            <i class="bi bi-shield-check"></i> PPK
                                        </span>
                                    @else
                                        @if($docPeruntukan === 'umum')
                                            <span class="badge-assignee umum badge-assignee-dynamic-table" data-doc-id="{{ $doc->id }}" style="font-size: 11px;">
                                                <i class="bi bi-building"></i> Subbag Umum
                                            </span>
                                        @elseif($docPeruntukan === 'teknis')
                                            <span class="badge-assignee teknis badge-assignee-dynamic-table" data-doc-id="{{ $doc->id }}" style="font-size: 11px;">
                                                <i class="bi bi-laptop"></i> Staf Teknis
                                            </span>
                                        @else
                                            <span class="badge-assignee sm badge-assignee-dynamic-table" data-doc-id="{{ $doc->id }}" style="font-size: 11px;">
                                                <i class="bi bi-person-check-fill"></i> Teknis / Umum
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                @if($isFirstJenisRow)
                                    <td rowspan="{{ $totalRowsJenis }}" class="excel-cell-peruntukan">
                                        <!-- Tombol Peruntukan Dropdown (Sama seperti di Card View) -->
                                        <div class="peruntukan-dropdown-wrapper" data-group-wrapper="{{ $groupKeyId }}">
                                            <button type="button" class="btn-peruntukan-toggle {{ $peruntukanVal }}" data-group-btn="{{ $groupKeyId }}" onclick="togglePeruntukanDropdown(event, this)" title="Klik untuk ubah peruntukan semua berkas (kecuali Bendahara)">
                                                <span class="peruntukan-current-label">
                                                    @if($peruntukanVal === 'umum')
                                                        <i class="bi bi-building"></i> Khusus Umum
                                                    @elseif($peruntukanVal === 'teknis')
                                                        <i class="bi bi-laptop"></i> Khusus Teknis
                                                    @else
                                                        <i class="bi bi-people-fill"></i> Dua-duanya
                                                    @endif
                                                </span>
                                                <i class="bi bi-chevron-down" style="font-size: 10px; margin-left: 3px;"></i>
                                            </button>
                                            <div class="peruntukan-menu-dropdown">
                                                <div class="dropdown-header-title">Ubah Peruntukan Belanja</div>
                                                <div class="dropdown-header-desc">Semua berkas otomatis mengikuti, kecuali berkas Bendahara.</div>
                                                <button type="button" class="dropdown-item-peruntukan {{ $peruntukanVal === 'semua' ? 'active' : '' }}" onclick="changeGroupPeruntukan('{{ addslashes($namaBelanja) }}', '{{ addslashes($jenisBelanja) }}', 'semua', this, '{{ $groupKeyId }}')">
                                                    <i class="bi bi-people-fill" style="color: #059669; font-size: 16px;"></i>
                                                    <div>
                                                        <strong>Dua-duanya (Teknis &amp; Umum)</strong>
                                                        <small>Berlaku untuk Tim Teknis dan Subbag Umum</small>
                                                    </div>
                                                </button>
                                                <button type="button" class="dropdown-item-peruntukan {{ $peruntukanVal === 'teknis' ? 'active' : '' }}" onclick="changeGroupPeruntukan('{{ addslashes($namaBelanja) }}', '{{ addslashes($jenisBelanja) }}', 'teknis', this, '{{ $groupKeyId }}')">
                                                    <i class="bi bi-laptop" style="color: #2563eb; font-size: 16px;"></i>
                                                    <div>
                                                        <strong>Khusus Staf Teknis</strong>
                                                        <small>Hanya tampil pada formulir Tim Teknis</small>
                                                    </div>
                                                </button>
                                                <button type="button" class="dropdown-item-peruntukan {{ $peruntukanVal === 'umum' ? 'active' : '' }}" onclick="changeGroupPeruntukan('{{ addslashes($namaBelanja) }}', '{{ addslashes($jenisBelanja) }}', 'umum', this, '{{ $groupKeyId }}')">
                                                    <i class="bi bi-building" style="color: #7c3aed; font-size: 16px;"></i>
                                                    <div>
                                                        <strong>Khusus Subbag Umum</strong>
                                                        <small>Hanya tampil pada formulir Subbag Umum</small>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    @php $isFirstJenisRow = false; @endphp
                                @endif

                                <td class="excel-cell-aksi">
                                    <button type="button" class="btn-action-icon edit" title="Edit Dokumen" onclick="openEditDocModalById({{ $doc->id }})">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <form action="{{ route('admin.syarat-dokumen.destroy', $doc->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hapus dokumen \'{{ addslashes($doc->nama_dokumen) }}\'?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-icon delete" title="Hapus Dokumen">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 36px; color: var(--dash-slate-500);">
                            <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                            Tidak ada dokumen persyaratan ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<!-- 5. MODAL TAMBAH DOKUMEN / JENIS BELANJA (MENDUKUNG INPUT BANYAK DENGAN TOMBOL +) -->
<div class="modal-backdrop-custom" id="modalAddDoc">
    <div class="modal-box-custom">
        <form action="{{ route('admin.syarat-dokumen.store') }}" method="POST">
            @csrf
            <div class="modal-header-custom">
                <h3><i class="bi bi-plus-circle-fill"></i> Tambah Dokumen Persyaratan Baru</h3>
                <button type="button" class="btn-close-modal" onclick="closeAddDocModal()">&times;</button>
            </div>
            <div class="modal-body-custom">
                <!-- Nama Belanja -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Nama Belanja (Kategori Akun) <span style="color: #ef4444;">*</span></label>
                    <select id="add_select_nama_belanja" class="form-control-doc" onchange="handleAddNamaBelanjaChange(this.value)">
                        <option value="">-- Pilih dari Kategori yang Ada --</option>
                        @foreach($daftarNamaBelanja as $nb)
                            <option value="{{ $nb }}">{{ $nb }}</option>
                        @endforeach
                        <option value="__NEW__">+ Tambah Kategori Belanja Baru...</option>
                    </select>
                    <input type="text" name="nama_belanja" id="add_input_nama_belanja" class="form-control-doc" style="margin-top: 8px;" placeholder="Ketik Nama Belanja (contoh: Belanja barang persediaan konsumsi (521811))" required>
                    <div class="form-help-modal">Pilih kategori yang sudah ada atau ketik nama belanja baru beserta kode akunnya.</div>
                </div>

                <!-- Jenis Belanja -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Jenis Belanja <span style="color: #ef4444;">*</span></label>
                    <select id="add_select_jenis_belanja" class="form-control-doc" onchange="handleAddJenisBelanjaChange(this.value)">
                        <option value="">-- Pilih dari Jenis Belanja yang Ada --</option>
                        @foreach($daftarJenisBelanja as $jb)
                            <option value="{{ $jb }}">{{ $jb }}</option>
                        @endforeach
                        <option value="__NEW__">+ Tambah Jenis Belanja Baru...</option>
                    </select>
                    <input type="text" name="jenis_belanja" id="add_input_jenis_belanja" class="form-control-doc" style="margin-top: 8px;" placeholder="Ketik Jenis Belanja (contoh: Belanja ATK CS / percetakan dokumen)" required>
                    <div class="form-help-modal">Pilih jenis belanja yang sudah terdaftar atau masukkan jenis baru.</div>
                </div>

                <!-- Peruntukan / Berlaku Untuk Siapa -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Peruntukan / Berlaku Untuk Siapa <span style="color: #ef4444;">*</span></label>
                    <select name="peruntukan" id="add_peruntukan" class="form-control-doc" required>
                        <option value="semua">Dua-duanya (Teknis &amp; Subbag Umum)</option>
                        <option value="umum">Khusus Subbag Umum (Contoh: Akun 51 &amp; Akun 53)</option>
                        <option value="teknis">Khusus Staf Teknis</option>
                    </select>
                    <div class="form-help-modal">Dokumen pengaju otomatis menyesuaikan peruntukan ini. Berkas Bendahara tetap bertugas sebagai Bendahara.</div>
                </div>

                <!-- DAFTAR DOKUMEN MULTI-INPUT (BISA BANYAK DENGAN TANDA PLUS) -->
                <div class="form-group-modal" style="margin-top: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label class="form-label-modal" style="margin: 0;">
                            Daftar Dokumen yang Ditambahkan <span style="color: #ef4444;">*</span>
                        </label>
                        <span style="font-size: 11px; color: var(--dash-slate-500);">Bisa tambah banyak sekaligus via tombol (+)</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 180px 75px 36px; gap: 8px; padding: 0 10px 4px 10px; font-size: 11px; font-weight: 700; color: var(--dash-slate-500); text-transform: uppercase;">
                        <span>Nama Dokumen</span>
                        <span>Pihak Penyiap</span>
                        <span style="text-align: center;">Urutan</span>
                        <span></span>
                    </div>

                    <div class="doc-input-rows-container" id="doc_input_rows_container">
                        <!-- Baris Pertama -->
                        <div class="doc-input-row" data-row-index="0">
                            <input type="text" name="dokumen[0][nama_dokumen]" class="form-control-doc" placeholder="Contoh: KAK / Laporan / BAST" required>
                            <select name="dokumen[0][assignee]" class="form-control-doc" required>
                                <option value="SM" selected>SM (Teknis / Umum)</option>
                                <option value="Bendahara">Bendahara</option>
                                <option value="PPK">PPK</option>
                            </select>
                            <input type="number" name="dokumen[0][urutan]" class="form-control-doc" value="1" min="1" style="text-align: center;" placeholder="1">
                            <button type="button" class="btn-remove-row" onclick="removeDocRow(this)" disabled title="Baris pertama tidak dapat dihapus">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Plus Tambah Dokumen Lain -->
                    <button type="button" class="btn-add-more-row" onclick="addDocRow()">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Dokumen Lain (+)
                    </button>
                </div>
            </div>
            <div class="modal-footer-custom">
                <span style="font-size: 12px; color: var(--dash-slate-500);" id="doc_rows_counter_hint">1 dokumen siap disimpan</span>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn-cancel-modal" onclick="closeAddDocModal()">Batal</button>
                    <button type="submit" class="btn-save-modal">Simpan Semua Dokumen</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- 6. MODAL EDIT DOKUMEN -->
<div class="modal-backdrop-custom" id="modalEditDoc">
    <div class="modal-box-custom">
        <form id="formEditDoc" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header-custom">
                <h3><i class="bi bi-pencil-square"></i> Edit Dokumen Persyaratan</h3>
                <button type="button" class="btn-close-modal" onclick="closeEditDocModal()">&times;</button>
            </div>
            <div class="modal-body-custom">
                <!-- Nama Belanja -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Nama Belanja (Kategori Akun) <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="nama_belanja" id="edit_nama_belanja" class="form-control-doc" required>
                </div>

                <!-- Jenis Belanja -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Jenis Belanja <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="jenis_belanja" id="edit_jenis_belanja" class="form-control-doc" required>
                </div>

                <!-- Nama Dokumen -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Nama Dokumen Persyaratan <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="nama_dokumen" id="edit_nama_dokumen" class="form-control-doc" required>
                </div>

                <!-- Peruntukan -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Peruntukan <span style="color: #ef4444;">*</span></label>
                    <select name="peruntukan" id="edit_peruntukan" class="form-control-doc" required>
                        <option value="semua">Dua-duanya (Teknis &amp; Subbag Umum)</option>
                        <option value="umum">Khusus Subbag Umum</option>
                        <option value="teknis">Khusus Staf Teknis</option>
                    </select>
                </div>

                <!-- Pihak Penyiap (Assignee) -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Pihak yang Menyiapkan / Bertanggung Jawab <span style="color: #ef4444;">*</span></label>
                    <select name="assignee" id="edit_assignee" class="form-control-doc" required>
                        <option value="SM">SM (Wajib Disiapkan Teknis / Subbag Umum)</option>
                        <option value="Bendahara">Bendahara (Dilengkapi Bendahara - Pajak/Transfer)</option>
                        <option value="PPK">PPK (Verifikasi Komitmen Anggaran)</option>
                    </select>
                </div>

                <!-- Urutan -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Urutan Tampil</label>
                    <input type="number" name="urutan" id="edit_urutan" class="form-control-doc" min="0">
                </div>
            </div>
            <div class="modal-footer-custom">
                <div></div>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn-cancel-modal" onclick="closeEditDocModal()">Batal</button>
                    <button type="submit" class="btn-save-modal">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let nextRowIndex = 1;

    // Registry dokumen tersinkron di sisi browser
    const allDocsRegistry = {};
    @if(isset($allSyaratDokumens))
        @foreach($allSyaratDokumens as $itemDoc)
            allDocsRegistry[{{ $itemDoc->id }}] = {!! json_encode($itemDoc) !!};
        @endforeach
    @endif

    // 1. SWITCH TAMPILAN (KARTU vs TABEL EXCEL)
    function switchViewMode(mode) {
        const containerCards = document.getElementById('containerViewCards');
        const containerTable = document.getElementById('containerViewTable');
        const btnCards = document.getElementById('btnViewCards');
        const btnTable = document.getElementById('btnViewTable');

        if (mode === 'table') {
            if (containerCards) containerCards.style.display = 'none';
            if (containerTable) containerTable.style.display = 'block';
            if (btnCards) btnCards.classList.remove('active');
            if (btnTable) btnTable.classList.add('active');
            localStorage.setItem('spj_syarat_view_mode', 'table');
        } else {
            if (containerCards) containerCards.style.display = 'flex';
            if (containerTable) containerTable.style.display = 'none';
            if (btnCards) btnCards.classList.add('active');
            if (btnTable) btnTable.classList.remove('active');
            localStorage.setItem('spj_syarat_view_mode', 'cards');
        }
    }

    // 2. TOGGLE DROPDOWN PERUNTUKAN
    function togglePeruntukanDropdown(event, button) {
        event.stopPropagation();
        const wrapper = button.closest('.peruntukan-dropdown-wrapper');
        const dropdown = wrapper ? wrapper.querySelector('.peruntukan-menu-dropdown') : null;

        // Tutup dropdown lain yang sedang terbuka
        document.querySelectorAll('.peruntukan-menu-dropdown.show').forEach(el => {
            if (el !== dropdown) el.classList.remove('show');
        });

        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }

    // 3. UBAH PERUNTUKAN KELOMPOK (KECUALI BERKAS BENDAHARA)
    function changeGroupPeruntukan(namaBelanja, jenisBelanja, newPeruntukan, itemEl, groupKeyId) {
        const dropdown = itemEl.closest('.peruntukan-menu-dropdown');
        const wrapper = itemEl.closest('.peruntukan-dropdown-wrapper');
        const toggleBtn = wrapper ? wrapper.querySelector('.btn-peruntukan-toggle') : null;

        if (toggleBtn) {
            toggleBtn.style.opacity = '0.5';
            toggleBtn.style.pointerEvents = 'none';
        }

        fetch("{{ route('admin.syarat-dokumen.update-peruntukan-group') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nama_belanja: namaBelanja,
                jenis_belanja: jenisBelanja,
                peruntukan: newPeruntukan
            })
        })
        .then(res => res.json())
        .then(data => {
            if (toggleBtn) {
                toggleBtn.style.opacity = '1';
                toggleBtn.style.pointerEvents = 'auto';
            }
            if (dropdown) dropdown.classList.remove('show');

            if (data.success) {
                // Perbarui data di allDocsRegistry dari server
                if (data.docs && Array.isArray(data.docs)) {
                    data.docs.forEach(doc => {
                        allDocsRegistry[doc.id] = doc;
                    });
                } else {
                    for (const id in allDocsRegistry) {
                        const d = allDocsRegistry[id];
                        if (d.nama_belanja === namaBelanja && d.jenis_belanja === jenisBelanja) {
                            if (d.assignee !== 'Bendahara') {
                                d.peruntukan = newPeruntukan;
                            }
                        }
                    }
                }

                // Perbarui semua badge/tombol di Card View dan Table View yang memiliki groupKeyId sama
                updatePeruntukanUI(groupKeyId, newPeruntukan, data.label);
                showToastNotification(data.message, 'success');
            } else {
                showToastNotification(data.message || 'Gagal mengubah peruntukan.', 'error');
            }
        })
        .catch(err => {
            if (toggleBtn) {
                toggleBtn.style.opacity = '1';
                toggleBtn.style.pointerEvents = 'auto';
            }
            if (dropdown) dropdown.classList.remove('show');
            showToastNotification('Terjadi kendala jaringan saat memperbarui peruntukan.', 'error');
        });
    }

    function updatePeruntukanUI(groupKeyId, peruntukan, label) {
        const wrappers = document.querySelectorAll(`[data-group-wrapper="${groupKeyId}"]`);
        
        let iconHtml = '<i class="bi bi-people-fill"></i> Dua-duanya';
        if (peruntukan === 'umum') {
            iconHtml = '<i class="bi bi-building"></i> Khusus Umum';
        } else if (peruntukan === 'teknis') {
            iconHtml = '<i class="bi bi-laptop"></i> Khusus Teknis';
        }

        wrappers.forEach(wrap => {
            const btn = wrap.querySelector('.btn-peruntukan-toggle');
            if (btn) {
                btn.className = `btn-peruntukan-toggle ${peruntukan}`;
                const labelSpan = btn.querySelector('.peruntukan-current-label');
                if (labelSpan) {
                    labelSpan.innerHTML = iconHtml;
                }
            }

            // Update item active class
            wrap.querySelectorAll('.dropdown-item-peruntukan').forEach(item => {
                if (item.onclick && item.onclick.toString().includes(`'${peruntukan}'`)) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        });

        // Update badge penanggung jawab pada setiap baris di Card View
        document.querySelectorAll(`.doc-row-${groupKeyId}`).forEach(row => {
            const isBendahara = row.getAttribute('data-is-bendahara') === '1';
            const badge = row.querySelector('.badge-assignee-dynamic');
            if (badge && !isBendahara) {
                if (peruntukan === 'umum') {
                    badge.className = 'badge-assignee umum badge-assignee-dynamic';
                    badge.innerHTML = '<i class="bi bi-building"></i> Disiapkan Subbag Umum';
                    badge.title = 'Wajib disediakan oleh Subbagian Umum';
                } else if (peruntukan === 'teknis') {
                    badge.className = 'badge-assignee teknis badge-assignee-dynamic';
                    badge.innerHTML = '<i class="bi bi-laptop"></i> Disiapkan Staf Teknis (SM)';
                    badge.title = 'Wajib disediakan oleh Staf Teknis (SM)';
                } else {
                    badge.className = 'badge-assignee sm badge-assignee-dynamic';
                    badge.innerHTML = '<i class="bi bi-person-check-fill"></i> Disiapkan Teknis / Umum (SM)';
                    badge.title = 'Wajib disediakan oleh pengaju kegiatan (Teknis / Umum)';
                }
            }
        });

        // Update badge penanggung jawab pada setiap baris di Table View
        document.querySelectorAll(`.doc-row-table-${groupKeyId}`).forEach(row => {
            const isBendahara = row.getAttribute('data-is-bendahara') === '1';
            const badge = row.querySelector('.badge-assignee-dynamic-table');
            if (badge && !isBendahara) {
                if (peruntukan === 'umum') {
                    badge.className = 'badge-assignee umum badge-assignee-dynamic-table';
                    badge.innerHTML = '<i class="bi bi-building"></i> Subbag Umum';
                } else if (peruntukan === 'teknis') {
                    badge.className = 'badge-assignee teknis badge-assignee-dynamic-table';
                    badge.innerHTML = '<i class="bi bi-laptop"></i> Staf Teknis';
                } else {
                    badge.className = 'badge-assignee sm badge-assignee-dynamic-table';
                    badge.innerHTML = '<i class="bi bi-person-check-fill"></i> Teknis / Umum';
                }
            }
        });
    }

    // 4. OPEN EDIT MODAL (SINKRON 100% DENGAN REGISTRY REAL-TIME)
    function openEditDocModalById(id) {
        const doc = allDocsRegistry[id];
        if (!doc) {
            console.error('Dokumen dengan ID ' + id + ' tidak ditemukan di registry.');
            return;
        }

        const form = document.getElementById('formEditDoc');
        form.action = "{{ url('admin/syarat-dokumen') }}/" + doc.id;
        document.getElementById('edit_nama_belanja').value = doc.nama_belanja || '';
        document.getElementById('edit_jenis_belanja').value = doc.jenis_belanja || '';
        document.getElementById('edit_nama_dokumen').value = doc.nama_dokumen || '';
        
        // Pilihan Peruntukan selalu sinkron dengan state terbaru
        const editPeruntukanEl = document.getElementById('edit_peruntukan');
        if (editPeruntukanEl) {
            editPeruntukanEl.value = doc.peruntukan || 'semua';
        }

        const editAssigneeEl = document.getElementById('edit_assignee');
        if (editAssigneeEl) {
            editAssigneeEl.value = doc.assignee || 'SM';
        }

        document.getElementById('edit_urutan').value = doc.urutan || 1;
        document.getElementById('modalEditDoc').style.display = 'flex';
    }

    // 5. TOAST NOTIFIKASI REAL-TIME
    function showToastNotification(message, type = 'success') {
        let container = document.getElementById('toastNoticeContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastNoticeContainer';
            container.style.cssText = 'position: fixed; top: 24px; right: 24px; z-index: 999999; display: flex; flex-direction: column; gap: 10px; pointer-events: none;';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.style.cssText = `
            pointer-events: auto;
            min-width: 300px;
            max-width: 460px;
            padding: 14px 18px;
            border-radius: 12px;
            background: ${type === 'success' ? '#065f46' : '#991b1b'};
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0,0,0,0.22);
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.25s ease;
        `;
        const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
        toast.innerHTML = `
            <i class="bi ${icon}" style="font-size: 20px; color: #a7f3d0; flex-shrink: 0;"></i>
            <div style="flex: 1; line-height: 1.45;">${message}</div>
            <button type="button" style="background: none; border: none; color: #ffffff; cursor: pointer; opacity: 0.8; font-size: 16px; padding: 0 0 0 6px;" onclick="this.parentElement.remove()">&times;</button>
        `;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => toast.remove(), 250);
        }, 4000);
    }

    // Tutup dropdown saat klik di luar
    window.addEventListener('click', function(e) {
        if (!e.target.closest('.peruntukan-dropdown-wrapper')) {
            document.querySelectorAll('.peruntukan-menu-dropdown.show').forEach(el => {
                el.classList.remove('show');
            });
        }
        if (e.target.id === 'modalAddDoc') closeAddDocModal();
        if (e.target.id === 'modalEditDoc') closeEditDocModal();
    });

    function detectPeruntukanFromText(text) {
        if (!text) return null;
        if (/\(51\d*\)/i.test(text) || /\(53\d*\)/i.test(text) || /\b51\d{4}\b/.test(text) || /\b53\d{4}\b/.test(text)) {
            return 'umum';
        }
        if (/\(52\d*\)/i.test(text) || /\b52\d{4}\b/.test(text)) {
            return 'semua';
        }
        return null;
    }

    function updateRowCounters() {
        const rows = document.querySelectorAll('#doc_input_rows_container .doc-input-row');
        const count = rows.length;
        const hint = document.getElementById('doc_rows_counter_hint');
        if (hint) {
            hint.textContent = `${count} dokumen siap disimpan`;
        }

        rows.forEach((row) => {
            const removeBtn = row.querySelector('.btn-remove-row');
            if (removeBtn) {
                removeBtn.disabled = (count <= 1);
            }
        });
    }

    function addDocRow() {
        const container = document.getElementById('doc_input_rows_container');
        const rows = container.querySelectorAll('.doc-input-row');
        const newUrutan = rows.length + 1;

        const rowDiv = document.createElement('div');
        rowDiv.className = 'doc-input-row';
        rowDiv.setAttribute('data-row-index', nextRowIndex);
        rowDiv.innerHTML = `
            <input type="text" name="dokumen[${nextRowIndex}][nama_dokumen]" class="form-control-doc" placeholder="Nama dokumen persyaratan" required>
            <select name="dokumen[${nextRowIndex}][assignee]" class="form-control-doc" required>
                <option value="SM" selected>SM (Teknis / Umum)</option>
                <option value="Bendahara">Bendahara</option>
                <option value="PPK">PPK</option>
            </select>
            <input type="number" name="dokumen[${nextRowIndex}][urutan]" class="form-control-doc" value="${newUrutan}" min="1" style="text-align: center;">
            <button type="button" class="btn-remove-row" onclick="removeDocRow(this)" title="Hapus baris dokumen ini">
                <i class="bi bi-x-lg"></i>
            </button>
        `;
        container.appendChild(rowDiv);
        nextRowIndex++;
        updateRowCounters();

        const firstInput = rowDiv.querySelector('input[type="text"]');
        if (firstInput) firstInput.focus();
    }

    function removeDocRow(button) {
        const row = button.closest('.doc-input-row');
        const container = document.getElementById('doc_input_rows_container');
        if (container.querySelectorAll('.doc-input-row').length > 1) {
            row.remove();
            updateRowCounters();
        }
    }

    function openAddDocModal() {
        document.getElementById('modalAddDoc').style.display = 'flex';
    }

    function closeAddDocModal() {
        document.getElementById('modalAddDoc').style.display = 'none';
    }

    function openAddDocForGroup(namaBelanja, jenisBelanja, peruntukan) {
        const selNama = document.getElementById('add_select_nama_belanja');
        const inpNama = document.getElementById('add_input_nama_belanja');
        const selJenis = document.getElementById('add_select_jenis_belanja');
        const inpJenis = document.getElementById('add_input_jenis_belanja');
        const selPeruntukan = document.getElementById('add_peruntukan');

        if (selNama) selNama.value = namaBelanja || '';
        if (inpNama) inpNama.value = namaBelanja || '';
        if (selJenis) selJenis.value = jenisBelanja || '';
        if (inpJenis) inpJenis.value = jenisBelanja || '';
        if (selPeruntukan && peruntukan) {
            selPeruntukan.value = peruntukan;
            selPeruntukan.dispatchEvent(new Event('change'));
        }

        openAddDocModal();
    }

    function handleAddNamaBelanjaChange(val) {
        const inp = document.getElementById('add_input_nama_belanja');
        if (val === '__NEW__') {
            inp.value = '';
            inp.focus();
        } else if (val) {
            inp.value = val;
            const detected = detectPeruntukanFromText(val);
            if (detected) {
                document.getElementById('add_peruntukan').value = detected;
            }
        }
    }

    function handleAddJenisBelanjaChange(val) {
        const inp = document.getElementById('add_input_jenis_belanja');
        if (val === '__NEW__') {
            inp.value = '';
            inp.focus();
        } else if (val) {
            inp.value = val;
        }
    }

    function closeEditDocModal() {
        document.getElementById('modalEditDoc').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Pulihkan preferensi tampilan (Cards vs Excel Table)
        const savedView = localStorage.getItem('spj_syarat_view_mode') || 'cards';
        switchViewMode(savedView);

        const addInput = document.getElementById('add_input_nama_belanja');
        if (addInput) {
            addInput.addEventListener('input', function() {
                const detected = detectPeruntukanFromText(this.value);
                if (detected) {
                    document.getElementById('add_peruntukan').value = detected;
                }
            });
        }

        const editInput = document.getElementById('edit_nama_belanja');
        if (editInput) {
            editInput.addEventListener('input', function() {
                const detected = detectPeruntukanFromText(this.value);
                if (detected) {
                    document.getElementById('edit_peruntukan').value = detected;
                }
            });
        }

        updateRowCounters();
    });
</script>
@endpush

@endsection