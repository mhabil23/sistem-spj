@extends('layouts.admin')

@php
    $title = 'Dashboard Administrator';
@endphp

@push('styles')
<style>
    /* =========================================================
       BADAN PUSAT STATISTIK (BPS) DESIGN SYSTEM - TIGHT & BALANCED
    ========================================================= */
    :root {
        --bps-navy: #082052;
        --bps-blue: #024b86;
        --bps-blue-dark: #013763;
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
        
        --dash-shadow-sm: 0 1px 3px rgba(8, 32, 82, 0.05);
        --dash-shadow-md: 0 4px 6px -1px rgba(8, 32, 82, 0.07);
        --dash-shadow-lg: 0 10px 15px -3px rgba(8, 32, 82, 0.09);
    }

    .modern-dashboard {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        color: var(--dash-slate-800);
    }

    /* 1. HERO BANNER */
    .hero-banner {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--bps-navy) 0%, var(--bps-blue) 65%, #0060a8 100%);
        border-radius: var(--dash-radius-xl);
        padding: 24px 30px;
        color: #ffffff;
        box-shadow: 0 12px 24px -6px rgba(2, 75, 134, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .hero-banner::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -30px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(243, 112, 33, 0.3) 0%, transparent 70%);
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .hero-meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #ffedd5;
        margin-bottom: 8px;
    }

    .hero-meta-badge i { color: var(--bps-orange); }

    .hero-title {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0 0 4px 0;
        color: #ffffff;
    }

    .hero-desc {
        font-size: 13.5px;
        color: #e2e8f0;
        margin: 0;
        max-width: 620px;
        line-height: 1.5;
    }

    .hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-hero-primary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        background: linear-gradient(135deg, var(--bps-orange) 0%, var(--bps-orange-dark) 100%);
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 700;
        border-radius: var(--dash-radius-md);
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(243, 112, 33, 0.4);
        transition: all 0.2s ease;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .btn-hero-primary:hover {
        background: linear-gradient(135deg, var(--bps-orange-dark) 0%, #c2410c 100%);
        transform: translateY(-2px);
        color: #ffffff;
    }

    .btn-hero-glass {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 600;
        border-radius: var(--dash-radius-md);
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.2s ease;
    }

    .btn-hero-glass:hover {
        background: rgba(255, 255, 255, 0.22);
        transform: translateY(-2px);
        color: #ffffff;
    }

    /* 2. STATS CARDS */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .kpi-card {
        background: #ffffff;
        border-radius: var(--dash-radius-lg);
        padding: 18px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: var(--dash-shadow-sm);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        cursor: pointer;
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--dash-shadow-md);
        border-color: #cbd5e1;
        color: inherit;
    }

    .kpi-card.bps-blue::before { background: linear-gradient(90deg, var(--bps-navy), var(--bps-blue)); }
    .kpi-card.bps-blue .kpi-icon-wrap { background: var(--bps-blue-light); color: var(--bps-blue); }
    .kpi-card.bps-blue .kpi-badge { background: #eff6ff; color: var(--bps-blue); border: 1px solid #bfdbfe; }

    .kpi-card.bps-orange::before { background: linear-gradient(90deg, var(--bps-orange), #fb923c); }
    .kpi-card.bps-orange .kpi-icon-wrap { background: var(--bps-orange-light); color: var(--bps-orange); }
    .kpi-card.bps-orange .kpi-badge { background: var(--bps-orange-light); color: var(--bps-orange-dark); border: 1px solid #fed7aa; }

    .kpi-card.bps-green::before { background: linear-gradient(90deg, var(--bps-green-dark), #34d399); }
    .kpi-card.bps-green .kpi-icon-wrap { background: var(--bps-green-light); color: var(--bps-green); }
    .kpi-card.bps-green .kpi-badge { background: var(--bps-green-light); color: var(--bps-green-dark); border: 1px solid #a7f3d0; }

    .kpi-card.bps-red::before { background: linear-gradient(90deg, var(--bps-danger), #f87171); }
    .kpi-card.bps-red .kpi-icon-wrap { background: var(--bps-danger-light); color: var(--bps-danger); }
    .kpi-card.bps-red .kpi-badge { background: var(--bps-danger-light); color: var(--bps-danger); border: 1px solid #fecaca; }

    .kpi-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .kpi-label {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--dash-slate-600);
    }

    .kpi-icon-wrap {
        width: 40px;
        height: 40px;
        border-radius: var(--dash-radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }

    .kpi-body {
        margin-bottom: 10px;
    }

    .kpi-value {
        font-size: 30px;
        font-weight: 800;
        color: var(--bps-navy);
        line-height: 1;
        margin: 0;
    }

    .kpi-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
        font-size: 11.5px;
        color: var(--dash-slate-500);
    }

    .kpi-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 10.5px;
    }

    /* 3. MONITORING ALUR 4 TAHAPAN */
    .pipeline-section {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        padding: 20px 24px;
        border: 1px solid #e2e8f0;
        box-shadow: var(--dash-shadow-sm);
    }

    .pipeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pipeline-header h3 {
        font-size: 16px;
        font-weight: 800;
        color: var(--bps-navy);
        margin: 0 0 2px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pipeline-header p {
        font-size: 12px;
        color: var(--dash-slate-500);
        margin: 0;
    }

    .pipeline-link {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--bps-blue);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
    }

    .pipeline-link:hover {
        color: var(--bps-orange);
        gap: 7px;
    }

    .stepper-pipeline {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    .step-box {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: var(--dash-radius-lg);
        padding: 14px 16px;
        text-align: center;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 125px;
        text-decoration: none;
        color: inherit;
        cursor: pointer;
    }

    .step-box:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: var(--dash-shadow-md);
        color: inherit;
    }

    .step-box.step-teknis.has-active { background: var(--bps-blue-subtle); border-color: #93c5fd; }
    .step-box.step-teknis.has-active .step-number { background: var(--bps-blue); color: #ffffff; }
    .step-box.step-teknis.has-active .step-counter { color: var(--bps-blue); }

    .step-box.step-umum.has-active { background: var(--bps-orange-light); border-color: #fed7aa; }
    .step-box.step-umum.has-active .step-number { background: var(--bps-orange); color: #ffffff; }
    .step-box.step-umum.has-active .step-counter { color: var(--bps-orange-dark); }

    .step-box.step-ppk.has-active { background: #f1f5f9; border-color: #cbd5e1; }
    .step-box.step-ppk.has-active .step-number { background: var(--bps-navy); color: #ffffff; }
    .step-box.step-ppk.has-active .step-counter { color: var(--bps-navy); }

    .step-box.step-ppspm.completed-step { background: var(--bps-green-light); border-color: #86efac; }
    .step-box.step-ppspm.completed-step .step-number { background: var(--bps-green-dark); color: #ffffff; }
    .step-box.step-ppspm.completed-step .step-counter { color: var(--bps-green-dark); }

    .step-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .step-number {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #e2e8f0;
        color: var(--dash-slate-700);
        font-size: 11px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .step-role-tag {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--dash-slate-500);
    }

    .step-counter {
        font-size: 26px;
        font-weight: 800;
        color: var(--bps-navy);
        line-height: 1;
        margin-bottom: 4px;
    }

    .step-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--dash-slate-800);
        margin-bottom: 2px;
    }

    .step-subtitle {
        font-size: 11px;
        color: var(--dash-slate-500);
    }

    .distribution-bar-wrap {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }

    .dist-labels {
        display: flex;
        justify-content: space-between;
        font-size: 11.5px;
        color: var(--dash-slate-600);
        margin-bottom: 6px;
        font-weight: 600;
    }

    .dist-progress-bar {
        height: 7px;
        border-radius: 999px;
        background: #f1f5f9;
        display: flex;
        overflow: hidden;
        gap: 2px;
    }

    .dist-segment {
        height: 100%;
        transition: width 0.6s ease;
    }

    .dist-segment.selesai { background: var(--bps-green); }
    .dist-segment.proses { background: var(--bps-blue); }
    .dist-segment.revisi { background: var(--bps-orange); }

    /* =========================================================
       4. BALANCED GAP-FREE ROWS (ROW 1 & ROW 2)
    ========================================================= */
    .dashboard-row-primary {
        display: grid;
        grid-template-columns: 1.85fr 1.15fr;
        gap: 20px;
        align-items: stretch;
    }

    .dashboard-row-secondary {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        align-items: stretch;
    }

    /* Common Card Styles */
    .dash-card {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        border: 1px solid #e2e8f0;
        box-shadow: var(--dash-shadow-sm);
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    .dash-card-header {
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
        flex-wrap: wrap;
        gap: 8px;
    }

    .dash-card-header h3 {
        font-size: 15px;
        font-weight: 800;
        color: var(--bps-navy);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .dash-card-header p {
        font-size: 11.5px;
        color: var(--dash-slate-500);
        margin: 2px 0 0 0;
    }

    .dash-card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Table Styles */
    .modern-table-wrap {
        overflow-x: auto;
        flex: 1;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        white-space: nowrap;
    }

    .modern-table th {
        padding: 11px 16px;
        background: #f8fafc;
        color: var(--dash-slate-600);
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
    }

    .modern-table td {
        padding: 13px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 12.5px;
        color: var(--dash-slate-700);
        vertical-align: middle;
    }

    .modern-table tbody tr:hover {
        background-color: var(--bps-blue-subtle);
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .spj-kegiatan-title {
        font-weight: 700;
        color: var(--bps-navy);
        display: block;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .spj-user-sub {
        font-size: 11px;
        color: var(--dash-slate-500);
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
    }

    .nilai-text {
        font-weight: 800;
        color: var(--bps-navy);
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 9999px;
        font-size: 10.5px;
        font-weight: 700;
    }

    .badge-status.diajukan { background: var(--bps-blue-light); color: var(--bps-blue); border: 1px solid #bfdbfe; }
    .badge-status.umum { background: var(--bps-orange-light); color: var(--bps-orange-dark); border: 1px solid #fed7aa; }
    .badge-status.ppk { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
    .badge-status.selesai { background: var(--bps-green-light); color: var(--bps-green-dark); border: 1px solid #a7f3d0; }
    .badge-status.revisi { background: var(--bps-danger-light); color: var(--bps-danger); border: 1px solid #fecaca; }
    .badge-status.draft { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

    .btn-table-action {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        background: #f1f5f9;
        color: var(--bps-navy);
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-table-action:hover {
        background: var(--bps-blue);
        color: #ffffff;
    }

    /* Activity Feed Styles */
    .activity-feed {
        padding: 14px 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        gap: 12px;
    }

    .activity-item {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        position: relative;
    }

    .activity-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 28px;
        left: 13px;
        width: 2px;
        height: calc(100% - 14px);
        background: #f1f5f9;
    }

    .activity-bubble {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
        z-index: 2;
    }

    .activity-bubble.green { background: var(--bps-green-light); color: var(--bps-green); }
    .activity-bubble.blue { background: var(--bps-blue-light); color: var(--bps-blue); }
    .activity-bubble.orange { background: var(--bps-orange-light); color: var(--bps-orange); }
    .activity-bubble.red { background: var(--bps-danger-light); color: var(--bps-danger); }

    .activity-detail {
        flex: 1;
        min-width: 0;
    }

    .activity-headline {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
    }

    .activity-time {
        font-size: 11px;
        color: var(--dash-slate-400);
        white-space: nowrap;
        font-weight: 400;
    }

    .activity-desc {
        font-size: 11.5px;
        color: var(--dash-slate-500);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 1px;
    }

    /* Shortcuts Grid */
    .shortcuts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        padding: 16px 20px;
        flex: 1;
    }

    .shortcut-btn {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: var(--dash-radius-md);
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .shortcut-btn:hover {
        background: #ffffff;
        border-color: var(--bps-blue);
        transform: translateY(-2px);
        box-shadow: var(--dash-shadow-md);
    }

    .shortcut-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .shortcut-btn:nth-child(1) .shortcut-icon { background: var(--bps-blue-light); color: var(--bps-blue); }
    .shortcut-btn:nth-child(2) .shortcut-icon { background: var(--bps-green-light); color: var(--bps-green); }
    .shortcut-btn:nth-child(3) .shortcut-icon { background: var(--bps-orange-light); color: var(--bps-orange); }
    .shortcut-btn:nth-child(4) .shortcut-icon { background: #eff6ff; color: var(--bps-navy); }
    .shortcut-btn:nth-child(5) .shortcut-icon { background: #ffe4e6; color: #e11d48; }

    .shortcut-label {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--bps-navy);
        line-height: 1.3;
    }

    /* User Analytics Card */
    .user-stats-wrap {
        padding: 16px 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
    }

    .user-kpi-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: var(--dash-radius-md);
        padding: 12px 16px;
        margin-bottom: 12px;
    }

    .user-kpi-item strong {
        font-size: 20px;
        font-weight: 800;
        color: var(--bps-navy);
        display: block;
    }

    .user-kpi-item span {
        font-size: 11px;
        color: var(--dash-slate-500);
        font-weight: 600;
    }

    .role-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .role-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        background: #f1f5f9;
        border-radius: 999px;
        font-size: 11px;
        color: var(--bps-navy);
        font-weight: 700;
    }

    .role-chip span {
        background: #ffffff;
        color: var(--bps-orange-dark);
        font-size: 10px;
        padding: 1px 6px;
        border-radius: 999px;
        font-weight: 800;
        border: 1px solid #fed7aa;
    }

    /* RESPONSIVE */
    @media (max-width: 1200px) {
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .dashboard-row-primary {
            grid-template-columns: 1fr;
        }
        .dashboard-row-secondary {
            grid-template-columns: 1fr;
        }
        .stepper-pipeline {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
        .stepper-pipeline {
            grid-template-columns: 1fr;
        }
        .shortcuts-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="modern-dashboard">

    <!-- 1. HERO WELCOME BANNER (WARNA LOGO BPS) -->
    <section class="hero-banner">
        <div class="hero-content">
            <div>
                <div class="hero-meta-badge">
                    <i class="bi bi-patch-check-fill"></i>
                    BPS Kolaka Utara &bull; Tahun Anggaran {{ date('Y') }}
                </div>
                <h1 class="hero-title">
                    Selamat Datang Kembali, {{ Auth::user()->name ?? 'Administrator' }} 👋
                </h1>
                <p class="hero-desc">
                    Monitoring operasional SPJ secara terpusat. Pantau kelancaran 4 tahapan verifikasi berkas, kinerja persetujuan, dan realisasi anggaran berbasis standar Badan Pusat Statistik.
                </p>
            </div>
            <div class="hero-actions">
                <a href="{{ route('admin.spj.create') }}" class="btn-hero-primary">
                    <i class="bi bi-plus-circle-fill"></i> Buat SPJ Baru
                </a>
                <a href="{{ route('admin.laporan.spj') }}" class="btn-hero-glass">
                    <i class="bi bi-file-earmark-bar-graph"></i> Rekap Laporan
                </a>
                <a href="{{ route('admin.pengguna.index') }}" class="btn-hero-glass">
                    <i class="bi bi-people"></i> Pengguna
                </a>
            </div>
        </div>
    </section>

    <!-- 2. EXECUTIVE KPI CARDS (BPS COLOR PALETTE) -->
    <section class="kpi-grid">
        {{-- Card 1: Total SPJ (Biru BPS) --}}
        <a href="{{ route('admin.spj.index') }}" class="kpi-card bps-blue" title="Klik untuk melihat seluruh berkas SPJ">
            <div>
                <div class="kpi-header">
                    <span class="kpi-label">Total Berkas SPJ</span>
                    <div class="kpi-icon-wrap">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                </div>
                <div class="kpi-body">
                    <div class="kpi-value">{{ number_format($totalSpj) }}</div>
                </div>
            </div>
            <div class="kpi-footer">
                <span>Total Pengajuan</span>
                <span class="kpi-badge">Rp {{ number_format($totalNilaiSpj, 0, ',', '.') }}</span>
            </div>
        </a>

        {{-- Card 2: Sedang Diproses (Oranye BPS) --}}
        <a href="{{ route('admin.spj.index', ['status' => 'diproses']) }}" class="kpi-card bps-orange" title="Klik untuk memfilter SPJ yang sedang dalam alur verifikasi">
            <div>
                <div class="kpi-header">
                    <span class="kpi-label">Sedang Diproses</span>
                    <div class="kpi-icon-wrap">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="kpi-body">
                    <div class="kpi-value">{{ number_format($menungguProses) }}</div>
                </div>
            </div>
            <div class="kpi-footer">
                <span>Alur Verifikasi</span>
                <span class="kpi-badge">{{ $persentaseProses }}% berkas aktif</span>
            </div>
        </a>

        {{-- Card 3: Selesai / Disetujui (Hijau BPS) --}}
        <a href="{{ route('admin.spj.index', ['status' => 'selesai']) }}" class="kpi-card bps-green" title="Klik untuk melihat SPJ yang telah disetujui / selesai">
            <div>
                <div class="kpi-header">
                    <span class="kpi-label">SPJ Disetujui (Selesai)</span>
                    <div class="kpi-icon-wrap">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <div class="kpi-body">
                    <div class="kpi-value">{{ number_format($selesai) }}</div>
                </div>
            </div>
            <div class="kpi-footer">
                <span>Total Dana Disetujui</span>
                <span class="kpi-badge">Rp {{ number_format($totalNilaiSelesai, 0, ',', '.') }}</span>
            </div>
        </a>

        {{-- Card 4: Perlu Revisi --}}
        <a href="{{ route('admin.spj.index', ['status' => 'revisi']) }}" class="kpi-card bps-red" title="Klik untuk melihat SPJ yang memerlukan perbaikan / revisi">
            <div>
                <div class="kpi-header">
                    <span class="kpi-label">Perlu Revisi</span>
                    <div class="kpi-icon-wrap">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>
                <div class="kpi-body">
                    <div class="kpi-value">{{ number_format($dikembalikan) }}</div>
                </div>
            </div>
            <div class="kpi-footer">
                <span>Perbaikan Berkas</span>
                <span class="kpi-badge">{{ $persentaseRevisi }}% revisi</span>
            </div>
        </a>
    </section>

    <!-- 3. MONITORING ALUR 4 TAHAPAN SPJ -->
    <section class="pipeline-section">
        <div class="pipeline-header">
            <div>
                <h3>
                    <i class="bi bi-diagram-3-fill" style="color: var(--bps-blue);"></i>
                    Monitoring Alur 4 Tahapan SPJ
                </h3>
                <p>Status posisi berkas SPJ secara bertingkat dari staf teknis hingga pengesahan akhir oleh PPSPM.</p>
            </div>
            <a href="{{ route('admin.spj.index') }}" class="pipeline-link">
                Buka Data SPJ Lengkap <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="stepper-pipeline">
            {{-- Tahap 1: Teknis (Biru BPS) --}}
            <a href="{{ route('admin.spj.index', ['status' => 'diajukan']) }}" class="step-box step-teknis {{ $pengajuan > 0 ? 'has-active' : '' }}" title="Filter SPJ Pengajuan Teknis">
                <div class="step-top">
                    <span class="step-number">1</span>
                    <span class="step-role-tag">Teknis</span>
                </div>
                <div>
                    <div class="step-counter">{{ $pengajuan }}</div>
                    <div class="step-title">Pengajuan Awal</div>
                    <div class="step-subtitle">Input berkas & kelengkapan</div>
                </div>
            </a>

            {{-- Tahap 2: Bagian Umum (Oranye BPS) --}}
            <a href="{{ route('admin.spj.index', ['status' => 'disetujui_umum']) }}" class="step-box step-umum {{ $verifikasiUmum > 0 ? 'has-active' : '' }}" title="Filter SPJ Verifikasi Bagian Umum">
                <div class="step-top">
                    <span class="step-number">2</span>
                    <span class="step-role-tag">Bagian Umum</span>
                </div>
                <div>
                    <div class="step-counter">{{ $verifikasiUmum }}</div>
                    <div class="step-title">Verifikasi Berkas</div>
                    <div class="step-subtitle">Pemeriksaan & checklist</div>
                </div>
            </a>

            {{-- Tahap 3: PPK (Navy BPS) --}}
            <a href="{{ route('admin.spj.index', ['status' => 'disetujui_ppk']) }}" class="step-box step-ppk {{ $persetujuanPpk > 0 ? 'has-active' : '' }}" title="Filter SPJ Persetujuan PPK">
                <div class="step-top">
                    <span class="step-number">3</span>
                    <span class="step-role-tag">PPK</span>
                </div>
                <div>
                    <div class="step-counter">{{ $persetujuanPpk }}</div>
                    <div class="step-title">Persetujuan PPK</div>
                    <div class="step-subtitle">Validasi komitmen anggaran</div>
                </div>
            </a>

            {{-- Tahap 4: PPSPM (Hijau BPS - Pengesahan Akhir) --}}
            <a href="{{ route('admin.spj.index', ['status' => 'selesai']) }}" class="step-box step-ppspm {{ $pengujianPpspm > 0 ? 'completed-step' : '' }}" title="Filter SPJ Pengesahan PPSPM">
                <div class="step-top">
                    <span class="step-number">4</span>
                    <span class="step-role-tag">PPSPM</span>
                </div>
                <div>
                    <div class="step-counter">{{ $pengujianPpspm }}</div>
                    <div class="step-title">Pengesahan SPM</div>
                    <div class="step-subtitle">Disetujui akhir & selesai</div>
                </div>
            </a>
        </div>

        {{-- Progress Ratio Bar (Warna Logo BPS: Hijau, Biru, Oranye) --}}
        <div class="distribution-bar-wrap">
            <div class="dist-labels">
                <span>
                    <strong style="color: var(--bps-green-dark);"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> {{ $persentaseSelesai }}% Disetujui PPSPM</strong> &bull; 
                    <span style="color: var(--bps-blue);"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> {{ $persentaseProses }}% Dalam Proses</span> &bull; 
                    <span style="color: var(--bps-orange);"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> {{ $persentaseRevisi }}% Revisi</span>
                </span>
                <span>Total: {{ $totalSpj }} Berkas</span>
            </div>
            <div class="dist-progress-bar">
                <div class="dist-segment selesai" style="width: {{ $persentaseSelesai }}%;" title="Selesai: {{ $persentaseSelesai }}%"></div>
                <div class="dist-segment proses" style="width: {{ $persentaseProses }}%;" title="Proses: {{ $persentaseProses }}%"></div>
                <div class="dist-segment revisi" style="width: {{ $persentaseRevisi }}%;" title="Revisi: {{ $persentaseRevisi }}%"></div>
            </div>
        </div>
    </section>

    <!-- 4. ROW 1: SPJ TERBARU (TABLE) & AKTIVITAS TERKINI (FEED) - EQUAL HEIGHTS -->
    <div class="dashboard-row-primary">

        <!-- LEFT: SPJ TERBARU TABLE -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div>
                    <h3>
                        <i class="bi bi-clock-history" style="color: var(--bps-blue);"></i>
                        Pengajuan SPJ Terbaru
                    </h3>
                    <p>Daftar 6 berkas SPJ yang baru saja diajukan atau diverifikasi.</p>
                </div>
                <a href="{{ route('admin.spj.index') }}" class="pipeline-link">
                    Lihat Semua ({{ $totalSpj }}) <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="dash-card-body">
                <div class="modern-table-wrap">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th style="width: 45px; text-align: center;">No</th>
                                <th>Kegiatan & Pengaju</th>
                                <th>Tanggal</th>
                                <th>Nilai</th>
                                <th>Status Posisi</th>
                                <th style="text-align: center; width: 50px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($spjTerbaru as $spj)
                            <tr>
                                <td style="text-align: center; font-weight: 800; color: var(--dash-slate-400);">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.spj.show', $spj->id) }}" class="spj-kegiatan-title" title="{{ $spj->kegiatan }}" style="text-decoration: none; color: inherit;">
                                        {{ $spj->kegiatan }}
                                    </a>
                                    <span class="spj-user-sub">
                                        <i class="bi bi-person-circle"></i>
                                        {{ $spj->user->name ?? 'Staf Teknis' }} 
                                        @if($spj->nomor_spj)
                                            &bull; <strong>{{ $spj->nomor_spj }}</strong>
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight: 600;">
                                        {{ $spj->tanggal ? \Carbon\Carbon::parse($spj->tanggal)->format('d M Y') : $spj->created_at->format('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="nilai-text">
                                        Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                                    </span>
                                </td>
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
                                <td style="text-align: center;">
                                    <a href="{{ route('admin.spj.show', $spj->id) }}" class="btn-table-action" title="Lihat Detail SPJ">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 36px 20px; color: var(--dash-slate-400);">
                                    <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 6px;"></i>
                                    <div style="font-size: 13px; font-weight: 600; color: var(--dash-slate-600); margin-bottom: 4px;">Belum ada berkas SPJ yang terdaftar dalam sistem.</div>
                                    <p style="font-size: 11.5px; margin: 0 0 12px 0;">Mulai dengan menginput pengajuan SPJ baru melalui tombol di bawah ini.</p>
                                    <a href="{{ route('admin.spj.create') }}" class="btn-hero-primary" style="font-size: 11.5px; padding: 7px 14px; box-shadow: none;">
                                        <i class="bi bi-plus-circle-fill"></i> Buat SPJ Baru
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT: AKTIVITAS TERKINI (FEED) -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div>
                    <h3>
                        <i class="bi bi-activity" style="color: var(--bps-green);"></i>
                        Aktivitas Terkini
                    </h3>
                    <p>Linimasa perubahan status berkas mutakhir.</p>
                </div>
                <a href="{{ route('admin.riwayat') }}" class="pipeline-link">
                    Riwayat <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="dash-card-body">
                <div class="activity-feed">
                    @forelse($aktivitasTerbaru as $act)
                    @php
                        $isHistory = $act instanceof \App\Models\SpjHistory;
                        $status = $isHistory ? $act->status_baru : $act->status;
                        $kegiatan = $isHistory ? ($act->spj->kegiatan ?? 'SPJ') : $act->kegiatan;
                        $nilai = $isHistory ? ($act->spj->nilai ?? 0) : $act->nilai;
                        $spjId = $isHistory ? ($act->spj_id ?? null) : $act->id;
                        $actorName = $isHistory ? ($act->user->name ?? 'Sistem') : ($act->user->name ?? 'Staf');
                        $timeAgo = $act->created_at ? $act->created_at->diffForHumans() : ($act->updated_at ? $act->updated_at->diffForHumans() : '-');
                        $aksiTitle = $isHistory ? ($act->aksi ?? null) : null;
                    @endphp
                    <div class="activity-item">
                        <div class="activity-bubble 
                            @if($status === 'selesai' || $status === 'disetujui_ppspm') green 
                            @elseif($status === 'menunggu_otp_kpa') blue
                            @elseif(str_contains((string)$status, 'revisi') || $status === 'dikembalikan') red 
                            @elseif($status === 'diajukan') blue 
                            @else orange @endif">
                            @if($status === 'selesai' || $status === 'disetujui_ppspm')
                                <i class="bi bi-check-lg"></i>
                            @elseif($status === 'menunggu_otp_kpa')
                                <i class="bi bi-key-fill"></i>
                            @elseif(str_contains((string)$status, 'revisi') || $status === 'dikembalikan')
                                <i class="bi bi-exclamation-lg"></i>
                            @elseif($status === 'diajukan')
                                <i class="bi bi-plus-lg"></i>
                            @else
                                <i class="bi bi-arrow-repeat"></i>
                            @endif
                        </div>
                        <div class="activity-detail">
                            <div class="activity-headline">
                                <span>
                                    @if($aksiTitle)
                                        {{ $aksiTitle }}
                                    @elseif($status === 'menunggu_otp_kpa')
                                        Menunggu OTP QLOLA KPA
                                    @elseif($status === 'selesai')
                                        Pembayaran Selesai
                                    @elseif($status === 'disetujui_ppspm')
                                        Disetujui PPSPM
                                    @elseif($status === 'disetujui_ppk')
                                        Disetujui PPK
                                    @elseif($status === 'disetujui_umum')
                                        Diverifikasi Umum
                                    @elseif($status === 'diajukan')
                                        Diajukan Teknis
                                    @elseif(str_contains((string)$status, 'revisi') || $status === 'dikembalikan')
                                        Revisi Berkas
                                    @else
                                        SPJ Diperbarui
                                    @endif
                                </span>
                                <span class="activity-time">{{ $timeAgo }}</span>
                            </div>
                            <div class="activity-desc" title="{{ $kegiatan }}">
                                @if($spjId)
                                    <a href="{{ route('admin.spj.edit', $spjId) }}" style="text-decoration: none; color: inherit; font-weight: 600;">
                                        {{ $kegiatan }}
                                    </a>
                                @else
                                    {{ $kegiatan }}
                                @endif
                                &bull; <strong style="color: var(--bps-navy);">Rp {{ number_format($nilai, 0, ',', '.') }}</strong>
                                @if($actorName)
                                    &bull; <span style="color: var(--dash-slate-500); font-size: 10.5px;">Oleh: {{ $actorName }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 24px 0; color: var(--dash-slate-400); font-size: 12.5px;">
                        Belum ada aktivitas tercatat.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- 5. ROW 2: PENGGUNA SISTEM & PINTASAN CEPAT - EQUAL BALANCED 2 COLUMNS -->
    <div class="dashboard-row-secondary">

        <!-- PENGGUNA SISTEM -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div>
                    <h3>
                        <i class="bi bi-people-fill" style="color: var(--bps-blue);"></i>
                        Pengguna Sistem
                    </h3>
                    <p>Distribusi hak akses user di sistem SPJ.</p>
                </div>
                <a href="{{ route('admin.pengguna.index') }}" class="pipeline-link">
                    Kelola Pengguna <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="dash-card-body">
                <div class="user-stats-wrap">
                    <div class="user-kpi-row">
                        <div class="user-kpi-item">
                            <strong>{{ $totalPengguna }}</strong>
                            <span>Total Akun Terdaftar</span>
                        </div>
                        <div class="user-kpi-item" style="text-align: right;">
                            <strong style="color: var(--bps-green-dark);">{{ $penggunaAktif }}</strong>
                            <span>Akun Berstatus Aktif</span>
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 11px; font-weight: 800; color: var(--bps-navy); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
                            Distribusi Berdasarkan Role:
                        </div>
                        <div class="role-pills">
                            <div class="role-chip">
                                Teknis <span>{{ $penggunaPerRole['teknis'] ?? 0 }}</span>
                            </div>
                            <div class="role-chip">
                                Umum <span>{{ $penggunaPerRole['umum'] ?? 0 }}</span>
                            </div>
                            <div class="role-chip">
                                PPK <span>{{ $penggunaPerRole['ppk'] ?? 0 }}</span>
                            </div>
                            <div class="role-chip">
                                PPSPM <span>{{ $penggunaPerRole['ppspm'] ?? 0 }}</span>
                            </div>
                            @if(isset($penggunaPerRole['bendahara']) && $penggunaPerRole['bendahara'] > 0)
                            <div class="role-chip">
                                Bendahara <span>{{ $penggunaPerRole['bendahara'] }}</span>
                            </div>
                            @endif
                            <div class="role-chip">
                                Admin <span>{{ $penggunaPerRole['admin'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PINTASAN AKSI CEPAT -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div>
                    <h3>
                        <i class="bi bi-lightning-charge-fill" style="color: var(--bps-orange);"></i>
                        Pintasan Cepat
                    </h3>
                    <p>Akses fitur utama dalam satu sentuhan.</p>
                </div>
            </div>
            <div class="dash-card-body">
                <div class="shortcuts-grid">
                    <a href="{{ route('admin.spj.create') }}" class="shortcut-btn">
                        <div class="shortcut-icon">
                            <i class="bi bi-file-earmark-plus-fill"></i>
                        </div>
                        <span class="shortcut-label">Input SPJ Baru</span>
                    </a>
                    <a href="{{ route('admin.template-berkas.index') }}" class="shortcut-btn">
                        <div class="shortcut-icon">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i>
                        </div>
                        <span class="shortcut-label">Template Berkas</span>
                    </a>
                    <a href="{{ route('admin.laporan.spj') }}" class="shortcut-btn">
                        <div class="shortcut-icon">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <span class="shortcut-label">Laporan & Rekap</span>
                    </a>
                    <a href="{{ route('admin.pengguna.create') }}" class="shortcut-btn">
                        <div class="shortcut-icon">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                        <span class="shortcut-label">Tambah Pengguna</span>
                    </a>
                    <a href="{{ route('admin.riwayat') }}" class="shortcut-btn">
                        <div class="shortcut-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <span class="shortcut-label">Log Riwayat</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection