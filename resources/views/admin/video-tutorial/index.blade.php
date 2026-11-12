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

    /* 2-COLUMN MANAGEMENT LAYOUT */
    .video-mgmt-grid {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 20px;
        align-items: flex-start;
    }

    @media (max-width: 992px) {
        .video-mgmt-grid {
            grid-template-columns: 1fr;
        }
    }

    .mgmt-card {
        background: #ffffff;
        border: 1px solid var(--dash-slate-200);
        border-radius: var(--dash-radius-xl);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .mgmt-card-header {
        padding: 18px 22px;
        background: #f8fafc;
        border-bottom: 1px solid var(--dash-slate-200);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mgmt-card-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--bps-navy);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mgmt-card-body {
        padding: 22px;
    }

    /* PREVIEW PLAYER BOX */
    .preview-player-box {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 ratio */
        height: 0;
        background: #0f172a;
        border-radius: var(--dash-radius-lg);
        overflow: hidden;
        margin-bottom: 16px;
        border: 1px solid var(--dash-slate-200);
    }

    .preview-player-box video,
    .preview-player-box iframe {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: var(--dash-radius-lg);
    }

    .empty-player-state {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        padding: 20px;
        text-align: center;
    }

    .empty-player-state i {
        font-size: 48px;
        color: #475569;
        margin-bottom: 8px;
    }

    /* FORM STYLES */
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
        font-size: 12px;
        color: var(--dash-slate-500);
        margin-top: 4px;
        line-height: 1.4;
    }

    .form-control-custom {
        width: 100%;
        padding: 10px 14px;
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

    .source-tab-row {
        display: flex;
        gap: 10px;
        margin-bottom: 14px;
    }

    .source-tab-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px;
        border: 1.5px solid var(--dash-slate-200);
        border-radius: var(--dash-radius-md);
        background: #f8fafc;
        cursor: pointer;
        font-size: 13px;
        font-weight: 700;
        color: var(--dash-slate-600);
        transition: all 0.2s;
    }

    .source-tab-btn.active {
        background: #e0f2fe;
        border-color: #0284c7;
        color: #0369a1;
    }

    .file-upload-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: var(--dash-radius-lg);
        padding: 24px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s;
    }

    .file-upload-dropzone:hover {
        border-color: #0284c7;
        background: #f0f9ff;
    }

    .file-upload-dropzone i {
        font-size: 36px;
        color: #0284c7;
        margin-bottom: 8px;
        display: inline-block;
    }

    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .badge-pill.active { background: #dcfce7; color: #15803d; }
    .badge-pill.inactive { background: #f1f5f9; color: #94a3b8; }
    .badge-pill.source { background: #e0f2fe; color: #0284c7; }
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
            <i class="bi bi-exclamation-triangle-fill"></i> Terdapat kendala saat menyimpan:
        </div>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- HEADER HERO --}}
    <div class="video-header-box">
        <div class="video-header-info">
            <div class="video-header-icon">
                <i class="bi bi-play-btn-fill"></i>
            </div>
            <div>
                <h1 class="video-header-title">Kelola Video Tutorial Panduan SPJ</h1>
                <p class="video-header-desc">
                    Unggah satu video tutorial utama yang ditampilkan secara langsung pada menu <strong>PANDUAN</strong> di halaman beranda.
                </p>
            </div>
        </div>
        <div class="video-header-actions">
            <a href="{{ route('home') }}#panduan" target="_blank" class="btn-action-secondary" title="Lihat tampilan modal panduan di beranda">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Lihat di Beranda</span>
            </a>
        </div>
    </div>

    {{-- 2-COLUMN MANAGEMENT LAYOUT --}}
    <div class="video-mgmt-grid">
        
        {{-- LEFT COLUMN: CURRENT ACTIVE VIDEO PREVIEW --}}
        <div class="mgmt-card">
            <div class="mgmt-card-header">
                <h3 class="mgmt-card-title">
                    <i class="bi bi-tv" style="color: #0284c7;"></i>
                    <span>Pratinjau Video Tutorial Aktif</span>
                </h3>
                @if($video && ($video->file_video || $video->url_video))
                    <span class="badge-pill {{ $video->is_active ? 'active' : 'inactive' }}">
                        <i class="bi {{ $video->is_active ? 'bi-check-circle-fill' : 'bi-dash-circle' }}"></i>
                        {{ $video->is_active ? 'Tayang di Publik' : 'Disembunyikan' }}
                    </span>
                @endif
            </div>
            <div class="mgmt-card-body">
                
                {{-- Video Player Box --}}
                <div class="preview-player-box">
                    @if($video && $video->embed_url)
                        @if($video->tipe_sumber === 'upload' && $video->file_video)
                            <video controls playsinline preload="metadata">
                                <source src="{{ $video->embed_url }}" type="video/mp4">
                                Browser Anda tidak mendukung video tag.
                            </video>
                        @else
                            <iframe src="{{ $video->embed_url }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @endif
                    @else
                        <div class="empty-player-state">
                            <i class="bi bi-camera-video-off"></i>
                            <strong style="font-size: 14px; color: #cbd5e1; margin-bottom: 4px;">Belum Ada Video yang Diunggah</strong>
                            <p style="font-size: 12px; margin: 0; max-width: 300px;">
                                Silakan gunakan formulir di samping untuk mengunggah file video MP4 atau memasukkan tautan video panduan.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Detail Meta Video Saat Ini --}}
                @if($video && ($video->file_video || $video->url_video))
                    <div style="background: #f8fafc; border: 1px solid var(--dash-slate-200); border-radius: var(--dash-radius-lg); padding: 16px; margin-bottom: 16px;">
                        <div style="font-size: 16px; font-weight: 800; color: var(--bps-navy); margin-bottom: 8px;">
                            {{ $video->judul }}
                        </div>

                        <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px;">
                            @if($video->tipe_sumber === 'upload')
                                <span class="badge-pill source">
                                    <i class="bi bi-file-earmark-play-fill"></i> Berkas Video Lokal (MP4)
                                </span>
                            @else
                                <span class="badge-pill source">
                                    <i class="bi bi-youtube"></i> Tautan Video / YouTube
                                </span>
                            @endif

                            @if($video->durasi)
                                <span class="badge-pill" style="background: #f1f5f9; color: var(--dash-slate-600);">
                                    <i class="bi bi-clock"></i> {{ $video->durasi }}
                                </span>
                            @endif

                            <span class="badge-pill" style="background: #f1f5f9; color: var(--dash-slate-500); font-size: 11px;">
                                Terakhir disimpan: {{ $video->updated_at ? $video->updated_at->format('d M Y, H:i') : '—' }} WITA
                            </span>
                        </div>

                        <p style="font-size: 12.5px; color: var(--dash-slate-600); margin: 0; line-height: 1.5;">
                            {{ $video->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}
                        </p>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <form action="{{ route('admin.video-tutorial.destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus video tutorial ini? Pengguna publik tidak akan melihat video hingga diunggah kembali.');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action-secondary" style="color: #dc2626; border-color: #fecaca; background: #fff5f5;">
                                <i class="bi bi-trash-fill"></i>
                                <span>Hapus Video Saat Ini</span>
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>

        {{-- RIGHT COLUMN: UPLOAD / UPDATE FORM --}}
        <div class="mgmt-card">
            <div class="mgmt-card-header">
                <h3 class="mgmt-card-title">
                    <i class="bi bi-cloud-arrow-up-fill" style="color: #0284c7;"></i>
                    <span>{{ $video && ($video->file_video || $video->url_video) ? 'Ganti / Perbarui Video' : 'Unggah Video Tutorial' }}</span>
                </h3>
            </div>
            <div class="mgmt-card-body">
                <form action="{{ route('admin.video-tutorial.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Judul Video --}}
                    <div class="form-group-custom">
                        <label for="judul">Judul Video Panduan <span class="req">*</span></label>
                        <input type="text" name="judul" id="judul" required class="form-control-custom" placeholder="Contoh: Video Tutorial Tata Laksana SPJ Digital BPS Kolaka Utara" value="{{ old('judul', $video->judul ?? 'Video Tutorial Panduan SPJ Digital') }}">
                    </div>

                    {{-- Sumber Video Selector --}}
                    <div class="form-group-custom">
                        <label>Pilih Metode Sumber Video <span class="req">*</span></label>
                        <div class="source-tab-row">
                            <label class="source-tab-btn {{ old('tipe_sumber', $video->tipe_sumber ?? 'upload') === 'upload' ? 'active' : '' }}" id="tabBtnUpload" onclick="setSourceTab('upload')">
                                <input type="radio" name="tipe_sumber" value="upload" {{ old('tipe_sumber', $video->tipe_sumber ?? 'upload') === 'upload' ? 'checked' : '' }} style="display: none;">
                                <i class="bi bi-cloud-arrow-up-fill" style="color: #0284c7;"></i>
                                <span>Upload File Video (MP4)</span>
                            </label>
                            <label class="source-tab-btn {{ old('tipe_sumber', $video->tipe_sumber ?? 'upload') === 'link' ? 'active' : '' }}" id="tabBtnLink" onclick="setSourceTab('link')">
                                <input type="radio" name="tipe_sumber" value="link" {{ old('tipe_sumber', $video->tipe_sumber ?? 'upload') === 'link' ? 'checked' : '' }} style="display: none;">
                                <i class="bi bi-youtube" style="color: #dc2626;"></i>
                                <span>Tautan YouTube / Embed</span>
                            </label>
                        </div>
                    </div>

                    {{-- Container Upload File Video --}}
                    <div class="form-group-custom" id="containerUpload">
                        <label for="file_video">Unggah Berkas Video {{ $video && $video->file_video ? '(Opsional: Isi hanya jika ingin mengganti)' : '' }}</label>
                        <div class="file-upload-dropzone" onclick="document.getElementById('file_video').click()">
                            <i class="bi bi-film"></i>
                            <div style="font-weight: 700; font-size: 13.5px; color: var(--bps-navy); margin-bottom: 2px;">
                                Klik di sini untuk memilih berkas video dari komputer
                            </div>
                            <div style="font-size: 12px; color: var(--dash-slate-500);">
                                Format didukung: <strong>MP4, WebM, OGG, MOV</strong> (Maksimal 200 MB)
                            </div>
                            <div id="selectedFileName" style="margin-top: 8px; font-weight: 700; color: #0284c7; font-size: 13px; display: none;"></div>
                        </div>
                        <input type="file" name="file_video" id="file_video" accept="video/mp4,video/webm,video/ogg,video/quicktime" style="display: none;" onchange="handleFileSelect(this)">
                        @if($video && $video->file_video)
                            <div class="hint" style="color: #059669; font-weight: 600; margin-top: 6px;">
                                <i class="bi bi-check-circle"></i> Berkas video saat ini tersimpan: <code>{{ basename($video->file_video) }}</code>
                            </div>
                        @endif
                    </div>

                    {{-- Container Input Link URL --}}
                    <div class="form-group-custom" id="containerLink" style="display: none;">
                        <label for="url_video">Tautan / URL Video (YouTube / Google Drive)</label>
                        <input type="text" name="url_video" id="url_video" class="form-control-custom" placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." value="{{ old('url_video', $video->url_video ?? '') }}">
                        <div class="hint">
                            Mendukung tautan video YouTube standar, YouTube Shorts, atau Google Drive preview link.
                        </div>
                    </div>

                    {{-- Durasi --}}
                    <div class="form-group-custom">
                        <label for="durasi">Estimasi Durasi Video (Opsional)</label>
                        <input type="text" name="durasi" id="durasi" class="form-control-custom" placeholder="Contoh: 08:30" value="{{ old('durasi', $video->durasi ?? '') }}">
                    </div>

                    {{-- Deskripsi Panduan --}}
                    <div class="form-group-custom">
                        <label for="deskripsi">Deskripsi / Ringkasan Isi Video</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control-custom" placeholder="Tuliskan ringkasan materi atau petunjuk singkat yang dibahas dalam video panduan ini...">{{ old('deskripsi', $video->deskripsi ?? '') }}</textarea>
                    </div>

                    {{-- Checkbox Status Aktif --}}
                    <div style="background: #f8fafc; border: 1px solid var(--dash-slate-200); border-radius: var(--dash-radius-md); padding: 12px 14px; margin-bottom: 20px;">
                        <label style="display: inline-flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 700; color: var(--dash-slate-700); cursor: pointer; margin: 0;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $video->is_active ?? true) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #0284c7;">
                            <span>Aktifkan &amp; Tampilkan Video di Menu Panduan Beranda</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn-action-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 14px;">
                        <i class="bi bi-cloud-check-fill" style="font-size: 18px;"></i>
                        <span>Simpan &amp; Terapkan Video Panduan</span>
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function setSourceTab(type) {
        const btnUpload = document.getElementById('tabBtnUpload');
        const btnLink = document.getElementById('tabBtnLink');
        const containerUpload = document.getElementById('containerUpload');
        const containerLink = document.getElementById('containerLink');

        if (type === 'upload') {
            btnUpload.classList.add('active');
            btnLink.classList.remove('active');
            btnUpload.querySelector('input').checked = true;
            containerUpload.style.display = 'block';
            containerLink.style.display = 'none';
        } else {
            btnLink.classList.add('active');
            btnUpload.classList.remove('active');
            btnLink.querySelector('input').checked = true;
            containerLink.style.display = 'block';
            containerUpload.style.display = 'none';
        }
    }

    function handleFileSelect(input) {
        const nameDisplay = document.getElementById('selectedFileName');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            nameDisplay.textContent = `Berkas dipilih: ${file.name} (${sizeMB} MB)`;
            nameDisplay.style.display = 'block';
        } else {
            nameDisplay.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const initialType = "{{ old('tipe_sumber', $video->tipe_sumber ?? 'upload') }}";
        setSourceTab(initialType);
    });
</script>
@endpush

@endsection
