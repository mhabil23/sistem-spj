@extends('layouts.admin')

@php
    $title = 'Template Berkas SPJ';
@endphp

@section('content')

@push('styles')
<style>
    /* =========================================================
       ADMIN - KELOLA TEMPLATE BERKAS SPJ (PUBLIC UPLOAD)
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

        --bps-green: #10b981;
        --bps-green-dark: #047857;
        --bps-green-light: #ecfdf5;

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

    .template-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 24px;
        width: 100%;
        color: var(--dash-slate-800);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* 1. HEADER HERO */
    .template-header-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
        background: #ffffff;
        padding: 24px 28px;
        border-radius: var(--dash-radius-xl);
        border: 1px solid var(--dash-slate-200);
        box-shadow: 0 2px 6px rgba(8, 32, 82, 0.04);
        position: relative;
        overflow: hidden;
    }

    .template-header-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(180deg, var(--bps-orange) 0%, var(--bps-blue) 100%);
    }

    .template-header-title h2 {
        margin: 0 0 6px 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 12px;
        letter-spacing: -0.02em;
    }

    .template-header-title p {
        margin: 0;
        color: var(--dash-slate-500);
        font-size: 13.5px;
        max-width: 650px;
        line-height: 1.5;
    }

    .btn-toggle-upload {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 22px;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: var(--dash-radius-md);
        background: linear-gradient(135deg, var(--bps-blue) 0%, #033b66 100%);
        color: #ffffff;
        text-decoration: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(2, 75, 134, 0.25);
        transition: all 0.2s ease;
    }

    .btn-toggle-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(2, 75, 134, 0.35);
        color: #ffffff;
    }

    /* 2. STATS ROW */
    .template-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .stat-card-modern {
        background: #ffffff;
        padding: 18px 20px;
        border-radius: var(--dash-radius-lg);
        border: 1px solid var(--dash-slate-200);
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.03);
    }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-card-icon.blue { background: var(--bps-blue-light); color: var(--bps-blue); }
    .stat-card-icon.orange { background: var(--bps-orange-light); color: var(--bps-orange); }
    .stat-card-icon.green { background: var(--bps-green-light); color: var(--bps-green-dark); }
    .stat-card-icon.slate { background: var(--dash-slate-100); color: var(--dash-slate-600); }

    .stat-card-content h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: var(--bps-navy);
        line-height: 1.2;
    }

    .stat-card-content p {
        margin: 3px 0 0 0;
        font-size: 12px;
        color: var(--dash-slate-500);
        font-weight: 600;
    }

    /* 3. FORM UPLOAD PANEL */
    .upload-panel-card {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        border: 1px solid var(--dash-slate-200);
        box-shadow: 0 2px 8px rgba(8, 32, 82, 0.04);
        padding: 24px 28px;
        transition: all 0.3s ease;
    }

    .upload-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--dash-slate-200);
    }

    .upload-panel-header h3 {
        margin: 0;
        font-size: 16.5px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .upload-panel-header p {
        margin: 3px 0 0 0;
        font-size: 12.5px;
        color: var(--dash-slate-500);
    }

    .form-grid-upload {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .form-group-full {
        grid-column: span 2;
    }

    .form-label-custom {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--dash-slate-700);
        margin-bottom: 6px;
    }

    .form-label-custom span.required {
        color: #ef4444;
    }

    .form-input-custom,
    .form-select-custom,
    .form-textarea-custom {
        width: 100%;
        padding: 10px 14px;
        font-size: 13.5px;
        border: 1px solid var(--dash-slate-200);
        border-radius: var(--dash-radius-md);
        background: var(--dash-slate-50);
        color: var(--dash-slate-800);
        outline: none;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        font-family: inherit;
        box-sizing: border-box;
    }

    .form-input-custom:focus,
    .form-select-custom:focus,
    .form-textarea-custom:focus {
        border-color: var(--bps-blue);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(2, 75, 134, 0.12);
    }

    /* File drop area */
    .file-dropzone-wrapper {
        border: 2px dashed var(--dash-slate-300);
        border-radius: var(--dash-radius-lg);
        background: var(--dash-slate-50);
        padding: 24px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .file-dropzone-wrapper:hover,
    .file-dropzone-wrapper.dragover {
        border-color: var(--bps-blue);
        background: var(--bps-blue-subtle);
    }

    .file-dropzone-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-dropzone-content i {
        font-size: 36px;
        color: var(--bps-blue);
        margin-bottom: 8px;
        display: inline-block;
    }

    .file-dropzone-content strong {
        display: block;
        font-size: 14px;
        color: var(--bps-navy);
        margin-bottom: 4px;
    }

    .file-dropzone-content p {
        margin: 0;
        font-size: 12px;
        color: var(--dash-slate-500);
    }

    .file-selected-info {
        margin-top: 10px;
        padding: 8px 14px;
        border-radius: 6px;
        background: #e0f2fe;
        color: #0369a1;
        font-size: 12.5px;
        font-weight: 600;
        display: none;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .form-actions-row {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid var(--dash-slate-200);
    }

    .btn-submit-upload {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 24px;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: var(--dash-radius-md);
        background: var(--bps-green);
        color: #ffffff;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .btn-submit-upload:hover {
        background: var(--bps-green-dark);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .btn-reset-form {
        padding: 11px 18px;
        font-size: 13px;
        font-weight: 600;
        border-radius: var(--dash-radius-md);
        background: var(--dash-slate-100);
        color: var(--dash-slate-600);
        border: 1px solid var(--dash-slate-200);
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-reset-form:hover {
        background: #e2e8f0;
        color: var(--bps-navy);
    }

    /* 4. LIST TEMPLATE SECTION */
    .list-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        background: #ffffff;
        padding: 16px 22px;
        border-radius: var(--dash-radius-lg);
        border: 1px solid var(--dash-slate-200);
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
        min-width: 280px;
        max-width: 450px;
    }

    .search-input-wrapper i.search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--dash-slate-400);
        font-size: 15px;
    }

    .search-input-modern {
        width: 100%;
        padding: 9px 14px 9px 38px;
        font-size: 13px;
        border: 1px solid var(--dash-slate-200);
        border-radius: var(--dash-radius-md);
        outline: none;
        background: var(--dash-slate-50);
        color: var(--dash-slate-800);
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }

    .search-input-modern:focus {
        border-color: var(--bps-blue);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(2, 75, 134, 0.12);
    }

    .category-pills-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .category-pill-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        font-size: 12.5px;
        font-weight: 600;
        border-radius: 20px;
        background: var(--dash-slate-100);
        color: var(--dash-slate-600);
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .category-pill-btn:hover {
        background: #e2e8f0;
        color: var(--bps-navy);
    }

    .category-pill-btn.active {
        background: var(--bps-blue-subtle);
        color: var(--bps-blue);
        border-color: rgba(2, 75, 134, 0.3);
        font-weight: 700;
    }

    /* 5. TEMPLATES GRID */
    .templates-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 18px;
    }

    .template-card {
        background: #ffffff;
        border-radius: var(--dash-radius-lg);
        border: 1px solid var(--dash-slate-200);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 22px;
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.02);
    }

    .template-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(8, 32, 82, 0.08);
        border-color: rgba(2, 75, 134, 0.3);
    }

    .template-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .template-code-badge {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        padding: 3px 9px;
        border-radius: 6px;
        background: var(--dash-slate-100);
        color: var(--dash-slate-600);
        border: 1px solid var(--dash-slate-200);
    }

    .template-format-badge {
        font-size: 11px;
        font-weight: 800;
        padding: 3px 9px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-transform: uppercase;
    }

    .template-format-badge.docx, .template-format-badge.doc { background: #dbeafe; color: #1e40af; }
    .template-format-badge.xlsx, .template-format-badge.xls, .template-format-badge.csv { background: #dcfce7; color: #15803d; }
    .template-format-badge.pdf { background: #fee2e2; color: #b91c1c; }
    .template-format-badge.zip, .template-format-badge.rar { background: #fef3c7; color: #b45309; }

    .template-title {
        margin: 0 0 6px 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--bps-navy);
        line-height: 1.35;
    }

    .template-cat-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--bps-orange-dark);
        background: var(--bps-orange-light);
        padding: 2px 8px;
        border-radius: 4px;
        margin-bottom: 12px;
    }

    .template-file-meta {
        background: var(--dash-slate-50);
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 14px;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .meta-label {
        color: var(--dash-slate-500);
        font-weight: 600;
    }

    .meta-value {
        color: var(--dash-slate-800);
        font-weight: 700;
        text-align: right;
    }

    .template-desc-text {
        font-size: 12.5px;
        color: var(--dash-slate-600);
        line-height: 1.5;
        margin: 0 0 16px 0;
    }

    .template-card-footer {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-top: 14px;
        border-top: 1px solid var(--dash-slate-200);
    }

    .btn-download-file {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 14px;
        font-size: 12.5px;
        font-weight: 700;
        border-radius: var(--dash-radius-md);
        background: var(--bps-blue);
        color: #ffffff;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-download-file:hover {
        background: #033b66;
        color: #ffffff;
    }

    .btn-delete-file {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 12px;
        font-size: 12.5px;
        font-weight: 600;
        border-radius: var(--dash-radius-md);
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-delete-file:hover {
        background: #fca5a5;
    }

    /* Empty state */
    .empty-state-card {
        background: #ffffff;
        padding: 60px 24px;
        border-radius: var(--dash-radius-xl);
        border: 2px dashed var(--dash-slate-300);
        text-align: center;
    }

    .empty-state-icon {
        font-size: 48px;
        color: var(--dash-slate-400);
        margin-bottom: 12px;
    }

    .empty-state-card h4 {
        margin: 0 0 6px 0;
        font-size: 18px;
        font-weight: 800;
        color: var(--bps-navy);
    }

    .empty-state-card p {
        margin: 0 0 16px 0;
        font-size: 13.5px;
        color: var(--dash-slate-500);
        max-width: 480px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.5;
    }

    @media (max-width: 992px) {
        .template-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .form-grid-upload {
            grid-template-columns: 1fr;
        }
        .form-group-full {
            grid-column: span 1;
        }
    }

    @media (max-width: 576px) {
        .template-stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

<div class="template-page-wrapper">

    {{-- ALERT PESAN SUKSES / ERROR --}}
    @if(session('success'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-left: 4px solid var(--bps-green); padding: 14px 18px; border-radius: var(--dash-radius-md); display: flex; align-items: center; justify-content: space-between; color: #065f46; font-size: 13.5px; font-weight: 600;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-check-circle-fill" style="color: var(--bps-green); font-size: 18px;"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" style="background: transparent; border: none; font-size: 18px; cursor: pointer; color: #065f46;">&times;</button>
    </div>
    @endif

    @if($errors->any())
    <div style="background: #fef2f2; border: 1px solid #fecaca; border-left: 4px solid #ef4444; padding: 14px 18px; border-radius: var(--dash-radius-md); color: #991b1b; font-size: 13.5px;">
        <strong style="display: block; margin-bottom: 6px;"><i class="bi bi-exclamation-triangle-fill"></i> Terdapat kendala pada isian formulir:</strong>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- 1. HEADER HERO --}}
    <div class="template-header-box">
        <div class="template-header-title">
            <h2>
                <i class="bi bi-file-earmark-arrow-down-fill" style="color: var(--bps-orange);"></i>
                Kelola Template Berkas SPJ
            </h2>
            <p>
                Unggah dan kelola berkas format template (Word, Excel, PDF) ke direktori publik sistem (<code>public/files/templates/</code>). Seluruh berkas yang diunggah dapat diunduh langsung oleh staf dan bagian verifikator.
            </p>
        </div>

        <button type="button" class="btn-toggle-upload" onclick="scrollToUploadForm()">
            <i class="bi bi-cloud-arrow-up-fill"></i>
            Unggah Template Berkas
        </button>
    </div>

    {{-- 2. STATS ROW --}}
    <div class="template-stats-grid">
        <div class="stat-card-modern">
            <div class="stat-card-icon blue">
                <i class="bi bi-files"></i>
            </div>
            <div class="stat-card-content">
                <h3>{{ $stats['total_template'] }} Berkas</h3>
                <p>Total Template Tersimpan</p>
            </div>
        </div>

        <div class="stat-card-modern">
            <div class="stat-card-icon orange">
                <i class="bi bi-folder-check"></i>
            </div>
            <div class="stat-card-content">
                <h3>{{ $stats['total_kategori'] }} Kategori</h3>
                <p>Kelompok Kategori Dokumen</p>
            </div>
        </div>

        <div class="stat-card-modern">
            <div class="stat-card-icon green">
                <i class="bi bi-hdd-network-fill"></i>
            </div>
            <div class="stat-card-content">
                <h3>{{ $stats['total_size'] }}</h3>
                <p>Ukuran Penyimpanan Publik</p>
            </div>
        </div>

        <div class="stat-card-modern">
            <div class="stat-card-icon slate">
                <i class="bi bi-folder-symlink-fill"></i>
            </div>
            <div class="stat-card-content">
                <h3 style="font-size: 15px; font-family: monospace;">/files/templates/</h3>
                <p>Lokasi Direktori Publik</p>
            </div>
        </div>
    </div>

    {{-- 3. FORM UNGGAH BERKAS TEMPLATE --}}
    <div class="upload-panel-card" id="uploadFormSection">
        <div class="upload-panel-header">
            <div>
                <h3>
                    <i class="bi bi-plus-circle-fill" style="color: var(--bps-blue);"></i>
                    Formulir Unggah Template Berkas Baru
                </h3>
                <p>Masukkan informasi format berkas lalu unggah dokumen template (Word, Excel, PDF, dsb.) ke direktori publik.</p>
            </div>
        </div>

        <form action="{{ route('admin.template-berkas.upload') }}" method="POST" enctype="multipart/form-data" id="formUploadTemplate">
            @csrf

            <div class="form-grid-upload">
                {{-- Nama Dokumen --}}
                <div>
                    <label class="form-label-custom">Nama Berkas Template <span class="required">*</span></label>
                    <input type="text" name="nama" class="form-input-custom" value="{{ old('nama') }}" placeholder="Contoh: Format Kerangka Acuan Kerja (KAK) 2026" required>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="form-label-custom">Kategori Dokumen <span class="required">*</span></label>
                    <select name="kategori" class="form-select-custom" required>
                        <option value="" disabled selected>Pilih Kategori...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat['nama'] }}" {{ old('kategori') === $cat['nama'] ? 'selected' : '' }}>
                            {{ $cat['nama'] }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kode Dokumen --}}
                <div>
                    <label class="form-label-custom">Kode Dokumen (Opsional)</label>
                    <input type="text" name="kode_dokumen" class="form-input-custom" value="{{ old('kode_dokumen') }}" placeholder="Contoh: KAK-TOR, BAST-01, SPPD-01">
                </div>

                {{-- Kode Akun / MAK --}}
                <div>
                    <label class="form-label-custom">Kode Akun MAK Terkait (Opsional)</label>
                    <input type="text" name="kode_akun" class="form-input-custom" value="{{ old('kode_akun') }}" placeholder="Contoh: 521211, 521811, 524111, Semua Akun">
                </div>

                {{-- Penanggung Jawab / PIC --}}
                <div>
                    <label class="form-label-custom">Penanggung Jawab / PIC (Opsional)</label>
                    <input type="text" name="pic" class="form-input-custom" value="{{ old('pic') }}" placeholder="Contoh: Seksi Teknis (SM), PPK, Bendahara">
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="form-label-custom">Keterangan / Panduan Singkat (Opsional)</label>
                    <input type="text" name="keterangan" class="form-input-custom" value="{{ old('keterangan') }}" placeholder="Contoh: Wajib dilampirkan bukti fisik foto dan rincian barang">
                </div>

                {{-- Upload File Dropzone --}}
                <div class="form-group-full">
                    <label class="form-label-custom">Berkas File Template <span class="required">*</span></label>
                    <div class="file-dropzone-wrapper" id="dropzoneWrapper">
                        <input type="file" name="berkas_template" id="berkasTemplateInput" class="file-dropzone-input" required accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.csv" onchange="handleFileSelected(this)">
                        <div class="file-dropzone-content">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <strong>Pilih atau Seret Berkas ke Area Ini</strong>
                            <p>Mendukung format Word (.docx, .doc), Excel (.xlsx, .xls), PDF (.pdf), CSV, atau Arsip (.zip). Maksimal 20 MB.</p>
                        </div>
                        <div class="file-selected-info" id="fileSelectedInfo">
                            <i class="bi bi-file-earmark-check-fill"></i>
                            <span id="fileSelectedName">Nama file terpilih</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions-row">
                <button type="reset" class="btn-reset-form" onclick="resetFilePicker()">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Form
                </button>
                <button type="submit" class="btn-submit-upload">
                    <i class="bi bi-cloud-upload-fill"></i> Simpan &amp; Unggah ke Direktori Publik
                </button>
            </div>
        </form>
    </div>

    {{-- 4. DAFTAR BERKAS TEMPLATE YANG TERSIMPAN --}}
    <div class="list-section-header">
        <div class="search-input-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" class="search-input-modern" placeholder="Cari nama template, kode dokumen, atau nama file..." onkeyup="filterClientSide()">
        </div>

        <div class="category-pills-row">
            <button type="button" class="category-pill-btn active" data-cat="all" onclick="filterByCat('all', this)">
                <i class="bi bi-collection-fill"></i> Semua ({{ $stats['total_template'] }})
            </button>
            @foreach($categories as $cat)
            <button type="button" class="category-pill-btn" data-cat="{{ $cat['slug'] }}" onclick="filterByCat('{{ $cat['slug'] }}', this)">
                <i class="bi {{ $cat['icon'] }}"></i> {{ $cat['nama'] }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- 5. GRID TEMPLATES --}}
    @if(count($templates) > 0)
    <div class="templates-grid" id="templatesGrid">
        @foreach($templates as $tpl)
        <div class="template-card template-node" data-cat="{{ $tpl['kategori_slug'] ?? '' }}" data-nama="{{ strtolower($tpl['nama'] ?? '') }}" data-code="{{ strtolower($tpl['kode_dokumen'] ?? '') }}" data-file="{{ strtolower($tpl['original_name'] ?? '') }}">
            <div>
                <div class="template-card-top">
                    <span class="template-code-badge">{{ $tpl['kode_dokumen'] ?? 'DOK' }}</span>
                    <span class="template-format-badge {{ $tpl['file_extension'] ?? 'pdf' }}">
                        <i class="bi bi-file-earmark"></i>
                        {{ strtoupper($tpl['file_extension'] ?? 'FILE') }}
                    </span>
                </div>

                <h4 class="template-title">{{ $tpl['nama'] }}</h4>

                <span class="template-cat-tag">
                    <i class="bi bi-tag-fill"></i> {{ $tpl['kategori'] }}
                </span>

                <div class="template-file-meta">
                    <div class="meta-row">
                        <span class="meta-label">File Fisik:</span>
                        <span class="meta-value" style="font-family: monospace; font-size: 11px;" title="{{ $tpl['file_name'] }}">{{ Str::limit($tpl['original_name'] ?? $tpl['file_name'], 25) }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Ukuran Berkas:</span>
                        <span class="meta-value">{{ $tpl['file_size'] ?? '—' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Kode Akun:</span>
                        <span class="meta-value">{{ $tpl['kode_akun'] ?? '—' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">PIC:</span>
                        <span class="meta-value">{{ $tpl['pic'] ?? '—' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Diunggah:</span>
                        <span class="meta-value" style="font-weight: 500; font-size: 11px;">{{ $tpl['uploaded_at'] ?? '—' }}</span>
                    </div>
                </div>

                @if(!empty($tpl['keterangan']))
                <p class="template-desc-text">
                    <i class="bi bi-info-circle" style="color: var(--bps-blue);"></i> {{ $tpl['keterangan'] }}
                </p>
                @endif
            </div>

            <div class="template-card-footer">
                <a href="{{ $tpl['download_url'] }}" download="{{ $tpl['original_name'] ?? $tpl['file_name'] }}" class="btn-download-file">
                    <i class="bi bi-download"></i> Unduh File
                </a>

                <form action="{{ route('admin.template-berkas.destroy', $tpl['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template berkas ini dari direktori publik?');" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete-file" title="Hapus template berkas">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    {{-- EMPTY STATE SAAT BELUM ADA TEMPLATE --}}
    <div class="empty-state-card">
        <i class="bi bi-folder-plus empty-state-icon"></i>
        <h4>Belum Ada Berkas Template di Direktori Publik</h4>
        <p>
            Anda belum mengunggah berkas template format SPJ. Silakan gunakan formulir di atas untuk mengunggah berkas Word (.docx), Excel (.xlsx), atau PDF format Anda.
        </p>
        <button type="button" class="btn-toggle-upload" onclick="scrollToUploadForm()">
            <i class="bi bi-plus-lg"></i> Mulai Unggah Berkas
        </button>
    </div>
    @endif

    {{-- Empty state saat filter pencarian kosong --}}
    <div id="noFilterResult" style="display: none;" class="empty-state-card">
        <i class="bi bi-search empty-state-icon"></i>
        <h4>Tidak Ada Template yang Cocok</h4>
        <p>Tidak ditemukan berkas template yang sesuai dengan kata kunci pencarian atau kategori yang Anda pilih.</p>
        <button type="button" class="btn-reset-form" onclick="resetFilters()">
            <i class="bi bi-arrow-clockwise"></i> Reset Pencarian
        </button>
    </div>

</div>

@push('scripts')
<script>
    function scrollToUploadForm() {
        const target = document.getElementById('uploadFormSection');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
            const inputNama = target.querySelector('input[name="nama"]');
            if (inputNama) inputNama.focus();
        }
    }

    function handleFileSelected(input) {
        const infoBox = document.getElementById('fileSelectedInfo');
        const nameSpan = document.getElementById('fileSelectedName');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeFormatted = (file.size / 1024 / 1024).toFixed(2);
            nameSpan.innerHTML = `<strong>${file.name}</strong> (${sizeFormatted} MB)`;
            infoBox.style.display = 'inline-flex';
        } else {
            infoBox.style.display = 'none';
        }
    }

    function resetFilePicker() {
        const infoBox = document.getElementById('fileSelectedInfo');
        if (infoBox) infoBox.style.display = 'none';
    }

    // Client-side search and category filtering
    let activeCategory = 'all';

    function filterByCat(catSlug, btnElement) {
        activeCategory = catSlug;
        document.querySelectorAll('.category-pill-btn').forEach(btn => btn.classList.remove('active'));
        if (btnElement) btnElement.classList.add('active');
        filterClientSide();
    }

    function filterClientSide() {
        const query = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.template-node');
        let visibleCount = 0;

        cards.forEach(card => {
            const cat = card.getAttribute('data-cat') || '';
            const nama = card.getAttribute('data-nama') || '';
            const code = card.getAttribute('data-code') || '';
            const file = card.getAttribute('data-file') || '';

            const matchCat = (activeCategory === 'all' || cat === activeCategory);
            const matchSearch = (query === '' || nama.includes(query) || code.includes(query) || file.includes(query));

            if (matchCat && matchSearch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noResult = document.getElementById('noFilterResult');
        if (noResult) {
            noResult.style.display = (visibleCount === 0 && cards.length > 0) ? 'block' : 'none';
        }
    }

    function resetFilters() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) searchInput.value = '';
        activeCategory = 'all';
        document.querySelectorAll('.category-pill-btn').forEach(btn => {
            if (btn.getAttribute('data-cat') === 'all') btn.classList.add('active');
            else btn.classList.remove('active');
        });
        filterClientSide();
    }

    // Drag and drop highlight
    const dropzone = document.getElementById('dropzoneWrapper');
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => dropzone.classList.add('dragover'), false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => dropzone.classList.remove('dragover'), false);
        });
    }
</script>
@endpush

@endsection
