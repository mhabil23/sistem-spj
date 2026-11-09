@extends('layouts.umum')

@php
$title = 'Pengajuan SPJ Baru';
$subtitle = 'Formulir pengajuan Surat Pertanggungjawaban (SPJ) Bagian Umum.';
@endphp

@push('styles')
    @vite(['resources/css/teknis/spj.css'])
    <style>
        /* ==========================================================================
           FORMULIR PENGAJUAN SPJ SUBBAG UMUM - MODERN CLEAN DESIGN
           ========================================================================== */

        .dashboard-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dashboard-heading-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--dash-text-dark, #1c3f52);
            margin: 0 0 0.25rem 0;
            letter-spacing: -0.02em;
        }

        .dashboard-heading-subtitle {
            font-size: 0.875rem;
            color: var(--dash-text-muted, #768f9e);
            margin: 0;
        }

        .btn-dash-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.1rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dash-text-dark, #1c3f52);
            background: var(--dash-card-bg, #ffffff);
            border: 1px solid var(--dash-border, #e2edf5);
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .btn-dash-back:hover {
            background: var(--dash-table-header-bg, #f8fafb);
            border-color: #cbdde8;
            transform: translateY(-1px);
            color: #0284c7;
        }

        /* Panel Container */
        .spj-create-card {
            background: var(--dash-card-bg, #ffffff);
            border: 1px solid var(--dash-border, #e2edf5);
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }

        .spj-create-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(to right, rgba(2, 132, 199, 0.04), transparent);
            border-bottom: 1px solid var(--dash-border, #e2edf5);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .spj-create-header-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        }

        .spj-create-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--dash-text-dark, #1c3f52);
            margin: 0 0 0.2rem 0;
            letter-spacing: -0.01em;
        }

        .spj-create-subtitle {
            font-size: 0.825rem;
            color: var(--dash-text-muted, #768f9e);
            margin: 0;
        }

        .spj-create-body {
            padding: 2rem 2.25rem;
        }

        /* Sections */
        .spj-form-section {
            margin-bottom: 2.25rem;
            padding-bottom: 2rem;
            border-bottom: 1px dashed var(--dash-border, #e2edf5);
        }

        .spj-form-section:last-of-type {
            margin-bottom: 1.5rem;
            padding-bottom: 0;
            border-bottom: none;
        }

        .spj-section-header {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 1.35rem;
        }

        .spj-step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.95rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.28);
        }

        .spj-section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--dash-text-dark, #1c3f52);
            margin: 0;
            letter-spacing: -0.01em;
        }

        .spj-section-subtitle {
            font-size: 0.8rem;
            color: var(--dash-text-muted, #768f9e);
            margin: 0.2rem 0 0 0;
        }

        /* Form Grid */
        .spj-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.35rem;
        }

        .grid-col-full {
            grid-column: 1 / -1;
        }

        .spj-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.45rem;
        }

        .spj-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dash-text-dark, #1c3f52);
            margin-bottom: 0.45rem;
        }

        .spj-label-row .spj-label {
            margin-bottom: 0;
        }

        .required-star {
            color: #ef4444;
            font-weight: 700;
        }

        .spj-chip-auto {
            font-size: 0.72rem;
            font-weight: 700;
            color: #0284c7;
            background: rgba(2, 132, 199, 0.1);
            padding: 2px 8px;
            border-radius: 6px;
        }

        .spj-chip-optional {
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--dash-text-muted, #768f9e);
            background: rgba(148, 163, 184, 0.12);
            padding: 2px 8px;
            border-radius: 6px;
        }

        /* Inputs & Controls */
        .spj-input,
        .spj-select,
        .spj-textarea {
            width: 100%;
            padding: 0.65rem 0.95rem;
            font-size: 0.875rem;
            font-family: inherit;
            color: var(--dash-text-dark, #1c3f52);
            background: #ffffff;
            border: 1.5px solid #cbdde8;
            border-radius: 10px;
            transition: all 0.2s ease;
            box-sizing: border-box;
            outline: none;
        }

        html.dark-theme .spj-input,
        html.dark-theme .spj-select,
        html.dark-theme .spj-textarea {
            background: #142530;
            border-color: #243e4f;
            color: #f0f6fa;
        }

        .spj-input:focus,
        .spj-select:focus,
        .spj-textarea:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.18);
        }

        .spj-input.has-error,
        .spj-select.has-error,
        .spj-textarea.has-error {
            border-color: #ef4444;
            background: #fffbfa;
        }

        html.dark-theme .spj-input.has-error,
        html.dark-theme .spj-select.has-error,
        html.dark-theme .spj-textarea.has-error {
            background: rgba(239, 68, 68, 0.08);
            border-color: #ef4444;
        }

        .spj-input-readonly {
            background: #f8fafc !important;
            color: #0284c7 !important;
            font-weight: 700 !important;
            cursor: not-allowed;
            border-style: dashed;
        }

        html.dark-theme .spj-input-readonly {
            background: #10212c !important;
            color: #38bdf8 !important;
        }

        .spj-textarea {
            resize: vertical;
            min-height: 80px;
            line-height: 1.45;
        }

        .spj-input-prefix-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .spj-input-prefix {
            position: absolute;
            left: 12px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            pointer-events: none;
        }

        .spj-input.with-prefix {
            padding-left: 2.35rem;
            font-weight: 600;
        }

        .spj-hint {
            display: block;
            font-size: 0.75rem;
            color: var(--dash-text-muted, #768f9e);
            margin-top: 0.35rem;
            line-height: 1.35;
        }

        .spj-error-msg {
            display: block;
            color: #ef4444;
            font-size: 0.775rem;
            font-weight: 500;
            margin-top: 0.35rem;
        }

        /* Category Card Box */
        .spj-category-box {
            background: linear-gradient(135deg, rgba(2, 132, 199, 0.02) 0%, rgba(248, 250, 252, 0.8) 100%);
            border: 1px solid var(--dash-border, #e2edf5);
            border-radius: 14px;
            padding: 1.35rem 1.5rem;
        }

        html.dark-theme .spj-category-box {
            background: linear-gradient(135deg, rgba(2, 132, 199, 0.05) 0%, rgba(20, 37, 48, 0.7) 100%);
            border-color: #1f3747;
        }

        /* Checklist Section */
        .checklist-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px dashed var(--dash-border, #e2edf5);
        }

        .checklist-header-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--dash-text-dark, #1c3f52);
            display: block;
        }

        .checklist-header-subtitle {
            font-size: 0.775rem;
            color: var(--dash-text-muted, #768f9e);
            margin: 0.15rem 0 0 0;
        }

        .checklist-action-btns {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-check-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            background: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .btn-check-toggle:hover {
            background: #bae6fd;
            color: #0369a1;
        }

        .btn-check-toggle.secondary {
            background: var(--dash-card-bg, #ffffff);
            color: var(--dash-text-muted, #768f9e);
            border-color: var(--dash-border, #e2edf5);
        }

        .btn-check-toggle.secondary:hover {
            background: var(--dash-table-header-bg, #f8fafb);
            color: var(--dash-text-dark, #1c3f52);
        }

        .checklist-items-wrapper {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .checklist-row-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 1.15rem;
            background: var(--dash-card-bg, #ffffff);
            border: 1px solid var(--dash-border, #e2edf5);
            border-radius: 12px;
            transition: all 0.2s ease;
            gap: 1rem;
        }

        .checklist-row-item:hover {
            border-color: #93c5fd;
            box-shadow: 0 3px 10px rgba(2, 132, 199, 0.06);
            transform: translateY(-1px);
        }

        html.dark-theme .checklist-row-item {
            background: #142530;
            border-color: #1f3747;
        }

        html.dark-theme .checklist-row-item:hover {
            border-color: #0284c7;
        }

        .checklist-doc-info {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex: 1;
            flex-wrap: wrap;
        }

        .checklist-doc-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--dash-text-dark, #1c3f52);
        }

        .badge-doc-status {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 9999px;
            white-space: nowrap;
        }

        .badge-doc-status.bendahara {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .badge-doc-status.wajib {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .checklist-checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            cursor: pointer;
            font-size: 0.825rem;
            font-weight: 700;
            color: #0284c7;
            user-select: none;
            padding: 0.3rem 0.65rem;
            border-radius: 8px;
            transition: background 0.15s ease;
        }

        .checklist-checkbox-label:hover {
            background: rgba(2, 132, 199, 0.08);
        }

        .checklist-checkbox-input {
            width: 17px;
            height: 17px;
            cursor: pointer;
            accent-color: #0284c7;
        }

        /* Status Radio Cards */
        .spj-status-radio-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.15rem;
        }

        .spj-status-card {
            position: relative;
            cursor: pointer;
            display: block;
            margin: 0;
        }

        .spj-status-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .spj-status-card-inner {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            padding: 1.15rem 1.25rem;
            border: 2px solid var(--dash-border, #e2edf5);
            border-radius: 14px;
            background: var(--dash-card-bg, #ffffff);
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            box-sizing: border-box;
        }

        .spj-status-card:hover .spj-status-card-inner {
            border-color: #93c5fd;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .status-card-radio-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #cbd5e1;
            margin-top: 2px;
            flex-shrink: 0;
            position: relative;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .status-card-radio-dot::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #0284c7;
            transition: transform 0.2s ease;
        }

        .spj-status-card input[type="radio"]:checked + .spj-status-card-inner {
            border-color: #0284c7;
            background: rgba(2, 132, 199, 0.04);
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.12);
        }

        .spj-status-card input[type="radio"]:checked + .spj-status-card-inner .status-card-radio-dot {
            border-color: #0284c7;
        }

        .spj-status-card input[type="radio"]:checked + .spj-status-card-inner .status-card-radio-dot::after {
            transform: translate(-50%, -50%) scale(1);
        }

        .status-card-title {
            display: block;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--dash-text-dark, #1c3f52);
            margin-bottom: 0.25rem;
        }

        .status-card-desc {
            display: block;
            font-size: 0.8rem;
            color: var(--dash-text-muted, #768f9e);
            line-height: 1.4;
        }

        /* Alert Banners */
        .spj-alert-banner {
            margin-top: 1.15rem;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            font-size: 0.85rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            line-height: 1.45;
        }

        .spj-alert-banner.warning {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #c2410c;
        }

        html.dark-theme .spj-alert-banner.warning {
            background: rgba(194, 65, 12, 0.15);
            border-color: rgba(254, 215, 170, 0.3);
            color: #fb923c;
        }

        .spj-alert-banner.success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857;
        }

        html.dark-theme .spj-alert-banner.success {
            background: rgba(4, 120, 87, 0.15);
            border-color: rgba(167, 243, 208, 0.3);
            color: #34d399;
        }

        .alert-banner-icon {
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Action Buttons Footer */
        .spj-form-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.85rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--dash-border, #e2edf5);
            flex-wrap: wrap;
        }

        .btn-spj-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.4rem;
            font-size: 0.875rem;
            font-weight: 700;
            border-radius: 11px;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            border: none;
            font-family: inherit;
            box-sizing: border-box;
        }

        .btn-spj-cancel {
            background: var(--dash-card-bg, #ffffff);
            color: var(--dash-text-muted, #768f9e);
            border: 1.5px solid var(--dash-border, #e2edf5);
        }

        .btn-spj-cancel:hover {
            background: var(--dash-table-header-bg, #f8fafb);
            color: var(--dash-text-dark, #1c3f52);
            border-color: #cbdde8;
        }

        .btn-spj-print {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
        }

        .btn-spj-print:hover {
            background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(2, 132, 199, 0.4);
        }

        .btn-spj-print.is-printed {
            background: #10b981;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
        }

        .btn-spj-submit {
            background: var(--dash-btn-primary-bg, #1e4253);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(30, 66, 83, 0.25);
        }

        .btn-spj-submit:hover {
            filter: brightness(1.1);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(30, 66, 83, 0.35);
        }

        /* Shake animation for print button */
        @keyframes printShake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }

        .print-btn-shake {
            animation: printShake 0.45s ease-in-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .spj-create-header {
                padding: 1.25rem 1.5rem;
            }
            .spj-create-body {
                padding: 1.35rem 1.25rem;
            }
            .spj-form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .spj-status-radio-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            .spj-form-footer {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-spj-action {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
<div style="padding-top: 4px;">
    <!-- PAGE HEADING -->
    <div class="dashboard-heading">
        <div>
            <h2 class="dashboard-heading-title">Formulir Pengajuan SPJ Baru</h2>
            <p class="dashboard-heading-subtitle">Isi rincian kegiatan belanja dan siapkan kelengkapan berkas fisik sebelum diajukan.</p>
        </div>
        <div>
            <a href="{{ route('umum.spj.index') }}" class="btn-dash-back" onclick="clearFormState()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Antrean
            </a>
        </div>
    </div>

    <!-- MAIN FORM CARD -->
    <div class="spj-create-card">
        <div class="spj-create-header">
            <div class="spj-create-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
            <div>
                <h3 class="spj-create-title">Detail Pengajuan SPJ Subbag Umum</h3>
                <p class="spj-create-subtitle">Pastikan data pengajuan valid dan sesuai dengan bukti fisik yang dipersiapkan.</p>
            </div>
        </div>

        <div class="spj-create-body">
            <form action="{{ route('umum.spj.store') }}" method="POST" id="formPengajuanSpj">
                @csrf

                <!-- STEP 1: IDENTITAS KEGIATAN & ANGGARAN -->
                <div class="spj-form-section">
                    <div class="spj-section-header">
                        <span class="spj-step-badge">1</span>
                        <div>
                            <h4 class="spj-section-title">Identitas Kegiatan &amp; Anggaran</h4>
                            <p class="spj-section-subtitle">Informasi umum mengenai rincian belanja dan alokasi anggaran SPJ.</p>
                        </div>
                    </div>

                    <div class="spj-form-grid">
                        <!-- Nomor Registrasi SPJ -->
                        <div>
                            <div class="spj-label-row">
                                <label class="spj-label" for="nomor_spj">Nomor Registrasi SPJ</label>
                                <span class="spj-chip-auto">Otomatis Sistem</span>
                            </div>
                            <input type="text" name="nomor_spj" id="nomor_spj" class="spj-input spj-input-readonly" value="{{ old('nomor_spj', $nomorSpjOtomatis) }}" readonly title="Nomor SPJ dibuat secara otomatis oleh sistem">
                            <span class="spj-hint">Nomor registrasi unik digenerate otomatis oleh sistem.</span>
                            @error('nomor_spj') <span class="spj-error-msg">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nilai Anggaran -->
                        <div>
                            <label class="spj-label" for="nilai_input">Nilai Anggaran / Nominal (Rp) <span class="required-star">*</span></label>
                            <div class="spj-input-prefix-wrapper">
                                <span class="spj-input-prefix">Rp</span>
                                <input type="number" name="nilai" id="nilai_input" class="spj-input with-prefix @error('nilai') has-error @enderror" value="{{ old('nilai') }}" placeholder="Contoh: 1500000" min="0" step="any" required>
                            </div>
                            @error('nilai') <span class="spj-error-msg">{{ $message }}</span> @enderror
                            <span class="spj-hint" id="terbilang_hint">Ketik angka nominal tanpa tanda titik atau koma.</span>
                        </div>

                        <!-- Uraian Kegiatan (Full width) -->
                        <div class="grid-col-full">
                            <label class="spj-label" for="kegiatan_input">Uraian / Nama Kegiatan Belanja <span class="required-star">*</span></label>
                            <textarea name="kegiatan" id="kegiatan_input" class="spj-textarea @error('kegiatan') has-error @enderror" rows="2" placeholder="Tuliskan uraian nama kegiatan belanja secara lengkap dan jelas..." required>{{ old('kegiatan') }}</textarea>
                            @error('kegiatan') <span class="spj-error-msg">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Mulai -->
                        <div>
                            <label class="spj-label" for="tanggal_mulai">Tanggal Mulai Pelaksanaan <span class="required-star">*</span></label>
                            <input type="date" name="tanggal" id="tanggal_mulai" class="spj-input @error('tanggal') has-error @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal') <span class="spj-error-msg">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div>
                            <div class="spj-label-row">
                                <label class="spj-label" for="tanggal_selesai">Tanggal Selesai Pelaksanaan</label>
                                <span class="spj-chip-optional">Opsional</span>
                            </div>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="spj-input @error('tanggal_selesai') has-error @enderror" value="{{ old('tanggal_selesai') }}" min="{{ old('tanggal', date('Y-m-d')) }}">
                            @error('tanggal_selesai') <span class="spj-error-msg">{{ $message }}</span> @enderror
                            <span class="spj-hint">Kosongkan jika kegiatan berlangsung satu hari saja.</span>
                        </div>

                        <!-- Seksi / Unit Kerja -->
                        <div>
                            <label class="spj-label" for="seksi_input">Seksi / Unit Kerja Pengusul <span class="required-star">*</span></label>
                            <select name="seksi" id="seksi_input" class="spj-select @error('seksi') has-error @enderror" required>
                                <option value="Subbag Umum" {{ old('seksi', 'Subbag Umum') === 'Subbag Umum' ? 'selected' : '' }}>Subbag Umum</option>
                                <option value="Sosial" {{ old('seksi') === 'Sosial' ? 'selected' : '' }}>Seksi Sosial</option>
                                <option value="Produksi" {{ old('seksi') === 'Produksi' ? 'selected' : '' }}>Seksi Produksi</option>
                                <option value="Distribusi" {{ old('seksi') === 'Distribusi' ? 'selected' : '' }}>Seksi Distribusi</option>
                                <option value="Neraca" {{ old('seksi') === 'Neraca' ? 'selected' : '' }}>Seksi Neraca</option>
                                <option value="IPDS" {{ old('seksi') === 'IPDS' ? 'selected' : '' }}>Seksi IPDS</option>
                            </select>
                            <span class="spj-hint">Unit kerja pembebanan anggaran SPJ ini.</span>
                            @error('seksi') <span class="spj-error-msg">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nomor WhatsApp PIC -->
                        <div>
                            <label class="spj-label" for="no_wa_input">Nomor WhatsApp PIC / Pengusul</label>
                            <input type="text" name="no_wa" id="no_wa_input" class="spj-input @error('no_wa') has-error @enderror" value="{{ old('no_wa', Auth::user()->no_wa ?? '') }}" placeholder="Contoh: 081234567890">
                            <span class="spj-hint">Nomor WhatsApp aktif untuk menerima pembaruan status SPJ.</span>
                            @error('no_wa') <span class="spj-error-msg">{{ $message }}</span> @enderror
                        </div>

                        <!-- Keterangan Tambahan (Full width) -->
                        <div class="grid-col-full">
                            <div class="spj-label-row">
                                <label class="spj-label" for="keterangan_input">Keterangan Tambahan</label>
                                <span class="spj-chip-optional">Opsional</span>
                            </div>
                            <textarea name="keterangan" id="keterangan_input" class="spj-textarea @error('keterangan') has-error @enderror" rows="2" placeholder="Catatan internal tambahan untuk verifikator atau bendahara (opsional)...">{{ old('keterangan') }}</textarea>
                            @error('keterangan') <span class="spj-error-msg">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- STEP 2: KATEGORI BELANJA & KELENGKAPAN BERKAS FISIK -->
                <div class="spj-form-section">
                    <div class="spj-section-header">
                        <span class="spj-step-badge">2</span>
                        <div>
                            <h4 class="spj-section-title">Kategori Belanja &amp; Kelengkapan Berkas Fisik</h4>
                            <p class="spj-section-subtitle">Tentukan kategori belanja untuk memuat daftar kelengkapan berkas fisik yang dipersyaratkan.</p>
                        </div>
                    </div>

                    <div class="spj-category-box">
                        <div class="spj-form-grid" style="margin-bottom: 1.25rem;">
                            <div>
                                <label class="spj-label" for="nama_belanja">Nama Belanja (Kategori Akun) <span class="required-star">*</span></label>
                                <select id="nama_belanja" name="nama_belanja" class="spj-select" required onchange="handleNamaBelanjaChange()">
                                    <option value="">-- Pilih Nama Belanja --</option>
                                </select>
                            </div>

                            <div>
                                <label class="spj-label" for="jenis_belanja">Jenis Belanja <span class="required-star">*</span></label>
                                <select id="jenis_belanja" name="jenis_belanja" class="spj-select" required disabled onchange="handleJenisBelanjaChange()">
                                    <option value="">-- Pilih Jenis Belanja --</option>
                                </select>
                            </div>
                        </div>

                        <!-- DOKUMEN CHECKLIST CONTAINER -->
                        <div id="dokumen_container" style="display: none;">
                            <div class="checklist-header-row">
                                <div>
                                    <span class="checklist-header-title">Dokumen Persyaratan Fisik Wajib</span>
                                    <p class="checklist-header-subtitle">Centang berkas fisik yang sudah disiapkan untuk diserahkan ke verifikator.</p>
                                </div>
                                <div class="checklist-action-btns">
                                    <button type="button" onclick="checkAllBerkas(true)" class="btn-check-toggle" title="Centang seluruh berkas fisik">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Centang Semua
                                    </button>
                                    <button type="button" onclick="checkAllBerkas(false)" class="btn-check-toggle secondary" title="Kosongkan seluruh centang berkas">
                                        Batal Semua
                                    </button>
                                </div>
                            </div>

                            <div id="dokumen_list" class="checklist-items-wrapper"></div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: TINDAKAN LANJUTAN -->
                <div class="spj-form-section">
                    <div class="spj-section-header">
                        <span class="spj-step-badge">3</span>
                        <div>
                            <h4 class="spj-section-title">Tindakan Lanjutan</h4>
                            <p class="spj-section-subtitle">Tentukan apakah pengajuan disimpan sementara sebagai draft atau langsung diajukan ke antrean.</p>
                        </div>
                    </div>

                    <div class="spj-status-radio-grid">
                        <label class="spj-status-card">
                            <input type="radio" name="status" value="draft" {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                            <div class="spj-status-card-inner">
                                <div class="status-card-radio-dot"></div>
                                <div class="status-card-text">
                                    <strong class="status-card-title">Simpan sebagai Draft</strong>
                                    <span class="status-card-desc">Simpan pengajuan tanpa memproses ke alur. Data dapat diubah dan diajukan nanti.</span>
                                </div>
                            </div>
                        </label>

                        <label class="spj-status-card">
                            <input type="radio" name="status" value="diajukan" {{ old('status') === 'diajukan' ? 'checked' : '' }}>
                            <div class="spj-status-card-inner">
                                <div class="status-card-radio-dot"></div>
                                <div class="status-card-text">
                                    <strong class="status-card-title">Ajukan Sekarang ke Alur SPJ</strong>
                                    <span class="status-card-desc">Simpan pengajuan dan teruskan ke alur pemeriksaan berkas Subbag Umum.</span>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('status') <span class="spj-error-msg" style="margin-top: 0.5rem;">{{ $message }}</span> @enderror

                    <!-- BANNERS -->
                    <div id="print_status_banner" class="spj-alert-banner success" style="display: none;"></div>

                    <div id="print_requirement_hint" class="spj-alert-banner warning" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="alert-banner-icon">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div class="alert-banner-content">
                            <strong>Wajib Cetak Lembar Checklist:</strong> Anda memilih opsi <strong>Ajukan Sekarang</strong>. Sesuai SOP, Anda wajib menekan tombol <strong>Print Checklist</strong> untuk mencetak lembar kendali checklist berkas fisik sebelum formulir dapat diajukan.
                        </div>
                    </div>

                    <input type="hidden" name="is_printed" id="is_printed_input" value="{{ old('is_printed', '0') }}">
                </div>

                <!-- BUTTON ACTIONS FOOTER -->
                <div class="spj-form-footer">
                    <a href="{{ route('umum.spj.index') }}" class="btn-spj-action btn-spj-cancel" onclick="clearFormState()">
                        Batal
                    </a>
                    <button type="button" id="btn_cetak_langsung" onclick="cetakLangsung()" class="btn-spj-action btn-spj-print" title="Cetak langsung lembar kendali checklist berkas fisik SPJ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        <span id="btn_cetak_text">Print Checklist</span>
                    </button>
                    <button type="submit" id="btn_submit_spj" class="btn-spj-action btn-spj-submit">
                        <span id="btn_submit_icon" style="display: inline-flex; align-items: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                        </span>
                        <span id="btn_submit_text">Simpan Pengajuan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-print-validation-modal />

@endsection

@push('scripts')
<script>
    // Data Kategori SPJ (Dinamis dari Database Syarat Dokumen SPJ)
    const spjData = @json($spjData ?? \App\Models\SyaratDokumenSpj::getSpjData());
    const oldNamaBelanja = {!! json_encode(old('nama_belanja', '')) !!};
    const oldJenisBelanja = {!! json_encode(old('jenis_belanja', '')) !!};
    const oldBerkas = {!! json_encode(old('berkas', [])) !!};

    function clearFormState() {
        sessionStorage.removeItem('umum_spj_form_nama_belanja');
        sessionStorage.removeItem('umum_spj_form_jenis_belanja');
    }

    function checkAllBerkas(checked) {
        const checkboxes = document.querySelectorAll('#dokumen_list input[type="checkbox"]:not([disabled])');
        checkboxes.forEach(cb => { cb.checked = checked; });
    }

    let isPrinted = {{ old('is_printed', '0') === '1' ? 'true' : 'false' }};

    function markAsPrinted() {
        isPrinted = true;

        const printedInput = document.getElementById('is_printed_input');
        if (printedInput) printedInput.value = '1';

        const printBtn = document.getElementById('btn_cetak_langsung');
        if (printBtn) {
            printBtn.classList.remove('print-btn-shake');
            printBtn.classList.add('is-printed');
            printBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Sudah Dicetak</span>
            `;
        }

        const banner = document.getElementById('print_status_banner');
        if (banner) {
            banner.style.display = 'flex';
            banner.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="alert-banner-icon">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <div class="alert-banner-content">
                    <strong>✓ Siap Diajukan:</strong> Lembar checklist berkas fisik telah dicetak. Tombol pengajuan sekarang aktif berwarna hijau!
                </div>
            `;
        }

        const printHint = document.getElementById('print_requirement_hint');
        if (printHint) printHint.style.display = 'none';

        updateSubmitButtonState();
    }

    function updateSubmitButtonState() {
        const statusSelected = document.querySelector('input[name="status"]:checked')?.value || 'draft';
        const btnSubmit = document.getElementById('btn_submit_spj');
        const btnSubmitText = document.getElementById('btn_submit_text');
        const btnSubmitIcon = document.getElementById('btn_submit_icon');
        const printHint = document.getElementById('print_requirement_hint');
        const banner = document.getElementById('print_status_banner');

        if (!btnSubmit) return;

        if (statusSelected === 'diajukan') {
            if (isPrinted) {
                // STATUS DIAJUKAN & SUDAH PRINT -> AKTIF (HIJAU EMERALD)
                btnSubmit.disabled = false;
                btnSubmit.style.background = '#059669';
                btnSubmit.style.borderColor = '#047857';
                btnSubmit.style.color = '#ffffff';
                btnSubmit.style.cursor = 'pointer';
                btnSubmit.style.opacity = '1';
                btnSubmit.style.boxShadow = '0 4px 14px rgba(5, 150, 105, 0.35)';
                btnSubmit.title = 'Lembar checklist telah dicetak. Klik untuk mengajukan SPJ ke alur pemeriksaan.';

                if (btnSubmitIcon) {
                    btnSubmitIcon.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    `;
                }
                if (btnSubmitText) btnSubmitText.innerText = 'Ajukan Sekarang ke Alur SPJ';
                if (printHint) printHint.style.display = 'none';
                if (banner) banner.style.display = 'flex';
            } else {
                // STATUS DIAJUKAN & BELUM PRINT -> JANGAN DISABLED AGAR BISA MEMICU MODAL KETIKA DIKLIK
                btnSubmit.disabled = false;
                btnSubmit.style.background = '#94a3b8';
                btnSubmit.style.borderColor = '#94a3b8';
                btnSubmit.style.color = '#ffffff';
                btnSubmit.style.cursor = 'pointer';
                btnSubmit.style.opacity = '0.85';
                btnSubmit.style.boxShadow = 'none';
                btnSubmit.title = 'Wajib menekan tombol Print Checklist terlebih dahulu sebelum pengajuan dapat disimpan.';

                if (btnSubmitIcon) {
                    btnSubmitIcon.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    `;
                }
                if (btnSubmitText) btnSubmitText.innerText = 'Ajukan SPJ (Wajib Print Dulu)';
                if (printHint) printHint.style.display = 'flex';
                if (banner) banner.style.display = 'none';
            }
        } else {
            // STATUS DRAFT -> BIRU / TEAL BRAND STANDAR
            btnSubmit.disabled = false;
            btnSubmit.style.background = 'var(--dash-btn-primary-bg, #1e4253)';
            btnSubmit.style.borderColor = 'var(--dash-btn-primary-bg, #1e4253)';
            btnSubmit.style.color = '#ffffff';
            btnSubmit.style.cursor = 'pointer';
            btnSubmit.style.opacity = '1';
            btnSubmit.style.boxShadow = '0 4px 14px rgba(30, 66, 83, 0.25)';
            btnSubmit.title = 'Simpan draft SPJ untuk diedit kembali nanti.';

            if (btnSubmitIcon) {
                btnSubmitIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                `;
            }
            if (btnSubmitText) btnSubmitText.innerText = 'Simpan sebagai Draft';
            if (printHint) printHint.style.display = 'none';
            if (banner) banner.style.display = 'none';
        }
    }

    function cetakLangsung() {
        const form = document.getElementById("formPengajuanSpj");
        const missingFields = [];

        const uraianVal = form.querySelector('[name="kegiatan"]')?.value?.trim();
        const nilaiVal = form.querySelector('[name="nilai"]')?.value?.trim();
        const tglVal = form.querySelector('[name="tanggal"]')?.value?.trim();
        const namaBelanjaVal = document.getElementById("nama_belanja")?.value?.trim();
        const jenisBelanjaVal = document.getElementById("jenis_belanja")?.value?.trim();

        if (!uraianVal) missingFields.push("Uraian / Nama Kegiatan Belanja");
        if (!nilaiVal) missingFields.push("Nilai Anggaran / Nominal (Rp)");
        if (!tglVal) missingFields.push("Tanggal Mulai Pelaksanaan");
        if (!namaBelanjaVal) missingFields.push("Nama Belanja (Kategori Akun)");
        if (!jenisBelanjaVal) missingFields.push("Jenis Belanja");

        if (missingFields.length > 0) {
            alert("Harap lengkapi isian berikut sebelum mencetak lembar checklist:\n\n• " + missingFields.join("\n• "));
            if (!uraianVal) { form.querySelector('[name="kegiatan"]')?.focus(); }
            else if (!nilaiVal) { form.querySelector('[name="nilai"]')?.focus(); }
            else if (!namaBelanjaVal) { document.getElementById("nama_belanja")?.focus(); }
            else if (!jenisBelanjaVal) { document.getElementById("jenis_belanja")?.focus(); }
            return;
        }

        const nomorSpj = document.getElementById("nomor_spj")?.value || "-";
        const kegiatan = uraianVal;
        const nilaiNum = parseFloat(nilaiVal) || 0;
        const nilaiFormatted = "Rp " + Math.round(nilaiNum).toLocaleString("id-ID");
        const tglMulai = tglVal;
        const tglSelesai = document.getElementById("tanggal_selesai")?.value?.trim() || "";
        const tanggalStr = tglSelesai && tglSelesai !== tglMulai ? tglMulai + " s.d. " + tglSelesai : tglMulai;
        const seksi = form.querySelector('[name="seksi"]')?.value || "Subbag Umum";
        const pengaju = "{{ Auth::user()->name ?? 'Subbagian Umum' }}";
        const noWa = form.querySelector('[name="no_wa"]')?.value || "-";
        const tglCetak = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

        const docs = [];
        const rows = document.querySelectorAll("#dokumen_list .checklist-row-item");
        rows.forEach(r => {
            const label = r.querySelector(".checklist-doc-name")?.innerText || "";
            const isChecked = r.querySelector('input[type="checkbox"]')?.checked;
            const isBendahara = r.innerText.includes("Dilengkapi Bendahara");
            docs.push({ name: label, isChecked: !!isChecked, isBendahara: isBendahara });
        });

        let rowsHtml = "";
        docs.forEach((d, idx) => {
            const statusBadge = d.isBendahara
                ? '<span style="color:#d97706; font-size:10.5pt; font-weight:700;">[ Dilengkapi Bendahara ]</span>'
                : (d.isChecked
                    ? '<span style="color:#15803d; font-size:11pt; font-weight:800;">[ ✓ Ada (Fisik) ]</span>'
                    : '<span style="color:#64748b; font-size:10.5pt;">[ - Belum Dilampirkan ]</span>');
            rowsHtml += `<tr>
                <td style="border:1px solid #cbd5e1; padding:7px 10px; text-align:center; width:35px; font-weight:600;">${idx + 1}</td>
                <td style="border:1px solid #cbd5e1; padding:7px 10px; font-weight:500;">${d.name}</td>
                <td style="border:1px solid #cbd5e1; padding:7px 10px; text-align:center; width:200px;">${statusBadge}</td>
            </tr>`;
        });

        const printHtml = `<!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Lembar Kendali SPJ - ${nomorSpj}</title>
            <style>
                @page { size: portrait; margin: 15mm; }
                * { box-sizing: border-box; }
                body { font-family: 'Plus Jakarta Sans', Arial, sans-serif; font-size: 10.5pt; color: #1e293b; margin: 0; padding: 10px; line-height: 1.4; }
                .kop-header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 10px; margin-bottom: 16px; }
                .kop-header h2 { margin: 0 0 4px 0; font-size: 13pt; font-weight: 800; color: #0c2556; letter-spacing: 0.5px; }
                .kop-header h3 { margin: 0 0 4px 0; font-size: 11pt; font-weight: 700; color: #0284c7; }
                .kop-header p { margin: 0; font-size: 9pt; color: #475569; }
                .title-badge { text-align: center; margin-bottom: 16px; }
                .title-badge h4 { display: inline-block; margin: 0; padding: 6px 18px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 11pt; font-weight: 800; color: #0f172a; text-transform: uppercase; }
                table.meta-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 10pt; }
                table.meta-table td { padding: 4px 6px; vertical-align: top; }
                table.meta-table td.label { width: 160px; font-weight: 700; color: #334155; }
                table.doc-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10pt; }
                table.doc-table th { background: #f8fafc; border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; font-size: 9.5pt; font-weight: 800; color: #1e293b; text-transform: uppercase; }
                table.doc-table td { border: 1px solid #cbd5e1; vertical-align: middle; }
                .sig-box { width: 100%; margin-top: 30px; display: flex; justify-content: space-between; font-size: 10pt; }
                .sig-col { width: 45%; text-align: center; }
                .sig-space { height: 60px; }
            </style>
        </head>
        <body>
            <div class="kop-header">
                <h2>BADAN PUSAT STATISTIK KABUPATEN KOLAKA UTARA</h2>
                <h3>SISTEM INFORMASI PENYELESAIAN TAGIHAN (SPJ)</h3>
                <p>Jl. Trans Sulawesi Kompleks Perkantoran Pemda Kolaka Utara, Lasusua &bull; Tanggal Cetak: ${tglCetak}</p>
            </div>

            <div class="title-badge">
                <h4>LEMBAR KENDALI KELENGKAPAN BERKAS FISIK SPJ</h4>
            </div>

            <table class="meta-table">
                <tr>
                    <td class="label">Nomor Registrasi SPJ</td>
                    <td>: <strong>${nomorSpj}</strong></td>
                    <td class="label">Unit Kerja / Seksi</td>
                    <td>: ${seksi}</td>
                </tr>
                <tr>
                    <td class="label">Uraian Kegiatan Belanja</td>
                    <td colspan="3">: ${kegiatan}</td>
                </tr>
                <tr>
                    <td class="label">Nilai Anggaran (Rp)</td>
                    <td>: <strong>${nilaiFormatted}</strong></td>
                    <td class="label">Tanggal Pelaksanaan</td>
                    <td>: ${tanggalStr}</td>
                </tr>
                <tr>
                    <td class="label">Nama Belanja (Kategori)</td>
                    <td>: ${namaBelanjaVal}</td>
                    <td class="label">Jenis Belanja</td>
                    <td>: ${jenisBelanjaVal}</td>
                </tr>
                <tr>
                    <td class="label">Pengusul / PIC</td>
                    <td>: ${pengaju}</td>
                    <td class="label">Nomor Kontak WhatsApp</td>
                    <td>: ${noWa}</td>
                </tr>
            </table>

            <table class="doc-table">
                <thead>
                    <tr>
                        <th style="text-align: center; width: 35px;">No</th>
                        <th>Daftar Dokumen Persyaratan Fisik</th>
                        <th style="text-align: center; width: 200px;">Status Kelengkapan</th>
                    </tr>
                </thead>
                <tbody>
                    ${rowsHtml}
                </tbody>
            </table>

            <div class="sig-box">
                <div class="sig-col">
                    <p style="margin: 0 0 4px 0;">Pengusul / Yang Menyerahkan,</p>
                    <p style="margin: 0; font-size: 9pt; color: #64748b;">Subbagian Umum</p>
                    <div class="sig-space"></div>
                    <p style="margin: 0; font-weight: 700; text-decoration: underline;">${pengaju}</p>
                </div>
                <div class="sig-col">
                    <p style="margin: 0 0 4px 0;">Verifikator / Yang Menerima Berkas,</p>
                    <p style="margin: 0; font-size: 9pt; color: #64748b;">Meja Pemeriksaan Subbag Umum</p>
                    <div class="sig-space"></div>
                    <p style="margin: 0; font-weight: 700;">( .................................................... )</p>
                </div>
            </div>

            <script>
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        window.focus();
                        window.print();
                    }, 350);
                });
            <\/script>
        </body>
        </html>`;

        const printWin = window.open("", "_blank");
        if (!printWin) {
            // Jika popup diblokir oleh browser
            markAsPrinted();
            alert("Pop-up cetak otomatis diblokir oleh browser Anda. Namun berkas telah berhasil ditandai 'Sudah Dicetak' sehingga Anda dapat langsung menekan tombol 'Ajukan Sekarang'.\n\nUntuk mencetak lembar fisik, silakan izinkan pop-up pada bilah URL browser Anda.");
            return;
        }

        printWin.document.open();
        printWin.document.write(printHtml);
        printWin.document.close();

        // Tandai langsung di form bahwa lembar checklist telah dicetak
        markAsPrinted();
    }

    function initNamaBelanja() {
        const selNamaBelanja = document.getElementById("nama_belanja");
        if (!selNamaBelanja) return;

        selNamaBelanja.innerHTML = '<option value="">-- Pilih Nama Belanja --</option>';
        Object.keys(spjData).forEach(nama => {
            const opt = document.createElement("option");
            opt.value = nama;
            opt.textContent = nama;
            selNamaBelanja.appendChild(opt);
        });

        const activeNama = oldNamaBelanja || sessionStorage.getItem('umum_spj_form_nama_belanja') || '';
        if (activeNama && spjData[activeNama]) {
            selNamaBelanja.value = activeNama;
            handleNamaBelanjaChange(true);
        }
    }

    function handleNamaBelanjaChange(isInitial = false) {
        const nama = document.getElementById("nama_belanja").value;
        const selJenisBelanja = document.getElementById("jenis_belanja");
        const docContainer = document.getElementById("dokumen_container");

        sessionStorage.setItem('umum_spj_form_nama_belanja', nama);

        selJenisBelanja.innerHTML = '<option value="">-- Pilih Jenis Belanja --</option>';
        docContainer.style.display = "none";

        if (nama && spjData[nama]) {
            selJenisBelanja.disabled = false;
            Object.keys(spjData[nama]).forEach(jenis => {
                const opt = document.createElement("option");
                opt.value = jenis;
                opt.textContent = jenis;
                selJenisBelanja.appendChild(opt);
            });

            const activeJenis = (isInitial ? oldJenisBelanja : '') || sessionStorage.getItem('umum_spj_form_jenis_belanja') || '';
            if (activeJenis && spjData[nama][activeJenis]) {
                selJenisBelanja.value = activeJenis;
                handleJenisBelanjaChange(isInitial);
            }
        } else {
            selJenisBelanja.disabled = true;
        }
    }

    function handleJenisBelanjaChange(isInitial = false) {
        const nama = document.getElementById("nama_belanja").value;
        const jenis = document.getElementById("jenis_belanja").value;
        const docContainer = document.getElementById("dokumen_container");
        const docList = document.getElementById("dokumen_list");

        sessionStorage.setItem('umum_spj_form_jenis_belanja', jenis);

        if (nama && jenis && spjData[nama] && spjData[nama][jenis]) {
            const items = spjData[nama][jenis];
            docList.innerHTML = "";

            items.forEach((item, index) => {
                const row = document.createElement("div");
                row.className = "checklist-row-item";

                const isBendahara = (item.assignee === 'Bendahara') || (item.name.toLowerCase().includes('pph 22') && !item.name.toLowerCase().includes('ppn'));

                let isChecked = false;
                if (isInitial && oldBerkas[index]) isChecked = true;
                else if (!isBendahara) isChecked = true;

                row.innerHTML = `
                    <div class="checklist-doc-info">
                        <input type="hidden" name="nama_dokumen[${index}]" value="${item.name}">
                        <input type="hidden" name="assignee_dokumen[${index}]" value="${item.assignee || 'SM'}">
                        <span class="checklist-doc-name">${item.name}</span>
                        ${isBendahara
                            ? '<span class="badge-doc-status bendahara">Dilengkapi Bendahara</span>'
                            : '<span class="badge-doc-status wajib">Wajib Disiapkan</span>'
                        }
                    </div>
                    <div>
                        ${isBendahara
                            ? '<span style="font-size: 0.775rem; color: #94a3b8; font-style: italic;">Tidak perlu (Diurus Bendahara)</span>'
                            : `<label class="checklist-checkbox-label">
                                   <input type="checkbox" name="berkas[${index}]" value="1" ${isChecked ? 'checked' : ''} class="checklist-checkbox-input">
                                   <span>Ada (Fisik)</span>
                               </label>`
                        }
                    </div>
                `;
                docList.appendChild(row);
            });

            docContainer.style.display = "block";
        } else {
            docContainer.style.display = "none";
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        initNamaBelanja();

        const radioStatus = document.querySelectorAll('input[name="status"]');
        radioStatus.forEach(r => {
            r.addEventListener('change', updateSubmitButtonState);
        });

        // Sinkronisasi rentang tanggal (mulai & selesai)
        const inputTglMulai = document.getElementById('tanggal_mulai');
        const inputTglSelesai = document.getElementById('tanggal_selesai');
        if (inputTglMulai && inputTglSelesai) {
            function syncTanggalMin() {
                if (inputTglMulai.value) {
                    inputTglSelesai.min = inputTglMulai.value;
                    if (inputTglSelesai.value && inputTglSelesai.value < inputTglMulai.value) {
                        inputTglSelesai.value = inputTglMulai.value;
                    }
                }
            }
            inputTglMulai.addEventListener('change', syncTanggalMin);
            syncTanggalMin();
        }

        // Intercept klik submit jika status diajukan & belum print -> TAMPILKAN MODAL POP-UP
        const submitBtn = document.getElementById('btn_submit_spj');
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                const statusSelected = document.querySelector('input[name="status"]:checked')?.value || 'draft';
                if (statusSelected === 'diajukan' && !isPrinted) {
                    e.preventDefault();
                    e.stopPropagation();

                    const printBtn = document.getElementById('btn_cetak_langsung');
                    if (printBtn) {
                        printBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        printBtn.classList.remove('print-btn-shake');
                        void printBtn.offsetWidth;
                        printBtn.classList.add('print-btn-shake');
                    }

                    if (typeof openPrintValidationModal === 'function') {
                        openPrintValidationModal();
                    } else {
                        alert("Sesuai ketentuan, Anda wajib menekan tombol 'Print Checklist' terlebih dahulu sebelum pengajuan dapat disimpan.");
                    }
                    return false;
                }
            });
        }

        const form = document.getElementById('formPengajuanSpj');
        if (form) {
            form.addEventListener('submit', function(e) {
                const statusSelected = document.querySelector('input[name="status"]:checked')?.value || 'draft';
                if (statusSelected === 'diajukan' && !isPrinted) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (typeof openPrintValidationModal === 'function') {
                        openPrintValidationModal();
                    }
                    return false;
                }
                clearFormState();
            });
        }

        if (isPrinted) {
            markAsPrinted();
        } else {
            updateSubmitButtonState();
        }

        @if($errors->has('status') && str_contains($errors->first('status'), 'Print'))
            setTimeout(function() {
                if (typeof openPrintValidationModal === 'function') {
                    openPrintValidationModal();
                }
            }, 250);
        @endif
    });
</script>
@endpush
