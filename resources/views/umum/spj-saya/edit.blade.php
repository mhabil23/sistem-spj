@extends('layouts.umum')

@php
$title = 'Edit SPJ Saya';
$subtitle = 'Perbaiki atau perbarui pengajuan SPJ mandiri Subbagian Umum.';
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
            border-color: #38bdf8;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.08);
        }
        .upload-info {
            flex: 1;
        }
        .upload-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dash-text-dark, #0f172a);
        }
        .upload-desc {
            font-size: 0.8rem;
            color: var(--dash-text-muted, #64748b);
        }
        .badge-assignee {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 9999px;
            margin-left: 0.5rem;
            background: #e0f2fe;
            color: #0369a1;
        }
        .badge-bendahara {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-ppk {
            background: #ede9fe;
            color: #6d28d9;
        }
        .spj-btn-print {
            background: #f8fafc;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }
        .spj-btn-print:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
            transform: translateY(-1px);
        }
        .spj-btn-print.is-printed {
            background: #ecfdf5 !important;
            color: #065f46 !important;
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }
        .spj-input-prefix-group {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .spj-input-prefix {
            position: absolute;
            left: 1rem;
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--dash-text-muted, #64748b);
            pointer-events: none;
            user-select: none;
            z-index: 2;
        }
        .spj-input-with-prefix {
            padding-left: 3.25rem !important;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--dash-text-dark, #0f172a);
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
        }
    </style>
@endpush

<div class="dashboard-heading">
    <div>
        <h2 class="dashboard-heading-title">Edit SPJ Mandiri Subbag Umum</h2>
        <p class="dashboard-heading-subtitle">Perbaiki rincian kegiatan belanja atau kelengkapan berkas fisik sebelum diajukan kembali.</p>
    </div>
    <a href="{{ route('umum.spj-saya.index') }}" class="btn-dash-back">
        <ion-icon name="arrow-back-outline"></ion-icon>
        Kembali ke SPJ Saya
    </a>
</div>

<div class="spj-panel" style="animation-delay: 0.1s;">
    <div class="spj-panel-header">
        <div>
            <h2>Detail Pengajuan SPJ</h2>
            <p>Perbarui data sesuai kebutuhan atau catatan verifikasi yang diberikan.</p>
        </div>
    </div>
    
    <div class="spj-panel-body">
        @if(in_array($spj->status, ['dikembalikan', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara']) && $spj->catatan_revisi)
            <div class="spj-alert spj-alert-error" style="align-items: flex-start; margin-bottom: 2rem;">
                <div style="flex-shrink: 0; margin-top: 0.125rem; font-size: 22px; color: var(--dash-peach-text);">
                    <ion-icon name="alert-circle"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.25rem; color: var(--dash-peach-text);">Catatan Revisi dari {{ strtoupper(explode('_', $spj->status)[1] ?? 'Pemeriksa') }}</h3>
                    <p style="font-weight: 400; margin: 0; white-space: pre-line; line-height: 1.5; color: var(--dash-text-dark);">{{ $spj->catatan_revisi }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('umum.spj-saya.update', $spj->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            @php
                $dokumen = $spj->dokumen_list;
                if (empty($dokumen) && !empty($spj->dokumen_file) && str_starts_with(trim($spj->dokumen_file), '[')) {
                    $dokumen = json_decode($spj->dokumen_file, true) ?: [];
                }
            @endphp
            
            <div class="spj-kategori-section">
                <div style="margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <label class="spj-label" style="margin-bottom: 0;">Kelengkapan Berkas Fisik</label>
                        <span class="spj-kategori-sub">Centang berkas fisik yang sudah siap dan dilampirkan</span>
                    </div>
                    @if(count($dokumen) > 0)
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="button" onclick="centangSemuaBerkas(true)" class="spj-btn spj-btn-secondary" style="padding: 0.25rem 0.65rem; font-size: 0.75rem; height: auto;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 2px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Centang Semua
                            </button>
                            <button type="button" onclick="centangSemuaBerkas(false)" class="spj-btn spj-btn-secondary" style="padding: 0.25rem 0.65rem; font-size: 0.75rem; height: auto; opacity: 0.85;">
                                Batal Semua
                            </button>
                        </div>
                    @endif
                </div>
                <div id="dokumen_list">
                    @if(count($dokumen) > 0)
                        @foreach($dokumen as $index => $doc)
                            @php
                                $isBendahara = \App\Models\Spj::isBendaharaDoc($doc);
                                $isPpk = \App\Models\Spj::isPpkDoc($doc);
                                $isUmum = !$isBendahara && !$isPpk;
                            @endphp
                            <div class="upload-item">
                                <div class="upload-info">
                                    <div style="display: flex; align-items: center; margin-bottom: 0.25rem; flex-wrap: wrap; gap: 0.25rem;">
                                        <span class="upload-label" style="margin: 0;">{{ $doc['nama'] }}</span>
                                        @if($isBendahara)
                                            <span class="badge-assignee badge-bendahara">Dilengkapi Bendahara</span>
                                        @elseif($isPpk)
                                            <span class="badge-assignee badge-ppk">Dilengkapi PPK</span>
                                        @else
                                            <span class="badge-assignee">Disiapkan Subbag Umum</span>
                                        @endif
                                    </div>
                                    <input type="hidden" name="nama_dokumen[{{ $index }}]" value="{{ $doc['nama'] }}">
                                    <input type="hidden" name="assignee_dokumen[{{ $index }}]" value="{{ $doc['assignee'] ?? ($isBendahara ? 'Bendahara' : ($isPpk ? 'PPK' : 'SM')) }}">
                                </div>
                                <div class="upload-action">
                                    @if($isUmum)
                                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; margin: 0;">
                                            <input type="checkbox" name="berkas[{{ $index }}]" id="doc_{{ $index }}" value="1" {{ !empty($doc['disiapkan']) ? 'checked' : '' }} class="berkas-teknis-cb" style="width: 1.25rem; height: 1.25rem; accent-color: var(--primary);" required>
                                            <span class="spj-doc-checkbox-label">Ada (Fisik)</span>
                                        </label>
                                    @elseif($isBendahara)
                                        <span style="font-size: 0.8rem; color: var(--dash-text-muted, #94a3b8); font-style: italic;">Tidak perlu (Diurus Bendahara)</span>
                                    @elseif($isPpk)
                                        <span style="font-size: 0.8rem; color: var(--dash-text-muted, #94a3b8); font-style: italic;">Tidak perlu (Diurus PPK)</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="font-size: 0.85rem; color: var(--dash-text-muted); font-style: italic;">Tidak ada berkas yang tercatat pada pengajuan ini.</p>
                    @endif
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;" class="spj-form-group">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
                        <label class="spj-label" for="nomor_spj_input" style="margin-bottom: 0;">Nomor SPJ</label>
                        <span class="spj-badge-auto">Otomatis Sistem</span>
                    </div>
                    <input type="text" name="nomor_spj" id="nomor_spj_input" class="spj-input spj-input-readonly" value="{{ old('nomor_spj', $spj->nomor_spj ?: ($nomorSpjOtomatis ?? '')) }}" placeholder="Digenerate otomatis oleh sistem" readonly title="Nomor SPJ dibuat otomatis oleh sistem">
                    <span class="spj-field-hint">Nomor registrasi SPJ digenerate secara otomatis oleh sistem.</span>
                    @error('nomor_spj') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="spj-label" for="nilai_display">Nilai Nominal (Rp) <span style="color: #ef4444;">*</span></label>
                    <div class="spj-input-prefix-group">
                        <span class="spj-input-prefix">Rp</span>
                        @php
                            $initialNilai = old('nilai', $spj->nilai);
                            $formattedInitialNilai = '';
                            if ($initialNilai !== null && $initialNilai !== '') {
                                $cleanInitial = is_numeric($initialNilai) ? (float) $initialNilai : (float) str_replace(',', '.', str_replace('.', '', $initialNilai));
                                $hasDecimals = floor($cleanInitial) != $cleanInitial;
                                $formattedInitialNilai = number_format($cleanInitial, $hasDecimals ? 2 : 0, ',', '.');
                            }
                        @endphp
                        <input type="text" 
                               id="nilai_display" 
                               class="spj-input spj-input-with-prefix" 
                               value="{{ $formattedInitialNilai }}" 
                               placeholder="Contoh: 1.500.000" 
                               inputmode="numeric" 
                               autocomplete="off" 
                               required>
                        <input type="hidden" name="nilai" id="nilai_input" value="{{ old('nilai', $spj->nilai) }}">
                    </div>
                    <span class="spj-field-hint" id="nilai_terbilang_hint">Pemisah ribuan (titik) ditambahkan otomatis saat mengetik.</span>
                    @error('nilai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-top: 1.5rem;" class="spj-form-group">
                <div>
                    <label class="spj-label" for="kegiatan_input">Uraian Kegiatan <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="kegiatan" id="kegiatan_input" required class="spj-input" value="{{ old('kegiatan', $spj->kegiatan) }}">
                    @error('kegiatan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;" class="spj-form-group">
                <div>
                    <label class="spj-label" for="tanggal_mulai_input">
                        Tanggal Mulai Kegiatan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal_mulai_input" required class="spj-input @error('tanggal') has-error @enderror" value="{{ old('tanggal', $spj->tanggal ? $spj->tanggal->format('Y-m-d') : '') }}">
                    <span class="spj-field-hint">Tanggal dimulainya pelaksanaan kegiatan.</span>
                    @error('tanggal') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="spj-label" for="tanggal_selesai_input" style="display: flex; justify-content: space-between; align-items: center;">
                        <span>Tanggal Selesai Kegiatan</span>
                        <span class="spj-badge-optional">Opsional</span>
                    </label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai_input" class="spj-input @error('tanggal_selesai') has-error @enderror" value="{{ old('tanggal_selesai', $spj->tanggal_selesai ? $spj->tanggal_selesai->format('Y-m-d') : '') }}">
                    <span class="spj-field-hint">Kosongkan jika kegiatan hanya berlangsung 1 hari.</span>
                    @error('tanggal_selesai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <input type="hidden" name="seksi" id="seksi_input" value="{{ old('seksi', $spj->seksi ?: 'Subbagian Umum') }}">

            <div class="spj-form-group">
                <label class="spj-label" for="no_wa_input">Nomor WhatsApp Pengaju / PIC</label>
                <input type="text" name="no_wa" id="no_wa_input" class="spj-input" value="{{ old('no_wa', $spj->no_wa ?? Auth::user()->no_wa ?? '') }}" placeholder="Contoh: 081234567890">
                <span class="spj-field-hint">Nomor WhatsApp PIC agar dapat dihubungi terkait tindak lanjut berkas.</span>
                @error('no_wa') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <div class="spj-form-group">
                <label class="spj-label" for="keterangan_input">Keterangan Tambahan</label>
                <textarea name="keterangan" id="keterangan_input" class="spj-input spj-textarea">{{ old('keterangan', $spj->keterangan) }}</textarea>
                @error('keterangan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <div class="spj-form-group" style="margin-top: 2rem;">
                <label class="spj-label">Tindakan Lanjutan</label>
                <div class="spj-radio-group">
                    <label class="spj-radio-card">
                        <input type="radio" name="status" value="draft" {{ old('status', $spj->status) === 'draft' ? 'checked' : '' }}>
                        <div class="spj-radio-content">
                            <strong>Simpan sebagai Draft</strong>
                            <span>Belum siap diajukan, simpan perubahan berkas sementara</span>
                        </div>
                    </label>
                    <label class="spj-radio-card">
                        <input type="radio" name="status" value="diajukan" {{ old('status', $spj->status) !== 'draft' ? 'checked' : '' }}>
                        <div class="spj-radio-content">
                            <strong>{{ str_contains($spj->status, 'revisi') ? 'Ajukan Ulang Perbaikan' : 'Ajukan SPJ ke Alur Verifikasi' }}</strong>
                            <span>Kirim berkas ke tahapan verifikasi dokumen dinas</span>
                        </div>
                    </label>
                </div>
                @error('status') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror

                <div id="print_status_banner" class="spj-banner-printed" style="display: none;"></div>

                <div id="print_requirement_hint" class="spj-hint-box" style="display: none;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span><strong>Wajib Print:</strong> Anda memilih opsi <strong>Ajukan SPJ</strong>. Silakan tekan tombol Print untuk mencetak lembar checklist berkas SPJ sebelum pengajuan dapat disimpan.</span>
                    </div>
                </div>

                <input type="hidden" name="is_printed" id="is_printed_input" value="{{ old('is_printed', '0') }}">
            </div>

            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 1rem; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); flex-wrap: wrap;">
                <a href="{{ route('umum.spj-saya.index') }}" class="spj-btn spj-btn-secondary">Batal</a>
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
                    <span id="btn_submit_text">Perbarui SPJ</span>
                </button>
            </div>
        </form>
    </div>
</div>

<x-print-validation-modal />

@endsection

@push('scripts')
<script>
    function centangSemuaBerkas(val = true) {
        const checkboxes = document.querySelectorAll('input.berkas-teknis-cb');
        checkboxes.forEach(cb => {
            cb.checked = val;
        });
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
        { label: 'Surat Tugas', keywords: ['Surat Tugas', 'SPPD', 'Visum'] },
        { label: 'SK / SPK', keywords: ['SK KPA', 'Surat Keputusan', 'Surat kontrak', 'SPK', 'Polis', 'Perjanjian'] },
        { label: 'Undangan', keywords: ['Undangan', 'Surat Undangan'] },
        { label: 'Daftar Hadir / Penerima', keywords: ['Daftar Hadir', 'Daftar Penerima', 'Daftar nama', 'Daftar Alokasi', 'Daftar rekap kuitansi', 'Daftar Gaji', 'Rekapitulasi'] },
        { label: 'Notula / Laporan', keywords: ['Notula', 'Notulensi', 'Laporan tim', 'Laporan pengajar', 'Laporan pelaksanaan', 'Laporan perjalanan dinas', 'SPTJM'] },
        { label: 'Bukti Beli / Nota', keywords: ['Bukti pembelian', 'Bukti Pembelian', 'nota', 'kwitansi', 'Kuitansi', 'invoice', 'Invoice', 'Tagihan', 'Tiket', 'Kwitansi Transpor', 'Surat Pesanan'] },
        { label: 'BAST', keywords: ['BAST', 'BAPP', 'Berita Acara'] },
        { label: 'Bukti Bayar / Pajak', keywords: ['Faktur Pajak', 'SSP', 'PPh', 'PPn', 'Screenshoot bukti transfer', 'Bukti pembayaran', 'Bukti transaksi'] },
        { label: 'Dokumentasi', keywords: ['Dokumentasi'] }
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
        const docInputs = document.querySelectorAll('input[name^="nama_dokumen"]');
        if (docInputs.length === 0) {
            alert("Belum ada dokumen yang tersedia untuk dicetak.");
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
        const tanggalInputSpj = "{{ $spj->created_at ? $spj->created_at->locale('id')->translatedFormat('d F Y') : '' }}";
        const tglMasukUmum = "{{ $spj->disetujui_umum_at ? \Carbon\Carbon::parse($spj->disetujui_umum_at)->locale('id')->translatedFormat('d F Y') : '-' }}";

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
            docHeadersHtml += '                    <th>' + col.label + '<\/th>\n';
        });

        let docValuesHtml = '';
        docCols.forEach(col => {
            docValuesHtml += '                    <td style="font-size: 11pt; font-weight: bold; text-align: center;">' + (col.isChecked ? '&radic;' : '') + '<\/td>\n';
        });

        const printHtml = '<!DOCTYPE html>\n'
            + '<html lang="id">\n'
            + '<head>\n'
            + '    <meta charset="UTF-8">\n'
            + '    <title>Laporan SPJ - ' + nomorSpj + '<\/title>\n'
            + '    <style>\n'
            + '        @page {\n'
            + '            size: landscape;\n'
            + '            margin: 10mm;\n'
            + '        }\n'
            + '        * { box-sizing: border-box; }\n'
            + '        body {\n'
            + '            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;\n'
            + '            background: #fff;\n'
            + '            color: #111;\n'
            + '            margin: 0;\n'
            + '            padding: 10px;\n'
            + '        }\n'
            + '        .print-btn-bar {\n'
            + '            display: flex;\n'
            + '            justify-content: flex-end;\n'
            + '            gap: 10px;\n'
            + '            margin-bottom: 12px;\n'
            + '        }\n'
            + '        .btn-print-action {\n'
            + '            background: #0284c7;\n'
            + '            color: #fff;\n'
            + '            border: none;\n'
            + '            padding: 8px 18px;\n'
            + '            font-size: 13px;\n'
            + '            font-weight: 600;\n'
            + '            border-radius: 6px;\n'
            + '            cursor: pointer;\n'
            + '        }\n'
            + '        @media print {\n'
            + '            .print-btn-bar { display: none !important; }\n'
            + '            body { padding: 0 !important; }\n'
            + '        }\n'
            + '        .report-header {\n'
            + '            text-align: center;\n'
            + '            margin-bottom: 16px;\n'
            + '            border-bottom: 2px solid #000;\n'
            + '            padding-bottom: 8px;\n'
            + '        }\n'
            + '        .report-header img { height: 48px; margin-bottom: 4px; }\n'
            + '        .report-header h2 { margin: 2px 0; font-size: 15pt; text-transform: uppercase; font-weight: bold; }\n'
            + '        .report-header p { margin: 0; font-size: 10pt; color: #444; }\n'
            + '        .report-meta {\n'
            + '            display: flex;\n'
            + '            justify-content: space-between;\n'
            + '            font-size: 9pt;\n'
            + '            margin-bottom: 8px;\n'
            + '            font-weight: 500;\n'
            + '        }\n'
            + '        table.preview-table {\n'
            + '            width: 100%;\n'
            + '            border-collapse: collapse;\n'
            + '            font-size: 8pt;\n'
            + '            margin-bottom: 14px;\n'
            + '        }\n'
            + '        table.preview-table th, table.preview-table td {\n'
            + '            border: 1px solid #333;\n'
            + '            padding: 5px 4px;\n'
            + '            text-align: center;\n'
            + '            vertical-align: middle;\n'
            + '        }\n'
            + '        table.preview-table th {\n'
            + '            background-color: #f1f5f9;\n'
            + '            font-weight: bold;\n'
            + '        }\n'
            + '        .signature-section {\n'
            + '            margin-top: 18px;\n'
            + '            display: flex;\n'
            + '            justify-content: flex-end;\n'
            + '            page-break-inside: avoid;\n'
            + '        }\n'
            + '        .signature-box {\n'
            + '            text-align: center;\n'
            + '            width: 260px;\n'
            + '            font-size: 9.5pt;\n'
            + '            line-height: 1.4;\n'
            + '        }\n'
            + '        .signature-space {\n'
            + '            height: 56px;\n'
            + '        }\n'
            + '    <\/style>\n'
            + '<\/head>\n'
            + '<body>\n'
            + '    <div class="print-btn-bar">\n'
            + '        <button class="btn-print-action" onclick="window.print()">Cetak Dokumen Sekarang<\/button>\n'
            + '    <\/div>\n'
            + '    <div class="report-header">\n'
            + '        <img src="' + logoUrl + '" alt="BPS">\n'
            + '        <h2>LAPORAN REKAPITULASI SPJ<\/h2>\n'
            + '        <p>Badan Pusat Statistik Kabupaten Kolaka Utara<\/p>\n'
            + '    <\/div>\n'
            + '    <div class="report-meta">\n'
            + '        <div>\n'
            + '            <strong>No. SPJ:<\/strong> ' + nomorSpj + ' | \n'
            + '            <strong>Periode Kegiatan:<\/strong> ' + periodeKegiatanFormatted + ' | \n'
            + '            <strong>Staf Pengaju / PIC:<\/strong> ' + pengajuName + ' (' + noWa + ')\n'
            + '        <\/div>\n'
            + '    <\/div>\n'
            + '    <table class="preview-table">\n'
            + '        <thead>\n'
            + '            <tr>\n'
            + '                <th rowspan="2" style="width: 25px;">No<\/th>\n'
            + '                <th rowspan="2" style="width: 170px;">Nama Kegiatan<\/th>\n'
            + '                <th rowspan="2" style="width: 85px;">Jumlah Anggaran<\/th>\n'
            + '                <th rowspan="2" style="width: 80px;">Tanggal Penginputan SPJ<\/th>\n'
            + '                <th colspan="' + totalKelengkapanColspan + '">Kelengkapan Administrasi<\/th>\n'
            + '                <th rowspan="2" style="width: 80px;">Keterangan<\/th>\n'
            + '            <\/tr>\n'
            + '            <tr>\n'
            + docHeadersHtml
            + '                <th style="width: 45px;">Tgl Masuk SPJ<\/th>\n'
            + '                <th style="width: 50px;">Tgl Periksa PPK<\/th>\n'
            + '                <th style="width: 50px;">Paraf PPSPM<\/th>\n'
            + '                <th style="width: 50px;">Paraf Bendahara<\/th>\n'
            + '            <\/tr>\n'
            + '        <\/thead>\n'
            + '        <tbody>\n'
            + '            <tr>\n'
            + '                <td>1<\/td>\n'
            + '                <td style="text-align: left; padding-left: 6px;">' + kegiatan + '<\/td>\n'
            + '                <td style="font-weight: bold; text-align: right; padding-right: 6px;">' + formatRupiah(nilai) + '<\/td>\n'
            + '                <td>' + (tanggalInputSpj || tanggalFormatted) + '<\/td>\n'
            + docValuesHtml
            + '                <td>' + tglMasukUmum + '<\/td>\n'
            + '                <td>-<\/td>\n'
            + '                <td>-<\/td>\n'
            + '                <td>-<\/td>\n'
            + '                <td style="text-align: left; font-size: 7.5pt;">' + keterangan + '<\/td>\n'
            + '            <\/tr>\n'
            + '            <tr style="font-weight: bold; background: #fafafa;">\n'
            + '                <td colspan="2" style="text-align: right; padding-right: 8px;">TOTAL<\/td>\n'
            + '                <td style="text-align: right; padding-right: 6px;">' + formatRupiah(nilai) + '<\/td>\n'
            + '                <td colspan="' + (totalKelengkapanColspan + 2) + '"><\/td>\n'
            + '            <\/tr>\n'
            + '        <\/tbody>\n'
            + '    <\/table>\n'
            + '    <div class="signature-section">\n'
            + '        <div class="signature-box">\n'
            + '            <div>Lasusua, ' + tanggalCetak + '<\/div>\n'
            + '            <div style="font-weight: 600; margin-top: 2px;">Pejabat Pembuat Komitmen<\/div>\n'
            + '            <div class="signature-space"><\/div>\n'
            + '            <div style="font-weight: bold; text-decoration: underline;">Hidayatullah, S.E.<\/div>\n'
            + '            <div style="font-size: 8.5pt; color: #333;">NIP. 198510292011011009<\/div>\n'
            + '        <\/div>\n'
            + '    <\/div>\n'
            + '<\/body>\n'
            + '<\/html>';

        const printWin = window.open('', '_blank', 'width=1100,height=750');
        if (!printWin) {
            alert("Pop-up diblokir oleh browser. Harap izinkan pop-up untuk mencetak lembar checklist.");
            return;
        }
        printWin.document.open();
        printWin.document.write(printHtml);
        printWin.document.close();

        // Mark as printed
        const isPrintedInput = document.getElementById("is_printed_input");
        if (isPrintedInput) isPrintedInput.value = "1";

        const printBtn = document.getElementById("btn_cetak_langsung");
        if (printBtn) {
            printBtn.classList.add("is-printed");
            const btnText = document.getElementById("btn_cetak_text");
            if (btnText) btnText.textContent = "Sudah Dicetak";
        }

        const banner = document.getElementById("print_status_banner");
        if (banner) {
            banner.style.display = "flex";
            banner.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Lembar checklist SPJ berhasil dicetak. Anda kini dapat memperbarui dokumen SPJ.</span>';
        }

        const hint = document.getElementById("print_requirement_hint");
        if (hint) hint.style.display = "none";
    }

    document.addEventListener("DOMContentLoaded", function() {
        initCurrencyMask();

        const statusRadios = document.querySelectorAll('input[name="status"]');
        const hint = document.getElementById("print_requirement_hint");
        const isPrintedInput = document.getElementById("is_printed_input");

        function updateStatusUI() {
            let selectedStatus = 'draft';
            statusRadios.forEach(r => { if (r.checked) selectedStatus = r.value; });
            if (selectedStatus === 'diajukan' && (!isPrintedInput || isPrintedInput.value !== '1')) {
                if (hint) hint.style.display = "block";
            } else {
                if (hint) hint.style.display = "none";
            }
        }

        statusRadios.forEach(r => r.addEventListener("change", updateStatusUI));
        updateStatusUI();
    });
</script>
@endpush

