@extends('layouts.admin')

@php
    $title = 'Kelola Video Tutorial';
@endphp

@section('content')

@push('styles')
<style>
    :root {
        --bps-navy: #082052;
        --bps-blue: #024b86;
        --bps-blue-light: #e0f2fe;
        --bps-orange: #f37021;
        --bps-green: #10b981;
        --bps-green-light: #ecfdf5;
        --dash-slate-800: #1e293b;
        --dash-slate-600: #475569;
        --dash-slate-500: #64748b;
        --dash-slate-200: #e2e8f0;
        --dash-slate-100: #f1f5f9;
        --dash-slate-50: #f8fafc;
        --dash-radius-xl: 16px;
        --dash-radius-lg: 12px;
        --dash-radius-md: 8px;
    }

    .video-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        color: var(--dash-slate-800);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* HEADER HERO */
    .video-header-box {
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
    }

    .video-header-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .video-header-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);
    }

    .video-header-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--bps-navy);
        margin: 0 0 4px 0;
    }

    .video-header-desc {
        font-size: 13px;
        color: var(--dash-slate-500);
        margin: 0;
    }

    .video-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        padding: 10px 18px;
        border-radius: var(--dash-radius-md);
        font-size: 13.5px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
        transition: all 0.2s ease;
    }

    .btn-action-primary:hover {
        background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
        transform: translateY(-1px);
        color: #ffffff;
    }

    .btn-action-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--dash-slate-50);
        color: var(--dash-slate-700);
        padding: 10px 16px;
        border-radius: var(--dash-radius-md);
        font-size: 13.5px;
        font-weight: 600;
        border: 1px solid var(--dash-slate-200);
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-action-secondary:hover {
        background: #ffffff;
        border-color: var(--dash-slate-300);
    }

    /* STATS STRIP */
    .video-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    @media (max-width: 900px) {
        .video-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .stat-card-mini {
        background: #ffffff;
        border: 1px solid var(--dash-slate-200);
        border-radius: var(--dash-radius-lg);
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
    }

    .stat-card-mini .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-card-mini.blue .stat-icon { background: #e0f2fe; color: #0284c7; }
    .stat-card-mini.green .stat-icon { background: #dcfce7; color: #16a34a; }
    .stat-card-mini.red .stat-icon { background: #fee2e2; color: #dc2626; }
    .stat-card-mini.purple .stat-icon { background: #f3e8ff; color: #9333ea; }

    .stat-card-mini .stat-val {
        font-size: 22px;
        font-weight: 800;
        color: var(--bps-navy);
        line-height: 1.2;
    }

    .stat-card-mini .stat-label {
        font-size: 12px;
        color: var(--dash-slate-500);
        font-weight: 600;
    }

    /* FILTER BAR */
    .video-filter-card {
        background: #ffffff;
        border: 1px solid var(--dash-slate-200);
        border-radius: var(--dash-radius-xl);
        padding: 16px 20px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
    }

    .video-filter-form {
        display: grid;
        grid-template-columns: 2fr 1.5fr 1.2fr auto;
        gap: 12px;
        align-items: flex-end;
    }

    @media (max-width: 992px) {
        .video-filter-form {
            grid-template-columns: 1fr 1fr;
        }
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-item label {
        font-size: 12px;
        font-weight: 700;
        color: var(--dash-slate-600);
    }

    .filter-input {
        width: 100%;
        padding: 9px 12px;
        border-radius: var(--dash-radius-md);
        border: 1px solid var(--dash-slate-200);
        font-size: 13px;
        color: var(--dash-slate-800);
        background: #ffffff;
        outline: none;
        transition: border-color 0.2s;
    }

    .filter-input:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }

    .filter-btn-group {
        display: flex;
        gap: 8px;
    }

    /* TABLE CARD */
    .video-table-card {
        background: #ffffff;
        border: 1px solid var(--dash-slate-200);
        border-radius: var(--dash-radius-xl);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .video-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .video-table th {
        background: #f8fafc;
        padding: 13px 16px;
        text-align: left;
        font-weight: 700;
        color: var(--dash-slate-600);
        border-bottom: 1px solid var(--dash-slate-200);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .video-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .video-table tr:hover td {
        background: #fafcff;
    }

    /* BADGES */
    .badge-kategori {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-kategori.badge-semua { background: #e0f2fe; color: #0284c7; }
    .badge-kategori.badge-teknis { background: #ecfdf5; color: #059669; }
    .badge-kategori.badge-umum { background: #fdf4ff; color: #c026d3; }
    .badge-kategori.badge-ppk { background: #eef2ff; color: #4f46e5; }
    .badge-kategori.badge-ppspm { background: #fff7ed; color: #ea580c; }
    .badge-kategori.badge-bendahara { background: #fefce8; color: #ca8a04; }

    .badge-sumber {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-sumber.yt { background: #fee2e2; color: #dc2626; }
    .badge-sumber.file { background: #f1f5f9; color: #475569; }
    .badge-sumber.link { background: #e0f2fe; color: #0284c7; }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        border: none;
    }

    .badge-status.active { background: #dcfce7; color: #15803d; }
    .badge-status.inactive { background: #f1f5f9; color: #94a3b8; }

    .video-thumb-preview {
        width: 72px;
        height: 44px;
        border-radius: 6px;
        background: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 18px;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        border: 1px solid var(--dash-slate-200);
        flex-shrink: 0;
    }

    .video-thumb-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .video-thumb-preview .play-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        opacity: 0.8;
        transition: opacity 0.2s;
    }

    .video-thumb-preview:hover .play-overlay {
        opacity: 1;
        background: rgba(2, 132, 199, 0.6);
    }

    /* ACTION BUTTONS */
    .btn-row-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--dash-slate-200);
        background: #ffffff;
        color: var(--dash-slate-600);
        cursor: pointer;
        font-size: 13px;
        transition: all 0.15s;
    }

    .btn-row-action:hover {
        background: #f8fafc;
        color: #0284c7;
        border-color: #bae6fd;
    }

    .btn-row-action.btn-del:hover {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }

    /* MODAL STYLES */
    .modal-backdrop-custom {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(8, 32, 82, 0.65);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-dialog-custom {
        background: #ffffff;
        width: 100%;
        max-width: 620px;
        border-radius: var(--dash-radius-xl);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        border: 1px solid var(--dash-slate-200);
        overflow: hidden;
        animation: modalSlideIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }

    .modal-dialog-custom.large {
        max-width: 820px;
    }

    @keyframes modalSlideIn {
        from { opacity: 0; transform: translateY(12px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-header-custom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        background: #f8fafc;
        border-bottom: 1px solid var(--dash-slate-200);
    }

    .modal-title-custom {
        font-size: 16px;
        font-weight: 800;
        color: var(--bps-navy);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-close-btn {
        background: none;
        border: none;
        color: var(--dash-slate-500);
        font-size: 20px;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: color 0.15s;
    }

    .modal-close-btn:hover {
        color: #dc2626;
        background: #fee2e2;
    }

    .modal-body-custom {
        padding: 22px 24px;
        overflow-y: auto;
    }

    .modal-footer-custom {
        padding: 14px 24px;
        background: #f8fafc;
        border-top: 1px solid var(--dash-slate-200);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .form-group-custom {
        margin-bottom: 16px;
    }

    .form-group-custom label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--dash-slate-700);
        margin-bottom: 6px;
    }

    .form-group-custom label .req {
        color: #dc2626;
    }

    .form-group-custom .hint {
        font-size: 11.5px;
        color: var(--dash-slate-500);
        margin-top: 4px;
    }

    .form-control-custom {
        width: 100%;
        padding: 9px 12px;
        border-radius: var(--dash-radius-md);
        border: 1px solid var(--dash-slate-200);
        font-size: 13.5px;
        color: var(--dash-slate-800);
        background: #ffffff;
        outline: none;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .form-control-custom:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }

    .source-segmented {
        display: flex;
        gap: 10px;
        margin-bottom: 14px;
    }

    .source-option-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px;
        border: 1.5px solid var(--dash-slate-200);
        border-radius: var(--dash-radius-md);
        background: #f8fafc;
        cursor: pointer;
        font-size: 13px;
        font-weight: 700;
        color: var(--dash-slate-600);
        transition: all 0.2s;
    }

    .source-option-btn.active {
        background: #e0f2fe;
        border-color: #0284c7;
        color: #0369a1;
    }

    /* Video Player iframe */
    .video-player-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
        height: 0;
        overflow: hidden;
        border-radius: 12px;
        background: #0f172a;
    }

    .video-player-container iframe,
    .video-player-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 12px;
    }
</style>
@endpush

<div class="video-page-wrapper">

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 18px; border-radius: 12px; display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600;">
        <i class="bi bi-check-circle-fill" style="color: #10b981; font-size: 18px;"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px; font-size: 13.5px;">
        <div style="font-weight: 700; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
            <i class="bi bi-exclamation-triangle-fill"></i> Terdapat kesalahan pada formulir:
        </div>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- HEADER BOX --}}
    <div class="video-header-box">
        <div class="video-header-info">
            <div class="video-header-icon">
                <i class="bi bi-play-btn-fill"></i>
            </div>
            <div>
                <h1 class="video-header-title">Kelola Video Tutorial Panduan</h1>
                <p class="video-header-desc">
                    Atur koleksi video panduan yang ditampilkan di menu <strong>PANDUAN</strong> untuk seluruh staf dan pejabat BPS.
                </p>
            </div>
        </div>
        <div class="video-header-actions">
            <a href="{{ route('home') }}" target="_blank" class="btn-action-secondary" title="Lihat tampilan menu panduan di beranda">
                <i class="bi bi-eye"></i>
                <span>Lihat di Beranda</span>
            </a>
            <button type="button" class="btn-action-primary" onclick="openAddVideoModal()">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Tambah Video Baru</span>
            </button>
        </div>
    </div>

    {{-- STATS STRIP --}}
    <div class="video-stats-grid">
        <div class="stat-card-mini blue">
            <div class="stat-icon"><i class="bi bi-collection-play-fill"></i></div>
            <div>
                <div class="stat-val">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Video Tutorial</div>
            </div>
        </div>
        <div class="stat-card-mini green">
            <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-val">{{ $stats['aktif'] }}</div>
                <div class="stat-label">Video Aktif (Tampil)</div>
            </div>
        </div>
        <div class="stat-card-mini red">
            <div class="stat-icon"><i class="bi bi-youtube"></i></div>
            <div>
                <div class="stat-val">{{ $stats['link'] }}</div>
                <div class="stat-label">Video YouTube / Link</div>
            </div>
        </div>
        <div class="stat-card-mini purple">
            <div class="stat-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
            <div>
                <div class="stat-val">{{ $stats['upload'] }}</div>
                <div class="stat-label">Video File Lokal</div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH BAR --}}
    <div class="video-filter-card">
        <form method="GET" action="{{ route('admin.video-tutorial.index') }}" class="video-filter-form">
            <div class="filter-item">
                <label for="search">Cari Video</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="filter-input" placeholder="Ketik judul tutorial atau deskripsi...">
            </div>
            <div class="filter-item">
                <label for="kategori">Kategori Role</label>
                <select name="kategori" id="kategori" class="filter-input">
                    <option value="all">Semua Kategori Role</option>
                    <option value="semua" {{ request('kategori') === 'semua' ? 'selected' : '' }}>Semua Pengguna</option>
                    <option value="teknis" {{ request('kategori') === 'teknis' ? 'selected' : '' }}>Staf Teknis</option>
                    <option value="umum" {{ request('kategori') === 'umum' ? 'selected' : '' }}>Subbagian Umum</option>
                    <option value="ppk" {{ request('kategori') === 'ppk' ? 'selected' : '' }}>PPK</option>
                    <option value="ppspm" {{ request('kategori') === 'ppspm' ? 'selected' : '' }}>PPSPM</option>
                    <option value="bendahara" {{ request('kategori') === 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                </select>
            </div>
            <div class="filter-item">
                <label for="status">Status Tampil</label>
                <select name="status" id="status" class="filter-input">
                    <option value="all">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif (Tampil)</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
                </select>
            </div>
            <div class="filter-btn-group">
                <button type="submit" class="btn-action-primary" style="padding: 9px 16px;">
                    <i class="bi bi-search"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.video-tutorial.index') }}" class="btn-action-secondary" style="padding: 9px 12px;" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- TABLE LIST --}}
    <div class="video-table-card">
        <div style="overflow-x: auto;">
            <table class="video-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th style="width: 80px;">Pratinjau</th>
                        <th>Judul &amp; Deskripsi Tutorial</th>
                        <th style="width: 140px;">Kategori Role</th>
                        <th style="width: 130px;">Tipe Sumber</th>
                        <th style="width: 80px; text-align: center;">Urutan</th>
                        <th style="width: 100px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tutorials as $index => $item)
                    <tr>
                        <td style="text-align: center; color: var(--dash-slate-500); font-weight: 700;">
                            {{ $tutorials->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="video-thumb-preview" onclick="openPlayerModal('{{ addslashes($item->judul) }}', '{{ $item->embed_url }}', '{{ $item->tipe_sumber }}')">
                                @if($item->thumbnail_url)
                                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->judul }}">
                                @else
                                    <i class="bi bi-play-circle-fill"></i>
                                @endif
                                <div class="play-overlay"><i class="bi bi-play-fill"></i></div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--bps-navy); font-size: 14px; margin-bottom: 3px;">
                                {{ $item->judul }}
                                @if($item->durasi)
                                    <span style="font-size: 11px; color: var(--dash-slate-500); font-weight: 600; margin-left: 6px;">
                                        <i class="bi bi-clock"></i> {{ $item->durasi }}
                                    </span>
                                @endif
                            </div>
                            <div style="font-size: 12px; color: var(--dash-slate-500); line-height: 1.4;">
                                {{ Str::limit($item->deskripsi, 90) ?: 'Tidak ada deskripsi tambahan.' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge-kategori {{ $item->kategori_badge_class }}">
                                {{ $item->kategori_label }}
                            </span>
                        </td>
                        <td>
                            @if($item->youtube_id)
                                <span class="badge-sumber yt"><i class="bi bi-youtube"></i> YouTube</span>
                            @elseif($item->tipe_sumber === 'upload')
                                <span class="badge-sumber file"><i class="bi bi-file-earmark-play-fill"></i> File Video</span>
                            @else
                                <span class="badge-sumber link"><i class="bi bi-link-45deg"></i> Link URL</span>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: 700; color: var(--dash-slate-700);">
                            {{ $item->urutan }}
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('admin.video-tutorial.toggle', $item->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="badge-status {{ $item->is_active ? 'active' : 'inactive' }}" title="Klik untuk mengubah status aktif">
                                    <i class="bi {{ $item->is_active ? 'bi-check-circle-fill' : 'bi-dash-circle' }}"></i>
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 6px;">
                                <button type="button" class="btn-row-action" title="Putar Pratinjau Video" onclick="openPlayerModal('{{ addslashes($item->judul) }}', '{{ $item->embed_url }}', '{{ $item->tipe_sumber }}')">
                                    <i class="bi bi-play-fill" style="font-size: 16px;"></i>
                                </button>
                                <button type="button" class="btn-row-action" title="Edit Video Tutorial" onclick="openEditVideoModal({{ json_encode($item) }})">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <form action="{{ route('admin.video-tutorial.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus video tutorial ini?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-row-action btn-del" title="Hapus Video">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 48px 20px; color: var(--dash-slate-500);">
                            <div style="font-size: 42px; color: #cbd5e1; margin-bottom: 10px;">
                                <i class="bi bi-film"></i>
                            </div>
                            <div style="font-size: 15px; font-weight: 700; color: var(--bps-navy); margin-bottom: 4px;">
                                Belum Ada Video Tutorial
                            </div>
                            <p style="margin: 0; font-size: 13px;">
                                Klik tombol <strong>Tambah Video Baru</strong> di atas untuk menambahkan link YouTube atau upload video panduan.
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tutorials->hasPages())
        <div style="padding: 14px 20px; border-top: 1px solid var(--dash-slate-200);">
            {{ $tutorials->links() }}
        </div>
        @endif
    </div>

</div>

{{-- =========================================================
     MODAL TAMBAH VIDEO TUTORIAL
========================================================= --}}
<div class="modal-backdrop-custom" id="modalAddVideo" onclick="if(event.target === this) closeAddVideoModal()">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3 class="modal-title-custom">
                <i class="bi bi-camera-video-fill" style="color: #0284c7;"></i>
                <span>Tambah Video Tutorial Baru</span>
            </h3>
            <button type="button" class="modal-close-btn" onclick="closeAddVideoModal()">&times;</button>
        </div>

        <form action="{{ route('admin.video-tutorial.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body-custom">
                
                {{-- Judul --}}
                <div class="form-group-custom">
                    <label for="add_judul">Judul Video Tutorial <span class="req">*</span></label>
                    <input type="text" name="judul" id="add_judul" required class="form-control-custom" placeholder="Contoh: Panduan Pengajuan SPJ untuk Staf Teknis" value="{{ old('judul') }}">
                </div>

                {{-- Kategori --}}
                <div class="form-group-custom">
                    <label for="add_kategori">Kategori Sasaran Role <span class="req">*</span></label>
                    <select name="kategori" id="add_kategori" required class="form-control-custom">
                        <option value="semua">Semua Pengguna (Umum &amp; Pegawai)</option>
                        <option value="teknis">Staf Teknis (Pelaksana Kegiatan)</option>
                        <option value="umum">Subbagian Umum (Verifikator Fisik)</option>
                        <option value="ppk">Pejabat Pembuat Komitmen (PPK)</option>
                        <option value="ppspm">Pejabat Penandatangan SPM (PPSPM)</option>
                        <option value="bendahara">Bendahara Pengeluaran (Kas &amp; QLOLA)</option>
                    </select>
                </div>

                {{-- Tipe Sumber: Link vs Upload --}}
                <div class="form-group-custom">
                    <label>Sumber Video <span class="req">*</span></label>
                    <div class="source-segmented">
                        <label class="source-option-btn active" id="btnSourceLinkAdd" onclick="selectSourceType('add', 'link')">
                            <input type="radio" name="tipe_sumber" value="link" checked style="display: none;">
                            <i class="bi bi-youtube" style="color: #dc2626;"></i>
                            <span>Link Video / YouTube / Embed</span>
                        </label>
                        <label class="source-option-btn" id="btnSourceUploadAdd" onclick="selectSourceType('add', 'upload')">
                            <input type="radio" name="tipe_sumber" value="upload" style="display: none;">
                            <i class="bi bi-cloud-arrow-up-fill" style="color: #0284c7;"></i>
                            <span>Upload File Video (MP4)</span>
                        </label>
                    </div>
                </div>

                {{-- Input Link URL --}}
                <div class="form-group-custom" id="containerLinkAdd">
                    <label for="add_url_video">URL / Link Video (YouTube / Google Drive) <span class="req">*</span></label>
                    <input type="text" name="url_video" id="add_url_video" class="form-control-custom" placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." value="{{ old('url_video') }}">
                    <div class="hint">Mendukung link video YouTube biasa, YouTube Shorts, link embed, atau Google Drive preview link.</div>
                </div>

                {{-- Input File Video --}}
                <div class="form-group-custom" id="containerUploadAdd" style="display: none;">
                    <label for="add_file_video">Upload File Video (Maks. 100 MB) <span class="req">*</span></label>
                    <input type="file" name="file_video" id="add_file_video" class="form-control-custom" accept="video/mp4,video/webm,video/ogg">
                    <div class="hint">Format yang didukung: MP4, WebM, OGG. Ukuran maksimal 100 MB.</div>
                </div>

                {{-- Deskripsi --}}
                <div class="form-group-custom">
                    <label for="add_deskripsi">Deskripsi / Ringkasan Panduan</label>
                    <textarea name="deskripsi" id="add_deskripsi" rows="3" class="form-control-custom" placeholder="Ringkasan poin-poin yang dipelajari dalam video tutorial ini...">{{ old('deskripsi') }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    {{-- Durasi --}}
                    <div class="form-group-custom">
                        <label for="add_durasi">Durasi (Opsional)</label>
                        <input type="text" name="durasi" id="add_durasi" class="form-control-custom" placeholder="Contoh: 05:30" value="{{ old('durasi') }}">
                    </div>

                    {{-- Urutan --}}
                    <div class="form-group-custom">
                        <label for="add_urutan">Urutan Tampil</label>
                        <input type="number" name="urutan" id="add_urutan" class="form-control-custom" min="1" placeholder="1" value="{{ old('urutan', $stats['total'] + 1) }}">
                    </div>
                </div>

                {{-- Status Aktif Checkbox --}}
                <div style="margin-top: 8px;">
                    <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked style="width: 17px; height: 17px; accent-color: #0284c7;">
                        <span>Aktifkan dan tampilkan langsung di modal Panduan publik</span>
                    </label>
                </div>

            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-action-secondary" onclick="closeAddVideoModal()">Batal</button>
                <button type="submit" class="btn-action-primary">
                    <i class="bi bi-save2-fill"></i>
                    <span>Simpan Video Tutorial</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =========================================================
     MODAL EDIT VIDEO TUTORIAL
========================================================= --}}
<div class="modal-backdrop-custom" id="modalEditVideo" onclick="if(event.target === this) closeEditVideoModal()">
    <div class="modal-dialog-custom">
        <div class="modal-header-custom">
            <h3 class="modal-title-custom">
                <i class="bi bi-pencil-square" style="color: #0284c7;"></i>
                <span>Edit Video Tutorial</span>
            </h3>
            <button type="button" class="modal-close-btn" onclick="closeEditVideoModal()">&times;</button>
        </div>

        <form id="formEditVideo" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body-custom">
                
                {{-- Judul --}}
                <div class="form-group-custom">
                    <label for="edit_judul">Judul Video Tutorial <span class="req">*</span></label>
                    <input type="text" name="judul" id="edit_judul" required class="form-control-custom">
                </div>

                {{-- Kategori --}}
                <div class="form-group-custom">
                    <label for="edit_kategori">Kategori Sasaran Role <span class="req">*</span></label>
                    <select name="kategori" id="edit_kategori" required class="form-control-custom">
                        <option value="semua">Semua Pengguna (Umum &amp; Pegawai)</option>
                        <option value="teknis">Staf Teknis (Pelaksana Kegiatan)</option>
                        <option value="umum">Subbagian Umum (Verifikator Fisik)</option>
                        <option value="ppk">Pejabat Pembuat Komitmen (PPK)</option>
                        <option value="ppspm">Pejabat Penandatangan SPM (PPSPM)</option>
                        <option value="bendahara">Bendahara Pengeluaran (Kas &amp; QLOLA)</option>
                    </select>
                </div>

                {{-- Tipe Sumber: Link vs Upload --}}
                <div class="form-group-custom">
                    <label>Sumber Video <span class="req">*</span></label>
                    <div class="source-segmented">
                        <label class="source-option-btn" id="btnSourceLinkEdit" onclick="selectSourceType('edit', 'link')">
                            <input type="radio" name="tipe_sumber" id="edit_tipe_link" value="link" style="display: none;">
                            <i class="bi bi-youtube" style="color: #dc2626;"></i>
                            <span>Link Video / YouTube / Embed</span>
                        </label>
                        <label class="source-option-btn" id="btnSourceUploadEdit" onclick="selectSourceType('edit', 'upload')">
                            <input type="radio" name="tipe_sumber" id="edit_tipe_upload" value="upload" style="display: none;">
                            <i class="bi bi-cloud-arrow-up-fill" style="color: #0284c7;"></i>
                            <span>Upload File Video (MP4)</span>
                        </label>
                    </div>
                </div>

                {{-- Input Link URL --}}
                <div class="form-group-custom" id="containerLinkEdit">
                    <label for="edit_url_video">URL / Link Video (YouTube / Google Drive)</label>
                    <input type="text" name="url_video" id="edit_url_video" class="form-control-custom" placeholder="https://www.youtube.com/watch?v=...">
                    <div class="hint">Ubah link YouTube kapan saja untuk mengganti video tutorial yang ditampilkan.</div>
                </div>

                {{-- Input File Video --}}
                <div class="form-group-custom" id="containerUploadEdit" style="display: none;">
                    <label for="edit_file_video">Upload File Video Baru (Opsional)</label>
                    <input type="file" name="file_video" id="edit_file_video" class="form-control-custom" accept="video/mp4,video/webm,video/ogg">
                    <div class="hint" id="editCurrentFileInfo">Biarkan kosong jika tidak ingin mengganti file video saat ini.</div>
                </div>

                {{-- Deskripsi --}}
                <div class="form-group-custom">
                    <label for="edit_deskripsi">Deskripsi / Ringkasan Panduan</label>
                    <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="form-control-custom"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    {{-- Durasi --}}
                    <div class="form-group-custom">
                        <label for="edit_durasi">Durasi (Opsional)</label>
                        <input type="text" name="durasi" id="edit_durasi" class="form-control-custom" placeholder="05:30">
                    </div>

                    {{-- Urutan --}}
                    <div class="form-group-custom">
                        <label for="edit_urutan">Urutan Tampil</label>
                        <input type="number" name="urutan" id="edit_urutan" class="form-control-custom" min="1">
                    </div>
                </div>

                {{-- Status Aktif Checkbox --}}
                <div style="margin-top: 8px;">
                    <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" style="width: 17px; height: 17px; accent-color: #0284c7;">
                        <span>Aktifkan dan tampilkan di modal Panduan publik</span>
                    </label>
                </div>

            </div>

            <div class="modal-footer-custom">
                <button type="button" class="btn-action-secondary" onclick="closeEditVideoModal()">Batal</button>
                <button type="submit" class="btn-action-primary">
                    <i class="bi bi-save2-fill"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =========================================================
     MODAL PREVIEW PLAYER
========================================================= --}}
<div class="modal-backdrop-custom" id="modalPreviewPlayer" onclick="if(event.target === this) closePlayerModal()">
    <div class="modal-dialog-custom large">
        <div class="modal-header-custom">
            <h3 class="modal-title-custom" id="playerModalTitle">
                <i class="bi bi-play-circle-fill" style="color: #0284c7;"></i>
                <span>Putar Video Tutorial</span>
            </h3>
            <button type="button" class="modal-close-btn" onclick="closePlayerModal()">&times;</button>
        </div>
        <div class="modal-body-custom" style="padding: 18px;">
            <div class="video-player-container" id="playerContainer">
                {{-- Dynamic player inserted via JS --}}
            </div>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-action-secondary" onclick="closePlayerModal()">Tutup Pemutar</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function selectSourceType(modalPrefix, type) {
        const btnLink = document.getElementById(`btnSourceLink${modalPrefix.charAt(0).toUpperCase() + modalPrefix.slice(1)}`);
        const btnUpload = document.getElementById(`btnSourceUpload${modalPrefix.charAt(0).toUpperCase() + modalPrefix.slice(1)}`);
        const containerLink = document.getElementById(`containerLink${modalPrefix.charAt(0).toUpperCase() + modalPrefix.slice(1)}`);
        const containerUpload = document.getElementById(`containerUpload${modalPrefix.charAt(0).toUpperCase() + modalPrefix.slice(1)}`);

        if (type === 'link') {
            btnLink.classList.add('active');
            btnUpload.classList.remove('active');
            btnLink.querySelector('input').checked = true;
            containerLink.style.display = 'block';
            containerUpload.style.display = 'none';
        } else {
            btnUpload.classList.add('active');
            btnLink.classList.remove('active');
            btnUpload.querySelector('input').checked = true;
            containerUpload.style.display = 'block';
            containerLink.style.display = 'none';
        }
    }

    function openAddVideoModal() {
        selectSourceType('add', 'link');
        document.getElementById('modalAddVideo').style.display = 'flex';
    }

    function closeAddVideoModal() {
        document.getElementById('modalAddVideo').style.display = 'none';
    }

    function openEditVideoModal(item) {
        const form = document.getElementById('formEditVideo');
        form.action = `/admin/video-tutorial/${item.id}`;

        document.getElementById('edit_judul').value = item.judul || '';
        document.getElementById('edit_kategori').value = item.kategori || 'semua';
        document.getElementById('edit_url_video').value = item.url_video || '';
        document.getElementById('edit_deskripsi').value = item.deskripsi || '';
        document.getElementById('edit_durasi').value = item.durasi || '';
        document.getElementById('edit_urutan').value = item.urutan || 1;
        document.getElementById('edit_is_active').checked = !!item.is_active;

        selectSourceType('edit', item.tipe_sumber || 'link');

        const fileInfo = document.getElementById('editCurrentFileInfo');
        if (fileInfo) {
            fileInfo.textContent = item.file_video 
                ? `File saat ini: ${item.file_video}. Unggah berkas baru jika ingin mengganti.`
                : 'Biarkan kosong jika tidak ingin mengunggah file video baru.';
        }

        document.getElementById('modalEditVideo').style.display = 'flex';
    }

    function closeEditVideoModal() {
        document.getElementById('modalEditVideo').style.display = 'none';
    }

    function openPlayerModal(title, embedUrl, type) {
        document.getElementById('playerModalTitle').querySelector('span').textContent = title;
        const container = document.getElementById('playerContainer');

        if (!embedUrl) {
            container.innerHTML = '<div style="padding: 40px; color: #ffffff; text-align: center;">URL atau file video belum tersedia.</div>';
        } else if (type === 'upload') {
            container.innerHTML = `<video controls autoplay style="width: 100%; height: 100%;"><source src="${embedUrl}" type="video/mp4">Browser Anda tidak mendukung pemutar video HTML5.</video>`;
        } else {
            container.innerHTML = `<iframe src="${embedUrl}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        }

        document.getElementById('modalPreviewPlayer').style.display = 'flex';
    }

    function closePlayerModal() {
        const container = document.getElementById('playerContainer');
        container.innerHTML = '';
        document.getElementById('modalPreviewPlayer').style.display = 'none';
    }
</script>
@endpush

@endsection
