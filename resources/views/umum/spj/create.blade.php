@extends('layouts.umum')

@php
$title = 'Pengajuan SPJ Baru';
$subtitle = 'Formulir pengajuan Surat Pertanggungjawaban (SPJ) Subbagian Umum.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
    <style>
        .spj-kategori-section {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: var(--radius-md, 12px);
            border: 1px solid var(--border, #e2edf5);
            margin-bottom: 1.5rem;
            transition: var(--transition, all 0.25s ease);
        }
        .spj-kategori-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dash-text-dark, #0f172a);
        }
        .spj-kategori-sub {
            font-size: 0.75rem;
            color: var(--dash-text-muted, #64748b);
            display: block;
        }
        .spj-field-hint {
            font-size: 0.75rem;
            color: var(--dash-text-muted, #64748b);
            margin-top: 0.25rem;
            display: block;
        }
        .spj-input-readonly {
            background-color: #f8fafc !important;
            font-weight: 700 !important;
            color: var(--dash-text-dark, #0f172a) !important;
            cursor: not-allowed !important;
            border-color: var(--dash-border, #cbdde8) !important;
        }
        .spj-badge-auto {
            font-size: 0.72rem;
            color: #0284c7;
            background: #e0f2fe;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
            display: inline-block;
        }
        .spj-badge-optional {
            font-size: 0.72rem;
            font-weight: normal;
            color: var(--dash-text-muted, #64748b);
            background: #f1f5f9;
            padding: 2px 7px;
            border-radius: 4px;
        }
        .spj-doc-checkbox-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--dash-text-dark, #0f172a);
        }
        .spj-hint-box {
            margin-top: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-md, 12px);
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #c2410c;
            font-size: 0.825rem;
        }
        .spj-banner-printed {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            margin-top: 1.25rem;
            padding: 0.85rem 1.25rem;
            border-radius: var(--radius-md, 12px);
            font-size: 0.875rem;
            font-weight: 500;
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }
        .upload-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: var(--dash-card-bg, #ffffff);
            border: 1px solid var(--dash-border, #e2edf5);
            border-radius: var(--radius-md, 12px);
            margin-bottom: 0.75rem;
            transition: var(--transition, all 0.25s ease);
        }
        .upload-item:hover {
            border-color: #0284c7;
            box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.15);
        }
        .upload-info {
            flex: 1;
        }
        .upload-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dash-text-dark, #1c3f52);
            display: block;
            margin-bottom: 0.25rem;
        }
        .upload-desc {
            font-size: 0.75rem;
            color: var(--dash-text-muted, #768f9e);
        }
        .upload-action {
            margin-left: 1rem;
        }
        .badge-assignee {
            font-size: 0.7rem;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            background: var(--dash-cyan-badge, #ddf2f7);
            color: var(--dash-cyan-text, #0284c7);
            margin-left: 0.5rem;
            display: inline-block;
            font-weight: 600;
        }
        .badge-bendahara {
            background: var(--dash-peach-badge, #fed7aa);
            color: var(--dash-peach-text, #ea580c);
        }
        .badge-ppk {
            background: #fce7f3;
            color: #db2777;
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

        .dashboard-heading {
            display: flex !important;
            justify-content: space-between !important;
            align-items: flex-end !important;
            margin-bottom: 1.75rem !important;
            padding-top: 0.5rem !important;
            gap: 1rem !important;
            flex-wrap: wrap !important;
        }
        .dashboard-heading-title {
            font-size: 1.55rem !important;
            font-weight: 800 !important;
            color: var(--dash-text-dark, #0f172a) !important;
            margin-bottom: 0.25rem !important;
            letter-spacing: -0.02em !important;
            line-height: 1.25 !important;
        }
        .dashboard-heading-subtitle {
            font-size: 0.9rem !important;
            color: var(--dash-text-muted, #64748b) !important;
            margin: 0 !important;
            line-height: 1.4 !important;
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
            white-space: nowrap;
        }
        .btn-dash-back:hover {
            background: var(--dash-table-header-bg, #f8fafb);
            border-color: #cbdde8;
            transform: translateY(-1px);
            color: #0284c7;
        }

        @media (max-width: 640px) {
            .dashboard-heading {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.75rem !important;
            }
            .btn-dash-back {
                width: 100% !important;
                justify-content: center !important;
            }
        }

        /* Dark Theme Specific Overrides */
        html.dark-theme .spj-panel {
            background: var(--dash-card-bg, #142530);
            border-color: var(--dash-border, #1f3747);
        }
        html.dark-theme .spj-panel-header,
        html.dark-theme .spj-panel-body {
            background: var(--dash-card-bg, #142530);
        }
        html.dark-theme .spj-kategori-section {
            background: #0f1c25 !important;
            border-color: #1f3747 !important;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        html.dark-theme .spj-kategori-title {
            color: var(--dash-text-dark, #f0f6fa) !important;
        }
        html.dark-theme .spj-kategori-sub,
        html.dark-theme .spj-field-hint {
            color: var(--dash-text-muted, #8ea8b8) !important;
        }
        html.dark-theme .upload-item {
            background: #142530 !important;
            border-color: #1f3747 !important;
        }
        html.dark-theme .upload-item:hover {
            background: #182e3c !important;
            border-color: #38bdf8 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
        }
        html.dark-theme .upload-label {
            color: var(--dash-text-dark, #f0f6fa) !important;
        }
        html.dark-theme .upload-desc {
            color: var(--dash-text-muted, #8ea8b8) !important;
        }
        html.dark-theme .spj-doc-checkbox-label {
            color: var(--dash-text-dark, #f0f6fa) !important;
        }
        html.dark-theme .spj-input-readonly {
            background-color: #0d1a22 !important;
            color: #f0f6fa !important;
            border-color: #1f3747 !important;
        }
        html.dark-theme .spj-badge-auto {
            color: #38bdf8 !important;
            background: rgba(2, 132, 199, 0.22) !important;
            border: 1px solid rgba(56, 189, 248, 0.25);
        }
        html.dark-theme .spj-badge-optional {
            color: var(--dash-text-muted, #8ea8b8) !important;
            background: #0d1a22 !important;
            border: 1px solid #1f3747;
        }
        html.dark-theme .badge-ppk {
            background: rgba(219, 39, 119, 0.2) !important;
            color: #f472b6 !important;
            border: 1px solid rgba(244, 114, 182, 0.25);
        }
        html.dark-theme .spj-hint-box {
            background: rgba(234, 88, 12, 0.16) !important;
            border-color: rgba(234, 88, 12, 0.35) !important;
            color: #fb923c !important;
        }
        html.dark-theme .spj-banner-printed {
            background-color: rgba(16, 185, 129, 0.16) !important;
            color: #6ee7b7 !important;
            border-color: rgba(16, 185, 129, 0.35) !important;
        }
        html.dark-theme .spj-kategori-section .spj-btn-secondary {
            background: #142530;
            color: #f0f6fa;
            border-color: #1f3747;
        }
        html.dark-theme .spj-kategori-section .spj-btn-secondary:hover {
            background: #1b3240;
            color: #ffffff;
            border-color: #38bdf8;
        }
        html.dark-theme .btn-dash-back {
            background: #142530;
            color: #f0f6fa;
            border-color: #1f3747;
        }
        html.dark-theme .btn-dash-back:hover {
            background: #1b3240;
            color: #38bdf8;
            border-color: #38bdf8;
        }
        html.dark-theme select.spj-input,
        html.dark-theme input.spj-input,
        html.dark-theme textarea.spj-input {
            background-color: #12212b;
            color: #f0f6fa;
            border-color: #243e4f;
            color-scheme: dark;
        }
        html.dark-theme select.spj-input:focus,
        html.dark-theme input.spj-input:focus,
        html.dark-theme textarea.spj-input:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.18);
        }
        html.dark-theme select.spj-input option {
            background-color: #142530;
            color: #f0f6fa;
        }
        html.dark-theme .spj-radio-content {
            background: #142530;
            border-color: #1f3747;
        }
        html.dark-theme .spj-radio-card input:checked + .spj-radio-content {
            border-color: #38bdf8;
            background: rgba(2, 132, 199, 0.2);
        }
        html.dark-theme .spj-label {
            color: var(--dash-text-dark, #f0f6fa);
        }
    </style>
@endpush

<div class="dashboard-heading">
    <div>
        <h2 class="dashboard-heading-title">Formulir Pengajuan SPJ Baru</h2>
        <p class="dashboard-heading-subtitle">Isi rincian kegiatan belanja dan siapkan kelengkapan berkas fisik sebelum diajukan.</p>
    </div>
    <a href="{{ route('umum.spj.index') }}" class="btn-dash-back" onclick="clearFormState()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Kembali ke Antrean
    </a>
</div>

<div class="spj-panel" style="animation-delay: 0.1s;">
    <div class="spj-panel-header">
        <div>
            <h2>Detail Pengajuan</h2>
            <p>Pastikan data yang dimasukkan valid dan sesuai bukti fisik.</p>
        </div>
    </div>
    
    <div class="spj-panel-body">
        <form action="{{ route('umum.spj.store') }}" method="POST" id="formPengajuanSpj">
            @csrf

            <!-- 1. KATEGORI & KELENGKAPAN BERKAS (ALUR SAMA PERSIS DENGAN TEKNIS) -->
            <div class="spj-kategori-section">
                <h3 class="spj-kategori-title">Kategori &amp; Kelengkapan Berkas</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label class="spj-label" for="nama_belanja">Nama Belanja</label>
                        <select id="nama_belanja" name="nama_belanja" class="spj-input" required onchange="handleNamaBelanjaChange()">
                            <option value="" disabled selected>Pilih Nama Belanja...</option>
                        </select>
                    </div>

                    <div>
                        <label class="spj-label" for="jenis_belanja">Jenis Belanja</label>
                        <select id="jenis_belanja" name="jenis_belanja" class="spj-input" required disabled onchange="handleJenisBelanjaChange()">
                            <option value="" disabled selected>Pilih Jenis Belanja...</option>
                        </select>
                    </div>
                </div>

                <div id="dokumen_container" style="display: none;">
                    <div style="margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <label class="spj-label" style="margin-bottom: 0;">Dokumen Wajib</label>
                            <span class="spj-kategori-sub">Centang berkas fisik yang sudah disiapkan</span>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="button" onclick="centangSemuaBerkas(true)" class="spj-btn spj-btn-secondary" style="padding: 0.25rem 0.65rem; font-size: 0.75rem; height: auto;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 2px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Centang Semua
                            </button>
                            <button type="button" onclick="centangSemuaBerkas(false)" class="spj-btn spj-btn-secondary" style="padding: 0.25rem 0.65rem; font-size: 0.75rem; height: auto; opacity: 0.85;">
                                Batal Semua
                            </button>
                        </div>
                    </div>
                    <div id="dokumen_list">
                        <!-- File inputs / checklists will be generated here -->
                    </div>
                </div>
            </div>
            <!-- DYNAMIC DROPDOWNS END -->

            <!-- 2. IDENTITAS KEGIATAN & ANGGARAN -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;" class="spj-form-group">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
                        <label class="spj-label" for="nomor_spj_input" style="margin-bottom: 0;">Nomor SPJ</label>
                        <span class="spj-badge-auto">Otomatis Sistem</span>
                    </div>
                    <input type="text" name="nomor_spj" id="nomor_spj_input" class="spj-input spj-input-readonly" value="{{ old('nomor_spj', $nomorSpjOtomatis ?? '') }}" placeholder="Digenerate otomatis oleh sistem" readonly title="Nomor SPJ dibuat otomatis oleh sistem">
                    <span class="spj-field-hint">Nomor registrasi SPJ digenerate secara otomatis oleh sistem.</span>
                    @error('nomor_spj') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="spj-label" for="nilai_display">Nilai (Rp) <span style="color: #ef4444;">*</span></label>
                    <div class="spj-input-prefix-group">
                        <span class="spj-input-prefix">Rp</span>
                        @php
                            $oldNilai = old('nilai');
                            $formattedOldNilai = '';
                            if ($oldNilai !== null && $oldNilai !== '') {
                                $cleanOld = is_numeric($oldNilai) ? (float) $oldNilai : (float) str_replace(',', '.', str_replace('.', '', $oldNilai));
                                $hasDecimals = floor($cleanOld) != $cleanOld;
                                $formattedOldNilai = number_format($cleanOld, $hasDecimals ? 2 : 0, ',', '.');
                            }
                        @endphp
                        <input type="text" 
                               id="nilai_display" 
                               class="spj-input spj-input-with-prefix" 
                               value="{{ $formattedOldNilai }}" 
                               placeholder="Contoh: 1.500.000" 
                               inputmode="numeric" 
                               autocomplete="off" 
                               required>
                        <input type="hidden" name="nilai" id="nilai_input" value="{{ old('nilai') }}">
                    </div>
                    <span class="spj-field-hint" id="nilai_terbilang_hint">Pemisah ribuan (titik) ditambahkan otomatis saat mengetik.</span>
                    @error('nilai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;" class="spj-form-group">
                <div style="grid-column: span 2;">
                    <label class="spj-label" for="kegiatan_input">Uraian Kegiatan <span style="color: #ef4444;">*</span></label>
                    <textarea name="kegiatan" required class="spj-input spj-textarea" placeholder="Contoh: Pengadaan Alat Tulis Kantor Subbagian Umum..." id="kegiatan_input">{{ old('kegiatan') }}</textarea>
                    @error('kegiatan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="spj-label" for="tanggal_mulai_input">
                        Tanggal Mulai Kegiatan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal_mulai_input" required class="spj-input @error('tanggal') has-error @enderror" value="{{ old('tanggal', date('Y-m-d')) }}">
                    <span class="spj-field-hint">Tanggal dimulainya pelaksanaan kegiatan.</span>
                    @error('tanggal') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="spj-label" for="tanggal_selesai_input" style="display: flex; justify-content: space-between; align-items: center;">
                        <span>Tanggal Selesai Kegiatan</span>
                        <span class="spj-badge-optional">Opsional</span>
                    </label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai_input" class="spj-input @error('tanggal_selesai') has-error @enderror" value="{{ old('tanggal_selesai') }}" min="{{ old('tanggal', date('Y-m-d')) }}">
                    <span class="spj-field-hint">Kosongkan jika kegiatan hanya berlangsung 1 hari.</span>
                    @error('tanggal_selesai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <input type="hidden" name="seksi" id="seksi_input" value="{{ old('seksi', 'Subbag Umum') }}">

                <div style="grid-column: span 2;">
                    <label class="spj-label" for="no_wa_input">Nomor WhatsApp Pengaju / PIC</label>
                    <input type="text" name="no_wa" id="no_wa_input" class="spj-input @error('no_wa') has-error @enderror" value="{{ old('no_wa', Auth::user()->no_wa ?? '') }}" placeholder="Contoh: 081234567890">
                    <span class="spj-field-hint">Nomor WhatsApp aktif untuk menerima pembaruan status SPJ.</span>
                    @error('no_wa') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="spj-form-group" style="margin-top: 1.5rem;">
                <label class="spj-label" for="keterangan_input">Keterangan Tambahan</label>
                <textarea name="keterangan" id="keterangan_input" class="spj-input spj-textarea" placeholder="Opsional: Keterangan tambahan untuk verifikator atau bendahara...">{{ old('keterangan') }}</textarea>
                @error('keterangan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <!-- 3. TINDAKAN LANJUTAN -->
            <div class="spj-form-group" style="margin-top: 2rem;">
                <label class="spj-label">Tindakan Lanjutan</label>
                <div class="spj-radio-group">
                    <label class="spj-radio-card">
                        <input type="radio" name="status" value="draft" {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                        <div class="spj-radio-content">
                            <strong>Simpan sebagai Draft</strong>
                            <span>Dapat diubah kembali nanti sebelum diajukan</span>
                        </div>
                    </label>
                    <label class="spj-radio-card">
                        <input type="radio" name="status" value="diajukan" {{ old('status') === 'diajukan' ? 'checked' : '' }}>
                        <div class="spj-radio-content">
                            <strong>Ajukan Sekarang ke Alur SPJ</strong>
                            <span>Simpan pengajuan dan teruskan ke alur pemeriksaan berkas</span>
                        </div>
                    </label>
                </div>
                @error('status') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror

                <div id="print_status_banner" class="spj-banner-printed" style="display: none;"></div>

                <div id="print_requirement_hint" class="spj-hint-box" style="display: none;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span><strong>Wajib Print:</strong> Anda memilih opsi <strong>Ajukan Sekarang</strong>. Sesuai ketentuan, Anda <u>wajib menekan tombol Print</u> dan mencetak lembar checklist berkas SPJ terlebih dahulu sebelum pengajuan dapat disimpan.</span>
                    </div>
                </div>

                <input type="hidden" name="is_printed" id="is_printed_input" value="{{ old('is_printed', '0') }}">
            </div>

            <!-- BUTTONS -->
            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 1rem; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border, #e2edf5); flex-wrap: wrap;">
                <a href="{{ route('umum.spj.index') }}" class="spj-btn spj-btn-secondary" onclick="clearFormState()">Batal</a>
                <button type="button" id="btn_cetak_langsung" onclick="cetakLangsung()" class="spj-btn spj-btn-print" title="Cetak langsung lembar checklist berkas fisik SPJ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    <span id="btn_cetak_text">Print</span>
                </button>
                <button type="submit" id="btn_submit_spj" class="spj-btn spj-btn-primary" style="transition: all 0.25s ease;">
                    <span id="btn_submit_icon" style="display: inline-flex; align-items: center; margin-right: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    </span>
                    <span id="btn_submit_text">Simpan Pengajuan</span>
                </button>
            </div>
        </form>
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
        sessionStorage.removeItem('umum_spj_form_berkas');
    }

    function saveBerkasState() {
        const checkboxes = document.querySelectorAll('input[name^="berkas["]');
        const state = {};
        checkboxes.forEach(cb => {
            const match = cb.name.match(/\[(\d+)\]/);
            if (match) {
                state[match[1]] = cb.checked ? 1 : 0;
            }
        });
        sessionStorage.setItem('umum_spj_form_berkas', JSON.stringify(state));
    }

    function centangSemuaBerkas(val = true) {
        const checkboxes = document.querySelectorAll('input[name^="berkas["]');
        checkboxes.forEach(cb => {
            cb.checked = val;
        });
        saveBerkasState();
    }

    function renderDokumenList(nama, jenis, savedCheckboxes = null) {
        const docContainer = document.getElementById("dokumen_container");
        const docList = document.getElementById("dokumen_list");
        if (!docContainer || !docList) return;

        docList.innerHTML = "";

        if (nama && jenis && spjData[nama] && spjData[nama][jenis]) {
            docContainer.style.display = "block";
            const docs = spjData[nama][jenis];
            
            docs.forEach((doc, idx) => {
                let badgeHtml = '';
                let uploadHtml = '';
                
                let isChecked = false;
                if (savedCheckboxes) {
                    isChecked = savedCheckboxes[idx] == 1 || savedCheckboxes[idx] === true || savedCheckboxes[idx] === '1';
                } else {
                    isChecked = true;
                }

                if (doc.assignee.includes('Bendahara')) {
                    badgeHtml = '<span class="badge-assignee badge-bendahara">Dilengkapi Bendahara</span>';
                    uploadHtml = '<span style="font-size: 0.8rem; color: var(--dash-text-muted, #94a3b8); font-style: italic;">Tidak perlu (Diurus Bendahara)</span>';
                } else if (doc.assignee.includes('PPK')) {
                    badgeHtml = '<span class="badge-assignee badge-ppk">Dilengkapi PPK</span>';
                    uploadHtml = '<span style="font-size: 0.8rem; color: var(--dash-text-muted, #94a3b8); font-style: italic;">Tidak perlu (Diurus PPK)</span>';
                } else {
                    badgeHtml = '<span class="badge-assignee">Wajib Disiapkan (Fisik)</span>';
                    uploadHtml = `
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="berkas[${idx}]" value="1" ${isChecked ? 'checked' : ''} onchange="saveBerkasState()" style="width: 1.25rem; height: 1.25rem; accent-color: var(--primary, #0284c7);">
                        <span class="spj-doc-checkbox-label">Ada (Fisik)</span>
                    </label>
                    `;
                }

                const item = document.createElement("div");
                item.className = "upload-item";
                item.innerHTML = `
                    <div class="upload-info">
                        <div style="display: flex; align-items: center; margin-bottom: 0.25rem;">
                            <span class="upload-label" style="margin: 0;">${doc.name}</span>
                            ${badgeHtml}
                        </div>
                        <input type="hidden" name="nama_dokumen[${idx}]" value="${doc.name}">
                        <input type="hidden" name="assignee_dokumen[${idx}]" value="${doc.assignee}">
                    </div>
                    <div class="upload-action">
                        ${uploadHtml}
                    </div>
                `;
                docList.appendChild(item);
            });
        } else {
            docContainer.style.display = "none";
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const selNamaBelanja = document.getElementById("nama_belanja");
        const selJenisBelanja = document.getElementById("jenis_belanja");
        
        // Populate first dropdown
        if (selNamaBelanja) {
            for (const nama in spjData) {
                const opt = document.createElement("option");
                opt.value = nama;
                opt.textContent = nama;
                selNamaBelanja.appendChild(opt);
            }
        }

        // Pulihkan pilihan form yang sudah dipilih agar tidak reset
        const activeNama = oldNamaBelanja || sessionStorage.getItem('umum_spj_form_nama_belanja') || '';
        const activeJenis = oldJenisBelanja || sessionStorage.getItem('umum_spj_form_jenis_belanja') || '';
        let activeBerkas = (oldBerkas && Object.keys(oldBerkas).length > 0)
            ? oldBerkas
            : JSON.parse(sessionStorage.getItem('umum_spj_form_berkas') || '{}');

        if (activeNama && spjData[activeNama] && selNamaBelanja) {
            selNamaBelanja.value = activeNama;

            if (selJenisBelanja) {
                selJenisBelanja.innerHTML = '<option value="" disabled>Pilih Jenis Belanja...</option>';
                selJenisBelanja.disabled = false;
                for (const jenis in spjData[activeNama]) {
                    const opt = document.createElement("option");
                    opt.value = jenis;
                    opt.textContent = jenis;
                    if (jenis === activeJenis) {
                        opt.selected = true;
                    }
                    selJenisBelanja.appendChild(opt);
                }
            }

            if (activeJenis && spjData[activeNama][activeJenis]) {
                renderDokumenList(activeNama, activeJenis, activeBerkas);
            }
        }
    });

    function handleNamaBelanjaChange() {
        resetPrintStatus();
        const nama = document.getElementById("nama_belanja").value;
        const selJenisBelanja = document.getElementById("jenis_belanja");
        const docContainer = document.getElementById("dokumen_container");
        
        sessionStorage.setItem('umum_spj_form_nama_belanja', nama);
        sessionStorage.removeItem('umum_spj_form_jenis_belanja');
        sessionStorage.removeItem('umum_spj_form_berkas');

        // Reset and enable 2nd dropdown
        selJenisBelanja.innerHTML = '<option value="" disabled selected>Pilih Jenis Belanja...</option>';
        docContainer.style.display = "none";

        if (nama && spjData[nama]) {
            selJenisBelanja.disabled = false;
            for (const jenis in spjData[nama]) {
                const opt = document.createElement("option");
                opt.value = jenis;
                opt.textContent = jenis;
                selJenisBelanja.appendChild(opt);
            }
        } else {
            selJenisBelanja.disabled = true;
        }
    }

    function handleJenisBelanjaChange() {
        resetPrintStatus();
        const nama = document.getElementById("nama_belanja").value;
        const jenis = document.getElementById("jenis_belanja").value;

        sessionStorage.setItem('umum_spj_form_jenis_belanja', jenis);
        renderDokumenList(nama, jenis);
    }

    function formatRupiah(number) {
        if (number === null || number === undefined || number === '') return 'Rp 0';
        let num = number;
        if (typeof num === 'string') {
            let clean = num.replace(/[^0-9,\.]/g, '');
            if (clean.includes(',') && clean.includes('.')) {
                clean = clean.replace(/\./g, '').replace(',', '.');
            } else if (clean.includes('.')) {
                let parts = clean.split('.');
                if (parts.length > 2 || (parts.length === 2 && parts[1].length === 3)) {
                    clean = clean.replace(/\./g, '');
                }
            } else if (clean.includes(',')) {
                clean = clean.replace(',', '.');
            }
            num = parseFloat(clean);
        }
        if (isNaN(num)) return 'Rp 0';
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    function initCurrencyMask() {
        const display = document.getElementById('nilai_display');
        const hidden = document.getElementById('nilai_input');
        const hint = document.getElementById('nilai_terbilang_hint');
        if (!display || !hidden) return;

        function terbilangAngka(n) {
            if (!n || isNaN(n) || n <= 0) return '';
            const angka = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
            n = Math.floor(n);
            if (n < 12) return angka[n];
            if (n < 20) return terbilangAngka(n - 10) + ' Belas';
            if (n < 100) return terbilangAngka(Math.floor(n / 10)) + ' Puluh ' + terbilangAngka(n % 10);
            if (n < 200) return 'Seratus ' + terbilangAngka(n - 100);
            if (n < 1000) return terbilangAngka(Math.floor(n / 100)) + ' Ratus ' + terbilangAngka(n % 100);
            if (n < 2000) return 'Seribu ' + terbilangAngka(n - 1000);
            if (n < 1000000) return terbilangAngka(Math.floor(n / 1000)) + ' Ribu ' + terbilangAngka(n % 1000);
            if (n < 1000000000) return terbilangAngka(Math.floor(n / 1000000)) + ' Juta ' + terbilangAngka(n % 1000000);
            if (n < 1000000000000) return terbilangAngka(Math.floor(n / 1000000000)) + ' Miliar ' + terbilangAngka(n % 1000000000);
            return terbilangAngka(Math.floor(n / 1000000000000)) + ' Triliun ' + terbilangAngka(n % 1000000000000);
        }

        function parseAndFormat(inputVal) {
            if (!inputVal) return { display: '', raw: '', intNum: 0 };
            let val = String(inputVal).trim();
            
            let hasComma = val.includes(',');
            let parts = val.split(',');
            let intPart = parts[0].replace(/\D/g, '');
            let decPart = parts.length > 1 ? parts.slice(1).join('').replace(/\D/g, '').substring(0, 2) : null;

            let formattedInt = intPart ? intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';

            let displayVal = formattedInt;
            if (hasComma || decPart !== null) {
                displayVal += ',' + (decPart !== null ? decPart : '');
            }

            let rawVal = intPart;
            if (decPart !== null && decPart.length > 0) {
                rawVal += '.' + decPart;
            }

            return { display: displayVal, raw: rawVal, intNum: intPart ? parseInt(intPart, 10) : 0 };
        }

        function handleInput(e) {
            const rawVal = display.value;
            const selStart = display.selectionStart;
            
            const beforeCursor = rawVal.slice(0, selStart);
            const digitsBefore = (beforeCursor.match(/\d/g) || []).length;
            const hasCommaBefore = beforeCursor.includes(',');

            const res = parseAndFormat(rawVal);
            display.value = res.display;
            hidden.value = res.raw;

            // Restore cursor position
            let newCursor = 0;
            let count = 0;
            for (let i = 0; i < display.value.length; i++) {
                if (display.value[i] === ',') {
                    if (hasCommaBefore) {
                        newCursor = i + 1;
                    }
                }
                if (/\d/.test(display.value[i])) {
                    count++;
                }
                if (count >= digitsBefore) {
                    newCursor = i + 1;
                    break;
                }
            }
            if (!display.value.includes(',') && digitsBefore === 0) {
                newCursor = 0;
            }
            try {
                display.setSelectionRange(newCursor, newCursor);
            } catch(err) {}

            if (hint) {
                if (res.intNum > 0) {
                    const kata = terbilangAngka(res.intNum);
                    hint.innerHTML = `<span style="color: var(--dash-cyan-text, #0284c7); font-weight: 600;">Rp ${res.display}</span>` + 
                        (kata ? ` &bull; <em style="color: var(--dash-text-muted, #768f9e);">${kata} Rupiah</em>` : '');
                } else {
                    hint.textContent = 'Pemisah ribuan (titik) ditambahkan otomatis saat mengetik.';
                }
            }
        }

        display.addEventListener('input', handleInput);

        if (display.value) {
            const res = parseAndFormat(display.value);
            display.value = res.display;
            hidden.value = res.raw;
            if (hint && res.intNum > 0) {
                const kata = terbilangAngka(res.intNum);
                hint.innerHTML = `<span style="color: var(--dash-cyan-text, #0284c7); font-weight: 600;">Rp ${res.display}</span>` + 
                    (kata ? ` &bull; <em style="color: var(--dash-text-muted, #768f9e);">${kata} Rupiah</em>` : '');
            }
        }
    }

    function formatDateIndo(dateStr) {
        if (!dateStr) return '—';
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            const year = parts[0];
            const month = months[parseInt(parts[1], 10) - 1] || parts[1];
            const day = parseInt(parts[2], 10);
            return `${day} ${month} ${year}`;
        }
        return dateStr;
    }

    const masterDocDefs = [
        { label: 'KAK', keywords: ['KAK'] },
        { label: 'Form Permintaan', keywords: ['FP', 'Form Permintaan'] },
        { label: 'Daftar Gaji Induk', keywords: ['Daftar Gaji Induk', 'Gaji Induk'] },
        { label: 'Rekap Daftar Gaji', keywords: ['Rekapitulasi Daftar Gaji', 'Rekap Gaji'] },
        { label: 'Perubahan Gaji', keywords: ['Perubahan Gaji', 'Daftar Perubahan'] },
        { label: 'SPTJM', keywords: ['SPTJM'] },
        { label: 'Surat Perintah Lembur', keywords: ['Surat Perintah Lembur', 'Perintah Lembur'] },
        { label: 'Daftar Hadir Lembur', keywords: ['Daftar Hadir Lembur'] },
        { label: 'Perhitungan Lembur', keywords: ['Perhitungan Uang Lembur', 'Perhitungan Lembur', 'Daftar Perhitungan Uang Lembur', 'Daftar Perhitungan'] },
        { label: 'Daftar Rekapitulasi', keywords: ['Daftar Rekapitulasi'] },
        { label: 'Surat Pesanan', keywords: ['Surat Pesanan', 'bukti pemesanan'] },
        { label: 'BA Pemeriksaan', keywords: ['Pemeriksaan Barang', 'Pemeriksaan Pekerjaan', 'Berita Acara Pemeriksaan'] },
        { label: 'SPMK', keywords: ['Surat Perintah Mulai Kerja', 'SPMK'] },
        { label: 'Progres Pekerjaan', keywords: ['progres pekerjaan', 'Progres Pekerjaan'] },
        { label: 'Dokumen Kebutuhan', keywords: ['permintaan atau kebutuhan', 'kebutuhan barang'] },
        { label: 'Dokumentasi Barang', keywords: ['Dokumentasi barang', 'Dokumentasi hasil pekerjaan', 'dokumentasi barang'] },
        { label: 'Bukti Pembayaran', keywords: ['Bukti transaksi', 'Dokumen pembayaran', 'Bukti pembayaran'] },
        { label: 'Surat Tugas', keywords: ['Surat Tugas', 'SPPD', 'Visum'] },
        { label: 'SK / SPK', keywords: ['SK KPA', 'Surat Keputusan', 'Surat kontrak', 'SPK', 'Polis', 'Perjanjian'] },
        { label: 'Undangan', keywords: ['Undangan', 'Surat Undangan'] },
        { label: 'Daftar Hadir / Penerima', keywords: ['Daftar Hadir', 'Daftar Penerima', 'Daftar nama', 'Daftar Alokasi', 'Daftar rekap kuitansi'] },
        { label: 'Notula / Laporan', keywords: ['Notula', 'Notulensi', 'Laporan tim', 'Laporan pengajar', 'Laporan pelaksanaan', 'Laporan perjalanan dinas'] },
        { label: 'Bukti Beli / Nota', keywords: ['Bukti pembelian', 'Bukti Pembelian', 'nota', 'kwitansi', 'Kuitansi', 'invoice', 'Invoice', 'Tagihan', 'Tiket', 'Kwitansi Transpor', 'Daftar Pengeluaran riil'] },
        { label: 'BAST', keywords: ['BAST', 'BAPP'] },
        { label: 'Bukti Bayar / Pajak', keywords: ['Faktur Pajak', 'SSP', 'PPh', 'PPn', 'Screenshoot bukti transfer'] },
        { label: 'Kesesuaian MRK', keywords: ['Kesesuaian MRK'] },
        { label: 'CMS', keywords: ['CMS'] },
        { label: 'Cek SBKS', keywords: ['SBKS'] }
    ];

    function getCleanDocLabel(docName) {
        for (let i = 0; i < masterDocDefs.length; i++) {
            const def = masterDocDefs[i];
            for (let j = 0; j < def.keywords.length; j++) {
                if (docName.toLowerCase().indexOf(def.keywords[j].toLowerCase()) !== -1) {
                    return def.label;
                }
            }
        }
        return docName.length > 25 ? docName.substring(0, 22) + '...' : docName;
    }

    function cetakLangsung() {
        const selNamaBelanja = document.getElementById("nama_belanja");
        const selJenisBelanja = document.getElementById("jenis_belanja");
        const namaBelanja = selNamaBelanja ? selNamaBelanja.value : '';
        const jenisBelanja = selJenisBelanja ? selJenisBelanja.value : '';

        if (!namaBelanja || !jenisBelanja) {
            alert("Silakan pilih Nama Belanja dan Jenis Belanja terlebih dahulu serta centang berkas fisik yang sudah disiapkan.");
            if (selNamaBelanja) selNamaBelanja.focus();
            return;
        }

        const docInputs = document.querySelectorAll('input[name^="nama_dokumen"]');
        if (docInputs.length === 0) {
            alert("Daftar dokumen belum tersedia. Silakan pilih Jenis Belanja terlebih dahulu.");
            return;
        }

        const nomorSpj = document.querySelector('[name="nomor_spj"]')?.value.trim() || '—';
        const kegiatan = document.querySelector('[name="kegiatan"]')?.value.trim() || '—';
        const tanggal = document.querySelector('[name="tanggal"]')?.value || '';
        const tanggalSelesai = document.querySelector('[name="tanggal_selesai"]')?.value || '';
        const nilai = document.querySelector('[name="nilai"]')?.value || '0';
        const noWa = document.querySelector('[name="no_wa"]')?.value.trim() || '—';
        const keterangan = document.querySelector('[name="keterangan"]')?.value.trim() || '—';
        const pengajuName = "{{ Auth::user()->name ?? 'Subbagian Umum' }}";
        const logoUrl = "{{ asset('images/logo-bps.png') }}";
        const tanggalCetak = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const tanggalFormatted = formatDateIndo(tanggal) || tanggalCetak;
        const periodeKegiatanFormatted = (tanggal && tanggalSelesai && tanggalSelesai !== tanggal)
            ? (formatDateIndo(tanggal) + ' s.d. ' + formatDateIndo(tanggalSelesai))
            : tanggalFormatted;

        const seenLabels = {};
        const docCols = [];
        docInputs.forEach((input, i) => {
            const match = input.name.match(/\[(\d+)\]/);
            const idx = match ? match[1] : i;
            const checkbox = document.querySelector(`input[name="berkas[${idx}]"]`);
            const docName = input.value;
            let label = getCleanDocLabel(docName);
            if (seenLabels[label]) {
                seenLabels[label]++;
                label = label + ' (' + seenLabels[label] + ')';
            } else {
                seenLabels[label] = 1;
            }
            docCols.push({
                label: label,
                fullName: docName,
                isChecked: checkbox ? checkbox.checked : false
            });
        });

        const totalDocCols = docCols.length;
        const totalKelengkapanColspan = totalDocCols + 4;

        let docHeadersHtml = '';
        docCols.forEach(col => {
            docHeadersHtml += '                    \x3Cth\x3E' + col.label + '\x3C/th\x3E\n';
        });

        let docValuesHtml = '';
        docCols.forEach(col => {
            docValuesHtml += '                    \x3Ctd style="font-size: 11pt; font-weight: bold; text-align: center;"\x3E' + (col.isChecked ? '&radic;' : '') + '\x3C/td\x3E\n';
        });

        const printHtml = '\x3C!DOCTYPE html\x3E\n'
            + '\x3Chtml lang="id"\x3E\n'
            + '\x3Chead\x3E\n'
            + '    \x3Cmeta charset="UTF-8"\x3E\n'
            + '    \x3Ctitle\x3ELaporan SPJ - ' + (kegiatan !== '—' ? kegiatan : 'Kegiatan') + '\x3C/title\x3E\n'
            + '    \x3Cstyle\x3E\n'
            + '        @page { size: landscape; margin: 10mm; }\n'
            + '        * { box-sizing: border-box; }\n'
            + '        body {\n'
            + '            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;\n'
            + '            background: #fff;\n'
            + '            color: #111;\n'
            + '            margin: 0;\n'
            + '            padding: 0;\n'
            + '            font-size: 10px;\n'
            + '        }\n'
            + '        .print-only-logo { text-align: center; margin-bottom: 18px; }\n'
            + '        .print-only-logo img { height: 50px; margin-bottom: 6px; }\n'
            + '        .print-only-logo h2 { font-size: 16px; margin: 5px 0 3px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }\n'
            + '        .print-only-logo p { font-size: 12px; margin: 0; color: #374151; }\n'
            + '        .meta-info {\n'
            + '            display: flex;\n'
            + '            justify-content: space-between;\n'
            + '            align-items: center;\n'
            + '            font-size: 11px;\n'
            + '            margin-bottom: 10px;\n'
            + '            padding: 0 4px;\n'
            + '        }\n'
            + '        .table-responsive { overflow: visible !important; width: 100% !important; }\n'
            + '        .cetak-table {\n'
            + '            width: 100%;\n'
            + '            border-collapse: collapse;\n'
            + '            font-size: 8px;\n'
            + '            text-align: center;\n'
            + '            margin-bottom: 15px;\n'
            + '            table-layout: auto;\n'
            + '        }\n'
            + '        .cetak-table th, .cetak-table td {\n'
            + '            border: 1px solid #333;\n'
            + '            padding: 4px 2px;\n'
            + '            white-space: normal;\n'
            + '            word-wrap: break-word;\n'
            + '        }\n'
            + '        .cetak-table th {\n'
            + '            background-color: #f5f5f5;\n'
            + '            font-weight: bold;\n'
            + '            text-align: center;\n'
            + '            min-width: 25px;\n'
            + '        }\n'
            + '        .ttd-container {\n'
            + '            display: flex;\n'
            + '            justify-content: flex-end;\n'
            + '            padding-right: 50px;\n'
            + '            text-align: left;\n'
            + '            font-size: 12px;\n'
            + '            margin-top: 35px;\n'
            + '            page-break-inside: avoid;\n'
            + '        }\n'
            + '        @media print {\n'
            + '            .no-print { display: none !important; }\n'
            + '            .print-wrapper { padding: 0 !important; }\n'
            + '        }\n'
            + '        .no-print {\n'
            + '            position: sticky;\n'
            + '            top: 0;\n'
            + '            left: 0;\n'
            + '            right: 0;\n'
            + '            background: #0f172a;\n'
            + '            color: #ffffff;\n'
            + '            padding: 12px 20px;\n'
            + '            display: flex;\n'
            + '            justify-content: space-between;\n'
            + '            align-items: center;\n'
            + '            box-shadow: 0 4px 12px rgba(0,0,0,0.15);\n'
            + '            z-index: 99999;\n'
            + '            margin-bottom: 20px;\n'
            + '            font-family: Arial, sans-serif;\n'
            + '        }\n'
            + '    \x3C/style\x3E\n'
            + '\x3C/head\x3E\n'
            + '\x3Cbody\x3E\n'
            + '    \x3Cdiv class="no-print"\x3E\n'
            + '        \x3Cdiv style="display: flex; align-items: center; gap: 8px;"\x3E\n'
            + '            \x3Cstrong style="font-size: 13px;"\x3E📄 Pratinjau Lembar Checklist Berkas Fisik SPJ\x3C/strong\x3E\n'
            + '            \x3Cspan style="background: rgba(255,255,255,0.15); padding: 2px 8px; border-radius: 4px; font-size: 11px;"\x3ETab Baru\x3C/span\x3E\n'
            + '        \x3C/div\x3E\n'
            + '        \x3Cdiv style="display: flex; gap: 10px;"\x3E\n'
            + '            \x3Cbutton type="button" onclick="window.print()" style="background: #0284c7; color: #ffffff; border: none; padding: 7px 16px; border-radius: 6px; font-size: 12px; font-weight: bold; cursor: pointer;"\x3E🖨️ Cetak Lembar Checklist\x3C/button\x3E\n'
            + '            \x3Cbutton type="button" onclick="window.close()" style="background: #475569; color: #ffffff; border: none; padding: 7px 14px; border-radius: 6px; font-size: 12px; cursor: pointer;"\x3E✕ Tutup Tab\x3C/button\x3E\n'
            + '        \x3C/div\x3E\n'
            + '    \x3C/div\x3E\n'
            + '    \x3Cdiv class="print-wrapper" style="padding: 10px 20px;"\x3E\n'
            + '        \x3Cdiv class="print-only-logo"\x3E\n'
            + '            \x3Cimg src="' + logoUrl + '" alt="Logo BPS" onerror="this.style.display=\'none\'"\x3E\n'
            + '            \x3Ch2\x3ELAPORAN REKAPITULASI SPJ\x3C/h2\x3E\n'
            + '            \x3Cp\x3EBadan Pusat Statistik Kabupaten Kolaka Utara\x3C/p\x3E\n'
            + '        \x3C/div\x3E\n'
            + '        \x3Cdiv class="meta-info"\x3E\n'
            + '            \x3Cdiv\x3ENo. SPJ: \x3Cstrong\x3E' + nomorSpj + '\x3C/strong\x3E | Periode Kegiatan: \x3Cstrong\x3E' + periodeKegiatanFormatted + '\x3C/strong\x3E | Staf Pengaju / PIC: \x3Cstrong\x3E' + pengajuName + '\x3C/strong\x3E ' + (noWa !== '—' ? '(' + noWa + ')' : '') + '\x3C/div\x3E\n'
            + '            \x3Cdiv\x3EBelanja: \x3Cstrong\x3E' + namaBelanja + ' (' + jenisBelanja + ')\x3C/strong\x3E\x3C/div\x3E\n'
            + '        \x3C/div\x3E\n'
            + '        \x3Cdiv class="table-responsive"\x3E\n'
            + '            \x3Ctable class="cetak-table"\x3E\n'
            + '                \x3Cthead\x3E\n'
            + '                    \x3Ctr\x3E\n'
            + '                        \x3Cth rowspan="2" style="width: 30px;"\x3ENo\x3C/th\x3E\n'
            + '                        \x3Cth rowspan="2" style="min-width: 170px;"\x3ENama Kegiatan\x3C/th\x3E\n'
            + '                        \x3Cth rowspan="2" style="min-width: 95px;"\x3EJumlah Anggaran\x3C/th\x3E\n'
            + '                        \x3Cth rowspan="2" style="min-width: 80px;"\x3ETanggal Penginputan SPJ\x3C/th\x3E\n'
            + '                        \x3Cth colspan="' + totalKelengkapanColspan + '"\x3EKelengkapan Administrasi\x3C/th\x3E\n'
            + '                        \x3Cth rowspan="2" style="min-width: 80px;"\x3EKeterangan\x3C/th\x3E\n'
            + '                    \x3C/tr\x3E\n'
            + '                    \x3Ctr\x3E\n'
            + docHeadersHtml
            + '                        \x3Cth style="min-width: 65px;"\x3ETgl Masuk SPJ\x3C/th\x3E\n'
            + '                        \x3Cth style="min-width: 65px;"\x3ETgl Periksa PPK\x3C/th\x3E\n'
            + '                        \x3Cth style="min-width: 45px;"\x3EParaf PPSPM\x3C/th\x3E\n'
            + '                        \x3Cth style="min-width: 45px;"\x3EParaf Bendahara\x3C/th\x3E\n'
            + '                    \x3C/tr\x3E\n'
            + '                \x3C/thead\x3E\n'
            + '                \x3Ctbody\x3E\n'
            + '                    \x3Ctr\x3E\n'
            + '                        \x3Ctd\x3E1\x3C/td\x3E\n'
            + '                        \x3Ctd style="text-align: left; padding: 5px 6px;"\x3E' + kegiatan + '\x3C/td\x3E\n'
            + '                        \x3Ctd style="text-align: right; white-space: nowrap; font-weight: bold; padding: 5px 6px;"\x3ERp. ' + formatRupiah(nilai) + '\x3C/td\x3E\n'
            + '                        \x3Ctd\x3E' + tanggalCetak + '\x3C/td\x3E\n'
            + docValuesHtml
            + '                        \x3Ctd\x3E-\x3C/td\x3E\n'
            + '                        \x3Ctd\x3E-\x3C/td\x3E\n'
            + '                        \x3Ctd\x3E\x3C/td\x3E\n'
            + '                        \x3Ctd\x3E\x3C/td\x3E\n'
            + '                        \x3Ctd style="text-align: left; padding: 5px 6px;"\x3E' + (keterangan !== '—' ? keterangan : '') + '\x3C/td\x3E\n'
            + '                    \x3C/tr\x3E\n'
            + '                \x3C/tbody\x3E\n'
            + '                \x3Ctfoot\x3E\n'
            + '                    \x3Ctr\x3E\n'
            + '                        \x3Cth colspan="2" style="text-align: right; padding-right: 15px;"\x3ETOTAL\x3C/th\x3E\n'
            + '                        \x3Cth style="text-align: right; white-space: nowrap; font-weight: bold; padding: 5px 6px;"\x3ERp. ' + formatRupiah(nilai) + '\x3C/th\x3E\n'
            + '                        \x3Cth colspan="' + (totalKelengkapanColspan + 2) + '"\x3E\x3C/th\x3E\n'
            + '                    \x3C/tr\x3E\n'
            + '                \x3C/tfoot\x3E\n'
            + '            \x3C/table\x3E\n'
            + '        \x3C/div\x3E\n'
            + '        \x3Cdiv class="ttd-container"\x3E\n'
            + '            \x3Cdiv\x3E\n'
            + '                \x3Cp style="margin: 0;"\x3ELasusua, ' + tanggalCetak + '\x3C/p\x3E\n'
            + '                \x3Cp style="margin: 0;"\x3EPejabat Pembuat Komitmen\x3C/p\x3E\n'
            + '                \x3Cbr\x3E\x3Cbr\x3E\x3Cbr\x3E\x3Cbr\x3E\n'
            + '                \x3Cp style="margin: 0; text-decoration: underline; font-weight: bold;"\x3EHidayatullah, S.E.\x3C/p\x3E\n'
            + '                \x3Cp style="margin: 0;"\x3ENIP.198510292011011009\x3C/p\x3E\n'
            + '            \x3C/div\x3E\n'
            + '        \x3C/div\x3E\n'
            + '    \x3C/div\x3E\n'
            + '\x3C/body\x3E\n'
            + '\x3C/html\x3E';

        let printCompleted = false;
        const markAsPrinted = function() {
            if (printCompleted) return;
            printCompleted = true;
            isPrinted = true;

            const isPrintedInput = document.getElementById('is_printed_input');
            if (isPrintedInput) isPrintedInput.value = '1';

            const printBtn = document.getElementById('btn_cetak_langsung');
            if (printBtn) {
                printBtn.classList.remove('print-btn-shake');
                printBtn.classList.add('is-printed');
                printBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Sudah Dicetak
                `;
            }

            const banner = document.getElementById('print_status_banner');
            if (banner) {
                banner.className = 'spj-banner-printed';
                banner.style.display = 'flex';
                banner.style.backgroundColor = '';
                banner.style.color = '';
                banner.style.border = '';
                banner.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span><strong>Checklist Telah Dicetak:</strong> Lembar checklist SPJ telah dicetak di tab baru. Tombol pengajuan sekarang aktif berwarna hijau!</span>
                `;
            }

            const hint = document.getElementById('print_requirement_hint');
            if (hint) {
                hint.style.display = 'none';
            }

            updateSubmitButtonState();
        };

        const printWin = window.open('', '_blank');
        if (!printWin) {
            markAsPrinted();
            alert("Pop-up cetak otomatis diblokir oleh browser Anda. Namun berkas telah berhasil ditandai 'Sudah Dicetak' sehingga Anda dapat langsung menekan tombol 'Ajukan Sekarang'.\n\nUntuk mencetak lembar fisik, silakan izinkan pop-up pada bilah URL browser Anda.");
            return;
        }

        printWin.document.open();
        printWin.document.write(printHtml);
        printWin.document.close();

        markAsPrinted();

        try {
            printWin.addEventListener('afterprint', markAsPrinted, { once: true });
        } catch(e) {}

        setTimeout(function() {
            try {
                printWin.focus();
                printWin.print();
            } catch(e) {}
        }, 350);
    }

    let isPrinted = {{ old('is_printed', '0') == '1' ? 'true' : 'false' }};

    function resetPrintStatus() {
        isPrinted = false;
        const input = document.getElementById('is_printed_input');
        if (input) input.value = '0';

        const printBtn = document.getElementById('btn_cetak_langsung');
        if (printBtn) {
            printBtn.classList.remove('is-printed');
            printBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span id="btn_cetak_text">Print</span>
            `;
        }

        const banner = document.getElementById('print_status_banner');
        if (banner) {
            banner.style.display = 'none';
        }

        updatePrintNotice();
        updateSubmitButtonState();
    }

    function updateSubmitButtonState() {
        const selectedStatus = document.querySelector('input[name="status"]:checked')?.value || 'draft';
        const submitBtn = document.getElementById('btn_submit_spj');
        const submitIcon = document.getElementById('btn_submit_icon');
        const submitText = document.getElementById('btn_submit_text');
        const hint = document.getElementById('print_requirement_hint');

        if (!submitBtn) return;

        if (selectedStatus === 'diajukan') {
            if (isPrinted) {
                // STATUS DIAJUKAN & SUDAH PRINT -> AKTIF & WARNA HIJAU
                submitBtn.disabled = false;
                submitBtn.style.backgroundColor = '#059669';
                submitBtn.style.borderColor = '#047857';
                submitBtn.style.color = '#ffffff';
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                submitBtn.style.boxShadow = '0 4px 14px rgba(5, 150, 105, 0.4)';
                submitBtn.title = 'Lembar checklist telah dicetak. Klik untuk mengajukan SPJ ke alur pemeriksaan.';
                
                if (submitIcon) {
                    submitIcon.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    `;
                }
                if (submitText) {
                    submitText.textContent = 'Ajukan Sekarang ke Alur SPJ';
                }
                if (hint) {
                    hint.style.display = 'none';
                }
            } else {
                // STATUS DIAJUKAN & BELUM PRINT -> JANGAN DISABLED AGAR MEMICU MODAL
                submitBtn.disabled = false;
                submitBtn.style.backgroundColor = '#94a3b8';
                submitBtn.style.borderColor = '#94a3b8';
                submitBtn.style.color = '#ffffff';
                submitBtn.style.opacity = '0.75';
                submitBtn.style.cursor = 'not-allowed';
                submitBtn.style.boxShadow = 'none';
                submitBtn.title = 'Wajib menekan tombol Print terlebih dahulu sebelum pengajuan dapat disimpan.';

                if (submitIcon) {
                    submitIcon.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    `;
                }
                if (submitText) {
                    submitText.textContent = 'Ajukan SPJ (Wajib Print Dulu)';
                }
                if (hint) {
                    hint.style.display = 'block';
                }
            }
        } else {
            // STATUS DRAFT -> BIRU / TEAL STANDAR
            submitBtn.disabled = false;
            submitBtn.style.backgroundColor = 'var(--primary, #1e4253)';
            submitBtn.style.borderColor = 'var(--primary, #1e4253)';
            submitBtn.style.color = '#ffffff';
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
            submitBtn.style.boxShadow = 'none';
            submitBtn.title = 'Simpan draft SPJ untuk diedit kembali nanti.';

            if (submitIcon) {
                submitIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                `;
            }
            if (submitText) {
                submitText.textContent = 'Simpan sebagai Draft';
            }
            if (hint) {
                hint.style.display = 'none';
            }
        }
    }

    function updatePrintNotice() {
        const selectedStatus = document.querySelector('input[name="status"]:checked')?.value || 'draft';
        const hint = document.getElementById('print_requirement_hint');
        if (hint) {
            if (selectedStatus === 'diajukan' && !isPrinted) {
                hint.style.display = 'block';
            } else {
                hint.style.display = 'none';
            }
        }
        updateSubmitButtonState();
    }

    document.addEventListener("DOMContentLoaded", function() {
        initCurrencyMask();

        const statusRadios = document.querySelectorAll('input[name="status"]');
        statusRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                updatePrintNotice();
                updateSubmitButtonState();
            });
        });

        if (isPrinted) {
            const printBtn = document.getElementById('btn_cetak_langsung');
            if (printBtn) {
                printBtn.classList.add('is-printed');
                printBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Sudah Dicetak
                `;
            }
        }

        updatePrintNotice();
        updateSubmitButtonState();

        // Sinkronisasi rentang tanggal (mulai & selesai)
        const inputTglMulai = document.getElementById('tanggal_mulai_input');
        const inputTglSelesai = document.getElementById('tanggal_selesai_input');
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

        // Intercept klik pada tombol submit jika status diajukan dan belum print -> langsung POP-UP MODAL!
        const submitBtn = document.getElementById('btn_submit_spj');
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                const selectedStatus = document.querySelector('input[name="status"]:checked')?.value || 'draft';
                if (selectedStatus === 'diajukan' && !isPrinted) {
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
                        alert("Sesuai ketentuan, Anda wajib menekan tombol 'Print' terlebih dahulu sebelum pengajuan dapat disimpan.");
                    }
                    return false;
                }
            });
        }

        const form = document.querySelector('form[action="{{ route('umum.spj.store') }}"]') || document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const selectedStatus = document.querySelector('input[name="status"]:checked')?.value || 'draft';
                if (selectedStatus === 'diajukan' && !isPrinted) {
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
                    }
                    return false;
                }

                clearFormState();
            });
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
